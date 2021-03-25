<?php

/**
 * Хук изменяет шаблон вывода архива постов (встроенного в WP типа записи)
 *
 * Когда главная страница выбрана как статическая, то для вывода всех записей типа - post
 * необходимо создать страницу и указать её как - Страница записей
 * По умолчанию, когда выбрана эта опция, WP подтягивает файл с именем - home.php и, если такой не найден,
 * то подключает - index.php
 * Чтобы заставить WP выводить посты из файла (шаблона) archives.php нужно использовать хук-фильтр
 */

add_filter('home' . '_template', 'filter_archive_post_template', 10, 3);

function filter_archive_post_template($template, $type, $templates)
{
    $template = locate_template('archive.php');

    return $template;
}

/**
 * Хук изменяет шаблон вывода архива постов (встроенного в WP типа записи)
 *
 * Когда главная страница выбрана как статическая, то для вывода её
 * необходимо создать страницу и указать её как - Главная страница
 * По умолчанию, когда выбрана эта опция, WP подтягивает файл с именем - front-page.php и, если такой не найден,
 * то подключает - page.php
 * Чтобы заставить WP выводить главную страницу из файла (шаблона) index.php нужно использовать хук-фильтр
 */

add_filter('frontpage' . '_template', 'filter_frontpage_template', 10, 3);

function filter_frontpage_template($template, $type, $templates)
{
    $template = locate_template('index.php');

    return $template;
}
