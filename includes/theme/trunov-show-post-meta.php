<?php

function trunov_show_post_meta($post_meta_data)
{
    foreach ($post_meta_data as $title => $post_meta) {

?>

        <div class="sidebar__item">
            <h2 class="sidebar__title">
                <?= $title; ?>
            </h2>
            <div class="sidebar__content">
                <ul class="sidebar__list">

                    <?php

                    foreach ($post_meta as $post_meta_item) {

                        foreach ($post_meta_item as $meta) {

                    ?>

                            <li class="sidebar__list-item">
                                <a href="<?= $meta['link'] ?>"><?= $meta['name'] ?></a>
                            </li>

                    <?php

                        }
                    }

                    ?>

                </ul>
            </div>
        </div>

<?php

    }
}
