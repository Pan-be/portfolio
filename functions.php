<?php
require_once get_template_directory() . '/inc/chat-demo.php';

add_action('after_setup_theme', 'panbe_setup');
function panbe_setup()
{
    load_theme_textdomain('panbe', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form', 'navigation-widgets'));
    add_theme_support('appearance-tools');
    add_theme_support('woocommerce');
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1920;
    }
    register_nav_menus(array(
        'main-menu'   => esc_html__('Main Menu', 'panbe'),
        'bottom-menu' => esc_html__('Bottom Menu', 'panbe'),
    ));


    add_image_size('blog-thumbnail', 300, 9999, false);
}
function register_acf_feature_translations()
{
    if (function_exists('pll_register_string')) {
        $features_group = acf_get_fields('group_67c4d66460ffa'); // Pobranie wszystkich pól z grupy

        if (is_array($features_group)) {
            foreach ($features_group as $field) {
                if ($field) {
                    // Rejestrujemy nazwę pola w Polylang
                    pll_register_string('acf_feature_' . $field['name'], $field['label'], 'ACF Features');
                }
            }
        }
    }
}
add_action('init', 'register_acf_feature_translations');
add_action('wp_enqueue_scripts', 'panbe_enqueue');
function panbe_enqueue()
{
    // Version = file mtime: a deploy that changes the file busts browser and SG Optimizer caches.
    wp_enqueue_style('panbe-style', get_stylesheet_directory_uri() . '/scss/style.css', array(), filemtime(get_stylesheet_directory() . '/scss/style.css'));
    wp_enqueue_script('panbe-script', get_stylesheet_directory_uri() . '/js/script.js', array(), filemtime(get_stylesheet_directory() . '/js/script.js'), true);
    wp_enqueue_script('panbe-consent', get_stylesheet_directory_uri() . '/js/consent.js', array(), filemtime(get_stylesheet_directory() . '/js/consent.js'), true);
    if (is_front_page()) {
        wp_enqueue_script('panbe-chat-demo', get_stylesheet_directory_uri() . '/js/chat-demo.js', array(), filemtime(get_stylesheet_directory() . '/js/chat-demo.js'), true);
    }

    wp_enqueue_script('jquery');
}
add_action('wp_footer', 'panbe_footer');
function panbe_footer()
{
?>
    <script>
        jQuery(document).ready(function($) {
            var deviceAgent = navigator.userAgent.toLowerCase();
            if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
                $("html").addClass("ios");
                $("html").addClass("mobile");
            }
            if (deviceAgent.match(/(Android)/)) {
                $("html").addClass("android");
                $("html").addClass("mobile");
            }
            if (navigator.userAgent.search("MSIE") >= 0) {
                $("html").addClass("ie");
            } else if (navigator.userAgent.search("Chrome") >= 0) {
                $("html").addClass("chrome");
            } else if (navigator.userAgent.search("Firefox") >= 0) {
                $("html").addClass("firefox");
            } else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
                $("html").addClass("safari");
            } else if (navigator.userAgent.search("Opera") >= 0) {
                $("html").addClass("opera");
            }
        });
    </script>
