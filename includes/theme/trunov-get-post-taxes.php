<?php

function trunov_get_post_taxes($post_id)
{
    // Получние таксономий, привязанных к типу записи
    $post_taxes = get_post_taxonomies($post_id);

    // Инициализация итогового массива
    $trunov_post_taxes = [];

    foreach ($post_taxes as $post_tax) {

        // Термины таксономии находящейся сейчас в цикле
        $post_terms = wp_get_post_terms($post_id, $post_tax);

        // Название таксономии
        $tax_name = get_taxonomy($post_tax)->label;

        // Добавление в итоговой массив названия таксономии
        $trunov_post_taxes[$tax_name] = [
            'terms' => []
        ];

        foreach ($post_terms as $post_term) {

            // Ссылка на архив термина (относительная)
            $term_archive_link = wp_make_link_relative(get_term_link($post_term->term_id));

            // Название термина
            $term_name = $post_term->name;

            // Добавление ссылки и названия термина в итоговый массив
            $trunov_post_taxes[$tax_name]['terms'][] = [
                'name' => $term_name,
                'link' => $term_archive_link
            ];
        }
    }

    foreach ($trunov_post_taxes as $key => $value) {

        // Очистка итогового массива от пустых таксономий
        if (empty($value['terms']))
            unset($trunov_post_taxes[$key]);
    }

    return $trunov_post_taxes;
}
