<?php
/*
Template Name: Pricing
*/
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php get_template_part('content', 'pricing'); ?>
<?php
        get_template_part('modal');
    endwhile;
endif;
?>
<?php get_footer(); ?>