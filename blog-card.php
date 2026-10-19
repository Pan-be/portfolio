<?php
// One post on the posts page. $args['featured']: the newest post, as a large card with a button.
$panbe_featured = !empty($args['featured']);
$panbe_pl = my_theme_is_polish();
$panbe_salon = panbe_salon_category();
?>
<?php // Plain class, not post_class(): its "post" class pulls in the old .post rules (italic links, centred grid). ?>
<article id="post-<?php the_ID(); ?>" class="blog-card<?php echo $panbe_featured ? ' blog-card--featured' : ''; ?><?php echo has_post_thumbnail() ? '' : ' blog-card--no-image'; ?>">
    <?php if (has_post_thumbnail()) : ?>
        <a class="blog-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php the_post_thumbnail($panbe_featured ? 'large' : 'medium', array('loading' => $panbe_featured ? 'eager' : 'lazy', 'alt' => '')); ?>
        </a>
    <?php endif; ?>
    <div class="blog-card__body">
        <?php if ($panbe_salon && has_category($panbe_salon->term_id)) : ?>
            <span class="blog-card__tag"><?php echo esc_html($panbe_salon->name); ?></span>
        <?php endif; ?>
        <?php if ($panbe_featured) : ?>
            <h2 class="blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php else : ?>
            <h3 class="blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php endif; ?>
        <p class="blog-card__excerpt"><?php echo esc_html(wp_trim_words(strip_shortcodes(has_excerpt() ? get_post()->post_excerpt : get_post()->post_content), $panbe_featured ? 40 : 26, '…')); ?></p>
        <p class="blog-card__meta">
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time>
            · <?php echo esc_html(panbe_reading_time()); ?>
        </p>
        <?php if ($panbe_featured) : ?>
            <a class="button primary blog-card__button" href="<?php the_permalink(); ?>"><?php echo $panbe_pl ? 'Czytaj wpis' : 'Read the post'; ?></a>
        <?php endif; ?>
    </div>
</article>
