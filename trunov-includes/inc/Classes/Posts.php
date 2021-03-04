<?php

namespace rybkinevg\trunov;

class Posts
{
    protected static function check_date($date)
    {
        if ($date == '0000-00-00') {

            return current_time('Y-m-d H:i:s');
        }

        return $date . ' ' . current_time('H:i:s');
    }

    protected static function show_error($obj, $message = null)
    {
        if (is_null($message))
            $message = "<p>Текст ошибки: {$obj->get_error_message()}</p>";
        else
            $message .= "<p>Текст ошибки: {$obj->get_error_message()}</p>";

        $args = [
            'back_link' => true
        ];

        wp_die($message, 'Ошибка!', $args);
    }

    /**
     * Получение массива новостей из исходной таблицы данных
     *
     * @param  int $type   parent_id категории из иходной таблицы
     *
     * @return array       массив найденных постов
     */
    public static function get(int $type): array
    {
        global $wpdb;

        $query = "SELECT `id`, `name`, `text`, `date`, `active` FROM `aleksnet_document` WHERE `parent_id` = '{$type}'";

        $news = $wpdb->get_results($query);

        return $news;
    }

    /**
     * Импорт данных из исходной таблицы в таблицу wp_posts
     *
     * @param  array $news    массив постов
     *
     * @param  int   $catid   ID категории
     *
     * @return int            количество импортированных постов
     */
    public static function insert(array $news, int $catid): int
    {
        global $wpdb;

        $count = 0;

        foreach ($news as $item) {

            $data = [
                'ID'           => $item->id,
                'post_title'   => sanitize_text_field($item->name),
                'post_content' => $item->text,
                'post_date'    => $item->date,
                'post_name'    => $item->id,
                'post_author'  => 1,
                'post_status'  => ($item->active == 1) ? "publish" : "pending"
            ];

            $inserted = $wpdb->insert(
                $wpdb->posts,
                $data
            );

            if ($inserted) {

                wp_set_post_categories($wpdb->insert_id, $catid);

                $count++;
            }
        }

        return $count;
    }

    /**
     * Получение массива ID постов, в которых есть метка фотоархив/видеоархив/анонс
     *
     * @param  string $type   тип метки, может быть: photoarchive, videoarchive, anons
     *
     * @return array          массив найденных ID, либо пустой массив при неправильной метке
     *
     * @throws array          пустой массив при неправильно указанной метке
     */
    public static function get_for_tags(string $type): array
    {
        global $wpdb;

        $news = [];

        switch ($type) {
            case 'photoarchive':
                $query = "SELECT `id` FROM `aleksnet_doc_topic` WHERE `id_topic` = '399'";
                break;

            case 'videoarchive':
                $query = "SELECT `id` FROM `aleksnet_doc_topic` WHERE `id_topic` = '398' OR `id_topic` = '401' OR `id_topic` = '268'";
                break;

            case 'anons':
                $query = "SELECT `id` FROM `aleksnet_document` WHERE `parent_id` = '14820'";
                break;

            default:
                $query = null;
                break;
        }

        if ($query)
            $news = $wpdb->get_results($query);

        return $news;
    }

    /**
     * Установка найденным постам метки фотоархив/видеоархив/анонс
     *
     * @param  array $posts   массив ID постов
     *
     * @param  array $tagid   массив с ID метки, которую нужно установить
     *
     * @return int            количество постов, которым проставилась метка
     */
    public static function set_post_tags(array $posts, array $tagid): int
    {
        $count = 0;

        foreach ($posts as $post) {

            $added = wp_set_post_tags($post->id, $tagid, true);

            if (!is_wp_error($added)) {

                $count++;
            }
        }

        return $count;
    }

    public static function set_post_tax()
    {
        $topics = Topics::get();

        foreach ($topics as $topic) {

            global $wpdb;

            $query = "
            SELECT `adt`.`id`, `adt`.`id_dir`, `atd`.`name`
                FROM `aleksnet_doc_topic` as `adt`
            JOIN `aleksnet_topic_document` as `atd`
                ON `adt`.`id_topic` = `atd`.`id`
            WHERE `adt`.`id_topic_dir` = '{$topic['id']}'
                AND `adt`.`id_dir` = '114'
                OR `adt`.`id_dir` = '115'
                OR `adt`.`id_dir` = '14820'
            ";

            $posts = $wpdb->get_results($query);

            $tax = get_taxonomies(['description' => $topic['id']]);

            $tax_slug = array_shift($tax);

            foreach ($posts as $post) {

                $term = get_term_by('name', $post->name, $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($post->id, [$term_id], $tax_slug, true);
            }
        }
    }

    public static function get_posts()
    {
        global $wpdb;

        // $count_query = "
        // SELECT
        //     COUNT(*)
        // FROM
        //     `aleksnet_document`
        // WHERE
        //     `parent_id` = '114'
        // OR
        //     `parent_id` = '115'
        // OR
        //     `parent_id` = '14820'
        // ";

        // $count = $wpdb->get_var($count_query);

        $query = "
        SELECT
            `id`,
            `name`,
            `text`,
            `date`,
            `active`,
            `parent_id`
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

        foreach ($posts as $post) {

            $data = [
                'import_id'    => $post->id,
                'post_title'   => sanitize_text_field($post->name),
                'post_content' => $post->text,
                'post_date'    => self::check_date($post->date),
                'post_name'    => $post->id,
                'post_author'  => 1,
                'post_status'  => ($post->active == 1) ? "publish" : "pending"
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted))
                self::show_error($inserted);

            if ($post->parent_id == '115')
                $cat_id = get_cat_ID('Новости');
            else
                $cat_id = get_cat_ID('Новости СМИ');

            wp_set_post_categories($wpdb->insert_id, $cat_id);
        }
    }

