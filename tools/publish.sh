#!/usr/bin/env bash
# Publish staging content to production: database + uploads.
#
# Staging is the source of truth for content. This script copies the whole
# staging database to production, with the domain and table prefix rewritten,
# and adds new files from wp-content/uploads.
#
#   tools/publish.sh                   dry run: check, build the dump, report, change nothing
#   tools/publish.sh --apply           back up production, then publish
#   tools/publish.sh --rollback FILE   restore a production backup made by --apply
#
# Runs on the home server next to the staging containers. Needs an SSH host
# alias for production with the key unlocked in ssh-agent. Settings live in
# tools/publish.conf (untracked, see publish.conf.example).

set -euo pipefail

here="$(cd "$(dirname "$0")" && pwd)"
conf="$here/publish.conf"
[[ -f $conf ]] || { echo "Missing $conf (copy publish.conf.example)" >&2; exit 1; }
# shellcheck source=/dev/null
source "$conf"

: "${SSH_HOST:?}" "${REMOTE_PATH:?}" "${PROD_URL:?}" "${STAGING_URL:?}"
: "${STAGING_CONTAINER:?}" "${STAGING_NETWORK:?}" "${PROD_PREFIX:?}" "${BACKUP_DIR:?}"
CLI_IMAGE="${CLI_IMAGE:-wordpress:cli-php8.2}"
DB_IMAGE="${DB_IMAGE:-mariadb:11.4}"

mode=dry
case "${1:-}" in
  "") ;;
  --apply) mode=apply ;;
  --rollback) mode=rollback; restore_file="${2:?--rollback needs a backup file}" ;;
  *) echo "Unknown option: $1" >&2; exit 1 ;;
esac

prod_host="${PROD_URL#https://}"
staging_host="${STAGING_URL#https://}"
stamp="$(date -u +%Y%m%d-%H%M%S)"
work="$(mktemp -d)"
tmp_net="panbe-publish-$stamp"
tmp_db="panbe-publish-db-$stamp"

# One shared SSH connection: Hostinger refuses bursts of new ones.
ssh_cmd="ssh -o BatchMode=yes -o ControlMaster=auto -o ControlPath=$work/ssh-%C -o ControlPersist=120"

cleanup() {
  $ssh_cmd -O exit "$SSH_HOST" >/dev/null 2>&1 || true
  docker rm -f "$tmp_db" >/dev/null 2>&1 || true
  docker network rm "$tmp_net" >/dev/null 2>&1 || true
  rm -rf "$work"
}
trap cleanup EXIT

say() { printf '\n== %s\n' "$*"; }
die() { printf 'ABORT: %s\n' "$*" >&2; exit 1; }

# WP-CLI on production, inside the site directory.
prod_wp() { $ssh_cmd "$SSH_HOST" "cd $REMOTE_PATH && wp $*"; }

# WP-CLI against staging: one-off container, staging files read-only.
staging_env() {
  docker inspect "$STAGING_CONTAINER" --format '{{range .Config.Env}}{{println .}}{{end}}' | grep '^WORDPRESS_'
}
staging_wp() {
  docker run --rm -i --network "$STAGING_NETWORK" --volumes-from "$STAGING_CONTAINER:ro" \
    --user 33:33 -e HOME=/tmp --env-file <(staging_env) "$CLI_IMAGE" wp "$@" 2>/dev/null
}

# WP-CLI against the throwaway DB that holds the rewritten dump.
tmp_wp() {
  docker run --rm -i --network "$tmp_net" --volumes-from "$STAGING_CONTAINER:ro" \
    --user 33:33 -e HOME=/tmp \
    -e WORDPRESS_DB_HOST="$tmp_db" -e WORDPRESS_DB_USER=root -e WORDPRESS_DB_PASSWORD=publish \
    -e WORDPRESS_DB_NAME=wp -e WORDPRESS_TABLE_PREFIX="$PROD_PREFIX" \
    "$CLI_IMAGE" wp --skip-plugins --skip-themes "$@" 2>/dev/null
}

