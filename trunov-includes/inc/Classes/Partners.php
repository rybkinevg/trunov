<?php

namespace rybkinevg\trunov;

class Partners extends Transfer
{
    public static function set_posts()
    {
        $table = "aleksnet_document";

        $where = "WHERE `parent_id` = '15962'";

        $posts = parent::get($table, $where);

        foreach ($posts as $post) {

            $args = [
                'post_type'    => 'partners',
                'meta_input'   => [
                    'url' => $post->url
                ]
            ];

            $data = parent::generate_args($post, $args);

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                parent::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            // self::set_post_thumb($inserted, $post->url_img);
        }
    }

    public static function set_thumbs()
    {
    }
}
