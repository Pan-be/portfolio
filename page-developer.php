<?php
/*
Template Name: Developer page
*/
// Page for recruiters: not in the menu, noindex (see functions.php), linked from the CV.
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <section class="hero wrapper hero__wrapper developer__hero">
            <div class="hero__text">
                <h1><?php echo esc_html(get_field('dev_title')); ?></h1>
                <?php the_field('dev_intro'); ?>
                <div class="hero__buttons">
                    <?php
                    foreach (['dev_cta' => 'button primary big', 'dev_cta_secondary' => 'button secondary big'] as $ctaField => $ctaClass):
                        $cta = get_field($ctaField);
                        if ($cta): ?>
                            <a class="<?php echo esc_attr($ctaClass); ?>" href="<?php echo esc_url($cta['url']); ?>"<?php echo $cta['target'] ? ' target="' . esc_attr($cta['target']) . '" rel="noopener"' : ''; ?>><?php echo esc_html($cta['title']); ?></a>
                    <?php endif;
                    endforeach; ?>
                </div>
            </div>
            <?php
            $photo = get_field('dev_photo');
            if (!empty($photo)): ?>
                <div class="hero__image">
                    <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($photo['alt']); ?>"
                        width="<?php echo esc_attr($photo['width']); ?>" height="<?php echo esc_attr($photo['height']); ?>" />
                </div>
            <?php endif; ?>
        </section>

        <?php
        $skills = get_field('dev_skills');
        if ($skills): ?>
            <section class="developer__skills wrapper">
                <h2><?php echo esc_html(get_field('dev_skills_title')); ?></h2>
                <div class="developer__skill-groups">
                    <?php echo $skills; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php
        $devProjects = get_field('dev_projects');
        if ($devProjects): ?>
            <section class="projects wrapper" id="projekty">
                <h2><?php echo esc_html(get_field('dev_projects_title')); ?></h2>

                <div class="projects__list">
                    <?php foreach ($devProjects as $post):
                        setup_postdata($post);
                        $projectImage = get_field('project_image'); ?>

                        <article class="projects__item<?php echo empty($projectImage) ? ' projects__item--text' : ''; ?>">
                            <?php
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
                                <?php
                                $tech = get_field('project_tech');
                                if ($tech) {
                                    echo $tech;
                                } else {
                                    the_content();
                                } ?>

                                <?php get_template_part('project-buttons'); ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php wp_reset_postdata(); ?>
            </section>
        <?php endif; ?>

        <?php
        $workflow = get_field('dev_workflow');
        if ($workflow): ?>
            <section class="developer__workflow wrapper">
                <h2><?php echo esc_html(get_field('dev_workflow_title')); ?></h2>
                <?php echo $workflow; ?>
            </section>
        <?php endif; ?>

        <?php get_template_part('contact-section', null, array('links' => get_field('dev_contact_links'), 'title' => get_field('dev_contact_title'))); ?>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>
