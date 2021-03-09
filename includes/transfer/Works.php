<?php

namespace rybkinevg\trunov;

class Works extends Transfer
{
    static $post_type = 'works';

    protected static function get(): array
    {
        global $wpdb;

        $query = "
            SELECT
                *
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
            ORDER BY
                `id`
        ";

        $posts = $wpdb->get_results($query);

        return $posts;
    }

    public static function set()
    {
        $posts = self::get();

        foreach ($posts as $post) {

            $args = [
                'post_type'    => self::$post_type,
                'meta_input'   => [
                    'url' => $post->url
                ]
            ];

            $data = parent::generate_args($post, $args);

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                parent::show_error($inserted, "<p>ID поста: {$post->id}</p>");
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
        }
    }

    public static function set_thumbs()
    {
        $posts = self::get();

        foreach ($posts as $post) {

            parent::set_post_thumb($post->id, $post->url_img);
        }
    }
}
