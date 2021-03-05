<?php

namespace rybkinevg\trunov;

class Topics
{
    public static function get_for_posts()
    {
        global $wpdb;

        $table_topics_id = 'aleksnet_doc_topic';

        $table_topics_name = 'aleksnet_topic_document';

        if ($type = 'news')
            $query = "
                SELECT
                    `add`.`id_topic_dir`,
                    `atd`.`name`
                FROM
                    `{$table_topics_id}` as `add`
                JOIN
                    `{$table_topics_name}` as `atd`
                ON
                    `add`.`id_topic_dir` = `atd`.`id`
                WHERE
                    `add`.`id_dir` = '114'
                OR
                    `add`.`id_dir` = '115'
                OR
                    `add`.`id_dir` = '14820'
                GROUP BY
                    `add`.`id_topic_dir`
            ";

        $topics = $wpdb->get_results($query);

        $result = [];

        foreach ($topics as $topic) {

            $topic_data = [
                'id'   => $topic->id_topic_dir,
                'name' => $topic->name
            ];

            array_push($result, $topic_data);
        }

        return $result;
    }

    public static function fill()
    {
        global $wpdb;

        $topics = Topics::get();

        foreach ($topics as $topic) {

            $query = "SELECT `id`, `name` FROM `aleksnet_topic_document` WHERE `parent_id` = '{$topic['id']}'";

            $tags = $wpdb->get_results($query);

            $tax = get_taxonomies(['description' => $topic['id']]);

            $tax_slug = array_shift($tax);

            foreach ($tags as $tag) {

                $args = [
                    'description' => $tag->id,
                    'slug'        => sanitize_title($tag->name),
                    'parent'      => 0
                ];

                $term = wp_insert_term($tag->name, $tax_slug, $args);
            }
        }
    }

    public static function get_for_lawyers()
    {
        global $wpdb;

        $table_topics_id = 'aleksnet_doc_topic';

        $table_topics_name = 'aleksnet_topic_document';

        $query = "
            SELECT
                `add`.`id`,
                `add`.`id_topic_dir`,
                `atd`.`name`
            FROM
                `{$table_topics_id}` as `add`
            JOIN
                `{$table_topics_name}` as `atd`
            ON
                `add`.`id_topic_dir` = `atd`.`id`
            WHERE
                `add`.`id_dir` = '512'
        ";

        $topics = $wpdb->get_results($query);

        $result = [];

        foreach ($topics as $topic) {

            $topic_data = [
                'id'   => $topic->id,
                'topic_id'   => $topic->id_topic_dir,
                'name' => $topic->name
            ];

            array_push($result, $topic_data);
        }

        return $result;
    }
}
