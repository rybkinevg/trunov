<?php

function trunov_get_select_posts_filter($title = 'Рубрики', $taxonomy_slug = 'category')
{
    $args = [
        'show_option_all' => 'Все',
        'echo'            => 0,
        'class'           => 'form__select select',
        'name'            => $taxonomy_slug,
        'taxonomy'        => $taxonomy_slug
    ];

    $output = "<h2 class='sidebar__title'>{$title}</h2>" . wp_dropdown_categories($args);

    return $output;
}

add_action('wp_ajax_filter_posts',        'trunov_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'trunov_filter_posts');

function trunov_filter_posts()
{
    unset($_POST['action']);

    $taxes = [];

    foreach ($_POST as $tax_name => $term_id) {

        if ($term_id != '0') {

            array_push($taxes, ['taxonomy' => $tax_name, 'terms' => $term_id]);
        }
    }

    $args = [
        'posts_per_page' => get_option('posts_per_page'),
        'post_type'      => 'post',
        'tax_query'      => $taxes
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        while ($query->have_posts()) {

            $query->the_post();

            get_template_part('template-parts/archive/content', 'post');
        }
    } else {

        get_template_part('template-parts/archive/content', 'none');
    }

    die();
}
