<?php
// Website and GitHub buttons of a portfolio project (inside the projects loop).
$websiteLink = get_field('website_button_link');
$githubLink = get_field('github_button_link');
if ($websiteLink || $githubLink): ?>
    <div class="projects__buttons">
        <?php if ($websiteLink): ?>
            <a target="_blank" rel="noopener" href="<?php echo esc_url($websiteLink); ?>" class="button primary">
                <?php echo esc_html(get_field('website_button_text')); ?>
            </a>
        <?php endif; ?>
        <?php if ($githubLink): ?>
            <a target="_blank" rel="noopener" href="<?php echo esc_url($githubLink); ?>" class="button secondary">
                <?php echo esc_html(get_field('github_button_text')); ?>
            </a>
        <?php endif; ?>
    </div>
<?php endif;
