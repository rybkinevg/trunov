<?php

// Подключение скриптов
add_action('wp_enqueue_scripts', 'trunov_scripts');

function trunov_scripts()
{
    // URI до папки
    $src = get_template_directory_uri() . '/assets';

    // Подключение стилей сайта
    wp_enqueue_style('trunov-style', get_stylesheet_uri());

    // Регистрация стилей Slick слайдера
    wp_register_style(
        'font-awesome',
        $src . '/vendor/slick/slick.css',
        [],
        null,
        'all'
    );

    wp_enqueue_style('font-awesome');

    // Регистрация Font Awesome 4
    wp_register_style(
        'slick-slider-css',
        $src . '/vendor/fontawesome/css/font-awesome.min.css',
        [],
        null,
        'all'
    );

    wp_enqueue_style('slick-slider-css');

    // Регистрация Slick слайдера
    wp_register_script(
        'slick-slider',
        $src . '/vendor/slick/slick.min.js',
        ['jquery'],
        null,
        true
    );

    wp_enqueue_script('slick-slider');

    // Регистрация скрипта сайта
    wp_register_script(
        'trunov-script',
        $src . '/js/main.js',
        ['jquery'],
        null,
        true
    );

    wp_enqueue_script('trunov-script');

    // Возможность получения ссылки на обработчик AJAX с фронта
    wp_localize_script(
        'trunov-script',
        'ajax',
        [
            'url' => admin_url('admin-ajax.php')
        ]
    );
}
