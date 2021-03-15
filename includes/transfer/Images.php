<?php

namespace rybkinevg\trunov;

class Images extends Transfer
{

    protected static function get()
    {
        global $wpdb;

        $query = "
            SELECT
                `post_content`,
                `ID`,
                `post_title`
            FROM
                {$wpdb->posts}
            WHERE
                `post_content`
            LIKE
                '%<img%'
        ";

        $posts = $wpdb->get_results($query);

        return $posts;
    }

    public static function set()
    {
        require(get_template_directory() . '/includes/vendor/simple_html_dom.php');

        $posts = self::get();

        global $wpdb;

        foreach ($posts as $post) {

            $html = new \simple_html_dom();

            $html->load($post->post_content);

            $imgs = $html->find('img');

            foreach ($imgs as $img) {

                foreach ($img->attr as $key => $value) {

                    if ($key == 'src') {

                        $img->attr['src'] = self::download_image($img->attr['src'], $post->ID, $post->post_title);
                    } elseif ($key == 'alt') {
                        $img->attr['alt'] = "";
                    } else {
                        unset($img->attr[$key]);
                    }
                }
            }

            $new_content = $html->save();

            $wpdb->update(
                $wpdb->posts,
                ['post_content' => $new_content],
                ['ID' => $post->ID]
            );
        }
    }

    protected static function download_image($url, $post_id, $post_title)
    {
        $url = str_replace('\\', '/', $url);

        $url_to_arr = explode('/', $url);

        if ($url_to_arr[0] == '..' || $url_to_arr[0] == '.' || $url_to_arr[0] == '' || $url_to_arr[0] == 'trunov.com') {

            $url_to_arr[0] = 'http://trunov.com';
        }

        $url = implode('/', $url_to_arr);

        $thumb_src = media_sideload_image($url, $post_id, $post_title, 'src');

        if (is_wp_error($thumb_src)) {

            $url = 'http://trunov.com/' . $url;

            $thumb_src = media_sideload_image($url, $post_id, $post_title, 'src');

            if (is_wp_error($thumb_src)) {

                $data = get_option('trunov_imported_images', '');

                if (empty($data)) {

                    $data = $url;
                } else {

                    if (in_array($url, $data))
                        return;

                    array_push($data, $url);
                }

                update_option('trunov_imported_images', $data);
            }
        }

        return (!is_wp_error($thumb_src)) ? $thumb_src : $url;
    }
}
