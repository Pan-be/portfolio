<?php
// AI assistant demo on the home page (ai-demo.php, inc/chat-demo.php): privacy policy entries for it, the
// pricing intro now points to the AI card instead of "priced individually", and the PL hero links to the demo.
// Run: wp eval-file wp-content/themes/panbe/migrations/2026-10-22-ai-assistant-demo.php
if (!defined('WP_CLI')) {
    exit;
}

// Page IDs: privacy policy PL 184 / EN 3, pricing PL 294 / EN 383.
$edits = array(
    184 => array(
        '<p><em>Ostatnia aktualizacja: 5 października 2026</em></p>' => '<p><em>Ostatnia aktualizacja: 22 października 2026</em></p>',
        ' 	<li><strong>Statystyki odwiedzin (Google Analytics).</strong>' => ' 	<li><strong>Demo asystenta AI na stronie głównej.</strong> Gdy piszesz do asystenta fikcyjnego salonu, treść rozmowy trafia do modelu AI firmy Anthropic, który przygotowuje odpowiedź. Rozmów nie zapisujemy. Aby chronić demo przed nadużyciami, liczymy wiadomości z danego adresu IP; zapisujemy przy tym tylko skrót (hash) adresu, nie sam adres, i usuwamy go najpóźniej po 24 godzinach. Podstawą jest nasz prawnie uzasadniony interes, czyli pokazanie działania usługi i ochrona przed nadużyciami (art. 6 ust. 1 lit. f RODO). Nie wpisuj w rozmowie danych osobowych ani informacji o zdrowiu.</li>' . "\n" . ' 	<li><strong>Statystyki odwiedzin (Google Analytics).</strong>',
        ' 	<li>Cloudflare, Inc. — Cloudflare Turnstile (ochrona przed spamem).</li>' => ' 	<li>Cloudflare, Inc. — Cloudflare Turnstile (ochrona przed spamem),</li>' . "\n" . ' 	<li>Anthropic, PBC — model AI w demo asystenta.</li>',
        'Dodatkowo stosują standardowe klauzule umowne zatwierdzone przez Komisję Europejską.' => 'Dodatkowo stosują standardowe klauzule umowne zatwierdzone przez Komisję Europejską. Anthropic przetwarza wiadomości z demo asystenta w Stanach Zjednoczonych na podstawie standardowych klauzul umownych zawartych w umowie powierzenia przetwarzania danych; nie wykorzystuje ich do trenowania modeli.',
        ' 	<li>Logi serwera:' => ' 	<li>Rozmowy z demo asystenta AI: nie zapisujemy ich; Anthropic przechowuje je przez krótki czas zgodnie ze swoimi zasadami przechowywania danych w API. Skrót adresu IP do limitu wiadomości: do 24 godzin.</li>' . "\n" . ' 	<li>Logi serwera:',
    ),
    3 => array(
        '<p><em>Last updated: 5 October 2026</em></p>' => '<p><em>Last updated: 22 October 2026</em></p>',
        ' 	<li><strong>Visitor statistics (Google Analytics).</strong>' => ' 	<li><strong>AI assistant demo on the home page.</strong> When you write to the fictional salon\'s assistant, the conversation is sent to Anthropic\'s AI model, which prepares the reply. We do not store conversations. To protect the demo against abuse we count messages per IP address; for this we keep only a hash of the address, not the address itself, and delete it within 24 hours. The legal basis is our legitimate interest in showing how the service works and protecting it against abuse (Art. 6(1)(f) GDPR). Please do not enter personal or health details in the chat.</li>' . "\n" . ' 	<li><strong>Visitor statistics (Google Analytics).</strong>',
        ' 	<li>Cloudflare, Inc. — Cloudflare Turnstile (spam protection).</li>' => ' 	<li>Cloudflare, Inc. — Cloudflare Turnstile (spam protection),</li>' . "\n" . ' 	<li>Anthropic, PBC — the AI model behind the assistant demo.</li>',
        'They also use standard contractual clauses approved by the European Commission.' => 'They also use standard contractual clauses approved by the European Commission. Anthropic processes messages from the assistant demo in the United States under the standard contractual clauses in its data processing agreement, and does not use them to train its models.',
        ' 	<li>Server logs:' => ' 	<li>Assistant demo conversations: we do not store them; Anthropic keeps them for a short period under its API data retention policy. IP hash for the message limit: up to 24 hours.</li>' . "\n" . ' 	<li>Server logs:',
    ),
    294 => array(
        'Wdrażam też rozwiązania AI, np. chatbota na stronie, który odpowiada na pytania Twoich klientów — wycena indywidualna.' => 'Do każdego pakietu mogę dołożyć asystenta AI, który odpowiada Twoim klientom na pytania o usługi i ceny. Szczegóły i ceny znajdziesz niżej.',
    ),
    383 => array(
        "I also build AI solutions, such as a website chatbot that answers your customers' questions — priced individually." => 'Any package can include an AI assistant that answers your customers\' questions about services and prices. Details and prices are below.',
    ),
);

foreach ($edits as $id => $pairs) {
    $content = get_post_field('post_content', $id);
    $new = $content;
    foreach ($pairs as $from => $to) {
        if (str_contains($new, $to)) {
            continue; // already applied
        }
        if (!str_contains($new, $from)) {
            WP_CLI::error("Page $id: expected text not found: " . mb_substr($from, 0, 60));
        }
        $new = str_replace($from, $to, $new);
    }
    if ($new === $content) {
        WP_CLI::log("Page $id: already up to date");
        continue;
    }
    $result = wp_update_post(wp_slash(array('ID' => $id, 'post_content' => $new)), true);
    if (is_wp_error($result)) {
        WP_CLI::error("Page $id: " . $result->get_error_message());
    }
    WP_CLI::success("Page $id updated");
}

// PL home page hero (ACF hero_text, page 159): link the AI sentence to the demo section.
$hero_from = 'Na życzenie dokładam asystenta AI, który odpowiada klientkom 24/7.';
$hero_to = 'Na życzenie dokładam asystenta AI, który odpowiada klientkom 24/7: <a href="#asystent-ai" data-track="ai_demo_link" data-track-location="hero">zobacz, jak działa</a>.';
$hero = get_field('hero_text', 159, false);
if (str_contains($hero, $hero_to)) {
    WP_CLI::log('Hero 159: already up to date');
} elseif (!str_contains($hero, $hero_from)) {
    WP_CLI::error('Hero 159: expected text not found');
} else {
    update_field('hero_text', str_replace($hero_from, $hero_to, $hero), 159);
    WP_CLI::success('Hero 159 updated');
}
