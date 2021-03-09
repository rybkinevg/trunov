<?php

/**
 * Переименование `Записи` в `Пресс-центр`
 */
add_filter('post_type_labels_post', 'rename_post_type_post_labels');

function rename_post_type_post_labels($labels)
{
    $new = [
        'name'                  => 'Пресс-центр',
        'singular_name'         => 'Пресс-центр',
        'add_new'               => 'Добавить новость',
        'add_new_item'          => 'Добавить новость',
        'edit_item'             => 'Редактировать новость',
        'new_item'              => 'Новая новость',
        'view_item'             => 'Просмотреть новость',
        'search_items'          => 'Поиск новостей',
        'not_found'             => 'Новостей не найдено.',
        'not_found_in_trash'    => 'Новостей в корзине не найдено.',
        'parent_item_colon'     => '',
        'all_items'             => 'Все новости',
        'archives'              => 'Архивы новостей',
        'insert_into_item'      => 'Вставить в новость',
        'uploaded_to_this_item' => 'Загруженные для этой новости',
        'featured_image'        => 'Миниатюра новости',
        'filter_items_list'     => 'Фильтровать список новостей',
        'items_list_navigation' => 'Навигация по списку новостей',
        'items_list'            => 'Список новостей',
        'menu_name'             => 'Пресс-центр',
        'name_admin_bar'        => 'Новость', // пункте "добавить"
    ];

    return (object) array_merge((array) $labels, $new);
}

/**
 * Создание рубрик
 */
function create_post_cats()
{
    require_once(ABSPATH . '/wp-admin/includes/taxonomy.php');

    $post_cats = [
        'Новости' => [
            'ID'   => 1,
            'name' => 'Новости',
            'desc' => 'Категория обычных новостей сайта',
            'slug' => 'news'
        ],
        'Новости СМИ' => [
            'ID'   => 0,
            'name' => 'Новости СМИ',
            'desc' => 'Категория новостей в СМИ',
            'slug' => 'news_smi'
        ]
    ];

    foreach ($post_cats as $cat) {

        $args = [
            'cat_ID'               => $cat['ID'],
            'cat_name'             => $cat['name'],
            'category_description' => $cat['desc'],
            'category_nicename'    => $cat['slug'],
            'taxonomy'             => 'category'
        ];

        wp_insert_category($args);
    }
}

create_post_cats();

/**
 * Создание таксономий
 */
add_action('init', 'create_post_taxes', 0);

function create_post_taxes()
{
    $post_taxes = [
        [
            'id'   => '55',
            'name' => 'СМИ',
            'slug' => 'smi'
        ],
        [
            'id'   => '397',
            'name' => 'Архивы',
            'slug' => 'archive'
        ],
        [
            'id'   => '400',
            'name' => 'Телепередачи',
            'slug' => 'tv'
        ],
        [
            'id'   => '403',
            'name' => 'Громкие дела',
            'slug' => 'high-profile-cases'
        ],
        [
            'id'   => '431',
            'name' => 'Адвокаты в СМИ',
            'slug' => 'media-lawyers'
        ],
        [
            'id'   => '461',
            'name' => 'Услуги',
            'slug' => 'services'
        ],
        [
            'id'   => '447',
            'name' => 'Темы',
            'slug' => 'topics'
        ]
    ];

    foreach ($post_taxes as $tax) {

        $args = [
            'label'             => $tax['name'],
            'description'       => $tax['id'],
            'public'            => true,
            'hierarchical'      => false,
            'meta_box_cb'       => 'post_categories_meta_box',
            'show_admin_column' => false,
            'show_in_rest'      => true
        ];

        register_taxonomy(
            $tax['slug'],
            ['post'],
            $args
        );
    }
}

/**
 * Добавляет фильтр по таксономиям
 */
add_action('restrict_manage_posts', 'post_taxes_filter');

function post_taxes_filter()
{
    global $typenow;

    if ($typenow == 'post') {

        $taxes = [
            'smi',
            'archive',
            'tv',
            'high-profile-cases',
            'media-lawyers',
            'services',
            'topics'
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

// Мета поля
if (class_exists('Kama_Post_Meta_Box')) {

    function get_options($post_type)
    {
        $options = [
            '' => 'Не выбрано'
        ];

        $args = [
            'posts_per_page' => -1,
            'post_type'      => $post_type,
            'post_status'    => 'publish'
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {

            while ($query->have_posts()) {

                $query->the_post();

                $options[get_the_ID()] = get_the_title();
            }
        }

        wp_reset_postdata();

        return $options;
    }

    $args = [
        'id'         => '_post',
        'title'      => 'Дополнительные поля',
        'post_type'  => [
            'post'
        ],
        'fields'     => [
            'person' => [
                'type'    => 'select',
                'title'   => 'Персона',
                'desc'    => 'Выберите ФИО адвоката из списка доступных',
                'options' => get_options('lawyers'),
            ],
            'service' => [
                'type'    => 'select',
                'title'   => 'Услуги',
                'desc'    => 'Выберите услугу из списка доступных',
                'options' => get_options('services-catalog'),
            ],
        ],
    ];

    new Kama_Post_Meta_Box($args);
}
