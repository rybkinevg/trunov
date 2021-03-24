<?php

$args = [
    'post_type'      => 'lawyers',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'name',
    'order'          => 'DESC',
    'meta_query'     => [
        [
            'key'   => '_status',
            'value' => 'head'
        ]
    ]
];

$query = new WP_Query($args);

?>

<section class="section management">
    <h2 class="section__title visually-hidden">Руководство</h2>
    <div class="management__inner">

        <?php

        if ($query->have_posts()) {

            while ($query->have_posts()) {

                $query->the_post();

                $extra = carbon_get_post_meta(get_the_ID(), 'extra');

        ?>

                <div class="management__item">
                    <div class="management__info">
                        <div class="management__img">
                            <img src="<?= get_the_post_thumbnail_url(); ?>" alt="" class="img img--cover">
                        </div>
                        <div class="management__speech">
                            <blockquote class="blockquote management__blockquote">
                                <div class="blockquote__text">
                                    <?= carbon_get_post_meta(get_the_ID(), 'speech') ?>
                                </div>
                                <footer class="blockquote__footer">
                                    <cite class="blockquote__cite">
                                        <a href="<?= get_the_permalink(); ?>"><?= get_the_title(); ?></a>
                                    </cite>
                                    <span class="management__position">
                                        <?= carbon_get_post_meta(get_the_ID(), 'position') ?>
                                    </span>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                    <div class="management__extra">
                        <?php

                        if ($extra) {

                            foreach ($extra as $item) {

                                if ($item == 'tv') {

                                    $link = 'news_smi?tax=tv&person=' . get_the_ID();
                                    $text = 'Телепередачи';
                                    $icon = 'fa fa-television';
                                } else if ($item == 'works') {

                                    $link = 'works?person=' . get_the_ID();
                                    $text = 'Научные статьи';
                                    $icon = 'fa fa-graduation-cap';
                                } else if ($item == 'actual') {

                                    $link = 'actual?person=' . get_the_ID();
                                    $text = 'Актуальные дела';
                                    $icon = 'fa fa-university';
                                }

                        ?>

                                <a href="<?= $link; ?>" class="management__link">
                                    <i class="<?= $icon; ?>" aria-hidden="true"></i>
                                    <?= $text; ?>
                                </a>

                        <?php
                            }
                        }

                        ?>

                    </div>
                </div>

        <?php

            }
        }

        wp_reset_postdata();

        ?>

    </div>
</section>