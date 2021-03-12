<?php

namespace rybkinevg\trunov;

class Certificates extends Transfer
{
    static $post_type = 'certificates';

    protected static function get(): array
    {
        $posts = [
            [
                'ID'      => '19790',
                'date'    => '2018-01-17',
                'img_url' => 'http://trunov.com/img/aleksnet_items/b_540.jpg',
                'name'    => 'Свидетельство о постановке на учет российской организации в налоговом органе по месту нахождения на территории Российской Федерации'
            ],
            [
                'ID'      => '19789',
                'date'    => '2018-01-17',
                'img_url' => 'http://trunov.com/img/aleksnet_items/b_541.jpg',
                'name'    => 'Свидетельство о государственной регистрации юридического лица'
            ],
            [
                'ID'      => '19791',
                'date'    => '2018-01-17',
                'img_url' => 'http://trunov.com/img/aleksnet_items/b_537.jpg',
                'name'    => 'Свидетельство о государственной регистрации некоммерческой организации'
            ]
        ];

        return $posts;
    }

    public static function set()
    {
        $status = parent::get_status(self::$post_type);

        if ($status)
            parent::show_error(null, 'Данные типа записи "' . self::$post_type . '" уже импортированы');

        $posts = self::get();

        foreach ($posts as $post) {

            $data = [
                'post_type'    => self::$post_type,
                'import_id' => $post['ID'],
                'post_title' => $post['name'],
                'post_content' => '',
                'post_date' => parent::check_date($post['date']),
                'post_name' => $post['ID'],
                'post_author' => 1,
                'post_status' => 'publish'
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                parent::show_error($inserted, "<p>ID поста: {$post['ID']}</p>");
            }
        }

        $updated = parent::set_status(self::$post_type, 'Выполнено');

        if (!$updated)
            parent::show_error(null, var_dump($status));
    }

    public static function set_thumbs()
    {
        $posts = self::get();

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        foreach ($posts as $post) {

            $thumb_src = media_sideload_image($post['img_url'], $post['ID'], $post['name'], 'src');

            if (is_wp_error($thumb_src)) {

                $message = "
                    <p>ID поста: {$post['ID']}</p>
                    <p>Переданная ссылка: {$post['img_url']}</p>
                ";

                parent::show_error($thumb_src, $message);
            }

            carbon_set_post_meta($post['ID'], 'certificates-img-url', $thumb_src);
        }
    }

    public static function actions()
    {
        add_action('admin_action_' . self::$post_type . '_get', function () {

            self::set();

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
            'title' => 'Регистрационные свидетельства',
            'status' => parent::get_status(self::$post_type),
            'forms' => [
                [
                    'title'  => 'Импорт',
                    'desc'   => 'Импорт регистрационных свидетельств',
                    'btn'    => 'Импортировать',
                    'action' => self::$post_type . '_get'
                ],
                [
                    'title'  => 'Сканы',
                    'desc'   => 'Скачать и установить сканы свидетельств',
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
