<?php

add_action('init', 'register_works');

function register_works()
{
    $post_type = 'works';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Научные и учебно-методические труды',
            'singular_name'      => 'Научные и учебно-методические труды',
            'add_new'            => 'Добавить труд',
            'add_new_item'       => 'Добавление труда',
            'edit_item'          => 'Редактирование труда',
            'new_item'           => 'Новый труд',
            'view_item'          => 'Смотреть труд',
            'search_items'       => 'Искать труд',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Научные труды',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-welcome-learn-more',
        'hierarchical'        => false,
        'supports'            => ['title', 'editor'],
        'taxonomies'          => [],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type, $args);
}

// Таксономия: Разделы
add_action('init', 'create_works_categories');

function create_works_categories()
{
    $tax_name = 'works-categories';

    $post_types = ['works'];

    $default_term = [
        'name'        => 'Публикации адвокатов коллегии',
        'slug'        => sanitize_title('Публикации адвокатов коллегии'),
        'description' => '16076'
    ];

    $args = [
        'label'                 => 'Разделы',
        'description'           => '',
        'public'                => true,
        'hierarchical'          => false,
        'rewrite'               => true,
        'meta_box_cb'           => 'post_categories_meta_box',
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'default_term'          => $default_term
    ];

    register_taxonomy($tax_name, $post_types, $args);

    $taxes = [
        [
            'name' => 'Публикации Айвар Людмилы Константиновны',
            'id'   => '15539'
        ],
        [
            'name' => 'Публикации Трунова Игоря Леонидовича',
            'id'   => '15010'
        ],
    ];

    foreach ($taxes as $tax) {

        wp_insert_term(
            $tax['name'],
            $tax_name,
            [
                'slug'        => sanitize_title($tax['name']),
                'description' => $tax['id']
            ]
        );
    }
}

// Таксономия: Типы
add_action('init', 'create_works_types');

function create_works_types()
{
    $tax_name = 'works-types';

    $post_types = ['works'];

    $default_term = [
        'name'        => 'Научные статьи',
        'slug'        => sanitize_title('Научные статьи'),
        'description' => 'Айвар - 15540, Трунов - 15011'
    ];

    $args = [
        'label'                 => 'Типы',
        'description'           => '',
        'public'                => true,
        'hierarchical'          => false,
        'rewrite'               => true,
        'meta_box_cb'           => 'post_categories_meta_box',
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'default_term'          => $default_term
    ];

    register_taxonomy($tax_name, $post_types, $args);

    wp_insert_term(
        'Книги, монографии',
        $tax_name,
        [
            'slug'        => sanitize_title('Книги, монографии'),
            'description' => 'Айвар - 15541, Трунов - 15012'
        ]
    );
}

// Колонка с ссылкой
add_filter('manage_' . 'works' . '_posts_columns', 'add_works_columns');

function add_works_columns($columns)
{
    $new_columns = [
        'work-url' => 'Ссылка'
    ];

    unset($columns['date']);

    return array_slice($columns, 0, 1) + $columns + $new_columns;
}

add_action('manage_' . 'works' . '_posts_custom_column', 'fill_works_columns');

function fill_works_columns($column_name)
{
    if ($column_name === 'work-url') {

        $link = carbon_get_post_meta(get_the_ID(), 'works-url');

        echo ($link) ? $link : '-';
    }
};

/**
 * Добавляет фильтр по таксономиям
 */
add_action('restrict_manage_posts', 'works_taxes_filter');

function works_taxes_filter()
{
    global $typenow;

    if ($typenow == 'works') {

        $taxes = [
            'works-categories',
            'works-types',
        ];

        foreach ($taxes as $tax) {

            $current_tax = isset($_GET[$tax]) ? $_GET[$tax] : '';

            $tax_obj = get_taxonomy($tax);

            $tax_name = $tax_obj->labels->name;

            $terms = get_terms($tax);

            if (count($terms) > 0) {

                echo "<select name='{$tax}' id='{$tax}' class='postform'>";
                echo "<option value=''>Все {$tax_name}</option>";

                foreach ($terms as $term) {

                    echo '<option value=' . $term->slug, $current_tax == $term->slug ? ' selected="selected"' : '', '>' . $term->name . ' (' . $term->count . ')</option>';
                }

                echo "</select>";
            }
        }
    }
}
