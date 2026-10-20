<?php
// Blog for salons: "Dla salonów" category, three PL posts (drafts unless run with the `publish` argument),
// posts page title + intro, and the PL posts page slug blog-2 -> porady (functions.php redirects the old URL).
// Run: wp eval-file wp-content/themes/panbe/migrations/2026-10-19-blog-for-salons.php [publish]
// Safe to run twice: everything is looked up by slug and updated in place.
if (!defined('WP_CLI')) {
    exit;
}

$publish = in_array('publish', $args ?? array(), true);

$term = get_term_by('slug', 'dla-salonow', 'category');
$term_id = $term ? (int) $term->term_id : (int) wp_insert_term('Dla salonów', 'category', array('slug' => 'dla-salonow'))['term_id'];
if (function_exists('pll_set_term_language')) {
    pll_set_term_language($term_id, 'pl');
}
WP_CLI::log("Category Dla salonów: $term_id");

$posts = array();
$posts[] = array(
    'slug' => 'klientki-z-map-google-dla-salonu',
    'title' => 'Jak salon może zdobywać klientki z Map Google: 9 rzeczy do zrobienia w jedno popołudnie',
    'excerpt' => 'Kiedy ktoś wpisuje „kosmetyczka w pobliżu”, Google pokazuje trzy salony na mapie. Wyjaśniam, od czego zależy, czy będzie wśród nich Twój, i co możesz poprawić sama, za darmo.',
    'date' => '2026-10-19 10:00:00',
    'content' => <<<'PHPEOF'
<p>Wpisz w telefonie „kosmetyczka” albo „brwi” w swoim mieście. Nad zwykłymi wynikami Google pokaże mapę i trzy salony. Większość osób kliknie właśnie w jeden z nich, często bez przewijania dalej.</p>
<p>Dobra wiadomość: o tym, czy będzie tam Twój salon, w dużej mierze decyduje coś, co możesz poprawić sama i za darmo, czyli <strong>Profil Firmy w Google</strong> (dawniej „wizytówka Google” albo Google Moja Firma).</p>
<h2>Od czego zależy pozycja w Mapach</h2>
<p>Google pisze otwarcie w swojej pomocy, że lokalne wyniki opierają się głównie na trzech rzeczach:</p>
<ul>
<li><strong>trafność:</strong> jak dobrze Twój profil pasuje do tego, czego ktoś szuka,</li>
<li><strong>odległość:</strong> jak daleko jesteś od osoby szukającej (albo od miejsca, które wpisała),</li>
<li><strong>rozpoznawalność:</strong> jak znana jest Twoja firma, w tym liczba i ocena opinii, linki do Twojej strony i wzmianki w sieci.</li>
</ul>
<p>Na odległość nie masz wpływu. Na pozostałe dwie rzeczy masz, i to sporo.</p>
<h2>9 rzeczy na jedno popołudnie</h2>
<p><strong>1. Właściwa kategoria główna.</strong> „Salon kosmetyczny”, „Salon makijażu permanentnego”, „Gabinet fizjoterapii”. Wybierz tę, która najlepiej opisuje to, z czego żyjesz, a pozostałe dodaj jako kategorie dodatkowe.</p>
<p><strong>2. Usługi z opisami i cenami.</strong> Dodaj każdy ważny zabieg jako osobną usługę, z krótkim opisem i ceną „od”. To pomaga Google dopasować Cię do konkretnych zapytań, a klientce oszczędza pisania z pytaniem o cenę.</p>
<p><strong>3. Opis firmy pisany dla ludzi.</strong> Kim jesteś, w czym się specjalizujesz, dla kogo pracujesz, gdzie jesteś. Bez upychania słów kluczowych, za to z konkretami: „makijaż permanentny brwi metodą pudrową, Bielsko-Biała, centrum”.</p>
<p><strong>4. Zdjęcia, dużo zdjęć.</strong> Wnętrze, wejście z ulicy (żeby klientka trafiła), Ty przy pracy, efekty zabiegów (za zgodą klientek). Dorzucaj nowe co jakiś czas, zamiast wrzucić wszystko raz.</p>
<p><strong>5. Godziny otwarcia, które się zgadzają.</strong> Także te świąteczne. Klientka, która przyjdzie pod zamknięte drzwi, nie wróci, a do tego może zostawić niezbyt miłą opinię.</p>
<p><strong>6. Link do strony i do rezerwacji.</strong> W profilu możesz podać adres strony i link do umawiania wizyt. Jeśli korzystasz z Booksy, rezerwacja przez Twój własny link zwykle wychodzi korzystniej niż przez wyszukiwarkę w aplikacji (więcej o tym w osobnym wpisie).</p>
<p><strong>7. Proś o opinie, regularnie.</strong> Najprościej: po zabiegu, kiedy klientka jest zadowolona, poproś ją o opinię i wyślij link SMS-em. Krótki skrót do wystawienia opinii znajdziesz w panelu profilu. Nie kupuj opinii i nie nagradzaj za nie rabatami: to łamie zasady Google i może skończyć się usunięciem opinii.</p>
<p><strong>8. Odpowiadaj na każdą opinię.</strong> Na dobre krótko i po ludzku. Na złe spokojnie i rzeczowo, bez kłótni: tę odpowiedź czytają przyszłe klientki, nie tylko autorka opinii.</p>
<p><strong>9. Te same dane wszędzie.</strong> Nazwa, adres i telefon powinny być identyczne w Profilu Firmy, na stronie, w Booksy i na Facebooku. Rozjazdy w stylu „ul. Mickiewicza 5” i „Mickiewicza 5/2” mieszają Google.</p>
<h2>Gdzie wchodzi strona</h2>
<p>Profil Firmy to wizytówka. Ale kiedy klientka chce dowiedzieć się więcej, klika „Witryna”. Jeśli tam nie ma nic albo jest strona, która nie działa na telefonie, kółko się urywa.</p>
<p>Dobra strona salonu domyka to, co zaczął profil:</p>
<ul>
<li>ma osobne podstrony dla najważniejszych zabiegów, z cenami i odpowiedziami na częste pytania,</li>
<li>pokazuje efekty i opinie,</li>
<li>ma widoczny przycisk „Umów wizytę”,</li>
<li>jest szybka na telefonie.</li>
</ul>
<p>A do tego to, co jest na Twojej stronie, pomaga Google zrozumieć, czym się zajmujesz, czyli wzmacnia trafność z punktu pierwszego.</p>
<h2>Chcesz, żebym zerknął?</h2>
<p>Przejrzę Twój Profil Firmy w Google i stronę i przygotuję bezpłatny, jednostronicowy audyt z listą konkretnych poprawek, od najważniejszej. <a href="/kontakt/">Umów bezpłatną rozmowę</a>.</p>
PHPEOF
);
$posts[] = array(
    'slug' => 'ile-kosztuje-strona-dla-salonu-beauty',
    'title' => 'Ile kosztuje strona internetowa dla salonu beauty w 2026 roku?',
    'excerpt' => 'Od kilkuset złotych za szablon po kilka tysięcy za stronę szytą na miarę. Wyjaśniam, za co faktycznie płacisz, ile kosztuje utrzymanie i jak nie przepłacić.',
    'date' => '2026-10-19 11:00:00',
    'content' => <<<'PHPEOF'
<p>Pytanie „ile kosztuje strona?” słyszę na każdej rozmowie z właścicielką salonu. I za każdym razem odpowiadam tak samo: to zależy, ale nie w ten wymijający sposób, w jaki odpowiada mechanik. Da się to rozpisać konkretnie:</p>
<h2>Trzy drogi i ich prawdziwy koszt</h2>
<h3>1. Kreator i gotowy szablon (Wix, Squarespace, WordPress z motywem za 200 zł).</h3>
<p>Na start najtaniej: abonament kreatora albo hosting i szablon to zwykle kilkaset złotych rocznie. Haczyk jest w czasie. Ktoś musi tę stronę złożyć, napisać teksty, dobrać zdjęcia i zadbać, żeby działała na telefonie. Jeśli robisz to sama wieczorami po zamknięciu salonu, płacisz swoim czasem. A strona złożona „na szybko” często wygląda jak sto innych i nie mówi, czym się wyróżniasz.</p>
<h3>2. Strona od freelancera lub małego studia.</h3>
<p>Tu mieści się większość salonów. Prosta wizytówka (strona główna, oferta, cennik, galeria, kontakt) to w Polsce zwykle od około 1500–2000 zł netto, a porządnie zaprojektowana strona z kilkoma podstronami zabiegów i dopracowanym SEO to raczej 3000–6000 zł. Różnica nie bierze się z liczby podstron, tylko z tego, ile pracy idzie w projekt, teksty i widoczność w Google.</p>
<h3>3. Agencja.</h3>
<p>Kilkanaście tysięcy złotych wzwyż. Dostajesz zespół, strategię, sesję zdjęciową. Dla sieci salonów ma to sens, dla jednego gabinetu zwykle nie.</p>
<h2>Za co płacisz, kiedy płacisz więcej</h2>
<p>Dwie strony za 1500 i za 4000 zł mogą wyglądać podobnie na pierwszy rzut oka. Różnice wychodzą później:</p>
<ul>
<li><strong>Telefon przede wszystkim.</strong> Większość Twoich klientek wejdzie na stronę z telefonu, często prosto z Map Google. Jeśli przycisk „Umów wizytę” jest gdzieś na dole albo numer telefonu nie jest klikalny, tracisz rezerwacje.</li>
<li><strong>Podstrony zabiegów.</strong> Klientka nie szuka w Google „salon kosmetyczny”, tylko „makijaż permanentny brwi Bielsko” albo „mezoterapia igłowa cena”. Osobna, dobrze opisana podstrona dla każdego ważnego zabiegu to najprostszy sposób, żeby pojawiać się na takie zapytania.</li>
<li><strong>Szybkość.</strong> Zdjęcia prosto z aparatu potrafią ważyć po kilka megabajtów. Strona, która ładuje się kilka sekund, traci ludzi, zanim cokolwiek zobaczą.</li>
<li><strong>Połączenie z rezerwacjami.</strong> Przycisk Booksy, Versum czy innego systemu powinien być widoczny od razu, a nie schowany w zakładce „Kontakt”.</li>
<li><strong>Teksty.</strong> Dobra strona odpowiada na pytania, które klientki zadają Ci w wiadomościach: ile trwa zabieg, ile kosztuje, jak się przygotować, czy boli.</li>
</ul>
<h2>Koszty, o których mało kto mówi: utrzymanie</h2>
<p>Strona to nie jest jednorazowy zakup jak lustro do salonu. Co roku płacisz za:</p>
<ul>
<li><strong>domenę</strong> (adres typu twojsalon.pl), zwykle kilkadziesiąt do ponad stu złotych rocznie,</li>
<li><strong>hosting</strong> (serwer, na którym strona stoi), od ok. 100 do kilkuset złotych rocznie,</li>
<li><strong>aktualizacje i kopie zapasowe</strong>, jeśli strona stoi na WordPressie. Nieaktualizowana strona po roku czy dwóch to łatwy cel dla włamań.</li>
</ul>
<p>Możesz to robić sama albo oddać w opiekę. U mnie opieka zaczyna się od 149 zł miesięcznie: aktualizacje, kopie zapasowe, monitoring i podstawowe SEO. Wyższy pakiet obejmuje też regularne wpisy na blogu i godzinę pracy programisty w miesiącu.</p>
<h2>Moje ceny, żebyś nie musiała zgadywać</h2>
<p>Nie lubię cenników „zapytaj o wycenę”, więc piszę wprost (ceny netto):</p>
<ul>
<li><strong>Mini, od 1800 zł:</strong> prosta wizytówka z galerią i kontaktem.</li>
<li><strong>Standard, od 3000 zł:</strong> to pakiet, który polecam większości salonów: dodatkowe podstrony na najważniejsze zabiegi, formularz kontaktowy, wizytówka Google, opcjonalnie druga wersja językowa.</li>
<li><strong>Business, od 4500 zł:</strong> jeszcze więcej podstron, domena w cenie i dodatkowe skrzynki e-mail.</li>
</ul>
<p>Hosting jest w cenie przez pierwszy rok. Do każdego pakietu mogę dołożyć asystenta AI, który o 23:00 odpowie klientce, ile kosztuje zabieg i jak się przygotować, a potem pokieruje ją do rezerwacji. Szczegóły są na stronie <a href="/pricing/">Oferta</a>.</p>
<h2>Jak nie przepłacić</h2>
<ol>
<li><strong>Zapytaj, czyja będzie strona.</strong> Domena powinna być zarejestrowana na Ciebie, a Ty powinnaś mieć dostęp do panelu. Inaczej przy zmianie wykonawcy zaczynasz od zera.</li>
<li><strong>Poproś o przykłady stron salonów</strong> i otwórz je na swoim telefonie.</li>
<li><strong>Sprawdź, co jest w cenie</strong>: teksty, zdjęcia, podłączenie Booksy, wizytówka Google, poprawki po starcie.</li>
<li><strong>Zapytaj o koszty po pierwszym roku.</strong> Tani start z drogim utrzymaniem to częsta pułapka.</li>
</ol>
<h2>Od czego zacząć</h2>
<p>Jeśli masz już stronę i nie wiesz, czy jest sens ją zmieniać, przygotuję Ci bezpłatny, jednostronicowy audyt: co działa, co odstrasza klientki i co poprawić najpierw. Bez zobowiązań. <a href="/kontakt/">Umów bezpłatną rozmowę</a>.</p>
PHPEOF
);
$posts[] = array(
    'slug' => 'booksy-czy-wlasna-strona',
    'title' => 'Booksy czy własna strona? Dlaczego salon potrzebuje obu',
    'excerpt' => 'Booksy świetnie zbiera rezerwacje, ale to nie Twoja wizytówka, tylko Twoje miejsce na cudzym targowisku. Wyjaśniam, jak połączyć jedno z drugim i nie płacić prowizji tam, gdzie nie trzeba.',
    'date' => '2026-10-19 12:00:00',
    'content' => <<<'PHPEOF'
<p>„Mam Booksy, po co mi strona?” To jedno z najczęstszych pytań, jakie słyszę od właścicielek salonów. I jest całkiem uczciwe: Booksy działa, klientki je znają, kalendarz się zapełnia. Więc po co płacić za coś jeszcze?</p>
<p>Krótka odpowiedź: Booksy i strona robią dwie różne rzeczy. Booksy jest od <strong>rezerwowania</strong>. Strona jest od <strong>przekonania</strong>, że to właśnie u Ciebie warto zarezerwować.</p>
<h2>Co daje Booksy</h2>
<p>Nie będę udawał, że Booksy jest zbędne, bo nie jest:</p>
<ul>
<li>klientki mogą się zapisać o każdej porze, bez dzwonienia,</li>
<li>przypomnienia SMS ograniczają nieobecności,</li>
<li>profil w aplikacji to dodatkowe miejsce, w którym ktoś może Cię znaleźć.</li>
</ul>
<h2>Czego Booksy nie da</h2>
<p><strong>Twój profil stoi obok konkurencji.</strong> Klientka, która szuka w aplikacji „brwi”, widzi listę salonów jeden pod drugim, z cenami i ocenami. Jeden ruch kciukiem i jest u kogoś innego.</p>
<p><strong>Nie masz wpływu na wygląd.</strong> Każdy profil wygląda tak samo: zdjęcia, lista usług, opinie. Nie opowiesz o swoim podejściu, nie pokażesz certyfikatów tak, jak chcesz, nie wytłumaczysz, czym Twój zabieg różni się od tańszego za rogiem.</p>
<p><strong>Google częściej pokazuje strony z treścią.</strong> Kiedy ktoś wpisuje „makijaż permanentny ust Bielsko-Biała”, dobrze opisana podstrona zabiegu na Twojej stronie ma szansę pojawić się w wynikach jako Twoja, a nie jako jeden z wielu profili na platformie.</p>
<p><strong>Prowizja za nowe klientki.</strong> Jeśli korzystasz z Booksy Boost, czyli promowania w aplikacji, Booksy pobiera prowizję od pierwszej wizyty nowej klientki, która trafiła do Ciebie przez Boost. Według strony Booksy standardowa stawka to 45% netto wartości usług z tej pierwszej wizyty (w ramach ofert specjalnych bywa niższa). Przy zabiegu za 800 zł to spora kwota za jedną klientkę.</p>
<h2>Najlepsze z obu światów</h2>
<p>Tu jest sedno: <strong>nie musisz wybierać.</strong> Najlepiej działa połączenie:</p>
<ol>
<li><strong>Strona przyciąga i przekonuje.</strong> Klientka trafia z Google lub Map Google na Twoją stronę, czyta o zabiegu, ogląda efekty, widzi opinie.</li>
<li><strong>Przycisk „Umów wizytę” prowadzi do Twojego kalendarza w Booksy.</strong> Rezerwacja dalej odbywa się w znanym systemie, z przypomnieniami SMS.</li>
<li><strong>Nie płacisz prowizji Boost za te rezerwacje.</strong> Według FAQ cennika Booksy prowizji nie nalicza się za klientki, które zarezerwowały przez Twój własny, bezprowizyjny link do profilu albo integrację. Czyli klientki, które przyprowadziła Twoja strona, są Twoje.</li>
</ol>
<p>Warunki potrafią się zmieniać, więc przed decyzją sprawdź aktualny cennik w panelu Booksy Biz albo u swojego opiekuna. Mechanizm jest jednak prosty: im więcej klientek trafia do Ciebie przez Twoją stronę, a nie przez wyszukiwarkę w aplikacji, tym mniej oddajesz w prowizjach.</p>
<h2>Jak to wygląda w praktyce</h2>
<p>Na dobrej stronie salonu przycisk rezerwacji jest:</p>
<ul>
<li><strong>widoczny od razu</strong>, na górze strony, bez przewijania,</li>
<li><strong>na każdej podstronie zabiegu</strong>, bo klientka decyduje się w momencie, gdy czyta o konkretnym zabiegu,</li>
<li><strong>przyklejony na dole ekranu na telefonie</strong>, żeby nie trzeba go było szukać.</li>
</ul>
<p>Do tego wizytówka Google z linkiem do strony i do rezerwacji, a na stronie odpowiedzi na pytania, które klientki zadają w wiadomościach. Mniej pisania w DM-ach po godzinach, więcej gotowych rezerwacji.</p>
<h2>A co, jeśli nie korzystasz z Booksy?</h2>
<p>Ta sama zasada działa z Versum, Moment, Fresha czy zwykłym formularzem. Strona jest Twoją wizytówką i punktem wejścia, a system rezerwacji tylko narzędziem. Zmienisz system, strona zostaje.</p>
<h2>Chcesz zobaczyć, ile tracisz?</h2>
<p>Przejrzę Twój profil Booksy, wizytówkę Google i stronę (jeśli już jakąś masz) i przygotuję bezpłatny, jednostronicowy audyt: gdzie klientki odpadają i co zmienić najpierw. <a href="/kontakt/">Umów bezpłatną rozmowę</a>.</p>
PHPEOF
);

