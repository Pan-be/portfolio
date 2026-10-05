<?php
// Cookie banner for Google Analytics. Texts live here (PL/EN) rather than in Polylang strings, so they are versioned with the code.
$panbe_consent_texts = array(
    'pl' => array(
        'title'   => 'Pliki cookie',
        'text'    => 'Używam Google Analytics, żeby sprawdzać, jak odwiedzający korzystają ze strony. Te statystyki włączę tylko za Twoją zgodą. Szczegóły znajdziesz w %s.',
        'policy'  => 'polityce prywatności',
        'accept'  => 'Akceptuj',
        'reject'  => 'Odrzuć',
    ),
    'en' => array(
        'title'   => 'Cookies',
        'text'    => 'I use Google Analytics to see how visitors use this site. These statistics only run with your consent. Details are in the %s.',
        'policy'  => 'privacy policy',
        'accept'  => 'Accept',
        'reject'  => 'Reject',
    ),
);
$panbe_consent = my_theme_is_polish() ? $panbe_consent_texts['pl'] : $panbe_consent_texts['en'];

$panbe_privacy_id = (int) get_option('wp_page_for_privacy_policy');
if ($panbe_privacy_id && function_exists('pll_get_post')) {
    $panbe_privacy_id = pll_get_post($panbe_privacy_id) ?: $panbe_privacy_id;
}
$panbe_policy_link = $panbe_privacy_id
    ? '<a href="' . esc_url(get_permalink($panbe_privacy_id)) . '">' . esc_html($panbe_consent['policy']) . '</a>'
    : esc_html($panbe_consent['policy']);
?>
<div class="consent" id="consent" role="dialog" aria-labelledby="consent-title" aria-describedby="consent-text" hidden>
    <p class="consent__title" id="consent-title"><?php echo esc_html($panbe_consent['title']); ?></p>
    <p class="consent__text" id="consent-text"><?php printf(esc_html($panbe_consent['text']), $panbe_policy_link); ?></p>
    <div class="consent__buttons">
        <button type="button" class="button primary" data-consent="granted"><?php echo esc_html($panbe_consent['accept']); ?></button>
        <button type="button" class="button primary" data-consent="denied"><?php echo esc_html($panbe_consent['reject']); ?></button>
    </div>
</div>
