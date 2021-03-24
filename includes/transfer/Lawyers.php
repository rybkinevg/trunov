<?php

namespace rybkinevg\trunov;

class Lawyers extends Transfer
{
    static $post_type = 'lawyers';

    protected static function get(): array
    {
        global $wpdb;

        $query = "
            SELECT
                `p`.`id`,
                `p`.`name`,
                `p`.`text`,
                `p`.`url_img`,
                `p`.`date`,
                `p`.`active`,
                `p`.`parent_id`,
                `t`.`id_topic`,
                `t`.`id_topic_dir`,
                `tn`.`name` as `topic_name`
            FROM
                `aleksnet_document` as `p`
            LEFT JOIN
                `aleksnet_doc_topic` as `t`
            ON
                `p`.`id` = `t`.`id`
            LEFT JOIN
                `aleksnet_topic_document` as `tn`
            ON
                `t`.`id_topic` = `tn`.`id`
            WHERE
                `p`.`parent_id` = '109'
            OR
                `p`.`parent_id` = '15712'
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
                'post_type' => self::$post_type
            ];

            $data = parent::generate_args($post, $args);

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                parent::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            if ($post->id == '15710' || $post->id == '15711') {

                carbon_set_post_meta($post->id, 'status', 'head');
            } else {

                carbon_set_post_meta($post->id, 'status', 'staff');
            }

            if ($post->parent_id == '109') {

                $tax_slug = 'lawyers_tax';

                $term = get_term_by('name', 'Адвокат', $tax_slug);

                if ($term) {

                    $term_id = $term->term_id;

                    wp_set_post_terms($post->id, [$term_id], $tax_slug, false);
                }
            }

            if (!is_null($post->id_topic)) {

                $tax_slug = 'offices';

                $term = get_term_by('name', $post->topic_name, $tax_slug);

                if ($term) {

                    $term_id = $term->term_id;

                    wp_set_post_terms($post->id, [$term_id], $tax_slug, false);
                }
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
            'title' => 'Адвокаты',
            'status' => parent::get_status(self::$post_type),
            'forms' => [
                [
                    'title'  => 'Импорт',
                    'desc'   => 'Импорт адвокатов и юристов, проставление таксономий',
                    'btn'    => 'Импортировать',
                    'action' => self::$post_type . '_get'
                ],
                [
                    'title'  => 'Миниатюры',
                    'desc'   => 'Скачать и установить миниатюры',
                    'btn'    => 'Скачать',
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
