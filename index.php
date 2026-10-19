<?php
// Posts page. Posts in the "for salons" category lead (newest as a large card, the rest in a grid);
// other posts (dev topics) follow as a short list. With no salon posts (EN), all posts fill the grid.
get_header();
$panbe_pl = my_theme_is_polish();
$panbe_blog_page = (int) get_option('page_for_posts');
$panbe_salon = panbe_salon_category();
$panbe_lead_posts = array();
$panbe_other_posts = array();
while (have_posts()) {
    the_post();
    if ($panbe_salon && has_category($panbe_salon->term_id)) {
        $panbe_lead_posts[] = get_post();
    } else {
        $panbe_other_posts[] = get_post();
    }
}
if (!$panbe_lead_posts) {
    $panbe_lead_posts = $panbe_other_posts;
    $panbe_other_posts = array();
}
$panbe_featured = (!is_paged() && $panbe_lead_posts) ? array_shift($panbe_lead_posts) : null;
$panbe_title = $panbe_blog_page ? (get_field('blog_title', $panbe_blog_page) ?: get_the_title($panbe_blog_page)) : 'Blog';
$panbe_intro = $panbe_blog_page ? get_field('blog_intro', $panbe_blog_page) : '';
?>
<div class="wrapper panbe-blog">
    <header class="panbe-blog__intro">
        <?php if (strcasecmp($panbe_title, 'Blog') !== 0) : ?>
            <p class="panbe-blog__eyebrow">Blog</p>
        <?php endif; ?>
        <h1><?php echo esc_html($panbe_title); ?></h1>
        <?php if ($panbe_intro) : ?>
            <p class="panbe-blog__lead"><?php echo esc_html($panbe_intro); ?></p>
        <?php endif; ?>
    </header>

    <?php if ($panbe_featured) {
        $post = $panbe_featured;
        setup_postdata($post);
        get_template_part('blog-card', null, array('featured' => true));
    } ?>

    <?php if ($panbe_lead_posts) : ?>
        <section class="panbe-blog__more" aria-labelledby="blog-more-heading">
            <h2 id="blog-more-heading"><?php echo $panbe_pl ? 'Więcej porad' : 'More posts'; ?></h2>
            <div class="panbe-blog__grid">
                <?php foreach ($panbe_lead_posts as $post) {
                    setup_postdata($post);
                    get_template_part('blog-card');
                } ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="panbe-blog__cta" aria-labelledby="blog-cta-heading">
        <div class="panbe-blog__cta-text">
            <h2 id="blog-cta-heading"><?php echo $panbe_pl ? 'Ciekawi Cię, co poprawić u siebie?' : 'Wondering what to fix on your site?'; ?></h2>
            <p><?php echo $panbe_pl
                    ? 'Przejrzę Twoją stronę, wizytówkę Google i profil rezerwacji, a potem przygotuję bezpłatny audyt na jedną stronę: co działa, co odstrasza klientki i od czego zacząć.'
                    : 'I will review your website and Google Business Profile and send you a free one-page audit: what works, what puts visitors off and what to fix first.'; ?></p>
        </div>
        <div class="panbe-blog__cta-buttons">
            <a class="button primary" href="<?php echo esc_url(panbe_contact_url()); ?>"><?php echo $panbe_pl ? 'Umów bezpłatną rozmowę' : 'Book a free call'; ?></a>
            <a class="button secondary" href="<?php echo esc_url(home_url($panbe_pl ? '/#realizacje' : '/en/panbe-homepage/#realizacje')); ?>"><?php echo $panbe_pl ? 'Zobacz realizacje' : 'See my work'; ?></a>
        </div>
    </section>

    <?php if ($panbe_other_posts) : ?>
        <section class="panbe-blog__dev" aria-labelledby="blog-dev-heading">
            <h2 id="blog-dev-heading"><?php echo $panbe_pl ? 'Z warsztatu programisty' : 'From the developer\'s desk'; ?></h2>
            <p><?php echo $panbe_pl ? 'Wpisy techniczne, raczej dla innych programistów niż dla salonów.' : 'Technical posts, mostly for other developers.'; ?></p>
            <ul class="panbe-blog__dev-list">
                <?php foreach ($panbe_other_posts as $post) :
                    setup_postdata($post); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif;
    wp_reset_postdata(); ?>

    <?php get_template_part('nav', 'below'); ?>
</div>
<?php get_footer(); ?>
