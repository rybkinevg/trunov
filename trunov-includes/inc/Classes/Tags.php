<?php

namespace rybkinevg\trunov;

class Tags
{
    /**
     * Создание метки, если категория существует, то просто берёт существующую
     *
     * @param int    $ID       ID метки, по умолчанию, равна нулю, то есть создаётся новая категория
     * @param string $tagname   Название метки
     * @param string $tagdesc   Описание метки
     * @param string $tagslug   Слаг метки
     * @param string $type      Тип таксономии, по умолчанию 'post-tag'
     */
    public static function create_tag(int $ID = 0, string $tagname, string $tagdesc, string $tagslug, string $type = 'post_tag'): int
    {
        $tag = get_term_by('slug', $tagslug, $type);

        $tag_id = isset($tag->term_id) ? $tag->term_id : null;

        if (is_null($tag_id)) {

            require_once(ABSPATH . '/wp-admin/includes/taxonomy.php');

            $tag = [
                'cat_ID'               => $ID,
                'cat_name'             => $tagname,
                'category_description' => $tagdesc,
                'category_nicename'    => $tagslug,
                'taxonomy'             => $type
            ];

            $tag_id = wp_insert_category($tag);
        }

        return (int) $tag_id;
    }
}
