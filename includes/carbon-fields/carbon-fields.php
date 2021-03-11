<?php

add_action('carbon_fields_register_fields', 'crb_attach_theme_options');

function crb_attach_theme_options()
{
    require(dirname(__FILE__) . '/post-fields.php');
    require(dirname(__FILE__) . '/works-fields.php');
    require(dirname(__FILE__) . '/partners-fields.php');
}
