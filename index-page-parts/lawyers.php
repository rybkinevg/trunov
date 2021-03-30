<?php

$args = [
    'post_type'      => 'lawyers',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'meta_query'     => [
        [
            'key' => 'status',
            'value' => ['head'],
            'compare' => 'NOT IN'
        ],
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

                        get_template_part('template-parts/index/content', get_post_type());
                    }
                }

                wp_reset_postdata();

                ?>

            </div>
        </div>

        <div class="show-more">
            <button class="show-more__btn" type="button" data-ajax="true" data-offset="6" data-posttype="lawyers" data-query='<?= serialize($args); ?>'>Показать больше</button>
        </div>
    </div>
</section>