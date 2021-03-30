<?php

$args = [
    'post_type'      => 'post',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
];

$query = new WP_Query($args);

if ($query->have_posts()) {

?>

    <h2 class="sidebar__title">Последние новости</h2>

    <ul class="single__post-list">

        <?php

        while ($query->have_posts()) {

            $query->the_post();

        ?>

            <li class="single__post-item">
                <a href="<?= get_the_permalink() ?>" class="single__post-img">
                    <?= trunov_get_thumbnail(); ?>
                </a>
                <a href="<?= get_the_permalink() ?>" class="single__post-title">
                    <?= strip_tags(kama_excerpt(['text' => get_the_title(), 'maxchar' => 80])); ?>
                </a>
            </li>

    <?php

        }
    }

    wp_reset_postdata();

    ?>

    </ul>