<?php

function trunov_show_post_meta($post_meta_data)
{
    foreach ($post_meta_data as $title => $post_meta) {

?>

        <div class="single__tax">
            <span class="single__tax-name"><?= $title; ?></span>
            <ul class="single__terms">

                <?php

                foreach ($post_meta as $post_meta_item) {

                    foreach ($post_meta_item as $meta) {

                ?>

                        <li class="single__term">
                            <a href="<?= $meta['link'] ?>"><?= $meta['name'] ?></a>
                        </li>

                <?php

                    }
                }

                ?>

            </ul>
        </div>

<?php

    }
}
