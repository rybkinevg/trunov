<?php

namespace rybkinevg\trunov;

class Court extends Transfer
{
    static $post_type = 'court';

    protected static function get(): array
    {
        global $wpdb;

        $query = "
            SELECT
                *
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '116'
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

            $args = [
                'post_type' => self::$post_type
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
