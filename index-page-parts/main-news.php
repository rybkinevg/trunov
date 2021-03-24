<?php

$tags = sanitize_title('Важное') . ',' . sanitize_title('Анонс');

$args = [
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'tag'            => $tags
];

$query = new WP_Query($args);

?>

<section class="section main-news">
    <h2 class="section__title visually-hidden">Важные новости и анонсы</h2>
    <div class="section__inner">
        <div class="slider">

            <?php

            if ($query->have_posts()) {

                while ($query->have_posts()) {

                    $query->the_post();

            ?>

                    <div class="slider__item">
                        <a class="main-news__item" style="background-image: url(<?= get_the_post_thumbnail_url(); ?>);">
                            <div class="main-news__info">
                                <h3 class="main-news__title">
                                    <?= get_the_title(); ?>
                                </h3>
                                <div class="main-news__desc">
                                    <?= get_the_excerpt(); ?>
                                </div>
                            </div>
                        </a>
                    </div>

            <?php

                }
            }

            wp_reset_postdata();

            ?>

        </div>
        <div class="slider__arrows"></div>
    </div>
</section>