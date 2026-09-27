<?php get_header(); ?>
<main class="wrapper page-wrapper">
    <header class="header">
        <h1 class="entry-title" itemprop="name"><?php the_archive_title(); ?></h1>
        <div class="archive-meta" itemprop="description">
            <?php if ('' != get_the_archive_description()) {
                echo esc_html(get_the_archive_description());
            } ?></div>
    </header>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php get_template_part('entry-multi'); ?>
    <?php endwhile;
    endif; ?>
</main>
<footer class="footer"><?php get_template_part('nav', 'below'); ?></footer>
<?php get_footer(); ?>