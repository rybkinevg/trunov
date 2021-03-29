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

            $source_link = carbon_get_post_meta(get_the_ID(), 'source') ?: '';

            if ($source_link) {

            ?>

                <div class="single__item single__source">
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <a href="<?= $source_link; ?>" class="single__source-link" target="_blank" rel="noopener noreferrer">Источник</a>
                </div>

            <?php

            }

            ?>

            <div class="single__share">
                <div class="share">
                    <a href="<?= trunov_get_share_url('vkontakte'); ?>" class="share__item">
                        <i class="fa fa-vk" aria-hidden="true"></i>
                    </a>
                    <a href="<?= trunov_get_share_url('facebook'); ?>" class="share__item">
                        <i class="fa fa-facebook" aria-hidden="true"></i>
                    </a>
                    <a href="<?= trunov_get_share_url('twitter'); ?>" class="share__item">
                        <i class="fa fa-twitter" aria-hidden="true"></i>
                    </a>
                    <a href="<?= trunov_get_share_url('odnoklassniki'); ?>" class="share__item">
                        <i class="fa fa-odnoklassniki" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="single__item single__taxes">
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
        <div class="col col-sidebar">
            <div class="single__item single__sidebar">

                <?php

                $args = [
                    'post_type'      => 'post',
                    'posts_per_page' => 5,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'post__not_in'   => [get_the_ID()]
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

            </div>
        </div>
    </div>

</section>