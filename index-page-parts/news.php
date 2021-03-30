<?php

$cats = get_categories();

$args = [
    'post_type'      => 'post',
    'posts_per_page' => 15,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
];

$query = new WP_Query($args);

?>

<section class="section news">
    <div class="section__header">
        <h2 class="section__title">Пресс-центр</h2>
        <div class="section__links">

            <?php

            foreach ($cats as $cat) {

            ?>

                <a class="section__link" href="<?= $cat->category_nicename; ?>"><?= $cat->cat_name; ?></a>

            <?php
            }

            ?>

        </div>
        <div class="slider__arrows"></div>
    </div>
    <div class="section__inner">
        <div class="slider">

            <?php

            if ($query->have_posts()) {

                while ($query->have_posts()) {

                    $query->the_post();

            ?>

                    <div class="slider__item">
                        <div class="news__item">
                            <div class="news__img">
                                <?= trunov_get_thumbnail(); ?>
                            </div>
                            <span class="news__date"><?= get_the_date('j F Y'); ?></span>
                            <h3 class="news__title">
                                <a href="<?= get_the_permalink(); ?>"><?= get_the_title(); ?></a>
                            </h3>

                            <?php

                            if (iconv_strlen(get_the_title(), 'UTF-8') < 130) {

                            ?>

                                <div class="news__excerpt">
                                    <?= kama_excerpt(['maxchar' => 200, 'autop' => 0]);  ?>
                                </div>

                            <?php

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
    </div>
</section>