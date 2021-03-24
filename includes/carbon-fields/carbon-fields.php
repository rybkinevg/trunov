<?php

add_action('carbon_fields_register_fields', 'crb_attach_theme_options');

function crb_attach_theme_options()
{
    require(dirname(__FILE__) . '/fields-post.php');
    require(dirname(__FILE__) . '/fields-works.php');
    require(dirname(__FILE__) . '/fields-partners.php');
    require(dirname(__FILE__) . '/fields-certificates.php');
    require(dirname(__FILE__) . '/fields-lawyers.php');
}
