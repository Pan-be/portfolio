# Content migrations

One-off changes to site content (pages, options, ACF fields) kept as code, so they are reviewed in a PR like everything else. Content itself lives in the production database; these scripts are how a fix travels.

- One file per change, named `YYYY-MM-DD-what.php`, run with WP-CLI: `wp eval-file wp-content/themes/panbe/migrations/<file>.php`.
- Scripts must be safe to run twice (set values, don't append blindly).
- Test on staging first. Since staging became the source of truth for content, a change made there reaches production with the next `tools/publish.sh` run.
- The folder is deployed with the theme but blocked from the web (`.htaccess`), and every script exits unless run under WP-CLI.
