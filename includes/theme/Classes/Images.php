<?php

namespace rybkinevg\trunov;

class Images
{
    public static function set_thumbs($type)
    {
        global $wpdb;

        // Всего на Новостях (parent_id = 115) - 464 поста с фотками, прогоняя через парсер выходит - 270, где-то битые ссылки, где-то неправильные

        $query = "SELECT `url_img`, `id`, `name` FROM `aleksnet_document` WHERE `parent_id` = '{$type}' AND `url_img` <> '' AND `url_img` IS NOT NULL";

        $posts = $wpdb->get_results($query);

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        foreach ($posts as $post) {

            $post_id = $post->id;

            $desc = $post->name;

            $path = parse_url($post->url_img, PHP_URL_PATH);

            $result = explode("/", trim($path, "/"));

            $url = "http://trunov.com/{$result[1]}/{$result[2]}/d_{$result[3]}";

            $thumb_id = media_sideload_image($url, $post_id, $desc, 'id');

            if (!is_wp_error($thumb_id)) {

                set_post_thumbnail($post_id, $thumb_id);
            }
        }
    }

    function get_old_post_imgs()
    {
        global $wpdb;

        require(get_template_directory() . "/trunov-includes/vendor/simple_html_dom.php");

        $_posts = $wpdb->get_results("SELECT `post_content`, `ID`, `post_title` FROM {$wpdb->posts} WHERE `post_content` LIKE '%<img%' AND `post_type` = 'post' AND `post_status` = 'publish'");

        foreach ($_posts as $_post) {

            $html = new \simple_html_dom();

            $html->load($_post->post_content);

            $imgs = $html->find('img');

            foreach ($imgs as $img) {

                foreach ($img->attr as $key => $value) {

                    if ($key != 'src' && $key != 'alt') {

                        unset($img->attr[$key]);
                    }

                    if (mb_substr($img->attr['src'], 0, 3) == '../' || mb_substr($img->attr['src'], 0, 3) == '..\\') {

                        $img->attr['src'] = "http://trunov.com" . mb_substr($img->attr['src'], 2);
                    }

                    if (isset($img->attr['alt'])) {

                        $img->attr['alt'] = "Фото";
                    }
                }
            }

            $new_post_content = $html->save();

            $wpdb->update(
                $wpdb->posts,
                ['post_content' => $new_post_content],
                ['ID' => $_post->ID]
            );
        }
    }
}
