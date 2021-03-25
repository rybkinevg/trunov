<?php

function trunov_archive_title($post_type)
{
    return get_post_type_object($post_type)->label;
}
