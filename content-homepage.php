<?php
// Homepage sections from ACF fields. Used by page-panbe-homepage.php and by the REST content filter in functions.php.
?>
        <section class="hero wrapper hero__wrapper">
            <div class="hero__text">
                <h1><?php
                    echo esc_html(get_field('hero_title'));
                    ?></h1>
                <?php the_field('hero_text'); ?>
                <div class="hero__buttons">
                    <?php
                    foreach (['cta_button' => 'button primary big', 'cta_button_secondary' => 'button secondary big'] as $ctaField => $ctaClass):
                        $cta = get_field($ctaField);
                        if ($cta): ?>
                            <a class="<?php echo esc_attr($ctaClass); ?>" href="<?php echo esc_url($cta['url']); ?>"<?php echo $cta['target'] ? ' target="' . esc_attr($cta['target']) . '"' : ''; ?>><?php echo esc_html($cta['title']); ?></a>
                    <?php endif;
                    endforeach; ?>
                </div>

            </div>
            <div class="hero__image">
                <?php
                $image = get_field('hero_image');
                if (!empty($image)): ?>

                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>"
                        width="<?php echo esc_attr($image['width']); ?>" height="<?php echo esc_attr($image['height']); ?>" />

                <?php endif; ?>
            </div>
        </section>


        <?php
        // Client work first, as large cards; own projects after it, as small tiles.
        // Reviews sit between them; a client card gets a teaser linking to its review.
        $testimonials = get_field('testimonials') ?: array();
        $reviewByProject = array();
        foreach ($testimonials as $testimonial) {
            foreach (get_field('testimonial_projects', $testimonial->ID) ?: array() as $reviewedProject) {
                $reviewByProject[$reviewedProject->ID] = $testimonial;
            }
        }
        $clientProjects = get_field('client_projects');
        if ($clientProjects): ?>
            <section class="projects wrapper" id="realizacje">
                <h2><?php echo esc_html(get_field('client_projects_title')); ?></h2>

                <div class="projects__list">
                    <?php foreach ($clientProjects as $post):
                        setup_postdata($post); ?>

                        <article class="projects__item">
                            <?php
                            $projectImage = get_field('project_image');
                            if (!empty($projectImage)): ?>
                                <img src="<?php echo esc_url($projectImage['url']); ?>"
                                    alt="<?php echo esc_attr($projectImage['alt']); ?>"
                                    width="<?php echo esc_attr($projectImage['width']); ?>"
                                    height="<?php echo esc_attr($projectImage['height']); ?>" class="projects__image" loading="lazy" />
                            <?php endif; ?>

                            <div class="projects__text">
                                <h3><?php the_title(); ?></h3>
                                <?php
                                $scope = get_field('project_scope');
                                if ($scope): ?>
                                    <p class="projects__scope"><?php echo esc_html($scope); ?></p>
                                <?php endif; ?>
                                <?php the_content(); ?>

                                <?php
                                $review = $reviewByProject[get_the_ID()] ?? null;
                                if ($review): ?>
                                    <a class="projects__review" href="#opinia-<?php echo esc_attr($review->ID); ?>">
                                        <?php echo panbe_stars((int) get_field('testimonial_rating', $review->ID)); ?>
                                        <span>„<?php echo esc_html(get_field('testimonial_teaser', $review->ID) ?: panbe_first_sentence(get_field('testimonial_text', $review->ID))); ?>”</span>
                                        <span class="projects__review-more"><?php echo my_theme_is_polish() ? 'Przeczytaj opinię' : 'Read the review'; ?> ↓</span>
                                    </a>
                                <?php endif; ?>

                                <?php get_template_part('project-buttons'); ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php wp_reset_postdata(); ?>
            </section>
        <?php endif; ?>

        <?php
        if ($testimonials):
            $ratings = array_map(fn($t) => (int) get_field('testimonial_rating', $t->ID), $testimonials);
            $average = array_sum($ratings) / count($ratings);
            $allReviews = get_field('testimonials_link'); ?>
            <section class="testimonials wrapper" id="opinie">
                <div class="testimonials__header">
                    <h2><?php echo esc_html(get_field('testimonials_title')); ?></h2>
                    <p class="testimonials__summary">
                        <?php echo panbe_stars((int) round($average)); ?>
                        <strong><?php echo esc_html(number_format_i18n($average, 1)); ?></strong>
                        · <?php echo esc_html(panbe_review_count(count($testimonials))); ?>
                        <?php if ($allReviews): ?>
                            · <a href="<?php echo esc_url($allReviews['url']); ?>" target="_blank" rel="noopener"><?php echo esc_html($allReviews['title']); ?></a>
                        <?php endif; ?>
                    </p>
                </div>

                <ul class="testimonials__list">
                    <?php foreach ($testimonials as $testimonial):
                        $company = get_field('testimonial_company', $testimonial->ID);
                        $date = get_field('testimonial_date', $testimonial->ID);
                        $link = get_field('testimonial_link', $testimonial->ID); ?>
                        <li class="testimonials__item" id="opinia-<?php echo esc_attr($testimonial->ID); ?>">
                            <?php echo panbe_stars((int) get_field('testimonial_rating', $testimonial->ID)); ?>
                            <blockquote><?php echo esc_html(get_field('testimonial_text', $testimonial->ID)); ?></blockquote>
                            <p class="testimonials__author">
                                <strong><?php echo esc_html(get_the_title($testimonial)); ?></strong>
                                <?php if ($company): ?><span><?php echo esc_html($company); ?></span><?php endif; ?>
                                <?php if ($date): ?>
                                    <?php if ($link): ?><a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener"><?php endif; ?>
                                    <span class="testimonials__date"><?php echo esc_html('Google · ' . date_i18n('F Y', strtotime($date))); ?></span>
                                    <?php if ($link): ?></a><?php endif; ?>
                                <?php endif; ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php get_template_part('ai-demo'); ?>

        <?php
        $projects = get_field('projects');
        if ($projects): ?>
            <section class="projects projects--own wrapper">
                <h2><?php echo esc_html(get_field('projects_title')); ?></h2>

                <ul class="projects__tiles">
                    <?php foreach ($projects as $post):
                        setup_postdata($post); ?>

                        <li class="projects__tile">
                            <h3><?php the_title(); ?></h3>
                            <?php
                            $scope = get_field('project_scope');
                            if ($scope): ?>
                                <p class="projects__scope"><?php echo esc_html($scope); ?></p>
                            <?php endif; ?>
                            <?php get_template_part('project-buttons'); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php
                wp_reset_postdata();
                $more = get_field('projects_more_link');
                if ($more): ?>
                    <a class="projects__more" href="<?php echo esc_url($more['url']); ?>" target="_blank" rel="noopener"><?php echo esc_html($more['title']); ?> →</a>
                <?php endif; ?>
            </section>
        <?php endif; ?>


        <?php
        $skills = get_field('skills');
        if ($skills): ?>
            <section class="skills wrapper section-grid">
                <h2><?php
                    echo esc_html(get_field('skills_title'));
                    ?></h2>
                <ul class="skills__tags">
                    <?php foreach ($skills as $skill): ?>
                        <li class="skills__tag"><?php echo $skill; ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php
        $contactLinks = get_field('contact_links');
        if ($contactLinks): ?>
            <section class="contact wrapper section-grid">
                <h2><?php
                    echo esc_html(get_field('contact_title'));
                    ?></h2>
                <ul class="contact__links">
                    <?php foreach ($contactLinks as $post):

                        // Setup this post for WP functions (variable must be named $post).
                        setup_postdata($post); ?>

                        <?php
                        if (get_field('link_type') == 'Email Link') {
                            $link = esc_url('mailto:' . get_field('contact_email'));
                        } elseif (get_field('link_type') == 'Website Link') {
                            $link = esc_attr(get_field('contact_url'));
                        }
                        ?>

                        <li class="contact__item">
                            <a href="<?php echo $link; ?>" class="contact__link">

                                <?php
                                $image = get_field('contact_icon');
                                if (!empty($image)): ?>
                                    <img class="contact__icon" src="<?php echo esc_url($image['url']); ?>"
                                        alt="<?php echo esc_attr($image['alt']); ?>" />
                                <?php endif; ?>

                                <?php
                                echo esc_html(get_field('contact_text'));
                                ?>

                            </a>
                        </li>
                    <?php endforeach; ?>

                    <?php
                    // Reset the global post object so that the rest of the page works correctly.
                    wp_reset_postdata(); ?>
                </ul>
            </section>
        <?php endif; ?>
