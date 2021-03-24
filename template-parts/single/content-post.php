<section class="single single__<?= get_post_type(); ?>">

    <div class="row">
        <div class="col col-content">
            <div class="single__item single__content">
                <h2 class="single__title">
                    <?= get_the_title(); ?>
                </h2>
                <div class="single__text">
                    <?= get_the_content(); ?>
                </div>
            </div>
        </div>
        <div class="col col-sidebar">
            <div class="single__item single__sidebar">

                <?php

                $post_taxes = trunov_get_post_taxes(get_the_ID());

                trunov_show_post_meta($post_taxes);

                $post_meta_persons = trunov_get_post_meta(get_the_ID(), 'persons', 'Персоны', 'lawyers');

                trunov_show_post_meta($post_meta_persons);

                $post_meta_services = trunov_get_post_meta(get_the_ID(), 'services', 'Услуги', 'services');

                trunov_show_post_meta($post_meta_services);

                ?>

            </div>
        </div>
    </div>

</section>