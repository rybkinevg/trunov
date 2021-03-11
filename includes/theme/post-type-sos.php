<?php

add_action('init', 'register_sos');

function register_sos()
{
    $post_type = 'sos';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'SOS',
            'singular_name'      => 'SOS',
            'add_new'            => 'Добавить запись',
            'add_new_item'       => 'Добавление записи',
            'edit_item'          => 'Редактирование записи',
            'new_item'           => 'Новая запись',
            'view_item'          => 'Смотреть записи',
            'search_items'       => 'Искать записи',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'SOS',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-sos',
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'thumbnail'],
        'taxonomies'          => [],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type, $args);
}
