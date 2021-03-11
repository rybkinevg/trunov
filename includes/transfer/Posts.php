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
        $status = parent::get_status(self::$post_type);

        if ($status)
            parent::show_error(null, 'Данные типа записи "' . self::$post_type . '" уже импортированы');

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

        $updated = parent::set_status(self::$post_type, 'Выполнено');

        if (!$updated)
            parent::show_error(null, var_dump($status));
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

                // Не импортировать "Громкий плагиат" из Сопутствующих тем, забил его руками в таксу - Громкие дела
                if ($term->id == '547')
                    continue;

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

                // Адвокаты в СМИ или Телепередачи
                if ($post->id_topic_dir == '431' || $post->id_topic_dir == '400') {

                    self::set_person_meta($post->id, $post->id_topic);
                }

                // "Громкий плагиат" в Громкие дела
                if ($post->id_topic_dir == '447' && $post->id_topic == '547') {

                    wp_set_post_terms(
                        $post->id,
                        [get_term_by('name', '«Громкий плагиат»', 'high-profile-cases')->term_id],
                        'high-profile-cases',
                        true
                    );

                    continue;
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

        if (is_null($service))
            return;

        $data = carbon_get_post_meta($post_id, 'services');

        if (empty($data)) {

            $data = $service->ID;
        } else {

            if (in_array($service->ID, $data))
                return;

            array_push($data, $service->ID);
        }

        carbon_set_post_meta($post_id, 'services', $data);
    }

    protected static function set_person_meta($post_id, $topic_id)
    {
        switch ($topic_id) {

            case '481':
                // Адвокаты в СМИ - Алексеева Татьяна в СМИ - 15697
                $value = '15697';
                break;

            case '572':
                // Адвокаты в СМИ - Гололобов Дмитрий Владимирович в СМИ - 19374
                $value = '19374';
                break;

            case '607':
                // Адвокаты в СМИ - Игорь Трунов на Mediametrics - 15711
                $value = '15711';

                $term = get_term_by('name', 'Mediametrics', 'smi');

                $term_id = $term->term_id;

                wp_set_post_terms($post_id, [$term_id], 'smi', true);
                break;

            case '477':
                // Адвокаты в СМИ - Людмила Айвар на Mediametrics - 15710
                $value = '15710';

                $term = get_term_by('name', 'Mediametrics', 'smi');

                $term_id = $term->term_id;

                wp_set_post_terms($post_id, [$term_id], 'smi', true);
                break;

            case '401':
                // Телепередачи - С участием Людмила Айвар - 15710
                $value = '15710';
                break;

            case '402':
                // Телепередачи - С участием Игоря Трунова - 15711
                $value = '15711';
                break;

            default:
                $value = null;
                break;
        }

        // $topic_id == '480' Аманлиев Марат в СМИ - нет ID, статус адвоката - на утверждении
        // $topic_id == '583' Ступин Евгений в СМИ - нет ID, статус адвоката - на утверждении
        // $topic_id == '432' Комаровская Марианна в СМИ - нет ID, статус адвоката - на утверждении

        if (is_null($value))
            return;

        $data = carbon_get_post_meta($post_id, 'persons');

        if (empty($data)) {

            $data = $value;
        } else {

            if (in_array($value, $data))
                return;

            array_push($data, $value);
        }

        carbon_set_post_meta($post_id, 'persons', $data);
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

        add_action('admin_action_' . self::$post_type . '_set_taxes', function () {

            self::set_taxes();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . self::$post_type . '_set_post_tax', function () {

            self::set_post_tax();

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
            'title' => 'Пресс-центр',
            'status' => parent::get_status(self::$post_type),
            'forms' => [
                [
                    'title'  => 'Импорт',
                    'desc'   => 'Импорт новостей, проставление категорий и таксономий',
                    'btn'    => 'Импортировать',
                    'action' => self::$post_type . '_get'
                ],
                [
                    'title'  => 'Таксономии',
                    'desc'   => 'Заполнение таксономий терминами',
                    'btn'    => 'Заполнить',
                    'action' => self::$post_type . '_set_taxes'
                ],
                [
                    'title'  => 'Связь',
                    'desc'   => 'Связать термины таксономий с записями и заполнить метаполя',
                    'btn'    => 'Связать',
                    'action' => self::$post_type . '_set_post_tax'
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
