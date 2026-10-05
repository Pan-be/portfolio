<?php
// Privacy policy rewrite (GDPR: controller, legal bases, cookies, recipients, retention, rights) and
// company details required by art. 206 KSH at the bottom of the contact pages.
// Run: wp eval-file wp-content/themes/panbe/migrations/2026-10-05-privacy-policy-company-info.php
if (!defined('WP_CLI')) {
    exit;
}

// Page IDs: privacy policy PL 184 / EN 3, contact PL 194 / EN 83.
$policy_pl = <<<'HTML'
<div style="display:grid; justify-items:start; text-align:left">
<p><em>Ostatnia aktualizacja: 5 października 2026</em></p>
<h4><strong>1. Administrator danych</strong></h4>
Administratorem Twoich danych osobowych jest E&amp;K Bieniek Sp. z o.o., ul. Dumna 35/1, 43-346 Bielsko-Biała, NIP 9372767895 (dalej: „my”). W sprawach dotyczących danych osobowych napisz na adres <a href="mailto:konrad@pan-be.com">konrad@pan-be.com</a>. Nie wyznaczyliśmy inspektora ochrony danych.
<h4><strong>2. Jakie dane przetwarzamy, w jakim celu i na jakiej podstawie</strong></h4>
<ul>
 	<li><strong>Formularze kontaktowe i zapytania o pakiety.</strong> Gdy wysyłasz formularz, przetwarzamy podane przez Ciebie dane: imię, adres e-mail, numer telefonu, treść wiadomości i wybrany pakiet. Robimy to, aby odpowiedzieć na zapytanie i przygotować ofertę (art. 6 ust. 1 lit. b RODO, czyli działania przed zawarciem umowy, podejmowane na Twoje żądanie), a w pozostałym zakresie na podstawie naszego prawnie uzasadnionego interesu, jakim jest prowadzenie korespondencji (art. 6 ust. 1 lit. f RODO). Wiadomości z formularzy trafiają na naszą skrzynkę e-mail; strona nie zapisuje ich w swojej bazie danych.</li>
 	<li><strong>Ochrona formularzy przed spamem.</strong> Formularze chroni usługa Cloudflare Turnstile, która sprawdza, czy formularz wysyła człowiek. W tym celu przetwarza dane techniczne, takie jak adres IP oraz informacje o przeglądarce i urządzeniu. Podstawą jest nasz prawnie uzasadniony interes, czyli ochrona strony przed nadużyciami (art. 6 ust. 1 lit. f RODO).</li>
 	<li><strong>Statystyki odwiedzin (Google Analytics).</strong> Wyłącznie za Twoją zgodą korzystamy z Google Analytics 4, aby sprawdzać, jak odwiedzający korzystają ze strony. Google Analytics przetwarza m.in. identyfikatory zapisane w plikach cookie, informacje o urządzeniu i przeglądarce, przybliżoną lokalizację oraz odwiedzane podstrony. Podstawą jest Twoja zgoda (art. 6 ust. 1 lit. a RODO oraz przepisy Prawa komunikacji elektronicznej). Bez zgody Google Analytics się nie uruchamia.</li>
 	<li><strong>Działanie i bezpieczeństwo strony.</strong> Serwer, na którym działa strona, zapisuje w dziennikach (logach) dane techniczne każdego zapytania: adres IP, datę i godzinę, adres odwiedzonej podstrony oraz informacje o przeglądarce. Przetwarzamy je w naszym prawnie uzasadnionym interesie, jakim jest zapewnienie działania i bezpieczeństwa strony (art. 6 ust. 1 lit. f RODO).</li>
</ul>
<h4><strong>3. Pliki cookie i pamięć przeglądarki</strong></h4>
Strona korzysta z następujących plików cookie i danych zapisywanych w przeglądarce:
<ul>
 	<li><code>panbe_consent</code>: zapamiętuje Twój wybór w banerze cookie; niezbędny; przechowywany ok. 6 miesięcy.</li>
 	<li><code>pll_language</code>: zapamiętuje wybraną wersję językową strony; niezbędny; przechowywany 1 rok.</li>
 	<li>ustawienia trybu ciemnego: zapisywane w pamięci Twojej przeglądarki (localStorage); funkcjonalne, nie trafiają do nas.</li>
 	<li><code>_ga</code>, <code>_ga_&lt;identyfikator&gt;</code>: pliki cookie Google Analytics; zapisywane <strong>tylko po wyrażeniu zgody</strong>; przechowywane do 2 lat.</li>
</ul>
Zgodę na statystyki możesz w każdej chwili zmienić lub wycofać, klikając „Ustawienia cookies” w stopce strony. Wycofanie zgody nie wpływa na zgodność z prawem przetwarzania, którego dokonano przed jej wycofaniem. Pliki cookie możesz też usunąć lub zablokować w ustawieniach przeglądarki.
<h4><strong>4. Odbiorcy danych</strong></h4>
Twoje dane mogą otrzymać podmioty, które świadczą dla nas usługi i przetwarzają dane w naszym imieniu:
<ul>
 	<li>Hostinger International Ltd. — hosting strony i poczty e-mail,</li>
 	<li>Google Ireland Ltd. — Google Analytics (tylko po wyrażeniu zgody),</li>
 	<li>Cloudflare, Inc. — Cloudflare Turnstile (ochrona przed spamem).</li>
