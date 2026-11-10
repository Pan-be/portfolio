#!/usr/bin/env bash
# Read-only: WordPress core, PHP and plugin versions on staging and production side by side,
# with the update each one has waiting. Uses the same settings as publish.sh (tools/publish.conf).
# Usage: tools/versions.sh
set -euo pipefail

here="$(cd "$(dirname "$0")" && pwd)"
conf="$here/publish.conf"
[[ -f $conf ]] || { echo "Missing $conf (copy publish.conf.example)" >&2; exit 1; }
# shellcheck source=/dev/null
source "$conf"
: "${SSH_HOST:?}" "${REMOTE_PATH:?}" "${STAGING_CONTAINER:?}" "${STAGING_NETWORK:?}"
CLI_IMAGE="${CLI_IMAGE:-wordpress:cli-php8.5}"

work="$(mktemp -d)"
ssh_cmd="ssh -o BatchMode=yes -o ControlMaster=auto -o ControlPath=$work/ssh-%C -o ControlPersist=60"
trap '$ssh_cmd -O exit "$SSH_HOST" >/dev/null 2>&1 || true; rm -rf "$work"' EXIT

prod_wp() { $ssh_cmd "$SSH_HOST" "cd $REMOTE_PATH && wp $*" 2>/dev/null; }
staging_wp() {
  docker run --rm -i --network "$STAGING_NETWORK" --volumes-from "$STAGING_CONTAINER:ro" \
    --user 33:33 -e HOME=/tmp \
    --env-file <(docker inspect "$STAGING_CONTAINER" --format '{{range .Config.Env}}{{println .}}{{end}}' | grep '^WORDPRESS_') \
    "$CLI_IMAGE" wp "$@" 2>/dev/null
}

printf '%-24s %-30s %-30s\n' "" "staging" "production"
printf '%-24s %-30s %-30s\n' "WordPress core" "$(staging_wp core version)" "$(prod_wp core version)"
printf '%-24s %-30s %-30s\n' "PHP" \
  "$(docker exec "$STAGING_CONTAINER" php -r 'echo PHP_VERSION;' 2>/dev/null)" \
  "$($ssh_cmd "$SSH_HOST" "php -r 'echo PHP_VERSION;'" 2>/dev/null)"
printf '%-24s %-30s %-30s\n' "core update" \
  "$(staging_wp core check-update --field=version | grep -E "^[0-9]" | head -1 || true)" \
  "$(prod_wp core check-update --field=version | grep -E "^[0-9]" | head -1 || true)"
echo
echo "Plugins (version -> waiting update; must-use hostinger-auto-updates is Hostinger's, left out)"
fields='plugin list --fields=name,status,version,update_version --format=csv'
staging_wp $fields | tail -n +2 | grep -v '^hostinger-auto-updates' | sort > "$work/s"
prod_wp $fields | tail -n +2 | grep -v '^hostinger-auto-updates' | sort > "$work/p"
cut -d, -f1 "$work/s" "$work/p" | sort -u | while read -r name; do
  cell() {
    local line; line="$(grep "^$name," "$1" || true)"
    [[ -n $line ]] || { echo "-"; return; }
    IFS=, read -r _ status version update <<<"$line"
    echo "$version${update:+ -> $update}${status:+ ($status)}"
  }
  printf '%-24s %-30s %-30s\n' "$name" "$(cell "$work/s")" "$(cell "$work/p")"
done
