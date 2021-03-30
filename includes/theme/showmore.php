<?php

add_action('wp_ajax_showmore', 'trunov_showmore');
add_action('wp_ajax_nopriv_showmore', 'trunov_showmore');

function trunov_showmore()
{
    $args = unserialize(stripslashes($_POST['query']));

    $offset = $args['posts_per_page'];
    $args['posts_per_page'] = 999;
    $args['offset'] = $offset;

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        while ($query->have_posts()) {

            $query->the_post();

            get_template_part('template-parts/index/content', $_POST['posttype']);
        }
    } else {
    }

    wp_reset_postdata();

    wp_die();
}
