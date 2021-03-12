<?php

add_action('init', 'register_certificates');

function register_certificates()
{
    $post_type = 'certificates';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Регистрационные свидетельства',
            'singular_name'      => 'Свидетельство',
            'add_new'            => 'Добавить свидетельство',
            'add_new_item'       => 'Добавление свидетельства',
            'edit_item'          => 'Редактирование свидетельства',
            'new_item'           => 'Новое свидетельство',
            'view_item'          => 'Смотреть свидетельство',
            'search_items'       => 'Искать свидетельство',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Свидетельства',
        ],
        'description'         => '',
        'public'              => true,
        'publicly_queryable'  => false,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-media-default',
        'hierarchical'        => false,
        'supports'            => ['title'],
        'taxonomies'          => [],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type, $args);
}

/**
 * Добавление новых колонок
 */
add_filter('manage_' . 'certificates' . '_posts_columns', 'add_' . 'certificates' . '_columns');

function add_certificates_columns($columns)
{
    $new_columns = [
        'certificates-url'  => 'Ссылка'
    ];

    unset($columns['date']);

    return array_slice($columns, 0, 1) + $columns + $new_columns;
}

/**
 * Заполнение новых колонок
 */
add_action('manage_' . 'certificates' . '_posts_custom_column', 'fill_' . 'certificates' . '_columns');

function fill_certificates_columns($column_name)
{
    if ($column_name === 'certificates-url') {

        $link = carbon_get_post_meta(get_the_ID(), 'certificates-img-url');

        echo ($link) ? $link : '-';
    }
};
