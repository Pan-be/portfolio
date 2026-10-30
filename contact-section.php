<?php
// Contact links section. Args: 'links' (contact-link posts), 'title'. Used by the homepage and the developer page.
?>
        <?php
        $contactLinks = $args['links'] ?? null;
        if ($contactLinks): ?>
            <section class="contact wrapper section-grid">
                <h2><?php
                    echo esc_html($args['title'] ?? '');
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
