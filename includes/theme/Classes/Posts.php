<?php

namespace rybkinevg\trunov;

class Posts
{


    // Колонки СМИ
    public static function get_media_columns()
    {
        global $wpdb;

        $query = "
            SELECT
                `id`,
                `name`,
                `url_img`,
                `url`,
                `date`,
                `active`,
                `parent_id`
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
            ";

        $posts = $wpdb->get_results($query);

        foreach ($posts as $post) {

            // Автор колонки
            if ($post->parent_id == '16018') {

                continue;
            }

            // СМИ
            if ($post->parent_id == '16019') {

                continue;
            }

            $meta = [
                'url' => $post->url
            ];

            $data = [
                'import_id'    => $post->id,
                'post_title'   => sanitize_text_field($post->name),
                'post_date'    => self::check_date($post->date),
                'post_author'  => 1,
                'post_name'    => $post->id,
                'post_status'  => ($post->active == 1) ? "publish" : "pending",
                'post_type'    => 'media-columns',
                'meta_input'   => $meta
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                self::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            // self::set_post_thumb($inserted, $post->url_img);
        }
    }
}
