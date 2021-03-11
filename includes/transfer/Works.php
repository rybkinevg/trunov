<?php

namespace rybkinevg\trunov;

class Works extends Transfer
{
    static $post_type = 'works';

    protected static function get(): array
    {
        global $wpdb;

        $query = "
            SELECT
                *
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '16076'
            OR
                `parent_id` = '15540'
            OR
                `parent_id` = '15011'
            OR
                `parent_id` = '15541'
            OR
                `parent_id` = '15012'
            ORDER BY
                `id`
        ";

        $posts = $wpdb->get_results($query);

        return $posts;
    }

    public static function set()
    {
        $status = parent::get_status(self::$post_type);

        if ($status)
            parent::show_error(null, 'Данные типа записи "' . self::$post_type . '" уже импортированы');

        $posts = self::get();

        foreach ($posts as $post) {

            $args = [
                'post_type'    => self::$post_type
            ];

            $data = parent::generate_args($post, $args);

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                parent::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            carbon_set_post_meta($inserted, 'works-url', $post->url);

            // Книги, монографии
            if ($post->parent_id == '15541') {

                $tax_slug = 'works-categories';

                $term = get_term_by('name', 'Публикации Айвар Людмилы Константиновны', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);

                $tax_slug = 'works-types';

                $term = get_term_by('name', 'Книги, монографии', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);
            }

            if ($post->parent_id == '15012') {

                $tax_slug = 'works-categories';

                $term = get_term_by('name', 'Публикации Трунова Игоря Леонидовича', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);

                $tax_slug = 'works-types';

                $term = get_term_by('name', 'Книги, монографии', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);
            }

            // Научные статьи
            if ($post->parent_id == '15540') {

                $tax_slug = 'works-categories';

                $term = get_term_by('name', 'Публикации Айвар Людмилы Константиновны', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);
            }

            if ($post->parent_id == '15011') {

                $tax_slug = 'works-categories';

                $term = get_term_by('name', 'Публикации Трунова Игоря Леонидовича', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);
            }
        }

        $updated = parent::set_status(self::$post_type, 'Выполнено');

        if (!$updated)
            parent::show_error(null, var_dump($status));
    }

    public static function set_thumbs()
    {
        $posts = self::get();

        foreach ($posts as $post) {

            parent::set_post_thumb($post->id, $post->url_img);
        }
    }

    public static function actions()
    {
        add_action('admin_action_' . self::$post_type . '_get', function () {

            self::set();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . self::$post_type . '_set_thumbs', function () {

            self::set_thumbs();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . self::$post_type . '_delete', function () {

            parent::delete(self::get(), self::$post_type);

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });
    }

    public static function page_block()
    {
        $data = [
            'title' => 'Научные и учебно-методические труды',
            'status' => parent::get_status(self::$post_type),
            'forms' => [
                [
                    'title'  => 'Импорт',
                    'desc'   => 'Импорт научных и учебно-методических трудов, проставление таксономий',
                    'btn'    => 'Импортировать',
                    'action' => self::$post_type . '_get'
                ],
                [
                    'title'  => 'Миниатюра',
                    'desc'   => 'Скачать и установить миниатюры',
                    'btn'    => 'Импортировать',
                    'action' => self::$post_type . '_set_thumbs'
                ],
                [
                    'title'  => 'Очистка',
                    'desc'   => 'Удаление записей, а так же привязки к таксономиям, комментариям и мета-полям',
                    'btn'    => 'Удалить',
                    'action' => self::$post_type . '_delete'
                ]
            ]
        ];

        return $data;
    }
}
