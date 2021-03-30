<section class="single single__<?= get_post_type(); ?>">

    <div class="row">
        <div class="col col-content">
            <div class="single__item single__content">
                <h2 class="single__title">
                    <?= get_the_title(); ?>
                </h2>
                <div class="single__props">
                    <span class="single__date">
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                        <?= get_the_date('j F Y'); ?>
                    </span>
                    <span class="single__views">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                        Кол-во просмотров
                    </span>
                </div>
                <div class="single__body">
                    <?= get_the_content(); ?>
                </div>
            </div>

            <?php

            $post_taxes = trunov_get_post_taxes(get_the_ID());

            if (!empty($post_taxes)) {

            ?>

                <div class="single__item single__taxes">
                    <?php trunov_show_post_meta($post_taxes); ?>
                </div>

            <?php

            }

            ?>

        </div>
        <!-- <div class="col col-sidebar">
            <div class="single__item single__sidebar">

                Здесь список услуг, если у услуги есть дочки

            </div>
            <div class="single__item single__sidebar">

                Здесь новости, связанные с услугой

            </div>
        </div> -->
        <div class="col col-sidebar">
            <?php

            get_template_part('template-parts/single/sidebar', get_post_type());

            ?>
        </div>
    </div>

</section>