# Content migrations

One-off changes to site content (pages, options, ACF fields) kept as code, so they are reviewed in a PR like everything else. Content itself lives in the production database; these scripts are how a fix travels.

- One file per change, named `YYYY-MM-DD-what.php`, run with WP-CLI: `wp eval-file wp-content/themes/panbe/migrations/<file>.php`.
- Scripts must be safe to run twice (set values, don't append blindly).
- Test on staging first. Production has no WP-CLI access yet, so for now the same change is made by hand in wp-admin (copy from staging).
- The folder is deployed with the theme but blocked from the web (`.htaccess`), and every script exits unless run under WP-CLI.
