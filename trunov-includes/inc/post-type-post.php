<?php

$trunov_taxonomies = [
    [
        'id'   => '55',
        'name' => 'СМИ',
        'slug' => 'smi',
        'desc' => ''
    ],
    [
        'id'   => '397',
        'name' => 'Архивы',
        'slug' => 'archive',
        'desc' => ''
    ],
    [
        'id'   => '400',
        'name' => 'Телепередачи',
        'slug' => 'tv',
        'desc' => ''
    ],
    [
        'id'   => '403',
        'name' => 'Громкие дела',
        'slug' => 'high-profile-cases',
        'desc' => ''
    ],
    [
        'id'   => '431',
        'name' => 'Адвокаты в СМИ',
        'slug' => 'media-lawyers',
        'desc' => ''
    ],
    [
        'id'   => '461',
        'name' => 'Услуги',
        'slug' => 'services',
        'desc' => ''
    ],
    [
        'id'   => '447',
        'name' => 'Темы',
        'slug' => 'topics',
        'desc' => ''
    ]
];

add_action('init', 'create_taxonomies', 0);

function create_taxonomies()
{
    global $trunov_taxonomies;

    foreach ($trunov_taxonomies as $tax) {

        $tax_args = [
            'label'             => $tax['name'],
            'description'       => $tax['id'],
            'public'            => true,
            'hierarchical'      => true,
            'meta_box_cb'       => 'post_categories_meta_box',
            'show_admin_column' => false,
            'show_in_rest'      => true
        ];

        register_taxonomy(
            $tax['slug'],
            ['post'],
            $tax_args
        );
    }
}

// Добавляет фильтр по таксономии
add_action('restrict_manage_posts', 'taxonomies_filter');

function taxonomies_filter()
{
    global $typenow;

    global $trunov_taxonomies;

    if ($typenow == 'post') {

        $taxes = [];

        foreach ($trunov_taxonomies as $tax) {

            array_push($taxes, $tax['slug']);
        }

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
