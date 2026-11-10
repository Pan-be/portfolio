# Updating WordPress core and plugins

Staging first, production second, same versions on both. `publish.sh` refuses to run when the active
plugins (name + version) differ between the two, so a half-done update also blocks publishing.

## When

- Monthly, in the first week.
- At once for a security release of core or of an installed plugin.
- Hostinger updates WordPress core on production by itself (7.1.3 → 7.1.4 seen on 2026-11-10), so
  staging can fall behind without anyone touching it: the monthly check catches that.

## Steps

1. **See what's waiting:** `tools/versions.sh` (read-only) prints core, PHP and every plugin on both
   sides, with the update each one has waiting.
2. **Read the changelogs** of everything you're about to update. Stop at a major version, a PHP
   requirement change, or anything touching ACF field storage, Polylang or Contact Form 7 submissions.
3. **Staging:**
   - back up its database: the WP-CLI container's `wp db export -` (as `publish.sh` does) into
     `~/backups/pan-be-staging-<date>.sql`;
   - update with the one-off WP-CLI container (as in `publish.sh`, but without `:ro` on the volumes):
     `wp core update --version=X`, `wp plugin update <slug> --version=X`, naming exact versions;
     if `plugin update --version` fails with `Undefined property: stdClass::$download_link` (seen with
     ACF), install the exact zip: `wp plugin install https://downloads.wordpress.org/plugin/<slug>.<X>.zip --force`;
     the version WP-CLI lists as waiting can lag behind wordpress.org, so check the latest there;
   - check: home PL and EN, `/cennik/`, `/en/pricing/`, a blog post, the contact form (submit once),
     `wp-admin` login (2FA), editing a page with ACF fields, and the PHP error log.
4. **Production**, the same day:
   - back up the database: WPvivid "Backup now" in wp-admin, or the `mariadb-dump` command from
     `backup_prod()` in `publish.sh` (`wp db export` doesn't work on Hostinger: `exec()` is disabled);
   - the same `wp core update --version=X` / `wp plugin update <slug> --version=X` over SSH, the same
     versions as staging;
   - `wp cache flush` and `wp sg purge`;
   - check the same pages; `tools/versions.sh` shows both columns identical.
5. **Rollback** of one plugin: `wp plugin install <slug> --version=<old> --force` on that side. A
   database problem: `tools/publish.sh --rollback <backup>` on production, the staging dump on staging.

## Log

| Date | What | Staging | Production |
|---|---|---|---|
| 2026-11-10 | First round (security fixes): core 7.1.4 on staging; ACF 6.8.11, CF7 6.2.5, Polylang 3.9.2, SG Optimizer 7.8.6, SVG Support 2.6.3, WPvivid 0.9.140 on both. Backups: staging `~/backups/pan-be-staging-2026-11-10-before-updates.sql`, prod `prod-20261110-before-updates.sql.gz`. Checked: pages 200 without PHP messages, PL/EN, CF7 validation, error log quiet | core 7.1.4, all identical | core 7.1.4, all identical |
| 2026-11-10 | Baseline. Two Factor 0.17.0 added (2FA). Waiting: core 7.1.4 (staging only), ACF 6.8.11, CF7 6.2.4.1, Polylang 3.9.2, SG Optimizer 7.8.6, SVG Support 2.6.3, WPvivid 0.9.140 | core 7.1.3 | core 7.1.4 |
