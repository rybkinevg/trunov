<?php

/**
 * Тип записи: Услуги
 */

$post_type_name = 'services';

add_action('init', 'register_services');

function register_services()
{
    global $post_type_name;

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Услуги',
            'singular_name'      => 'Услуга',
            'add_new'            => 'Добавить услугу',
            'add_new_item'       => 'Добавление услуги',
            'edit_item'          => 'Редактирование услуги',
            'new_item'           => 'Новая услуга',
            'view_item'          => 'Смотреть услугу',
            'search_items'       => 'Искать услугу',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Услуги',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-megaphone',
        'hierarchical'        => true,
        'supports'            => ['title', 'editor', 'page-attributes'],
        'taxonomies'          => [],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type_name, $args);
}

add_action('init', 'create_services_categories');

function create_services_categories()
{
    global $post_type_name;

    $tax_name = 'services_categories';

    $post_types = [$post_type_name];

    $default_term = [
        'name'        => 'Юридическим лицам',
        'slug'        => sanitize_title('Юридическим лицам'),
        'description' => '16789'
    ];

    $args = [
        'label'                 => 'Категории',
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
        'Физическим лицам',
        $tax_name,
        [
            'slug'        => sanitize_title('Физическим лицам'),
            'description' => '16790'
        ]
    );

    wp_insert_term(
        'Юридический бизнес',
        $tax_name,
        [
            'slug'        => sanitize_title('Юридический бизнес'),
            'description' => '118'
        ]
    );
}

/**
 * Добавляет фильтр по таксономиям
 */
add_action('restrict_manage_posts', 'services_taxes_filter');

function services_taxes_filter()
{
    global $typenow;

    if ($typenow == 'services') {

        $taxes = [
            'services_categories'
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
