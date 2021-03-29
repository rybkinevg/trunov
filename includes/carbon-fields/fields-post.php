<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Массив Адвокатов
$person_arr = [];

$posts = get_posts(
    [
        'numberposts' => -1,
        'post_type'   => 'lawyers',
        'post_status' => 'publish'
    ]
);

foreach ($posts as $post) {
    $person_arr[$post->ID] = $post->post_title;
}

wp_reset_postdata();

// Массив услуг
$services_arr = [];

$posts = get_posts(
    [
        'numberposts' => -1,
        'post_type'   => 'services',
        'post_status' => 'publish'
    ]
);

foreach ($posts as $post) {
    $services_arr[$post->ID] = $post->post_title;
}

wp_reset_postdata();

Container::make('post_meta', 'Дополнительные поля')
    ->show_on_post_type('post')
    ->add_fields(
        [
            Field::make('text', 'source', 'Источник')
                ->set_help_text('Укажите ссылку на источник, если таковой имеется'),
            Field::make('multiselect', 'persons', 'Персоны')
                ->set_options($person_arr)
                ->set_help_text('Выберите одного или несколько адвокатов, упомянутых в новости'),
            Field::make('multiselect', 'services', 'Услуги')
                ->set_options($services_arr)
                ->set_help_text('Выберите одну или несколько услуг, упомянутых в новости')
        ]
    );