foreach ($posts as $p) {
    $existing = get_posts(array('name' => $p['slug'], 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1, 'lang' => ''));
    $data = array(
        'post_type' => 'post',
        'post_title' => $p['title'],
        'post_name' => $p['slug'],
        'post_excerpt' => $p['excerpt'],
        'post_content' => $p['content'],
        'post_status' => $publish ? 'publish' : ($existing ? $existing[0]->post_status : 'draft'),
        'post_date' => $p['date'],
        'post_date_gmt' => get_gmt_from_date($p['date']),
        'edit_date' => true,
        'comment_status' => 'closed',
        'post_category' => array($term_id),
    );
    if ($existing) {
        $data['ID'] = $existing[0]->ID;
    }
    $id = wp_insert_post(wp_slash($data), true);
    if (is_wp_error($id)) {
        WP_CLI::error($id->get_error_message());
    }
    if (function_exists('pll_set_post_language')) {
        pll_set_post_language($id, 'pl');
    }
    // The header logo reads this per-post field (wp-admin fills its default "PB"; wp_insert_post doesn't).
    update_field('field_6777c9129755b', 'PB', $id);
    WP_CLI::log(sprintf('%s post %d: %s (%s)', $existing ? 'Updated' : 'Created', $id, $p['slug'], get_post_status($id)));
}