check_site() {
  local ok=0 url code mitigated
  for url in "$PROD_URL/" "$PROD_URL/en/" "$PROD_URL/wp-login.php"; do
    code="$(curl -sL -D "$work/headers.txt" -o "$work/page.html" -w '%{http_code}' "$url?nocache=$stamp")"
    mitigated="$(grep -i '^cf-mitigated:' "$work/headers.txt" | tail -n 1 | tr -d '\r' | awk '{print $2}')"
    # Cloudflare challenges wp-login.php (rule since 2026-10-19); that 403 comes from Cloudflare, not WordPress
    if [[ $code == 403 && $mitigated == challenge ]]; then
      echo "  $url $code  Cloudflare challenge, not WordPress: OK"
      continue
    fi
    if grep -q "$staging_host" "$work/page.html"; then
      echo "  $url $code  contains $staging_host"; ok=1
    else
      echo "  $url $code"
    fi
    [[ $code == 200 ]] || ok=1
  done
  return $ok
}

purge_caches() {
  prod_wp cache flush >/dev/null
  prod_wp sg purge >/dev/null 2>&1 || echo "  (sg purge failed, purge the cache in hPanel)"
}

backup_prod() {
  say "Backing up production"
  mkdir -p "$BACKUP_DIR"
  local file="$BACKUP_DIR/prod-$stamp.sql.gz"
  # Hostinger disables exec() for PHP, which `wp db export` needs, so call
  # mariadb-dump directly. The DB password is read and used on the server only.
  $ssh_cmd "$SSH_HOST" "cd $REMOTE_PATH && MYSQL_PWD=\"\$(wp config get DB_PASSWORD)\" mariadb-dump \
    -u \"\$(wp config get DB_USER)\" -h \"\$(wp config get DB_HOST)\" --single-transaction \
    \"\$(wp config get DB_NAME)\"" | gzip > "$file" || die "production backup failed: $file"
  zcat "$file" | tail -1 | grep -q 'Dump completed' || die "production backup looks incomplete: $file"
  echo "  $file ($(du -h "$file" | cut -f1))"
}

import_to_prod() {
  local dump="$1" remote="panbe-publish-$stamp.sql"
  scp -q -o BatchMode=yes -o ControlPath="$work/ssh-%C" "$dump" "$SSH_HOST:$remote"
  $ssh_cmd "$SSH_HOST" "cd $REMOTE_PATH && wp db import ~/$remote; rc=\$?; rm -f ~/$remote; exit \$rc"
}

if [[ $mode == rollback ]]; then
  [[ -f $restore_file ]] || die "no such file: $restore_file"
  backup_prod
  say "Restoring $restore_file"
  zcat -f "$restore_file" > "$work/restore.sql"
  import_to_prod "$work/restore.sql"
  purge_caches
  say "Checking the site"
  check_site || die "site check failed after rollback"
  echo "Rolled back."
  exit 0
fi

say "Preflight"
[[ "$(prod_wp db prefix)" == "$PROD_PREFIX" ]] || die "production table prefix is not $PROD_PREFIX"
staging_prefix="$(staging_wp db prefix)"
for what in "core version" "option get db_version"; do
  p="$(prod_wp $what)"; s="$(staging_wp $what)"
  echo "  $what: prod $p, staging $s"
  if [[ $p != "$s" ]]; then
    [[ $what == "core version" ]] || die "$what differs"
    echo "  (core versions differ, schema is the same, continuing)"
  fi
done
plugins='plugin list --status=active,must-use --fields=name,version --format=csv'
diff <(prod_wp $plugins | sort) <(staging_wp $plugins | sort) >/dev/null \
  || { diff <(prod_wp $plugins | sort) <(staging_wp $plugins | sort) || true; die "active plugins differ"; }
echo "  active plugins: identical"

say "Exporting staging"
staging_wp db export - > "$work/staging.sql"
tail -1 "$work/staging.sql" | grep -q 'Dump completed' || die "staging export looks incomplete"
echo "  $(du -h "$work/staging.sql" | cut -f1), prefix $staging_prefix"

say "Rewriting the dump in a throwaway database"
docker network create "$tmp_net" >/dev/null
docker run -d --name "$tmp_db" --network "$tmp_net" --tmpfs /var/lib/mysql \
  -e MARIADB_ROOT_PASSWORD=publish -e MARIADB_DATABASE=wp "$DB_IMAGE" >/dev/null
for _ in $(seq 60); do
  docker exec "$tmp_db" mariadb -uroot -ppublish -e 'select 1' wp >/dev/null 2>&1 && break
  sleep 1
done
sql() { docker exec -i "$tmp_db" mariadb -uroot -ppublish wp "$@"; }
sql < "$work/staging.sql"