</ul>
<h4><strong>5. Przekazywanie danych poza Europejski Obszar Gospodarczy</strong></h4>
Google i Cloudflare mogą przetwarzać dane także w Stanach Zjednoczonych. Obie firmy uczestniczą w programie EU-U.S. Data Privacy Framework, na podstawie którego Komisja Europejska uznała odpowiedni stopień ochrony danych. Dodatkowo stosują standardowe klauzule umowne zatwierdzone przez Komisję Europejską.
<h4><strong>6. Jak długo przechowujemy dane</strong></h4>
<ul>
 	<li>Korespondencja z formularzy, która nie doprowadziła do zawarcia umowy: 12 miesięcy od ostatniego kontaktu.</li>
 	<li>Korespondencja związana z zawartą umową: przez czas współpracy, a następnie do upływu terminu przedawnienia roszczeń; dokumenty księgowe przez okres wymagany przepisami podatkowymi.</li>
 	<li>Dane statystyczne w Google Analytics: 2 miesiące.</li>
 	<li>Logi serwera: zgodnie z ustawieniami dostawcy hostingu, zwykle nie dłużej niż kilka tygodni.</li>
</ul>
<h4><strong>7. Twoje prawa</strong></h4>
Masz prawo dostępu do swoich danych, ich sprostowania, usunięcia, ograniczenia przetwarzania i przenoszenia, a także prawo sprzeciwu wobec przetwarzania opartego na naszym prawnie uzasadnionym interesie. Jeśli przetwarzamy dane na podstawie zgody, możesz ją w każdej chwili wycofać. Aby skorzystać z tych praw, napisz na adres <a href="mailto:konrad@pan-be.com">konrad@pan-be.com</a>. Masz też prawo wnieść skargę do Prezesa Urzędu Ochrony Danych Osobowych (ul. Stawki 2, 00-193 Warszawa).
<h4><strong>8. Czy musisz podać dane</strong></h4>
Podanie danych w formularzu jest dobrowolne, ale bez adresu e-mail lub numeru telefonu nie będziemy mogli odpowiedzieć na Twoje zapytanie.
<h4><strong>9. Zautomatyzowane decyzje</strong></h4>
Nie podejmujemy wobec Ciebie decyzji opartych wyłącznie na zautomatyzowanym przetwarzaniu, w tym profilowaniu, które wywoływałyby skutki prawne lub w podobny sposób istotnie na Ciebie wpływały.
<h4><strong>10. Zmiany polityki</strong></h4>
Politykę możemy aktualizować, na przykład gdy zmienią się usługi, z których korzysta strona. Aktualna wersja jest zawsze dostępna na tej stronie, a data ostatniej zmiany znajduje się na górze.
</div>
HTML;

$policy_en = <<<'HTML'
<div style="display:grid; justify-items:start; text-align:left">
<p><em>Last updated: 5 October 2026</em></p>
<h4><strong>1. Data controller</strong></h4>
The controller of your personal data is E&amp;K Bieniek Sp. z o.o., ul. Dumna 35/1, 43-346 Bielsko-Biała, Poland, NIP (Tax ID) 9372767895 ("we"). For anything related to your personal data, write to <a href="mailto:konrad@pan-be.com">konrad@pan-be.com</a>. We have not appointed a data protection officer.
<h4><strong>2. What data we process, why, and on what legal basis</strong></h4>
<ul>
 	<li><strong>Contact and plan enquiry forms.</strong> When you send a form, we process the data you provide: your name, email address, phone number, message and the selected plan. We do this to reply to your enquiry and prepare an offer (Art. 6(1)(b) GDPR, steps taken at your request before entering into a contract) and otherwise on the basis of our legitimate interest in handling correspondence (Art. 6(1)(f) GDPR). Form messages are delivered to our email inbox; the website does not store them in its database.</li>
 	<li><strong>Spam protection for forms.</strong> The forms are protected by Cloudflare Turnstile, which checks that a form is sent by a human. To do so it processes technical data such as your IP address and information about your browser and device. The legal basis is our legitimate interest in protecting the website against abuse (Art. 6(1)(f) GDPR).</li>
 	<li><strong>Visitor statistics (Google Analytics).</strong> Only with your consent, we use Google Analytics 4 to see how visitors use the website. Google Analytics processes, among other things, identifiers stored in cookies, information about your device and browser, approximate location and the pages you visit. The legal basis is your consent (Art. 6(1)(a) GDPR and the Polish Electronic Communications Law). Without consent, Google Analytics does not run.</li>
 	<li><strong>Running and securing the website.</strong> The server hosting the website records technical data about every request in its logs: IP address, date and time, the page requested and browser information. We process this data in our legitimate interest in keeping the website running and secure (Art. 6(1)(f) GDPR).</li>