// Posts pages: PL title + intro and the new slug; EN keeps its title, gets an intro.
$blog_pl = (int) get_option('page_for_posts');
if (function_exists('pll_get_post')) {
    $blog_pl = (int) pll_get_post($blog_pl, 'pl');
}
if (!$blog_pl) {
    WP_CLI::error('No posts page set.');
}
wp_update_post(array('ID' => $blog_pl, 'post_name' => 'porady'));
update_field('field_67a0786af30ad', 'Porady dla salonów beauty i gabinetów', $blog_pl);
update_field('field_6714b10c0a1e2', 'Jak zdobywać klientki z Google, ile naprawdę kosztuje strona i jak połączyć ją z rezerwacjami. Konkretnie, bez technicznego żargonu.', $blog_pl);
WP_CLI::log("PL posts page $blog_pl: " . get_permalink($blog_pl));
$blog_en = function_exists('pll_get_post') ? (int) pll_get_post($blog_pl, 'en') : 0;
if ($blog_en) {
    update_field('field_6714b10c0a1e2', 'Notes on building websites, web development and working with AI.', $blog_en);
    WP_CLI::log("EN posts page $blog_en: " . get_permalink($blog_en));
}
WP_CLI::success($publish ? 'Done, posts published.' : 'Done, new posts are drafts, existing ones keep their status.');
