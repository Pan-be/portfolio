<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php panbe_schema_type(); ?>>

<head>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JHD02FRXD5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-JHD02FRXD5');
    </script>

    <?php
    // Tagline (Settings > General, translated per language by Polylang); English fallback while it's empty.
    $panbe_description = get_bloginfo('description');
    if ($panbe_description === '') {
        $panbe_description = 'Pan.Be - Freelance Web Developer. I build modern, responsive websites, e-commerce platforms, and custom web applications.';
    }
    $panbe_title = wp_get_document_title();
    ?>
    <meta name="description" content="<?php echo esc_attr($panbe_description); ?>">
    <meta name="keywords" content="freelance web developer, web development, WordPress, e-commerce, frontend, backend">
    <meta name="author" content="Pan.Be">

    <meta property="og:title" content="<?php echo esc_attr($panbe_title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($panbe_description); ?>">
    <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/panbe_hero.webp">
    <meta property="og:url" content="<?php echo get_permalink(); ?>">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($panbe_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($panbe_description); ?>">
    <meta name="twitter:image" content="<?php echo get_template_directory_uri(); ?>/panbe_hero.webp">


    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <?php wp_head(); ?>
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/site.webmanifest">
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <header class="header wrapper header__wrapper">
        <a href="/" class="header__home">
            <?php
            echo esc_html(get_field('header_initials'));
            ?>
        </a>
        <nav>
            <span id="nav-label" hidden>Navigation</span>
            <button id="btnOpen" class="header__nav-open" aria-expanded="false" aria-labelledby="nav-label">
                <img src="<?php echo get_template_directory_uri(); ?>/icons/hamburger.svg" alt="" width="40"
                    height="24">
            </button>
            <div class="header__menu" role="dialog" aria-labelledby="nav-label">
                <button id="btnClose" aria-label="Close" class="header__nav-close">
                    <img src="<?php echo get_template_directory_uri(); ?>/icons/close.svg" width="28" height="27">
                </button>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'main-menu',
                    'menu_class' => 'header__nav-links',
                    'container' => null,
                    'walker' => new Custom_Nav_Walker()

                ));
                ?>
            </div>


        </nav>
    </header>
    <main id="content" role="main">