<?php

add_action('init', 'register_court');

function register_court()
{
    $post_type = 'court';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Реквизиты судов',
            'singular_name'      => 'Реквизиты суда',
            'add_new'            => 'Добавить реквизиты',
            'add_new_item'       => 'Добавление реквизитов',
            'edit_item'          => 'Редактирование реквизитов',
            'new_item'           => 'Новые реквизиты',
            'view_item'          => 'Смотреть реквизиты',
            'search_items'       => 'Искать реквизиты',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Суды',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-bank',
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'taxonomies'          => [],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type, $args);
}
