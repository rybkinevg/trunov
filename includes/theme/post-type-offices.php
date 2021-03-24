<?php

add_action('init', 'register_offices');

function register_offices()
{
    $post_type = 'offices';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Представительства',
            'singular_name'      => 'Представительство',
            'add_new'            => 'Добавить офис',
            'add_new_item'       => 'Добавление офиса',
            'edit_item'          => 'Редактирование офиса',
            'new_item'           => 'Новый офис',
            'view_item'          => 'Смотреть офис',
            'search_items'       => 'Искать офис',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Офисы',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-admin-site',
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'thumbnail'],
        'taxonomies'          => [],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type, $args);
}
