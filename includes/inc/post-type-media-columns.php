<?php

add_action('init', 'register_media_columns');

function register_media_columns()
{
    $post_type = 'media-columns';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Колонки СМИ',
            'singular_name'      => 'Колонка СМИ',
            'add_new'            => 'Добавить колонку',
            'add_new_item'       => 'Добавление колонки',
            'edit_item'          => 'Редактирование колонки',
            'new_item'           => 'Новая колонка',
            'view_item'          => 'Смотреть колонку',
            'search_items'       => 'Искать колонку',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Колонки СМИ',
        ],
        'description'         => '',
        'public'              => true,
        'publicly_queryable'  => false,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-rss',
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
 * Таксономия: Автор колонки
 */
add_action('init', 'create_column_author');

function create_column_author()
{
    $tax_name = 'column-author';

    $post_types = ['media-columns'];

    $args = [
        'label'                 => 'Автор колонки',
        'description'           => '',
        'public'                => true,
        'hierarchical'          => false,
        'rewrite'               => true,
        'meta_box_cb'           => 'post_categories_meta_box',
        'show_admin_column'     => true,
        'show_in_rest'          => true
    ];

    register_taxonomy($tax_name, $post_types, $args);

    wp_insert_term(
        'Трунов Игорь Леонидович',
        $tax_name,
        [
            'slug'        => sanitize_title('Трунов Игорь Леонидович'),
            'description' => '16019'
        ]
    );
}

/**
 * Таксономия: СМИ
 */
add_action('init', 'create_media');

function create_media()
{
    $tax_name = 'media';

    $post_types = ['media-columns'];

    $args = [
        'label'                 => 'СМИ',
        'description'           => '',
        'public'                => true,
        'hierarchical'          => false,
        'rewrite'               => true,
        'meta_box_cb'           => 'post_categories_meta_box',
        'show_admin_column'     => true,
        'show_in_rest'          => true
    ];

    register_taxonomy($tax_name, $post_types, $args);
}

// Мета поля
if (class_exists('Kama_Post_Meta_Box')) {

    $args = [
        'id'         => '_media_columns',
        'title'      => 'Ссылка на издание',
        'post_type'  => [
            'media-columns'
        ],
        'fields'     => [
            'url' => [
                'type'  => 'url',
                'title' => 'Укажите ссылку на издание'
            ],
        ],
    ];

    new Kama_Post_Meta_Box($args);
}
