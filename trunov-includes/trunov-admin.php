<?php

function get_old_news($cat = '115')
{
    global $wpdb;

    $old_news = $wpdb->get_results("SELECT `id`, `name`, `text`, `date`, `active` FROM `aleksnet_document` WHERE `parent_id` = '{$cat}'");

    foreach ($old_news as $old_news_item) {

        $data = [
            'ID' => $old_news_item->id,
            'post_title' => $old_news_item->name,
            'post_content' => $old_news_item->text,
            'post_date' => $old_news_item->date,
            'post_name' => $old_news_item->id,
            'post_author' => 1,
            'post_status' => ($old_news_item->active == 1) ? "publish" : "pending"
        ];

        $wpdb->insert(
            $wpdb->posts,
            $data
        );

        wp_set_post_categories($wpdb->insert_id, get_cat_ID('Новости'));
    }
}

function get_old_news_thumbs($cat = '115')
{
    global $wpdb;

    $_old_posts = $wpdb->get_results("SELECT `url_img`, `id`, `name` FROM `aleksnet_document` WHERE `parent_id` = '{$cat}' AND `active` = '1' AND `url_img` <> '' AND `url_img` IS NOT NULL");

    foreach ($_old_posts as $_post) {

        $post_id = $_post->id;

        $desc = $_post->name;

        $path = parse_url($_post->url_img, PHP_URL_PATH);

        $result = explode("/", trim($path, "/"));

        $url = "http://trunov.com/{$result[1]}/{$result[2]}/d_{$result[3]}";

        $thumb_id = media_sideload_image($url, $post_id, $desc, 'id');

        if (is_wp_error($thumb_id)) {

            //echo $thumb_img->get_error_message();
            // сделать присваивание пустой картинки
        } else {

            set_post_thumbnail($post_id, $thumb_id);
        }
    }
}

require(get_template_directory() . "/trunov-includes/vendor/simple_html_dom.php");

