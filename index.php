<?php
get_header();
?>
<main class="wrapper page-wrapper">
    <?php

    if (is_home() && !is_front_page()) {
        echo '<header class="page-header">';
        echo '<h1 class="page-title">' . get_the_title(get_option('page_for_posts')) . '</h1>';
        echo '</header>';
    } ?>

    <div class="post__wrapper">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
                get_template_part('entry-multi');
                comments_template();
            endwhile;
        endif;
        ?>
    </div>
</main>
<?php
get_template_part('nav', 'below'); ?>
<?php get_footer(); ?>