<?php
}
add_filter('document_title_separator', 'panbe_document_title_separator');
function panbe_document_title_separator($sep)
{
    $sep = esc_html('|');
    return $sep;
}
// Front page: short per-language title instead of "site name | full tagline" (95+ chars, cut off in search results).
add_filter('document_title_parts', 'panbe_front_page_title_parts');
function panbe_front_page_title_parts($parts)
{
    if (!is_front_page()) {
        return $parts;
    }
    $parts['title'] = my_theme_is_polish()
        ? 'Strony internetowe, sklepy i aplikacje web'
        : 'Websites, E-commerce & Web Apps';
    $parts['site'] = get_bloginfo('name');
    unset($parts['tagline']);
    return $parts;
}
add_filter('the_title', 'panbe_title');
function panbe_title($title)
{
    if ($title == '') {
        return esc_html('...');
    } else {
        return wp_kses_post($title);
    }
}
function panbe_schema_type()
{
    $schema = 'https://schema.org/';
    if (is_single()) {
        $type = "Article";
    } elseif (is_author()) {
        $type = 'ProfilePage';
    } elseif (is_search()) {
        $type = 'SearchResultsPage';
    } else {
        $type = 'WebPage';
    }
    echo 'itemscope itemtype="' . esc_url($schema) . esc_attr($type) . '"';
}
add_filter('nav_menu_link_attributes', 'panbe_schema_url', 10);
function panbe_schema_url($atts)
{
    $atts['itemprop'] = 'url';
    return $atts;
}
if (!function_exists('panbe_wp_body_open')) {
    function panbe_wp_body_open()
    {
        do_action('wp_body_open');
    }
}
add_action('wp_body_open', 'panbe_skip_link', 5);
function panbe_skip_link()
{
    $label = my_theme_is_polish() ? 'Przejdź do treści' : 'Skip to the content';
    echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html($label) . '</a>';
}
add_filter('the_content_more_link', 'panbe_read_more_link');
function panbe_read_more_link()
{
    if (!is_admin()) {
        return ' <a href="' . esc_url(get_permalink()) . '" class="more-link">' . sprintf(__('...%s', 'panbe'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
    }
}
add_filter('excerpt_more', 'panbe_excerpt_read_more_link');
function panbe_excerpt_read_more_link($more)
{
    if (!is_admin()) {
        global $post;
        return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link">' . sprintf(__('...%s', 'panbe'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
    }
}
add_filter('big_image_size_threshold', '__return_false');
add_filter('intermediate_image_sizes_advanced', 'panbe_image_insert_override');
function panbe_image_insert_override($sizes)
{
    unset($sizes['medium_large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    return $sizes;
}
add_action('widgets_init', 'panbe_widgets_init');
function panbe_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar Widget Area', 'panbe'),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('wp_head', 'panbe_pingback_header');
function panbe_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('comment_form_before', 'panbe_enqueue_comment_reply_script');
function panbe_enqueue_comment_reply_script()
{
    if (get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
function panbe_custom_pings($comment)
{
?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url(comment_author_link()); ?>
    </li>
<?php
}
add_filter('get_comments_number', 'panbe_comment_count', 0);
function panbe_comment_count($count)
{
    if (!is_admin()) {
        global $id;
        $get_comments = get_comments('status=approve&post_id=' . $id);
        $comments_by_type = separate_comments($get_comments);
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}

class Custom_Nav_Walker extends Walker_Nav_Menu
{
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        // Sprawdź, jakie menu jest renderowane i przypisz odpowiednie klasy
        if ($args->theme_location === 'bottom-menu') {
            $classes[] = 'bottom__nav-item';
            $link_class = 'bottom__nav-link';
        } else {
            $classes[] = 'header__nav-item';
            $link_class = 'header__nav-link';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= '<li' . $class_names . '>';

        // Dostosowanie <a>
        $attributes  = !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= ' class="' . esc_attr($link_class) . '"';

        $output .= '<a' . $attributes . '>';
        $output .= apply_filters('the_title', $item->title, $item->ID);
        $output .= '</a>';
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        // Dodajemy switcher tylko dla menu nagłówkowego
        if ($args->theme_location === 'header-menu' && function_exists('pll_get_the_languages')) {
            $languages = pll_get_the_languages(array('raw' => 1));
            foreach ($languages as $lang) {
                $output .= '<li class="header__nav-item">';
                $output .= '<a href="' . esc_url($lang['url']) . '" class="header__nav-link">';
                $output .= '<img src="' . esc_url($lang['flag']) . '" alt="' . esc_attr($lang['name']) . '" width="16" height="11">';
                $output .= '</a>';
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
    }
}

//remove _EN from strings
function remove_en($string)
{
    if (str_ends_with($string, '_EN')) {
        // Usuwamy "_EN" z końca ciągu
        return substr($string, 0, -3);  // "-3" oznacza usunięcie ostatnich trzech znaków
    }
    return $string;  // Jeśli "_EN" nie występuje, zwrócimy oryginalny ciąg
}

function custom_the_title($title)
{
    // Sprawdzamy, czy jesteśmy na frontendzie (nie w panelu administracyjnym)
    if (!is_admin()) {
        // Usuwamy "_EN" z tytułu, jeśli występuje
        return remove_en($title);
    }

    // W panelu administracyjnym zwracamy pełny tytuł
    return $title;
}
add_filter('the_title', 'custom_the_title');


//helper to locations
function my_theme_get_current_language()
{
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    return 'pl'; // Domyślny język
}

function my_theme_is_polish()
{
    return my_theme_get_current_language() == 'pl';
}

function my_theme_is_english()
{
    return my_theme_get_current_language() == 'en';
}

// REST API: the homepage and pricing templates build their text from ACF fields, so
// content.rendered was almost empty. Render the same sections there (view context only).
add_filter('rest_prepare_page', 'panbe_rest_acf_content', 10, 3);
function panbe_rest_acf_content($response, $page, $request)
{
    if ($request['context'] === 'edit') {
        return $response;
    }

    // Same template the front end uses: assigned template, else page-{slug}.php.
    $template = get_page_template_slug($page) ?: 'page-' . $page->post_name . '.php';
    $parts = array(
        'page-panbe-homepage.php' => 'homepage',
        'page-pricing.php' => 'pricing',
    );
    if (!isset($parts[$template])) {
        return $response;
    }

    global $wp_query, $post;
    $saved_query = $wp_query;
    $saved_post = $post;

    // Render in the page's own language (my_theme_is_polish() reads the current language).
    $saved_lang = null;
    if (function_exists('PLL') && function_exists('pll_get_post_language')) {
        $saved_lang = PLL()->curlang;
        $page_lang = PLL()->model->get_language(pll_get_post_language($page->ID));
        if ($page_lang) {
            PLL()->curlang = $page_lang;
        }
    }

    $wp_query = new WP_Query(array('page_id' => $page->ID));
    ob_start();
    while ($wp_query->have_posts()) {
        $wp_query->the_post();
        get_template_part('content', $parts[$template]);
    }
    $html = ob_get_clean();

    $wp_query = $saved_query;
    $post = $saved_post;
    if ($post) {
        setup_postdata($post);
    }
    if (function_exists('PLL')) {
        PLL()->curlang = $saved_lang;
    }

    $data = $response->get_data();
    $data['content']['rendered'] = $html;
    $response->set_data($data);
    return $response;
}

// Dodaj obsługę AJAX dla Contact Form 7
add_action('wp_ajax_nopriv_cf7_ajax_submit', 'custom_cf7_ajax_submit');
add_action('wp_ajax_cf7_ajax_submit', 'custom_cf7_ajax_submit');

function custom_cf7_ajax_submit()
{
    // Sprawdź, czy to żądanie Contact Form 7
    if (!defined('WPCF7_VERSION')) {
        wp_send_json_error('Contact Form 7 not active');
        wp_die();
    }

    // Sprawdź dane POST
    if (empty($_POST)) {
        wp_send_json_error('No data received');
        wp_die();
    }

    // Pobierz ID formularza
    $form_id = isset($_POST['_wpcf7']) ? intval($_POST['_wpcf7']) : 0;

    // Załaduj formularz bezpośrednio
    $cf7 = WPCF7_ContactForm::get_instance($form_id);

    if (!$cf7) {
        wp_send_json_error('Form not found');
        wp_die();
    }

    // Przetwórz formularz
    $result = $cf7->submit();

    // Wyślij odpowiedź
    if ($result['status'] === 'mail_sent') {
        wp_send_json_success([
            'status' => 'mail_sent',
            'message' => $result['message']
        ]);
    } else {
        // Pass CF7's real status (validation_failed, spam, mail_failed, ...): they share one generic message.
        wp_send_json_error([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    wp_die();
}

add_filter('wpcf7_load_js', '__return_true');
add_filter('wpcf7_load_css', '__return_true');

// Star rating for reviews, e.g. ★★★★★ with a text label for screen readers.
function panbe_stars($rating)
{
    $rating = max(0, min(5, (int) $rating));
    $label = my_theme_is_polish() ? sprintf('Ocena %d na 5', $rating) : sprintf('Rated %d out of 5', $rating);
    return '<span class="stars" role="img" aria-label="' . esc_attr($label) . '">' . str_repeat('★', $rating) . str_repeat('☆', 5 - $rating) . '</span>';
}

// First sentence of a review, for the teaser on a project card.
function panbe_first_sentence($text, $max = 110)
{
    $text = trim(preg_replace('/\s+/u', ' ', (string) $text));
    if (preg_match('/^.+?[.!?](?=\s|$)/u', $text, $m)) {
        $text = $m[0];
    }
    return mb_strlen($text) > $max ? rtrim(mb_substr($text, 0, $max - 1)) . '…' : $text;
}

// "3 opinie w Google" / "3 reviews on Google", with Polish plural forms.
function panbe_review_count($count)
{
    if (!my_theme_is_polish()) {
        return sprintf($count === 1 ? '%d review on Google' : '%d reviews on Google', $count);
    }
    $lastTwo = $count % 100;
    $last = $count % 10;
    if ($count === 1) {
        $word = 'opinia';
    } elseif ($last >= 2 && $last <= 4 && ($lastTwo < 12 || $lastTwo > 14)) {
        $word = 'opinie';
    } else {
        $word = 'opinii';
    }
    return sprintf('%d %s w Google', $count, $word);
}

// Blog: posts in the "for salons" category lead the posts page; the rest go to a smaller list below it.
function panbe_salon_category()
{
    foreach (array('dla-salonow', 'for-salons') as $slug) {
        $term = get_term_by('slug', $slug, 'category');
        if ($term && (!function_exists('pll_get_term_language') || pll_get_term_language($term->term_id) === my_theme_get_current_language())) {
            return $term;
        }
    }
    return null;
}

// "5 min czytania" / "5 min read", at about 200 words a minute.
function panbe_reading_time($post = null)
{
    $text = wp_strip_all_tags(strip_shortcodes(get_post_field('post_content', $post)));
    $words = count(preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY));
    $minutes = max(1, (int) round($words / 200));
    return my_theme_is_polish() ? sprintf('%d min czytania', $minutes) : sprintf('%d min read', $minutes);
}

// Contact page in the current language (PL /kontakt/, EN its Polylang translation).
function panbe_contact_url()
{
    $page = get_page_by_path('kontakt');
    if (!$page) {
        return home_url('/');
    }
    if (function_exists('pll_get_post') && pll_get_post($page->ID)) {
        return get_permalink(pll_get_post($page->ID));
    }
    return get_permalink($page);
}

// Posts page URL (with /page/N/), or '' when this isn't the posts page.
function panbe_posts_page_url()
{
    if (!is_home() || is_front_page()) {
        return '';
    }
    $url = get_permalink(get_option('page_for_posts'));
    $paged = (int) get_query_var('paged');
    if ($paged > 1) {
        $url = trailingslashit($url) . user_trailingslashit('page/' . $paged, 'paged');
    }
    return $url;
}

// WordPress prints a canonical only on single posts and pages; add one for the posts page.
add_action('wp_head', 'panbe_blog_canonical');
function panbe_blog_canonical()
{
    $url = panbe_posts_page_url();
    if ($url !== '') {
        echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n";
    }
}

// og:url for the page being shown. get_permalink() alone gives the first post's URL on the posts page and archives.
function panbe_og_url()
{
    $url = panbe_posts_page_url();
    if ($url !== '') {
        return $url;
    }
    if (is_singular()) {
        return wp_get_canonical_url() ?: get_permalink();
    }
    $path = (string) wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    return home_url($path);
}

// Pages renamed in 2026-10: PL posts page /blog-2/ → /porady/, PL pricing /pricing/ → /cennik/, EN pricing /en/pricing-2/ → /en/pricing/.
// Pages keep no old-slug record, so redirect by hand (sub-paths like /blog-2/page/2/ too).
add_action('template_redirect', 'panbe_old_slug_redirect', 1);
function panbe_old_slug_redirect()
{
    $moved = array(
        'blog-2' => 'porady',
        'pricing' => 'cennik',
        'en/pricing-2' => 'en/pricing',
    );
    $path = trim((string) wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    foreach ($moved as $old => $new) {
        if ($path === $old || str_starts_with($path, $old . '/')) {
            wp_safe_redirect(home_url(user_trailingslashit('/' . $new . substr($path, strlen($old)))), 301);
            exit;
        }
    }
}

// Author archives, ?author=N and the public users endpoint all reveal the `admin` login. Send the archives home, hide the endpoint from visitors.
add_action('template_redirect', 'panbe_author_archive_redirect', 1);
function panbe_author_archive_redirect()
{
    if (is_author() || isset($_GET['author'])) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
}

add_filter('rest_endpoints', 'panbe_hide_rest_users');
function panbe_hide_rest_users($endpoints)
{
    if (!is_user_logged_in()) {
        unset($endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
}
