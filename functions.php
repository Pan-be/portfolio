<?php
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
    echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__('Skip to the content', 'panbe') . '</a>';
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
