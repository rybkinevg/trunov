<?php

namespace rybkinevg\trunov;

class Transfer
{
    # HELPERS

    protected static function show_error($obj = null, $message = '')
    {
        if (!is_null($obj))
            $message .= "<p>Текст ошибки: {$obj->get_error_message()}</p>";

        $args = [
            'back_link' => true
        ];

        wp_die($message, 'Ошибка!', $args);
    }

    protected static function check_date($date)
    {
        if ($date == '0000-00-00') {

            return current_time('Y-m-d H:i:s');
        }

        return $date . ' ' . current_time('H:i:s');
    }

    protected static function check_title($str, $encoding = 'UTF-8')
    {
        if ($str === mb_strtoupper($str)) {

            $str = sanitize_text_field(mb_strtolower($str));

            return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) . mb_substr($str, 1, null, $encoding);
        }

        return sanitize_text_field($str);
    }

    # INSERT FUNCS

    protected static function generate_args($post, array $args): array
    {
        $default = [
            'import_id'    => $post->id,
            'post_title'   => self::check_title($post->name),
            'post_content' => $post->text, // null
            'post_date'    => self::check_date($post->date),
            'post_name'    => $post->id, // опт. не только ID
            'post_author'  => 1,
            'post_status'  => ($post->active == 1) ? "publish" : "pending",
            'post_type'    => 'post',
            'post_excerpt' => '',
            'meta_input'   => '',
            'post_parent'  => 0,
        ];

        $data = wp_parse_args($args, $default);

        return $data;
    }

    protected static function set_post_thumb($post_id, $thumb_url, $type = 'post', $prefix = 'd_')
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $path = parse_url($thumb_url, PHP_URL_PATH);

        $result = explode("/", trim($path, "/"));

        $url = "http://trunov.com/{$result[1]}/{$result[2]}/d_{$result[3]}";

        $thumb_id = media_sideload_image($url, $post_id, null, 'id');

        if (is_wp_error($thumb_id)) {

            $try_this_url = "http://trunov.com/{$result[1]}/aleksnet_document/{$prefix}{$result[3]}";

            $try_thumb_id = media_sideload_image($try_this_url, $post_id, null, 'id');

            if (is_wp_error($try_thumb_id)) {

                $message = "
                    <p>ID поста: {$post_id}</p>
                    <p>Переданная ссылка: {$thumb_url}</p>
                    <p>Конвертированная ссылка: {$url}</p>
                    <p>Повторно конвертированная ссылка: {$try_this_url}</p>
                ";

                self::show_error($try_this_url, $message);
            }

            $thumb_id = $try_thumb_id;
        }

        set_post_thumbnail($post_id, $thumb_id);
    }

    # SETTINGS PAGE

    public static function init()
    {
        add_action('admin_menu', function () {

            add_menu_page(
                'Опции переноса сайта',
                'Перенос сайта',
                'manage_options',
                'transfer-options',
                [__CLASS__, 'generate_page'],
                '',
                4
            );
        });

        Lawyers::actions();
        Services::actions();
        Posts::actions();
    }

    public static function generate_page()
    {
        include(dirname(__FILE__) . '/transfer-page.php');
    }
}
