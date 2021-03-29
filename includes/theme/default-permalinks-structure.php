<?php

function default_permalinks_structure()
{
    global $wp_rewrite;

    $structure = $wp_rewrite->root . '/%category%/%post_id%/';

    $wp_rewrite->set_permalink_structure($structure);
}

// add_action('init', 'default_permalinks_structure');
