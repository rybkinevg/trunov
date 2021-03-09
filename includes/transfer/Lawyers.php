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

            if ($post->parent_id == '109') {

                $tax_slug = 'lawyers_tax';

                $term = get_term_by('name', 'Адвокат', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($post->id, [$term_id], $tax_slug, false);
            }

            if (!is_null($post->id_topic)) {

                $tax_slug = 'offices';

                $term = get_term_by('name', $post->topic_name, $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($post->id, [$term_id], $tax_slug, false);
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
