<?php

function trunov_get_share_url($social)
{
    global $post;

    $title = get_the_title();

    $url = get_the_permalink();

    $img = get_the_post_thumbnail_url();

    $desc = get_the_content();

    switch ($social) {

        case 'vkontakte':

            $share_link = "http://vk.com/share.php?url={$url}&title={$title}&image={$img}&noparse=true";
            break;

        case 'facebook':

            $share_link = "https://www.facebook.com/sharer/sharer.php?u={$url}";
            break;

        case 'twitter':

            $share_link = "https://twitter.com/share?url={$url}&text={$title}";
            break;

        case 'odnoklassniki':

            $share_link = "http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={$url}&st.comments={$title}";
            break;

        default:

            $share_link = "";
            break;
    }

    return $share_link;
}