    public static function get_lawyers(): int
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
        ";

        $posts = $wpdb->get_results($query);

        $count = 0;

        foreach ($posts as $post) {

            $wpdb->insert(
                $wpdb->posts,
                ['ID' => $post->id]
            );

            $data = [
                'ID'           => $post->id,
                'post_title'   => sanitize_text_field($post->name),
                'post_content' => $post->text,
                'post_date'    => self::check_date($post->date),
                'post_name'    => $post->id,
                'post_author'  => 1,
                'post_status'  => ($post->active == 1) ? "publish" : "pending",
                'post_type'    => 'lawyers'
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted))
                wp_die($inserted->get_error_message());

            if ($inserted) {

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

                self::set_post_thumb($post->id, $post->url_img);

                $count++;
            }
        }

        return $count;
    }

    protected static function set_post_thumb($post_id, $thumb_url)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $path = parse_url($thumb_url, PHP_URL_PATH);

        $result = explode("/", trim($path, "/"));

        $url = "http://trunov.com/{$result[1]}/{$result[2]}/d_{$result[3]}";

        $thumb_id = media_sideload_image($url, $post_id, null, 'id');

        if (is_wp_error($thumb_id)) {

            $try_this_url = "http://trunov.com/{$result[1]}/aleksnet_document/d_{$result[3]}";

            $try_thumb_id = media_sideload_image($try_this_url, $post_id, null, 'id');

            if (is_wp_error($try_thumb_id)) {

                $message = "
                <p>ID поста: {$post_id}</p>
                <p>Переданная ссылка: {$thumb_url}</p>
                <p>Конвертированная ссылка: {$url}</p>
                <p>Повторно конвертированная ссылка: {$try_this_url}</p>
                <p>Текст ошибки: {$try_thumb_id->get_error_message()}</p>
                ";

                $args = [
                    'back_link' => true
                ];

                wp_die($message, "Ошибка", $args);
            }

            $thumb_id = $try_thumb_id;
        }

        set_post_thumbnail($post_id, $thumb_id);
    }

    public static function get_services_catalog()
    {
        global $wpdb;

        $query = "
            SELECT
                `id`,
                `name`,
                `date`,
                `text`,
                `active`,
                `parent_id`
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '16789'
            OR
                `parent_id` = '16790'
            ";

        $posts = $wpdb->get_results($query);

        foreach ($posts as $post) {

            $data = [
                'import_id'    => $post->id,
                'post_title'   => sanitize_text_field($post->name),
                'post_content' => $post->text,
                'post_date'    => self::check_date($post->date),
                'post_author'  => 1,
                'post_name'    => $post->id,
                'post_status'  => ($post->active == 1) ? "publish" : "pending",
                'post_type'    => 'services-catalog'
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                $message = "
                <p>ID поста: {$post->id}</p>
                <p>ID родителя: {$post->parent_id}</p>
                <p>Текст ошибки: {$inserted->get_error_message()}</p>
                ";

                $args = [
                    'back_link' => true
                ];

                wp_die($message, '', $args);
            }

            // При импорте не понятно почему в конце `post_name` проставлялась цифра 2 (`post_name-2`)
            // Проставлялась почему-то только при использовании числового `post_name`
            // Получилось вылечить только обновлением данных поста после импорта
            $wpdb->update(
                $wpdb->posts,
                ['post_name' => $inserted],
                ['ID' => $inserted]
            );

            if ($post->parent_id == '16790') {

                $tax_slug = 'services_catalog_categories';

                $term = get_term_by('name', 'Физическим лицам', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug, false);
            } else {

                $term_id = null;
            }

            self::get_services_catalog_children($inserted, $term_id);
        }
    }

    protected static function get_services_catalog_children($id, $term_id = null)
    {
        global $wpdb;

        $query = "
        SELECT
            `id`,
            `name`,
            `date`,
            `text`,
            `active`,
            `parent_id`
        FROM
            `aleksnet_document`
        WHERE
            `parent_id` = '{$id}'
        ";

        $posts = $wpdb->get_results($query);

        if (!is_null($posts) && !empty($posts)) {

            foreach ($posts as $post) {

                $data = [
                    'import_id'    => $post->id,
                    'post_title'   => sanitize_text_field($post->name),
                    'post_content' => $post->text,
                    'post_date'    => self::check_date($post->date),
                    'post_name'    => $post->id,
                    'post_author'  => 1,
                    'post_status'  => ($post->active == 1) ? "publish" : "pending",
                    'post_type'    => 'services-catalog',
                    'post_parent'  => $id
                ];

                $child_inserted = wp_insert_post($data, true);

                if (is_wp_error($child_inserted)) {

                    $message = "
                            <p>ID поста: {$post->id}</p>
                            <p>ID родителя: {$post->parent_id}</p>
                            <p>Текст ошибки: {$child_inserted->get_error_message()}</p>
                            ";

                    $args = [
                        'back_link' => true
                    ];

                    wp_die($message, '', $args);
                }

                // При импорте не понятно почему в конце `post_name` проставлялась цифра 2 (`post_name-2`)
                // Проставлялась почему-то только при использовании числового `post_name`
                // Получилось вылечить только обновлением данных поста после импорта
                $wpdb->update(
                    $wpdb->posts,
                    ['post_name' => $child_inserted],
                    ['ID' => $child_inserted]
                );

                if (!is_null($term_id)) {

                    $tax_slug = 'services_catalog_categories';

                    wp_set_post_terms($child_inserted, [$term_id], $tax_slug, false);
                }

                self::get_services_catalog_children($child_inserted, $term_id);
            }
        }
    }
}
