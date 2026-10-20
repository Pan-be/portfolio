<?php
// Single post: tag, title, date + reading time, image, content, an audit call to action, then related posts.
get_header();
$panbe_pl = my_theme_is_polish();
$panbe_salon = panbe_salon_category();
$panbe_blog_url = get_permalink(get_option('page_for_posts'));
?>
<div class="wrapper panbe-blog panbe-blog--single">
    <?php if (have_posts()) : while (have_posts()) : the_post();
            $panbe_is_salon = $panbe_salon && has_category($panbe_salon->term_id); ?>
            <article id="post-<?php the_ID(); ?>" class="blog-post">
                <nav class="blog-post__crumbs" aria-label="<?php echo $panbe_pl ? 'Okruszki' : 'Breadcrumbs'; ?>">
                    <a href="<?php echo esc_url($panbe_blog_url); ?>">Blog</a>
                    <?php if ($panbe_is_salon) : ?>
                        <span aria-hidden="true">/</span>
                        <span><?php echo esc_html($panbe_salon->name); ?></span>
                    <?php endif; ?>
                </nav>
                <header class="blog-post__header">
                    <?php if ($panbe_is_salon) : ?>
                        <span class="blog-card__tag"><?php echo esc_html($panbe_salon->name); ?></span>
                    <?php endif; ?>
                    <h1 itemprop="headline"><?php the_title(); ?></h1>
                    <p class="blog-card__meta">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished"><?php echo esc_html(get_the_date('j F Y')); ?></time>
                        · <?php echo esc_html(panbe_reading_time()); ?>
                    </p>
                    <?php edit_post_link(); ?>
                </header>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="blog-post__image"><?php the_post_thumbnail('large', array('itemprop' => 'image', 'loading' => 'eager')); ?></div>
                <?php endif; ?>
                <div class="blog-post__content" itemprop="articleBody">
                    <?php the_content(); ?>
                    <?php wp_link_pages(); ?>
                </div>
                <aside class="panbe-blog__cta panbe-blog__cta--post" aria-labelledby="post-cta-heading">
                    <div class="panbe-blog__cta-text">
                        <h2 id="post-cta-heading"><?php echo $panbe_pl ? 'Bezpłatny audyt Twojej strony i wizytówki Google' : 'A free audit of your website'; ?></h2>
                        <p><?php echo $panbe_pl ? 'Jedna strona A4: co działa, gdzie klientki odpadają i co poprawić najpierw. Bez zobowiązań.' : 'One page: what works, where visitors drop off and what to fix first. No strings attached.'; ?></p>
                    </div>
                    <div class="panbe-blog__cta-buttons">
                        <a class="button primary" href="<?php echo esc_url(panbe_contact_url()); ?>"><?php echo $panbe_pl ? 'Umów bezpłatną rozmowę' : 'Book a free call'; ?></a>
                    </div>
                </aside>
            </article>
            <?php
            $panbe_related = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 2,
                'post__not_in' => array(get_the_ID()),
                'category__in' => wp_get_post_categories(get_the_ID()),
                'ignore_sticky_posts' => true,
            ));
            ?>
            <section class="panbe-blog__more blog-post__related" aria-labelledby="related-heading">
                <?php if ($panbe_related->have_posts()) : ?>
                    <h2 id="related-heading"><?php echo $panbe_pl ? 'Czytaj też' : 'Read next'; ?></h2>
                    <div class="panbe-blog__grid">
                        <?php while ($panbe_related->have_posts()) {
                            $panbe_related->the_post();
                            get_template_part('blog-card');
                        }
                        wp_reset_postdata(); ?>
                    </div>
                <?php endif; ?>
                <a class="blog-post__back" href="<?php echo esc_url($panbe_blog_url); ?>">&larr; <?php echo $panbe_pl ? 'Wszystkie wpisy' : 'All posts'; ?></a>
            </section>
    <?php endwhile;
    endif; ?>
</div>
<?php get_footer(); ?>
