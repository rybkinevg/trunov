<?php

namespace rybkinevg\trunov;

class Posts extends Transfer
{
    static $post_type = 'post';

    protected static function get(): array
    {
        global $wpdb;

        $query = "
            SELECT
                *
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '114'
            OR
                `parent_id` = '115'
            OR
                `parent_id` = '14820'
            ORDER BY
                `id`
        ";

        $posts = $wpdb->get_results($query);

        return $posts;
    }

    public static function set()
    {
        global $wpdb;

        $posts = self::get();

        foreach ($posts as $post) {

            $args = [
                'post_type' => self::$post_type
            ];

            $data = parent::generate_args($post, $args);

            $data['ID'] = $post->id;
            unset($data['import_id']);
            unset($data['meta_input']);

            $inserted = $wpdb->insert(
                $wpdb->posts,
                $data
            );

            if (!$inserted) {

                $message = "
                    <p>ID поста: {$post->id}</p>
                ";

                self::show_error(null, $message);
            }

            $cat_id = get_cat_ID('Новости СМИ');

            if ($post->parent_id == '115')
                $cat_id = get_cat_ID('Новости');

            $cat = wp_set_post_categories($wpdb->insert_id, $cat_id);

            if (is_wp_error($cat))
                self::show_error($cat);
        }
    }

    protected static function get_taxes(): array
    {
        global $wpdb;

        $query = "
            SELECT
                `add`.`id_topic_dir`,
                `atd`.`name`
            FROM
                `aleksnet_doc_topic` as `add`
            JOIN
                `aleksnet_topic_document` as `atd`
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
            ORDER BY
                `id`
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

    public static function set_taxes()
    {
        global $wpdb;

        $topics = self::get_taxes();

        foreach ($topics as $topic) {

            $query = "
                SELECT
                    `id`,
                    `name`
                FROM
                    `aleksnet_topic_document`
                WHERE
                    `parent_id` = '{$topic['id']}'
                ORDER BY
                    `id`
            ";

            $terms = $wpdb->get_results($query);

            $tax = get_taxonomies(['description' => $topic['id']]);

            $tax_slug = array_shift($tax);

            foreach ($terms as $term) {

                $args = [
                    'description' => $term->id,
                    'slug'        => sanitize_title($term->name),
                    'parent'      => 0
                ];

                $term = wp_insert_term($term->name, $tax_slug, $args);
            }
        }
    }

    public static function set_post_tax()
    {
        $topics = self::get_taxes();

        foreach ($topics as $topic) {

            global $wpdb;

            $query = "
                SELECT
                    `adt`.`id`,
                    `adt`.`id_dir`,
                    `atd`.`name`,
                    `atd`.`id_topic`
                FROM
                    `aleksnet_doc_topic` as `adt`
                JOIN
                    `aleksnet_topic_document` as `atd`
                ON
                    `adt`.`id_topic` = `atd`.`id`
                WHERE
                    `adt`.`id_topic_dir` = '{$topic['id']}'
                AND
                    `adt`.`id_dir` = '114'
                OR
                    `adt`.`id_dir` = '115'
                OR
                    `adt`.`id_dir` = '14820'
                ORDER BY
                    `id`
            ";

            $posts = $wpdb->get_results($query);

            $tax = get_taxonomies(['description' => $topic['id']]);

            $tax_slug = array_shift($tax);

            foreach ($posts as $post) {

                // Аманлиев Марат в СМИ - нет ID он на утв.
                if ($post->id_topic == '480') {

                    // continue;
                }

                // Ступин Евгений в СМИ - нет ID он на утв.
                if ($post->id_topic == '583') {

                    // continue;
                }

                // Комаровская Марианна в СМИ - нет ID он на утв.
                if ($post->id_topic == '432') {

                    // continue;
                }

                // Алексеева Татьяна в СМИ - 15697
                if ($post->id_topic == '481') {

                    update_post_meta($post->id, 'person', '15697');
                }

                // Гололобов Дмитрий Владимирович в СМИ - 19374
                if ($post->id_topic == '572') {

                    update_post_meta($post->id, 'person', '19374');
                }

                // Игорь Трунов на Mediametrics - 15711
                if ($post->id_topic == '607') {

                    update_post_meta($post->id, 'person', '15711');
                }

                // Людмила Айвар на Mediametrics - 15710
                if ($post->id_topic == '477') {

                    update_post_meta($post->id, 'person', '15710');
                }

                $term = get_term_by('name', $post->name, $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($post->id, [$term_id], $tax_slug, true);
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

    public static function actions()
    {
        add_action('admin_action_' . 'get_posts', function () {

            Posts::set();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . 'set_taxes', function () {

            Posts::set_taxes();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . 'set_post_tax', function () {

            Posts::set_post_tax();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . 'set_thumbs', function () {

            Posts::set_thumbs();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });
    }
}
