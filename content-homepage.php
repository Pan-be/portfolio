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
                                $testimonial = get_field('testimonial_text');
                                if ($testimonial):
                                    $testimonialLink = get_field('testimonial_link'); ?>
                                    <figure class="projects__quote">
                                        <blockquote><?php echo esc_html($testimonial); ?></blockquote>
                                        <figcaption>
                                            <?php echo esc_html(get_field('testimonial_author')); ?>
                                            <?php if ($testimonialLink): ?>
                                                · <a href="<?php echo esc_url($testimonialLink); ?>" target="_blank" rel="noopener">Google</a>
                                            <?php endif; ?>
                                        </figcaption>
                                    </figure>
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