function get_old_post_imgs()
{
    global $wpdb;

    $_posts = $wpdb->get_results("SELECT `post_content`, `ID`, `post_title` FROM {$wpdb->posts} WHERE `post_content` LIKE '%<img%' AND `post_type` = 'post' AND `post_status` = 'publish'");

    foreach ($_posts as $_post) {

        $html = new simple_html_dom();

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

function get_old_post_links()
{
    global $wpdb;

    $_posts = $wpdb->get_results("SELECT `post_content`, `ID` FROM `{$wpdb->posts}` WHERE `post_content` LIKE '%content.php?%' AND `post_type` = 'post'");

    foreach ($_posts as $_post) {

        $html = new simple_html_dom();

        $html->load($_post->post_content);

        $links = $html->find('a[href*=content.php?]');

        foreach ($links as $link) {

            $link_explode = explode("=", $link->attr['href']);
            $content_id = array_pop($link_explode);

            $content_table = $wpdb->get_results("SELECT `title` FROM `content` WHERE `id` = '{$content_id}' LIMIT 1");

            if (empty($content_table)) {

                $link->attr['href'] = "#content-empty-{$content_id}";

                continue;
            }

            $post_title = $content_table[0]->title;

            $posts_table = $wpdb->get_results("SELECT `id` FROM `aleksnet_document` WHERE `name` LIKE '{$post_title}' LIMIT 1");

            if (empty($posts_table)) {

                $link->attr['href'] = "#posts-empty-{$content_id}";

                continue;
            }

            $link->attr['href'] = "/news/{$posts_table[0]->id}";
        }

        $new_post_content = $html->save();

        $wpdb->update(
            $wpdb->posts,
            ['post_content' => $new_post_content],
            ['ID' => $_post->ID]
        );
    }
}

add_action('wp_ajax_add_news', 'action_add_news');

function action_add_news()
{
    get_old_news();

    wp_send_json_success('!!!');
}

add_action('wp_ajax_add_thumbs', 'action_add_thumbs');

function action_add_thumbs()
{
    $cat = '115';

    global $wpdb;

    $_old_posts = $wpdb->get_results("SELECT `url_img`, `id`, `name` FROM `aleksnet_document` WHERE `parent_id` = '{$cat}' AND `url_img` <> '' AND `url_img` IS NOT NULL");

    foreach ($_old_posts as $_post) {

        $post_id = $_post->id;

        $desc = $_post->name;

        $path = parse_url($_post->url_img, PHP_URL_PATH);

        $result = explode("/", trim($path, "/"));

        $url = "http://trunov.com/{$result[1]}/{$result[2]}/d_{$result[3]}";

        $thumb_id = media_sideload_image($url, $post_id, $desc, 'id');

        if (is_wp_error($thumb_id)) {

            //echo $thumb_img->get_error_message();
            // сделать присваивание пустой картинки
        } else {

            set_post_thumbnail($post_id, $thumb_id);
        }
    }

    wp_send_json_success('Миниатюры скачаны');
}

add_action('wp_ajax_repair_link', 'action_repair_link');

function action_repair_link()
{
    get_old_post_links();

    wp_send_json_success('!!!');
}

add_action('wp_ajax_repair_imgs', 'action_repair_imgs');

function action_repair_imgs()
{
    global $wpdb;

    $_posts = $wpdb->get_results("SELECT `post_content`, `ID`, `post_title` FROM {$wpdb->posts} WHERE `post_content` LIKE '%<img%' AND `post_type` = 'post'");

    foreach ($_posts as $_post) {

        $html = new simple_html_dom();

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

    wp_send_json_success('Изображения оптимизированы');
}

function add_my_setting()
{
?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <?php
        // settings_errors() не срабатывает автоматом на страницах отличных от опций
        if (get_current_screen()->parent_base !== 'options-general')
            settings_errors('название_опции');
        ?>

        <style>
            .container {
                margin-top: 40px;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(3, auto);
                gap: 40px;
            }

            .item {
                display: flex;
                flex-direction: column;
                padding: 25px;
                box-shadow: 0 0 1px #1e1e1e;
            }

            .item h2 {
                flex: 1 1 auto;
                margin-top: 0;
            }

            .item p {
                margin-top: 0;
            }
        </style>

        <div class="container">
            <div class="item">
                <h2>Новости</h2>
                <?php

                if (get_cat_ID('Новости') == 0) {

                    $cat_news = [
                        'cat_name' => 'Новости',
                        'category_description' => 'Описание',
                        'category_nicename' => 'news'
                    ];

                    wp_insert_category($cat_news);
                }

                global $wpdb;

                $_pc = $wpdb->get_results("SELECT COUNT(*) as total FROM `wp_posts` WHERE `post_status` = 'publish' AND `post_type` = 'post' LIMIT 1");

                if ($_pc[0]->total == 0) {

                    echo "<p>Таблица пуста, постов не найдено</p>";
                }

                $posts_count = $wpdb->get_results("SELECT COUNT(*) as total FROM `aleksnet_document` WHERE `parent_id` = '115'");

                echo "<p>Количество постов доступных для импорта: {$posts_count[0]->total}</p>";

                ?>
                <div>
                    <button class="button button-primary" data-action="add_news">Импортировать в базу</button>
                </div>
            </div>
            <div class="item">
                <h2>Миниатюры</h2>
                <?php

                if ($_pc[0]->total != 0) {

                    $thumbs_count = $wpdb->get_results("SELECT COUNT(*) as total FROM `aleksnet_document` WHERE `parent_id` = '115' AND `url_img` <> '' AND `url_img` IS NOT NULL");

                    echo "<p>Количество миниатюр доступных для загрузки и импорта: {$thumbs_count[0]->total}</p>";
                } else {

                    echo "<p>Таблица пуста, постов не найдено</p>";
                }

                ?>
                <div>
                    <button class="button button-primary" data-action="add_thumbs">Загрузить и импортировать в базу</button>
                </div>
            </div>
            <div class="item">
                <h2>Внутренние ссылки</h2>
                <?php

                if ($_pc[0]->total != 0) {

                    $links_count = $wpdb->get_results("SELECT COUNT(*) as total FROM `{$wpdb->posts}` WHERE `post_content` LIKE '%content.php?%' AND `post_type` = 'post'");

                    echo "<p>Количество постов с неоптимизированными ссылками: {$links_count[0]->total}</p>";
                } else {

                    echo "<p>Таблица пуста, постов не найдено</p>";
                }

                ?>
                <div>
                    <button class="button button-primary" data-action="repair_link">Исправить</button>
                </div>
            </div>
            <div class="item">
                <h2>Внутренние изображения</h2>
                <?php

                if ($_pc[0]->total != 0) {

                    $imgs_count = $wpdb->get_results("SELECT COUNT(*) as total FROM {$wpdb->posts} WHERE `post_content` LIKE '%<img src=../%' OR `post_content` LIKE '%<img src=..\%' AND `post_type` = 'post'");

                    echo "<p>Количество постов с неоптимизированными изображениями: {$imgs_count[0]->total}</p>";
                } else {

                    echo "<p>Таблица пуста, постов не найдено</p>";
                }

                ?>
                <div>
                    <button class="button button-primary" data-action="repair_imgs">Исправить</button>
                </div>
            </div>
        </div>

        <form action="options.php" method="POST">
            <?php
            settings_fields("opt_group");     // скрытые защитные поля
            do_settings_sections("opt_page"); // секции с настройками (опциями).
            submit_button();
            ?>
        </form>
    </div>
<?php

}

add_action('admin_menu', function () {

    add_menu_page('Дополнительные настройки сайта', 'Пульт', 'manage_options', 'site-options', 'add_my_setting', '', 4);
});

add_action('admin_print_footer_scripts', function () {

?>

    <script>
        jQuery(".container .item button").on("click", function() {

            jQuery(this).attr('disabled', true);
            jQuery(this).text('Загрузка..');

            const $this = jQuery(this);

            jQuery.ajax({

                type: 'GET',
                url: ajaxurl,
                data: `action=${jQuery(this).data('action')}`,
                success: function(respond, status, jqXHR) {
                    if (respond.success) {

                        $this.removeAttr('disabled');
                        $this.text('Загружено');
                        console.log(respond.data);
                    } else {

                        jQuery(this).after('Ошибка');
                        console.warn(respond.data);
                    }
                },
                error: function(jqXHR, status, errorThrown) {
                    console.log('Ошибка AJAX запроса: ' + status + ', ' + jqXHR + ', ' + errorThrown);
                }
            });
        });
    </script>

<?php

});
