<?php
/*
Template Name: Panbe Homepage
*/
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <section class="hero wrapper hero__wrapper">
            <div class="hero__text">
                <h1><?php
                    echo esc_html(get_field('hero_title'));
                    ?></h1>
                <?php the_field('hero_text'); ?>
                <?php
                $cta = get_field('cta_button');

                if ($cta):

                    $cta_url = $cta['url'];
                    $cta_title = $cta['title'];
                    $cta_target = $cta['target'] ? $cta['target'] : '_self';
                ?>
                    <a class="button primary big" href="<?php echo esc_url($cta_url); ?>"><?php echo esc_html($cta_title); ?></a>
                <?php endif; ?>

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
        $projects = get_field('projects');
        if ($projects): ?>
            <section class="projects wrapper">
                <h2><?php echo esc_html(get_field('projects_title')); ?></h2>

                <div class="carousel">
                    <div class="carousel-content">
                        <?php foreach ($projects as $post):
                            setup_postdata($post); ?>

                            <div class="carousel-item">
                                <div class="projects__item">
                                    <?php
                                    $projectImage = get_field('project_image');
                                    if (!empty($projectImage)): ?>
                                        <img src="<?php echo esc_url($projectImage['url']); ?>"
                                            alt="<?php echo esc_attr($projectImage['alt']); ?>"
                                            width="<?php echo esc_attr($projectImage['width']); ?>"
                                            height="<?php echo esc_attr($projectImage['height']); ?>" class="projects__image" />
                                    <?php endif; ?>

                                    <div class="projects__text">
                                        <h3><?php the_title(); ?></h3>
                                        <?php the_content(); ?>
                                        <div class="projects__buttons">
                                            <?php
                                            $websiteLink = get_field('website_button_link');
                                            if (!empty($websiteLink)): ?>
                                                <a target="_blank" href="<?php echo esc_attr($websiteLink); ?>" class="button primary">
                                                    <?php echo esc_html(get_field('website_button_text')); ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php
                                            $githubLink = get_field('github_button_link');
                                            if (!empty($githubLink)): ?>
                                                <a target="_blank" href="<?php echo esc_attr($githubLink); ?>" class="button secondary">
                                                    <?php echo esc_html(get_field('github_button_text')); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="carousel-prev" aria-label="Previous slide">❮</button>
                    <button class="carousel-next" aria-label="Next slide">❯</button>
                </div>

                <?php wp_reset_postdata(); ?>
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
<?php endwhile;
endif; ?>
<?php get_footer(); ?>