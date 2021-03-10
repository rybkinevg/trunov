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

            $tax = get_taxonomies(['description' => $topic['id']]);

            if (!$tax) continue;

            $tax_slug = array_shift($tax);

            global $wpdb;

            $query = "
                SELECT
                    `adt`.`id`,
                    `atd`.`name`,
                    `adt`.`id_topic`,
                    `adt`.`id_topic_dir`
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
            ";

            $posts = $wpdb->get_results($query);

            foreach ($posts as $post) {

                // Услуги
                if ($post->id_topic_dir == '461') {

                    self::set_service_meta($post->id, $post->name);
                }

                // Адвокаты в СМИ
                if ($post->id_topic_dir == '431') {

                    self::set_person_meta($post->id, $post->id_topic);
                }

                $term = get_term_by('name', $post->name, $tax_slug);

                $term_id = $term->term_id;

                $inserted = wp_set_post_terms($post->id, [$term_id], $tax_slug, true);

                if (is_wp_error($inserted)) {

                    $message = "
                        <p>ID поста: {$post->id}</p>
                        <p>Topic ID: {$topic['id']}</p>
                        <p>Slug таксы: {$tax_slug}</p>
                        <p>ID термина: {$post->id_topic}</p>
                        <p>Термин: {$term_id}</p>
                    ";

                    parent::show_error($inserted, $message);
                }
            }
        }
    }

    protected static function set_service_meta($post_id, $topic_name)
    {
        $service = get_page_by_title($topic_name, 'OBJECT', 'services');

        if (is_null($service)) return;

        $data = carbon_get_post_meta($post_id, 'services');

        if (empty($data)) {

            $data = $service->ID;
        } else {

            array_push($data, $service->ID);
        }

        carbon_set_post_meta($post_id, 'services', $data);
    }

    protected static function set_person_meta($post_id, $topic_id)
    {
        $data = carbon_get_post_meta($post_id, 'persons');

        switch ($topic_id) {

            case '481':
                // Алексеева Татьяна в СМИ - 15697
                $value = '15697';
                break;

            case '572':
                // Гололобов Дмитрий Владимирович в СМИ - 19374
                $value = '19374';
                break;

            case '607':
                // Игорь Трунов на Mediametrics - 15711
                $value = '15711';
                break;

            case '477':
                // Людмила Айвар на Mediametrics - 15710
                $value = '15710';
                break;

            default:
                $value = null;
                break;
        }

        // $topic_id == '480' Аманлиев Марат в СМИ - нет ID, статус адвоката - на утверждении
        // $topic_id == '583' Ступин Евгений в СМИ - нет ID, статус адвоката - на утверждении
        // $topic_id == '432' Комаровская Марианна в СМИ - нет ID, статус адвоката - на утверждении

        if (is_null($value)) return;

        if (empty($data)) {

            $data = $value;
        } else {

            array_push($data, $value);
        }

        carbon_set_post_meta($post_id, 'person', $data);
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
        add_action('admin_action_' . 'news' . '_get', function () {

            self::set();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . 'news' . '_set_taxes', function () {

            self::set_taxes();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . 'news' . '_set_post_tax', function () {

            self::set_post_tax();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . 'news' . '_set_thumbs', function () {

            self::set_thumbs();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });
    }
}
