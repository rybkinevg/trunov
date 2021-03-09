<?php

namespace rybkinevg\trunov;

class Media_columns extends Transfer
{
    static $post_type = 'media-columns';

    protected static function get(): array
    {
        global $wpdb;

        $query = "
            SELECT
                *
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '16018'
            OR
                `parent_id` = '16019'
            OR
                `parent_id` = '16020'
            OR
                `parent_id` = '16021'
            OR
                `parent_id` = '16022'
            OR
                `parent_id` = '16023'
            OR
                `parent_id` = '16024'
            ORDER BY
                `id`
        ";

        $posts = $wpdb->get_results($query);

        return $posts;
    }

    public static function set()
    {
        $posts = self::get();

        foreach ($posts as $post) {

            // Автор колонки
            if ($post->parent_id == '16018') {

                continue;
            }

            // СМИ
            if ($post->parent_id == '16019') {

                continue;
            }

            $args = [
                'post_type'    => self::$post_type,
                'post_content' => '',
                'meta_input'   => [
                    'url' => $post->url
                ]
            ];

            $data = parent::generate_args($post, $args);

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                parent::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }
        }
    }

    public static function set_thumbs()
    {
        $posts = self::get();

        foreach ($posts as $post) {

            parent::set_post_thumb($post->id, $post->url_img);
        }
    }
}
