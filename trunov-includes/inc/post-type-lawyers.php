<?php

add_action('init', 'register_lawyers');

function register_lawyers()
{
    $post_type = 'lawyers';

    $args = [
        'label'  => null,
        'labels' => [
            'name'               => 'Юристы',
            'singular_name'      => 'Юрист',
            'add_new'            => 'Добавить юриста',
            'add_new_item'       => 'Добавление юриста',
            'edit_item'          => 'Редактирование юриста',
            'new_item'           => 'Новый юрист',
            'view_item'          => 'Смотреть юриста',
            'search_items'       => 'Искать юриста',
            'not_found'          => 'Не найдено',
            'not_found_in_trash' => 'Не найдено в корзине',
            'parent_item_colon'  => '',
            'menu_name'          => 'Юристы',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => 'dashicons-businessman',
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'thumbnail'],
        'taxonomies'          => ['lawyers_tax', 'offices'],
        'has_archive'         => true,
        'rewrite'             => true,
        'query_var'           => true,
    ];

    register_post_type($post_type, $args);
}

add_action('init', 'create_lawyers_tax');

function create_lawyers_tax()
{
    $tax_name = 'lawyers_tax';

    $post_types = ['lawyers'];

    $default_term = [
        'name'        => 'Юрист',
        'slug'        => 'jurist',
        'description' => '15712'
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
        'Адвокат',
        $tax_name,
        [
            'slug'        => 'advocat',
            'description' => '109'
        ]
    );
}

add_action('init', 'create_offices');

function create_offices()
{
    $tax_name = 'offices';

    $post_types = ['lawyers'];

    $default_term = [
        'name'        => 'Адвокаты в России',
        'slug'        => sanitize_title('Адвокаты в России'),
        'description' => ''
    ];

    $args = [
        'label'                 => 'Представительства',
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

    $offices = [
        [
            'name' => 'Адвокаты во Франции',
            'desc' => '513'
        ],
        [
            'name' => 'Адвокаты в Ирландии',
            'desc' => '514'
        ],
        [
            'name' => 'Адвокаты в Соединённых Штатах Америки',
            'desc' => '519'
        ],
        [
            'name' => 'Адвокаты в Болгарии',
            'desc' => '527'
        ],
        [
            'name' => 'Адвокаты в Эстонии',
            'desc' => '541'
        ],
        [
            'name' => 'Адвокаты в Португалии',
            'desc' => '555'
        ],
        [
            'name' => 'Адвокаты в Англии',
            'desc' => '568'
        ],
        [
            'name' => 'Адвокаты в Израиле',
            'desc' => '575'
        ],
        [
            'name' => 'Адвокаты в Украине',
            'desc' => '581'
        ],
        [
            'name' => 'Адвокаты в Канаде',
            'desc' => '589'
        ],
    ];

    foreach ($offices as $office) {

        wp_insert_term(
            $office['name'],
            $tax_name,
            [
                'slug'        => sanitize_title($office['name']),
                'description' => $office['desc']
            ]
        );
    }
}

// Добавляет фильтр по таксономии
add_action('restrict_manage_posts', 'lawyers_taxonomies_filter');

function lawyers_taxonomies_filter()
{
    global $typenow;

    if ($typenow == 'lawyers') {

        $taxes = [
            'lawyers_tax',
            'offices'
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

add_filter('manage_' . 'lawyers' . '_posts_columns', 'add_lawyers_columns');

function add_lawyers_columns($columns)
{
    $new_columns = [
        'thumb' => 'Фото'
    ];

    return array_slice($columns, 0, 1) + $new_columns + $columns;

    return $columns;
}

add_action('manage_' . 'lawyers' . '_posts_custom_column', 'fill_lawyers_columns');

function fill_lawyers_columns($column_name)
{
    if ($column_name === 'thumb' && has_post_thumbnail()) {

        $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail');

        echo $thumbnail;
    }
};