</ul>
<h4><strong>3. Cookies and browser storage</strong></h4>
The website uses the following cookies and data stored in your browser:
<ul>
 	<li><code>panbe_consent</code>: remembers your choice in the cookie banner; strictly necessary; kept for about 6 months.</li>
 	<li><code>pll_language</code>: remembers the language version of the website; strictly necessary; kept for 1 year.</li>
 	<li>dark mode settings: saved in your browser's local storage; functional, never sent to us.</li>
 	<li><code>_ga</code>, <code>_ga_&lt;ID&gt;</code>: Google Analytics cookies; set <strong>only after you give consent</strong>; kept for up to 2 years.</li>
</ul>
You can change or withdraw your consent to statistics at any time by clicking "Cookie settings" in the website footer. Withdrawing consent does not affect the lawfulness of processing carried out before the withdrawal. You can also delete or block cookies in your browser settings.
<h4><strong>4. Recipients of data</strong></h4>
Your data may be received by companies that provide services to us and process data on our behalf:
<ul>
 	<li>Hostinger International Ltd. — website and email hosting,</li>
 	<li>Google Ireland Ltd. — Google Analytics (only after you give consent),</li>
 	<li>Cloudflare, Inc. — Cloudflare Turnstile (spam protection).</li>
</ul>
<h4><strong>5. Transfers outside the European Economic Area</strong></h4>
Google and Cloudflare may also process data in the United States. Both companies participate in the EU-U.S. Data Privacy Framework, for which the European Commission has recognised an adequate level of data protection. They also use standard contractual clauses approved by the European Commission.
<h4><strong>6. How long we keep data</strong></h4>
<ul>
 	<li>Form correspondence that did not lead to a contract: 12 months from the last contact.</li>
 	<li>Correspondence related to a contract: for the duration of the cooperation and then until the limitation period for claims expires; accounting documents for the period required by tax law.</li>
 	<li>Statistics in Google Analytics: 2 months.</li>
 	<li>Server logs: according to the hosting provider's settings, usually no longer than a few weeks.</li>
</ul>
<h4><strong>7. Your rights</strong></h4>
You have the right to access your data, to have it rectified or erased, to restrict its processing and to data portability, as well as the right to object to processing based on our legitimate interest. Where we process data on the basis of consent, you can withdraw it at any time. To exercise these rights, write to <a href="mailto:konrad@pan-be.com">konrad@pan-be.com</a>. You also have the right to lodge a complaint with the Polish supervisory authority, the President of the Personal Data Protection Office (Prezes Urzędu Ochrony Danych Osobowych, ul. Stawki 2, 00-193 Warszawa, Poland).
<h4><strong>8. Do you have to provide data</strong></h4>
Providing data in the form is voluntary, but without an email address or phone number we will not be able to reply to your enquiry.
<h4><strong>9. Automated decisions</strong></h4>
We do not make decisions about you based solely on automated processing, including profiling, that produce legal effects or similarly significantly affect you.
<h4><strong>10. Changes to this policy</strong></h4>
We may update this policy, for example when the services used by the website change. The current version is always available on this page, and the date of the last change is shown at the top.
</div>
HTML;

$company_pl = '<p class="company-info"><small>E&amp;K Bieniek Sp. z o.o., ul. Dumna 35/1, 43-346 Bielsko-Biała · Sąd Rejonowy w Bielsku-Białej, VIII Wydział Gospodarczy KRS · KRS 0001149712 · NIP 9372767895 · Kapitał zakładowy 5 000,00 zł</small></p>';
$company_en = '<p class="company-info"><small>E&amp;K Bieniek Sp. z o.o., ul. Dumna 35/1, 43-346 Bielsko-Biała, Poland · District Court in Bielsko-Biała, 8th Commercial Division of the National Court Register · KRS 0001149712 · NIP (Tax ID) 9372767895 · Share capital PLN 5,000.00</small></p>';

function panbe_migration_set_content($id, $content)
{
    $result = wp_update_post(array('ID' => $id, 'post_content' => $content), true);
    if (is_wp_error($result)) {
        WP_CLI::error("Page $id: " . $result->get_error_message());
    }
    WP_CLI::log("Page $id updated.");
}

// Replaces an earlier company-info paragraph instead of adding a second one.
function panbe_migration_set_company_info($id, $paragraph)
{
    $content = get_post_field('post_content', $id);
    $content = preg_replace('#\s*<p class="company-info">.*?</p>#s', '', $content);
    panbe_migration_set_content($id, rtrim($content) . "\n\n" . $paragraph);
}

panbe_migration_set_content(184, $policy_pl);
panbe_migration_set_content(3, $policy_en);
panbe_migration_set_company_info(194, $company_pl);
panbe_migration_set_company_info(83, $company_en);
