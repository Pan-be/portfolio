<?php
// Home page section: what the AI assistant does for a salon, with a live demo of a fictional salon's assistant.
// The chat talks to the endpoint in inc/chat-demo.php; js/chat-demo.js drives it.
$pl = my_theme_is_polish();
$enabled = panbe_chat_enabled();
$starters = $pl
    ? array('Ile kosztują brwi pudrowe?', 'Jak przygotować się do makijażu permanentnego?', 'Czy jesteście otwarci w sobotę?')
    : array('How much are powder brows?', 'How do I prepare for permanent make-up?', 'Are you open on Saturday?');
?>
<section class="ai-demo wrapper" id="asystent-ai">
    <div class="ai-demo__text">
        <p class="ai-demo__eyebrow"><?php echo $pl ? 'Dodatek do strony' : 'Website add-on'; ?></p>
        <h2><?php echo $pl ? 'Asystent AI, który odpowiada klientkom, kiedy Ty pracujesz' : 'An AI assistant that answers clients while you work'; ?></h2>
        <p><?php echo $pl
                ? 'Jest 23:00, klientka pyta o cenę brwi i o to, czy zdąży przed weekendem. Zamiast czekać do rana na odpowiedź w wiadomościach, dostaje ją od razu na Twojej stronie, razem z przyciskiem do rezerwacji.'
                : "It's 11 pm and a client wants to know what brows cost and whether she can still get in before the weekend. Instead of waiting until morning for a reply, she gets it straight away on your site, with a button to book."; ?></p>
        <ul class="ai-demo__points">
            <li><?php echo $pl ? 'Odpowiada na pytania o zabiegi, ceny, przygotowanie i zadatki, z Twojego cennika i FAQ.' : 'Answers questions about treatments, prices, preparation and deposits, from your price list and FAQ.'; ?></li>
            <li><?php echo $pl ? 'Kieruje do rezerwacji w Booksy albo Twoim kalendarzu.' : 'Sends clients to booking in Booksy or your own calendar.'; ?></li>
            <li><?php echo $pl ? 'Nie udziela porad zdrowotnych i nie zbiera danych o zdrowiu.' : "Gives no medical advice and doesn't collect health data."; ?></li>
            <li><?php echo $pl ? 'Zmiana cennika? Wystarczy wiadomość do mnie, aktualizacje są w abonamencie.' : 'Price changes take one message to me, updates are part of the monthly plan.'; ?></li>
        </ul>
        <p class="ai-demo__price"><?php echo $pl
                ? 'Wdrożenie <strong>od 1990 zł</strong> netto, abonament <strong>od 199 zł/mies.</strong> (koszty AI w cenie)'
                : 'Setup <strong>from €490</strong>, monthly plan <strong>from €49</strong> (AI costs included)'; ?></p>
        <a class="button primary" href="<?php echo esc_url(panbe_contact_url()); ?>"><?php echo $pl ? 'Chcę takiego asystenta' : 'I want one for my salon'; ?></a>
    </div>

    <div class="chat" data-chat data-endpoint="<?php echo esc_url(rest_url('panbe/v1/chat')); ?>" data-lang="<?php echo $pl ? 'pl' : 'en'; ?>">
        <div class="chat__header">
            <span class="chat__avatar" aria-hidden="true">L</span>
            <div>
                <p class="chat__name">Studio Lumi</p>
                <p class="chat__status"><?php echo $pl ? 'Asystent AI · salon fikcyjny, to demo' : 'AI assistant · fictional salon, a demo'; ?></p>
            </div>
        </div>

        <div class="chat__log" data-chat-log role="log" aria-live="polite" aria-label="<?php echo $pl ? 'Rozmowa z asystentem' : 'Conversation with the assistant'; ?>">
            <p class="chat__message chat__message--assistant"><?php echo $pl
                    ? 'Cześć! Jestem asystentem Studio Lumi. Zapytaj o zabiegi, ceny albo przygotowanie do wizyty.'
                    : "Hi! I'm Studio Lumi's assistant. Ask me about treatments, prices or how to prepare for a visit."; ?></p>
        </div>

        <?php if ($enabled): ?>
            <div class="chat__starters" data-chat-starters>
                <?php foreach ($starters as $starter): ?>
                    <button type="button" class="chat__starter"><?php echo esc_html($starter); ?></button>
                <?php endforeach; ?>
            </div>

            <form class="chat__form" data-chat-form>
                <label class="screen-reader-text" for="chat-input"><?php echo $pl ? 'Twoja wiadomość' : 'Your message'; ?></label>
                <input class="chat__input" id="chat-input" name="message" type="text" maxlength="500" autocomplete="off" required placeholder="<?php echo $pl ? 'Napisz pytanie…' : 'Type a question…'; ?>">
                <input class="chat__trap" name="website" type="text" tabindex="-1" autocomplete="off" aria-hidden="true">
                <button class="chat__send button primary" type="submit"><?php echo $pl ? 'Wyślij' : 'Send'; ?></button>
            </form>

            <div class="chat__footer">
                <button type="button" class="chat__book button secondary" data-chat-book><?php echo $pl ? 'Zarezerwuj' : 'Book a visit'; ?></button>
                <p class="chat__note" data-chat-book-note hidden><?php echo $pl
                        ? 'W prawdziwym wdrożeniu ten przycisk otwiera Booksy albo Twój kalendarz rezerwacji.'
                        : 'On a real salon site this button opens Booksy or your booking calendar.'; ?></p>
            </div>
        <?php else: ?>
            <p class="chat__off"><?php echo $pl ? 'Demo jest chwilowo wyłączone.' : 'The demo is switched off for now.'; ?></p>
        <?php endif; ?>

        <p class="chat__privacy"><?php echo $pl
                ? 'Nie wpisuj danych osobowych ani informacji o zdrowiu. Wiadomości obsługuje model AI firmy Anthropic, nie zapisuję ich.'
                : "Please don't enter personal or health details. Messages are handled by Anthropic's AI model and I don't store them."; ?></p>
    </div>
</section>
