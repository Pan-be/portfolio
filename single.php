<?php get_header(); ?>
<div class="post">

    <main class="wrapper">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php get_template_part('entry'); ?>
                <?php if (comments_open() && !post_password_required()) {
                    comments_template('', true);
                } ?>
        <?php endwhile;
        endif; ?>
    </main>
    <footer class="footer">
        <?php get_template_part('nav', 'below-single'); ?>
    </footer>
</div>
<?php get_footer(); ?>