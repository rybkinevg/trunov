<?php

// TODO: сделать проверку на миниатюру у таксы СМИ, и если нет миниатюры выбранной, подхватывать её, но с другим классом (img--contain)
function trunov_get_thumbnail()
{
    $alt = 'Миниатюра записи';

    if (has_post_thumbnail()) {

        $src = get_the_post_thumbnail_url();

        $class = 'img--cover';
    } else {

        $src = get_template_directory_uri() . '/assets/images/img-default.png';

        $class = 'img--cover img--default';
    }

    $thumbnail = "<img class='img {$class}' src='{$src}' alt='{$alt}'>";

    return $thumbnail;
}
