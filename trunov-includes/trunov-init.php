<?php

/**
 * Последовательность:
 *
 * 1. Импорт всех постов
 * 2. Создание таксономий (СМИ, Громкие дела, Архив)
 * 3. Создание категорий (Новости, Новости СМИ)
 * 4. Создание меток (Анонс, ...)
 * 5. Проставить таксономии постам
 * 6. Проставить категории постам
 * 7. Проставить метки постам
 */

/**
 * Примерное время импорта:
 * 1000 постов за 1 минуту
 * 18179 постов за 18 минут
 * 18179 постов за 20 минут
 * 18179 постов за 16 минут
 */

require(dirname(__FILE__) . '/vendor/taxonomy-thumb.php');

require(dirname(__FILE__) . '/inc/Classes/Cats.php');
require(dirname(__FILE__) . '/inc/Classes/Tags.php');
require(dirname(__FILE__) . '/inc/Classes/Posts.php');
require(dirname(__FILE__) . '/inc/Classes/Images.php');
require(dirname(__FILE__) . '/inc/Classes/Taxonomy.php');
require(dirname(__FILE__) . '/inc/Classes/Topics.php');

require(dirname(__FILE__) . '/inc/post-type-post.php');
require(dirname(__FILE__) . '/inc/post-type-lawyers.php');
require(dirname(__FILE__) . '/inc/post-type-services-catalog.php');
require(dirname(__FILE__) . '/inc/settings-page.php');
require(dirname(__FILE__) . '/inc/disable-gutenberg.php');

use rybkinevg\trunov\{Cats, Tags, Posts, Images, Taxonomy, Topics};

function set_tags()
{
    $types = [
        'Фотоархив'  => 'photoarchive',
        'Видеоархив' => 'videoarchive',
        'Анонс'      => 'anons'
    ];

    $tags = [
        'Фотоархив' => [
            'ID'       => 399,
            'Название' => 'Фотоархив',
            'Описание' => 'Метка поста, в котором есть фотографии',
            'Слаг'     => 'fotoarchive'
        ],
        'Видеоархив' => [
            'ID'       => 398,
            'Название' => 'Видеоархив',
            'Описание' => 'Метка поста, в котором есть видеозаписи',
            'Слаг'     => 'videoarchive'
        ],
        'Анонс' => [
            'ID'       => 628,
            'Название' => 'Анонс',
            'Описание' => 'Метка поста, который что-либо анонсирует',
            'Слаг'     => 'anons'
        ]
    ];

    foreach ($types as $key => $value) {

        $posts = Posts::get_for_tags($value);

        $tag = Tags::create_tag($tags[$key]['ID'], $tags[$key]['Название'], $tags[$key]['Описание'], $tags[$key]['Слаг']);

        Posts::set_post_tags($posts, [$tag]);
    }
}

// function generate_smi_tags()
// {
//     global $wpdb;

//     require_once(ABSPATH . 'wp-admin/includes/media.php');
//     require_once(ABSPATH . 'wp-admin/includes/file.php');
//     require_once(ABSPATH . 'wp-admin/includes/image.php');

//     $query = "SELECT `id`, `name` FROM `aleksnet_topic_document` WHERE `parent_id` = '55'";

//     $tags = $wpdb->get_results($query);

//     foreach ($tags as $tag) {

//         $tax_id = Taxonomy::create_tax($tag->name, 'smi', $tag->name, sanitize_title($tag->name));

//         $url = "http://trunov.com/img/aleksnet_topic_document/t_{$tag->id}.jpg";

//         $thumb_id = media_sideload_image($url, 0, "Миниатюра метки СМИ: {$tag->name}", 'id');

//         if (!is_wp_error($thumb_id)) {

//             update_term_meta($tax_id, '_thumbnail_id', $thumb_id);
//         }
//     }
// }

// generate_smi_tags();

function set_taxes()
{
    $topics = Topics::get();

    foreach ($topics as $topic) {

        $tax = new Taxonomy($topic);
    }
}

// set_taxes();

// init();

// set_tags();

function show_profile_fields($user)
{
?>
    <h3>Дополнительная информация</h3>
    <table class="form-table">
        <tr>
            <th><label for="branch">Отделение</label></th>
            <td>23123123</td>
        </tr>
        <tr>
            <th><label for="position">Должность (выпадающий список)</label></th>
            <td>sadsad</td>
        </tr>
    </table>
<?php }

add_action('show_user_profile', 'show_profile_fields');
add_action('edit_user_profile', 'show_profile_fields');

// Заполняет таксономии данными
function trunov_taxonomies()
{
    Topics::fill();
    Posts::set_post_tax();
}

// trunov_taxonomies();
