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

    protected static function check_title($str, $encoding = 'UTF-8')
    {
        $str = mb_strtolower($str);

        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
            . mb_substr($str, 1, null, $encoding);
    }

    protected static function show_error($obj = null, $message = '')
    {
        if (!is_null($obj))
            $message .= "<p>Текст ошибки: {$obj->get_error_message()}</p>";

        $args = [
            'back_link' => true
        ];

        wp_die($message, 'Ошибка!', $args);
    }

    public static function set_post_tax()
    {
        $topics = Topics::get_for_posts();

        foreach ($topics as $topic) {

            global $wpdb;

            $query = "
            SELECT `adt`.`id`, `adt`.`id_dir`, `atd`.`name`, `atd`.`id_topic`
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

    static $imported_option_name = 'trunov_imported_posts';

    private static function get_part(): int
    {
        global $wpdb;

        $query = "
        SELECT
            COUNT(*)
        FROM
            `aleksnet_document`
        WHERE
            `parent_id` = '114'
        OR
            `parent_id` = '115'
        OR
            `parent_id` = '14820'
        ";

        $count = $wpdb->get_var($query);

        $part = round($count / 3);

        return (int) $part;
    }

    private static function get_limit(): string
    {
        $start = 0;

        $step = self::get_part();

        $count = $step;

        $status = self::get_status();

        if ($status == 1) {

            $start = $step;
        } elseif ($status == 2) {

            $start = $step + $step;

            $count = $step - 1;
        }

        $limit = "
            LIMIT
                {$start}, {$count}
        ";

        return $limit;
    }

    private static function get_status(): int
    {
        $option = get_option(self::$imported_option_name, 0);

        return (int) $option;
    }

    private static function set_status($status): bool
    {
        $updated = update_option(self::$imported_option_name, $status, false);

        return $updated;
    }

    public static function get_posts()
    {
        $status = self::get_status();

        if ($status == 3)
            self::show_error(null, 'Все импортировано');

        global $wpdb;

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

        $limit = self::get_limit();

        $query .= $limit;

        $posts = $wpdb->get_results($query);

        foreach ($posts as $post) {

            $data = [
                'ID'           => $post->id,
                'post_title'   => sanitize_text_field($post->name),
                'post_content' => $post->text,
                'post_date'    => self::check_date($post->date),
                'post_name'    => $post->id,
                'post_author'  => 1,
                'post_status'  => ($post->active == 1) ? "publish" : "pending"
            ];

            // $wpdb->insert используется вместо wp_insert_post,
            // так как работа с базой напрямую намного быстрее
            // в среднем, через wp_insert_post одна операция - 9 мин. (без категорий)
            // в среднем, через wp_insert_post одна операция - 10 мин. (с категориями)
            // в среднем, через $wpdb->insert одна операция - 30 сек. (без категорий)
            // в среднем, через $wpdb->insert одна операция - 2 мин. (с категориями)
            $inserted = $wpdb->insert(
                $wpdb->posts,
                $data
            );

            if (!$inserted)
                self::show_error(
                    null,
                    "
                    <h2>Ошибка импорта</h2>
                    <p>ID поста: {$post->id}</p>
                    "
                );

            $cat_id = get_cat_ID('Новости СМИ');

            if ($post->parent_id == '115')
                $cat_id = get_cat_ID('Новости');

            $cat = wp_set_post_categories($wpdb->insert_id, $cat_id);

            if (is_wp_error($cat))
                self::show_error($cat);
        }

        $updated = self::set_status(++$status);

        if (!$updated)
            wp_die($status);
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
            OR
                `parent_id` = '118'
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
                ";

                self::show_error($inserted, $message);
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
            } elseif ($post->parent_id == '118') {

                $tax_slug = 'services_catalog_categories';

                $term = get_term_by('name', 'Юридический бизнес', $tax_slug);

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
                    ";

                    self::show_error($child_inserted, $message);
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

    public static function get_books()
    {
        global $wpdb;

        $query = "
            SELECT
                `id`,
                `name`,
                `anons`,
                `url_img`,
                `date`,
                `text`,
                `active`
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '16400'
            ";

        $posts = $wpdb->get_results($query);

        foreach ($posts as $post) {

            $data = [
                'import_id'    => $post->id,
                'post_title'   => sanitize_text_field($post->name),
                'post_content' => $post->text,
                'post_excerpt' => $post->anons,
                'post_date'    => self::check_date($post->date),
                'post_author'  => 1,
                'post_name'    => $post->id,
                'post_status'  => ($post->active == 1) ? "publish" : "pending",
                'post_type'    => 'books'
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                self::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            // self::set_post_thumb($inserted, $post->url_img);
        }
    }

    public static function get_works()
    {
        global $wpdb;

        $query = "
            SELECT
                `id`,
                `name`,
                `url_img`,
                `url`,
                `date`,
                `text`,
                `active`,
                `parent_id`
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '16076'
            OR
                `parent_id` = '15540'
            OR
                `parent_id` = '15011'
            OR
                `parent_id` = '15541'
            OR
                `parent_id` = '15012'
            ";

        $posts = $wpdb->get_results($query);

        foreach ($posts as $post) {

            $meta = [
                'work-url' => $post->url
            ];

            $data = [
                'import_id'    => $post->id,
                'post_title'   => sanitize_text_field($post->name),
                'post_content' => $post->text,
                'post_date'    => self::check_date($post->date),
                'post_author'  => 1,
                'post_name'    => $post->id,
                'post_status'  => ($post->active == 1) ? "publish" : "pending",
                'post_type'    => 'works',
                'meta_input'   => $meta,
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                self::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            // Книги, монографии
            if ($post->parent_id == '15541') {

                $tax_slug = 'works-categories';

                $term = get_term_by('name', 'Публикации Айвар Людмилы Константиновны', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);

                $tax_slug = 'works-types';

                $term = get_term_by('name', 'Книги, монографии', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);
            }

            if ($post->parent_id == '15012') {

                $tax_slug = 'works-categories';

                $term = get_term_by('name', 'Публикации Трунова Игоря Леонидовича', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);

                $tax_slug = 'works-types';

                $term = get_term_by('name', 'Книги, монографии', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);
            }

            // Научные статьи
            if ($post->parent_id == '15540') {

                $tax_slug = 'works-categories';

                $term = get_term_by('name', 'Публикации Айвар Людмилы Константиновны', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);
            }

            if ($post->parent_id == '15011') {

                $tax_slug = 'works-categories';

                $term = get_term_by('name', 'Публикации Трунова Игоря Леонидовича', $tax_slug);

                $term_id = $term->term_id;

                wp_set_post_terms($inserted, [$term_id], $tax_slug);
            }

            // self::set_post_thumb($inserted, $post->url_img);
        }
    }

    public static function get_partners()
    {
        global $wpdb;

        $query = "
            SELECT
                `id`,
                `name`,
                `url_img`,
                `url`,
                `date`,
                `active`
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '15962'
            ";

        $posts = $wpdb->get_results($query);

        foreach ($posts as $post) {

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
                'post_type'    => 'partners',
                'meta_input'   => $meta,
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                self::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            // self::set_post_thumb($inserted, $post->url_img);
        }
    }

    // Реквизиты судов
    public static function get_court()
    {
        global $wpdb;

        $query = "
            SELECT
                `id`,
                `name`,
                `text`,
                `url_img`,
                `url`,
                `date`,
                `active`
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '116'
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
                'post_type'    => 'court'
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                self::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            // self::set_post_thumb($inserted, $post->url_img);
        }
    }

    // Для адвоката
    public static function get_for_lawyer()
    {
        global $wpdb;

        $query = "
            SELECT
                `id`,
                `name`,
                `text`,
                `url_img`,
                `url`,
                `date`,
                `active`
            FROM
                `aleksnet_document`
            WHERE
                `parent_id` = '117'
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
                'post_type'    => 'for-lawyer'
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                self::show_error($inserted, "<p>ID поста: {$post->id}</p>");
            }

            // self::set_post_thumb($inserted, $post->url_img);
        }
    }

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
