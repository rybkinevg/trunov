<?php

add_action('init', 'register_books');

function register_books()
{
    $post_type = 'books';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Книги',
            'singular_name'      => 'Книга',
            'add_new'            => 'Добавить книгу',
            'add_new_item'       => 'Добавление книги',
            'edit_item'          => 'Редактирование книги',
            'new_item'           => 'Новая книга',
            'view_item'          => 'Смотреть книгу',
            'search_items'       => 'Искать книгу',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Книги',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-book',
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'taxonomies'          => [],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type, $args);
}

add_filter('manage_' . 'books' . '_posts_columns', 'add_books_columns');

function add_books_columns($columns)
{
    $new_columns = [
        'thumb' => 'Обложка'
    ];

    return array_slice($columns, 0, 1) + $new_columns + $columns;
}

add_action('manage_' . 'books' . '_posts_custom_column', 'fill_books_columns');

function fill_books_columns($column_name)
{
    if ($column_name === 'thumb' && has_post_thumbnail()) {

        $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail');

        echo $thumbnail;
    }
};
