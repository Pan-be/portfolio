<?php
// Pricing page sections from ACF fields. Used by page-pricing.php and by the REST content filter in functions.php.
?>



        <header class="wrapper header__wrapper">
            <h1><?php the_title(); ?></h1>
        </header>
        <section class="wrapper offer">
            <div class="offer__text">
                <?php the_content(); ?>
            </div>
            <div class="offer__icon"><?php
                                        $image = get_field('top_image');
                                        if (!empty($image)): ?>

                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>"
                        width="<?php echo esc_attr($image['width']); ?>" height="<?php echo esc_attr($image['height']); ?>" />

                <?php endif; ?>
            </div>
        </section>

        <section class="pricing wrapper pricing__wrapper">
            <div class="pricing__elements">
                <?php
                $pricing_query = get_field('pricing_plans');
                $index = 0; // Licznik iteracji

                if ($pricing_query) :
                    foreach ($pricing_query as $post):
                        setup_postdata($post);

                        $pricing_price = get_field('price');
                        $is_first = $index === 0; // Sprawdzamy, czy to pierwszy pakiet

                        // Pobieramy wszystkie pola z grupy
                        if (my_theme_is_polish()) {
                            $features_group = acf_get_fields('group_67c4d66460ffa');
                        } else {
                            $features_group = acf_get_fields('group_67ec3fe414ec9');
                        }

                        if (is_array($features_group)) {
                            // Filtrowanie tylko pól typu "true_false"
                            $features = array_filter($features_group, function ($field) {
                                return isset($field['type']) && $field['type'] === 'true_false';
                            });

                            // Przekształcamy tablicę w format klucz => etykieta (nazwa pola)
                            $features = array_column($features, 'label', 'name'); // ['nazwa_pola' => 'Etykieta']
                        } else {
                            $features = [];
                        }


                ?>
                        <div class="pricing__element">
                            <div class="pricing__features">
                                <div class="pricing__plans">
                                    <div class="pricing__empty"></div>
                                    <div class="pricing__plan">
                                        <h4><?php the_title(); ?></h4>
                                        <h3><span class="very-small"><?php echo esc_html(get_field('text_before_price')); ?></span>
                                            <?php echo esc_html($pricing_price); ?>
                                            <span class="very-small"><?php echo esc_html(get_field('currency')); ?></span>
                                        </h3>
                                    </div>
                                </div>
                                <?php
                                // By key, not name: both language groups have a 'pozycje_w_menu' field, and some PL plans point at the EN one.
                                $pozycje_w_menu_field = acf_get_field(my_theme_is_polish() ? 'field_67c5593019c40' : 'field_67ec3fe41e70b');
                                $pozycje_w_menu_label = $pozycje_w_menu_field ? $pozycje_w_menu_field['label'] : 'Pozycje w menu';
                                ?>
                                <div class="pricing__feature">
                                    <span class="<?php echo $is_first ? 'pricing__title' : ''; ?>">
                                        <?php echo esc_html(pll__($pozycje_w_menu_label)); ?>
                                    </span><span class="very-small"><?php echo get_field('pozycje_w_menu'); ?></span>
                                </div>

                                <?php foreach ($features as $field_name => $label) : ?>
                                    <div class="pricing__feature">
                                        <span class="<?php echo $is_first ? 'pricing__title' : ''; ?>">
                                            <?php echo esc_html(pll__($label)); ?>
                                        </span>
                                        <?php
                                        $feature_value = get_field($field_name); // Pobranie wartości pola dla danego pakietu
                                        $is_active = !empty($feature_value); // Jeśli pole jest true, ustaw 'plus'
                                        ?>
                                        <span class="<?php echo $is_active ? 'plus' : ''; ?>">
                                            <?php echo $is_active ? '+' : '-'; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                <?php
                        $index++; // Zwiększamy licznik iteracji
                    endforeach;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>

        <section class="">
            <center>
                <button class="open-modal button primary"><?php echo esc_html(get_field('choose_pricing_plan')); ?></button>
            </center>
        </section>

        <section class="wrapper ecommerce">
            <h2><?php echo esc_html(get_field('e-commerce_title')); ?></h2>
            <div class="ecommerce__container">
                <?php $e_commerce_image = get_field('e-commerce_image');
                if (!empty($e_commerce_image)): ?>

                    <img src="<?php echo esc_url($e_commerce_image['url']); ?>"
                        alt="<?php echo esc_attr($e_commerce_image['alt']); ?>"
                        width="<?php echo esc_attr($e_commerce_image['width']); ?>"
                        height="<?php echo esc_attr($e_commerce_image['height']); ?>" />
                <?php endif; ?>
                <p><?php the_field('e-commerce_content'); ?></p>
            </div>
        </section>

        <div class="subscription__container">
            <section class="subscription wrapper">
                <div class="subscription__content">
                    <h2><?php echo esc_html(get_field('subscriptions_title')); ?></h2>
                    <p><?php the_field('subscriptions_description'); ?></p>
                </div>

                <ul class="subscription__plans" id="serwis">


                    <?php
                    $subscription_query = get_field('subscription_plans');

                    if ($subscription_query) :
                        foreach ($subscription_query as $post):
                            setup_postdata($post);

                            $is_the_best = get_field('the_best_plan');
                    ?>
                            <li class="subscription__plan <?php echo $is_the_best ? 'subscription__plan--best' : ''; ?>
">
                                <div class="plan__info">
                                    <h4 class="plan__title"><?php the_title(); ?></h4>
                                    <p class="plan__description very-small" style="min-height: 30px;">
                                        <?php echo esc_html(get_field('plan_description')); ?></p>
                                </div>
                                <h3 class=" plan__price">
                                    <span><?php echo esc_html(get_field('text_before_price')); ?></span><?php echo esc_html(get_field('price_with_currency')); ?>/<span><?php echo esc_html(get_field('period')); ?></span>
                                </h3>
                                <?php
                                $planNameFull = get_the_title();
                                $planName = strtolower($planNameFull);
                                ?>
                                <button data-plan="<?php echo esc_attr($planName); ?>"
                                    class="plan__cta open-modal"><?php echo esc_html(get_field('button_text')); ?></button>
                                <?php
                                $features = get_field('plan_features');
                                if ($features):
                                    $features_array = explode("\n", $features);
                                ?>
                                    <ul class="plan__details">
                                        <?php foreach ($features_array as $feature): ?>
                                            <li class="plan__detail">
                                                <span class="plus">+</span>
                                                <span><?php echo esc_html(trim($feature)); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                            </li>
                    <?php


                        endforeach;
                        wp_reset_postdata();

                    endif;

                    ?>

                </ul>

            </section>
        </div>
        <section class="lastcta wrapper">
            <h2><?php echo esc_html(get_field('contact_title')); ?></h2>
            <p><?php echo esc_html(get_field('contact_content')); ?></p>
            <?php
            $contactLink = get_field('contact_link_url');
            ?>
            <a href="<?php echo esc_attr($contactLink); ?>"
                class="button primary"><?php echo esc_html(get_field('contact_link_text')); ?></a>
        </section>



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