if [[ $staging_prefix != "$PROD_PREFIX" ]]; then
  renames="$(sql -N -e "select concat('rename table \`', table_name, '\` to \`', '$PROD_PREFIX', substr(table_name, length('$staging_prefix') + 1), '\`;') from information_schema.tables where table_schema = 'wp' and table_name like '${staging_prefix//_/\\_}%'")"
  sql -e "$renames"
  # WordPress stores the prefix inside a few keys too: roles and per-user capabilities.
  sql -e "update ${PROD_PREFIX}options set option_name = concat('$PROD_PREFIX', substr(option_name, length('$staging_prefix') + 1)) where option_name = '${staging_prefix}user_roles';
          update ${PROD_PREFIX}usermeta set meta_key = concat('$PROD_PREFIX', substr(meta_key, length('$staging_prefix') + 1)) where meta_key like '${staging_prefix//_/\\_}%';"
  echo "  prefix $staging_prefix -> $PROD_PREFIX"
fi

tmp_wp search-replace "$staging_host" "$prod_host" --all-tables-with-prefix --precise --report-changed-only

# WPvivid's options describe backups on the machine they live on: keep production's.
tmp_wp transient delete --all >/dev/null
for name in $(tmp_wp option list --search='wpvivid_*' --field=option_name); do
  if value="$(prod_wp option get "$name" --format=json 2>/dev/null)"; then
    tmp_wp option update "$name" --format=json <<< "$value" >/dev/null
  else
    tmp_wp option delete "$name" >/dev/null
  fi
done
echo "  transients cleared, WPvivid options taken from production"

tmp_wp db export - > "$work/publish.sql"

say "Checking the rewritten dump"
# One row per line, so WPvivid's rows (production's own backup records) can be left out.
tmp_wp db export - --skip-extended-insert \
  | grep "$staging_host" | grep -v -e "^INSERT INTO \`${PROD_PREFIX}wpvivid_options\`" -e "'wpvivid_" \
  > "$work/leftovers.sql" || true
if [[ -s $work/leftovers.sql ]]; then
  grep -o ".\{0,100\}$staging_host.\{0,40\}" "$work/leftovers.sql" | head -5 >&2
  die "$staging_host still in the dump"
fi
grep -q "CREATE TABLE \`$staging_prefix" "$work/publish.sql" && [[ $staging_prefix != "$PROD_PREFIX" ]] \
  && die "tables with prefix $staging_prefix still in the dump"
[[ "$(tmp_wp option get siteurl)" == "$PROD_URL" ]] || die "siteurl is not $PROD_URL"
[[ "$(tmp_wp user list --role=administrator --field=user_login | wc -l)" -ge 1 ]] || die "no administrator in the dump"
echo "  $(du -h "$work/publish.sql" | cut -f1), siteurl $PROD_URL, administrator present"

# Options production has and the dump doesn't would be lost: show them.
opts='option list --fields=option_name --format=csv --no-transients'
only_prod="$(comm -23 <(prod_wp $opts | sort) <(tmp_wp $opts | sort))"
if [[ -n $only_prod ]]; then
  echo "  options only on production (lost on publish):"
  echo "$only_prod" | sed 's/^/    /'
fi

say "Uploads to add (nothing is deleted on production)"
uploads_src="$(docker inspect "$STAGING_CONTAINER" --format '{{range .Mounts}}{{if eq .Destination "/var/www/html"}}{{.Source}}{{end}}{{end}}')/wp-content/uploads/"
new_uploads="$(rsync -e "$ssh_cmd" -rltn --itemize-changes --exclude '.htaccess' "$uploads_src" "$SSH_HOST:$REMOTE_PATH/wp-content/uploads/" | grep '^<' || true)"
echo "${new_uploads:-none}" | sed 's/^/  /'

if [[ $mode == dry ]]; then
  say "Dry run: production untouched. Run with --apply to publish."
  exit 0
fi

backup_prod

say "Publishing"
rsync -e "$ssh_cmd" -rlt --exclude '.htaccess' "$uploads_src" "$SSH_HOST:$REMOTE_PATH/wp-content/uploads/"
echo "  uploads copied"
import_to_prod "$work/publish.sql"
purge_caches

say "Checking the site"
if ! check_site; then
  echo "Site check failed. Restore with: tools/publish.sh --rollback $BACKUP_DIR/prod-$stamp.sql.gz" >&2
  exit 1
fi
echo "Published. Backup: $BACKUP_DIR/prod-$stamp.sql.gz"
