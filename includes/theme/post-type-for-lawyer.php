<?php

add_action('init', 'register_for_lawyer');

function register_for_lawyer()
{
    $post_type = 'for-lawyer';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Для адвоката',
            'singular_name'      => 'Для адвоката',
            'add_new'            => 'Добавить информацию',
            'add_new_item'       => 'Добавление информации',
            'edit_item'          => 'Редактирование информации',
            'new_item'           => 'Новая информация',
            'view_item'          => 'Смотреть информацию',
            'search_items'       => 'Искать информацию',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Адвокату',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-portfolio',
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'taxonomies'          => [],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type, $args);
}
