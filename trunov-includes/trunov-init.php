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

require(dirname(__FILE__) . '/vendor/Kama_Post_Meta_Box.php');
require(dirname(__FILE__) . '/vendor/taxonomy-thumb.php');

require(dirname(__FILE__) . '/inc/meta-box-custom.php');
require(dirname(__FILE__) . '/inc/default-permalinks-structure.php');

require(dirname(__FILE__) . '/inc/Classes/Cats.php');
require(dirname(__FILE__) . '/inc/Classes/Tags.php');
require(dirname(__FILE__) . '/inc/Classes/Posts.php');
require(dirname(__FILE__) . '/inc/Classes/Images.php');
require(dirname(__FILE__) . '/inc/Classes/Taxonomy.php');
require(dirname(__FILE__) . '/inc/Classes/Topics.php');
require(dirname(__FILE__) . '/inc/Classes/Transfer.php');
require(dirname(__FILE__) . '/inc/Classes/Partners.php');

require(dirname(__FILE__) . '/inc/post-type-post.php');
require(dirname(__FILE__) . '/inc/post-type-lawyers.php');
require(dirname(__FILE__) . '/inc/post-type-books.php');
require(dirname(__FILE__) . '/inc/post-type-works.php');
require(dirname(__FILE__) . '/inc/post-type-court.php');
require(dirname(__FILE__) . '/inc/post-type-partners.php');
require(dirname(__FILE__) . '/inc/post-type-media-columns.php');
require(dirname(__FILE__) . '/inc/post-type-for-lawyer.php');
require(dirname(__FILE__) . '/inc/post-type-services-catalog.php');

require(dirname(__FILE__) . '/inc/settings-page.php');
require(dirname(__FILE__) . '/inc/disable-gutenberg.php');

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
