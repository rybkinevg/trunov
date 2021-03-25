<?php

function trunov_get_post_meta($post_id, $meta, $title = '', $post_type = 'post')
{
    // Массив с данными мета поля поста
    $meta_arr = carbon_get_post_meta($post_id, $meta);

    // Инициализация итогового массива
    $trunov_post_meta[$title] = [
        'meta' => []
    ];

    foreach ($meta_arr as $id) {

        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => 1,
            'p'              => $id,
            'post_status'    => 'publish'
        ];

        // Запрос в БД для выборки нужных постов
        $query = new WP_Query($args);

        if ($query->have_posts()) {

            while ($query->have_posts()) {

                $query->the_post();

                // Добавление ссылки и названия поста в итоговый массив
                $trunov_post_meta[$title]['meta'][] = [
                    'name' => get_the_title(),
                    'link' => wp_make_link_relative(get_the_permalink())
                ];
            }
        }

        wp_reset_postdata();
    }

    foreach ($trunov_post_meta as $key => $value) {

        // Очистка итогового массива от пустых мета полей
        if (empty($value['meta']))
            unset($trunov_post_meta[$key]);
    }

    return $trunov_post_meta;
}
