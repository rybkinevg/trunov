<?php

$cats = get_terms(['taxonomy' => 'services_categories']);

$args = [
    'post_type'      => 'services',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post__not_in'   => [16053],
    'tax_query'      => [
        [
            'taxonomy' => 'services_categories',
            'field'    => 'slug',
            'terms'    => 'juridicheskij-biznes'
        ]
    ]
];

$query = new WP_Query($args);

?>

<section class="section services">
    <div class="section__header">
        <h2 class="section__title">Услуги</h2>
        <div class="section__links">

            <?php

            foreach ($cats as $cat) {

            ?>

                <a class="section__link" href="services_categories/<?= $cat->slug; ?>"><?= $cat->name; ?></a>

            <?php
            }

            ?>

        </div>
        <div class="slider__arrows"></div>
    </div>
    <div class="section__inner">
        <div class="preview">
            <div class="row cols-4">

                <?php

                if ($query->have_posts()) {

                    while ($query->have_posts()) {

                        $query->the_post();

                ?>

                        <div class="col">
                            <a href="<?= get_the_permalink(); ?>" class="services__item">
                                <div class="services__img">
                                    <?= trunov_get_thumbnail(); ?>
                                </div>
                                <div class="services__name"><?= get_the_title(); ?></div>
                            </a>
                        </div>

                <?php

                    }
                }

                wp_reset_postdata();

                ?>
            </div>
        </div>
        <div class="show-more">
            <button class="show-more__btn" data-ajax="true" data-posttype="services" data-offset="4" data-query='<?= serialize($args); ?>'>Показать больше</button>
        </div>
    </div>
</section>