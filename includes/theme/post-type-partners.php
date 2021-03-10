<?php

add_action('init', 'register_partners');

function register_partners()
{
    $post_type = 'partners';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Партнёры',
            'singular_name'      => 'Партнёр',
            'add_new'            => 'Добавить партнёра',
            'add_new_item'       => 'Добавление партнёра',
            'edit_item'          => 'Редактирование партнёра',
            'new_item'           => 'Новый партнёр',
            'view_item'          => 'Смотреть партнёра',
            'search_items'       => 'Искать партнёра',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Партнёры',
        ],
        'description'         => '',
        'public'              => true,
        'publicly_queryable'  => false,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-groups',
        'hierarchical'        => false,
        'supports'            => ['title', 'thumbnail'],
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
add_filter('manage_' . 'partners' . '_posts_columns', 'add_partners_columns');

function add_partners_columns($columns)
{
    $new_columns = [
        'thumb'         => 'Логотип',
        'partners-url'  => 'Ссылка'
    ];

    unset($columns['date']);

    return array_slice($columns, 0, 1) + $columns + $new_columns;
}

/**
 * Заполнение новых колонок
 */
add_action('manage_' . 'partners' . '_posts_custom_column', 'fill_partners_columns');

function fill_partners_columns($column_name)
{
    if ($column_name === 'thumb' && has_post_thumbnail()) {

        $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail');

        echo $thumbnail;
    }

    if ($column_name === 'partners-url') {

        $link = get_post_meta(get_the_ID(), 'url', true);

        echo ($link) ? $link : '-';
    }
};

/**
 * Мета поля
 */
if (class_exists('Kama_Post_Meta_Box')) {

    $args = [
        'id'         => '_partners',
        'title'      => 'Ссылка на партнёра',
        'post_type'  => [
            'partners'
        ],
        'fields'     => [
            'url' => [
                'type'  => 'url',
                'title' => 'Ссылка',
                'desc'  => 'Укажите ссылку на партнёра'
            ],
        ],
    ];

    new Kama_Post_Meta_Box($args);
}
