<?php

namespace rybkinevg\trunov;

class Services extends Transfer
{
    static $post_type = 'services';

    protected static function get(): array
    {
        global $wpdb;

        $query = "
            SELECT
                *
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '16789'
            OR
                `parent_id` = '16790'
            OR
                `parent_id` = '118'
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

            global $wpdb;

            $args = [
                'post_type' => self::$post_type
            ];

            $data = parent::generate_args($post, $args);

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                parent::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            // При импорте не понятно почему в конце `post_name` проставлялась цифра 2 (`post_name-2`)
            // Проставлялась почему-то только при использовании числового `post_name`
            // Получилось вылечить только обновлением данных поста после импорта
            $wpdb->update(
                $wpdb->posts,
                ['post_name' => $inserted],
                ['ID' => $inserted]
            );

            if ($post->parent_id == '16790') {

                $tax_slug = 'services_categories';

                $term = get_term_by('name', 'Физическим лицам', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug, false);
            } elseif ($post->parent_id == '118') {

                $tax_slug = 'services_categories';

                $term = get_term_by('name', 'Юридический бизнес', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug, false);
            } else {

                $term_id = null;
            }

            self::get_services_children($inserted, $term_id);
        }

        $updated = parent::set_status(self::$post_type, 'Выполнено');

        if (!$updated)
            parent::show_error(null, var_dump($status));
    }

    protected static function get_services_children($id, $term_id = null)
    {
        global $wpdb;

        $query = "
            SELECT
                *
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '{$id}'
            ORDER BY
                `id`
        ";

        $posts = $wpdb->get_results($query);

        if (!is_null($posts) && !empty($posts)) {

            foreach ($posts as $post) {

                $args = [
                    'post_type'    => self::$post_type,
                    'post_parent'  => $id
                ];

                $data = parent::generate_args($post, $args);

                $child_inserted = wp_insert_post($data, true);

                if (is_wp_error($child_inserted)) {

                    $message = "
                        <p>ID поста: {$post->id}</p>
                        <p>ID родителя: {$post->parent_id}</p>
                    ";

                    self::show_error($child_inserted, $message);
                }

                // При импорте не понятно почему в конце `post_name` проставлялась цифра 2 (`post_name-2`)
                // Проставлялась почему-то только при использовании числового `post_name`
                // Получилось вылечить только обновлением данных поста после импорта
                $wpdb->update(
                    $wpdb->posts,
                    ['post_name' => $child_inserted],
                    ['ID' => $child_inserted]
                );

                if (!is_null($term_id)) {

                    $tax_slug = 'services_categories';

                    wp_set_post_terms($child_inserted, [$term_id], $tax_slug, false);
                }

                self::get_services_children($child_inserted, $term_id);
            }
        }
    }

    public static function actions()
    {
        add_action('admin_action_' . self::$post_type . '_get', function () {

            self::set();

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
            'title' => 'Услуги',
            'status' => parent::get_status(self::$post_type),
            'forms' => [
                [
                    'title'  => 'Импорт',
                    'desc'   => 'Импорт услуг, их дочерних страниц и таксономий',
                    'btn'    => 'Импортировать',
                    'action' => self::$post_type . '_get'
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
