<?php

add_filter('post_type_labels_post', 'rename_posts_labels');

function rename_posts_labels($labels)
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
