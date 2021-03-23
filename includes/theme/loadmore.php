<?php

add_action('wp_ajax_loadmore', 'trunov_load_more');
add_action('wp_ajax_nopriv_loadmore', 'trunov_load_more');

function trunov_load_more()
{
    $args = unserialize(stripslashes($_POST['query']));

    $args['posts_per_page'] = 999;
    // unset($args['posts_per_page']);
    // $args['nopaging'] = true;
    $args['offset'] = $_POST['offset'];

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        while ($query->have_posts()) {

            $query->the_post();

            get_template_part('index-page-parts/content', $_POST['posttype']);
        }
    } else {
    }

    wp_reset_postdata();

    wp_die();
}
