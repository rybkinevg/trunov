<?php

$args = [
    'post_type'      => 'lawyers',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => [
        [
            'key'     => '_status',
            'value'   => 'staff',
            'compare' => '='
        ]
    ],
    'tax_query'      => [
        [
            'taxonomy' => 'lawyers_tax',
            'field'    => 'slug',
            'terms'    => 'advocat'
        ]
    ]
];

$query = new WP_Query($args);

?>

<section class="section lawyers">
    <div class="section__header">
        <h2 class="section__title">Адвокаты</h2>
    </div>
    <div class="section__inner">
        <div class="preview">
            <div class="row cols-6">

                <?php

                if ($query->have_posts()) {

                    while ($query->have_posts()) {

                        $query->the_post();

                ?>

                        <div class="col">
                            <a href="<?= get_the_permalink() ?>" class="lawyers__item">
                                <div class="lawyers__img">
                                    <img src="<?= get_the_post_thumbnail_url(); ?>" alt="" class="img img--cover">
                                </div>
                                <div class="lawyers__name">
                                    <?= get_the_title(); ?>
                                </div>
                            </a>
                        </div>

                <?php

                    }
                }

                wp_reset_postdata();

                ?>

            </div>

            <div class="show-more">
                <button class="show-more__btn" type="button">Показать больше</button>
            </div>

            <?php

            ?>


</section>