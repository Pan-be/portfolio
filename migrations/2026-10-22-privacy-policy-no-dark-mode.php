<?php
// Dracula Dark Mode was deleted on 2026-10-22, so the site no longer keeps dark mode settings in
// localStorage: drop that entry from the cookie list in the privacy policy.
// Run: wp eval-file wp-content/themes/panbe/migrations/2026-10-22-privacy-policy-no-dark-mode.php
if (!defined('WP_CLI')) {
    exit;
}

// Page IDs: privacy policy PL 184 / EN 3.
$lines = array(
    184 => ' 	<li>ustawienia trybu ciemnego: zapisywane w pamięci Twojej przeglądarki (localStorage); funkcjonalne, nie trafiają do nas.</li>' . "\n",
    3 => ' 	<li>dark mode settings: saved in your browser\'s local storage; functional, never sent to us.</li>' . "\n",
);

foreach ($lines as $id => $line) {
    $content = get_post_field('post_content', $id);
    if (!str_contains($content, $line)) {
        WP_CLI::log("Page $id: already up to date");
        continue;
    }
    $result = wp_update_post(wp_slash(array('ID' => $id, 'post_content' => str_replace($line, '', $content))), true);
    if (is_wp_error($result)) {
        WP_CLI::error("Page $id: " . $result->get_error_message());
    }
    WP_CLI::success("Page $id updated");
}
