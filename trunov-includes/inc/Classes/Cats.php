<?php

namespace rybkinevg\trunov;

class Cats
{
    /**
     * Создание категории, если категория существует, то просто берёт существующую
     *
     * @param  int    $ID        ID категории, по умолчанию, равна нулю, то есть создаётся новая категория
     * @param  string $catname   Название категории
     * @param  string $catdesc   Описание категории
     * @param  string $catslug   Слаг категории
     *
     * @return int               ID созданной/существующей категории
     */
    public static function create_cat(int $ID = 0, string $catname, string $catdesc, string $catslug): int
    {
        $catid = get_cat_ID($catname);

        if ($catid == 0) {

            require_once(ABSPATH . '/wp-admin/includes/taxonomy.php');

            $cat = [
                'cat_ID'               => $ID,
                'cat_name'             => $catname,
                'category_description' => $catdesc,
                'category_nicename'    => $catslug,
                'taxonomy'             => 'category'
            ];

            $catid = wp_insert_category($cat);
        }

        return $catid;
    }
}
