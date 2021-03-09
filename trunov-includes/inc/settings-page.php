<?php

use rybkinevg\trunov\{Cats, Tags, Posts, Images, Partners, Taxonomy, Topics};

add_action('admin_menu', function () {

    add_menu_page('Дополнительные настройки сайта', 'Пульт', 'manage_options', 'site-options', 'add_my_setting', '', 4);
});

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
                gap: 40px;
            }

            .item {
                display: flex;
                flex-direction: column;
                padding: 25px;
                box-shadow: 0 0 1px #1e1e1e;
                background-color: #fff;
            }

            .item form {
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            .item__body {
                flex: 1 1 auto;
                margin: 20px 0;
            }

            .item__btn {
                text-align: right;
            }

            .item h2 {
                margin: 0;
            }

            .item p {
                margin-top: 0;
            }
        </style>

        <?php

        function upFirstLetter($str, $encoding = 'UTF-8')
        {
            $str = mb_strtolower($str);

            return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
                . mb_substr($str, 1, null, $encoding);
        }

        echo upFirstLetter('ПРИВЕТ');

        ?>

        <div class="block">
            <h2>Новости</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_posts" />
                        <h2 class="item__title">Импорт постов</h2>
                        <div class="item__body">
                            <p>Импорт разделен на 3 части, так как очень большой объём данных, после первого импорта нужно импортировать ещё 2 раза.</p>
                            <p>Статус импорта - <?= get_option('trunov_imported_posts', 0); ?> из 3</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="set_taxonomies" />
                        <h2 class="item__title">Заполнение таксономий</h2>
                        <div class="item__body">
                            <p>Заполнение таксономий: СМИ, Услуги, Телеперадачи и прочих, данными</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Заполнить таксономии</button>
                        </div>
                    </form>
                </div>
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="set_post_tax" />
                        <h2 class="item__title">Проставить таксономии постам</h2>
                        <div class="item__body">
                            <p>Привязать таксономии к постам.</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Проставить таксономии</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="block">
            <h2>Адвокаты</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_lawyers" />
                        <h2 class="item__title">Импортировать адвокатов</h2>
                        <div class="item__body">
                            <p>Импортировать адвокатов и юристов, проставить категории и представительства, скачать и привязать миниатюры.</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="block">
            <h2>Услуги</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_services_catalog" />
                        <h2 class="item__title">Импортировать услуги</h2>
                        <div class="item__body">
                            <p>Импортировать все услуги с их дочерними страницами и проставить категории.</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="block">
            <h2>Книги</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_books" />
                        <h2 class="item__title">Импортировать книги</h2>
                        <div class="item__body">
                            <p>Импортировать книги</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="block">
            <h2>Научные и учебно-методические труды</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_works" />
                        <h2 class="item__title">Импортировать труды</h2>
                        <div class="item__body">
                            <p>Импортировать научные и учебно-методические труды, проставить разделы и типы.</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="block">
            <h2>Партнёры</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_partners" />
                        <h2 class="item__title">Импортировать партнёров</h2>
                        <div class="item__body">
                            <p>Импортировать партнёров</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="block">
            <h2>Реквизиты судов</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_court" />
                        <h2 class="item__title">Импортировать реквизиты судов</h2>
                        <div class="item__body">
                            <p>Импортировать реквизиты судов</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="block">
            <h2>Адвокату</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_for_lawyer" />
                        <h2 class="item__title">Импортировать информацию для адвокатов</h2>
                        <div class="item__body">
                            <p>Импортировать информацию для адвокатов</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="block">
            <h2>Колонки СМИ</h2>
            <div class="container">
                <div class="item">
                    <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                        <input type="hidden" name="action" value="get_media_columns" />
                        <h2 class="item__title">Импортировать колонки СМИ</h2>
                        <div class="item__body">
                            <p>Импортировать колонки СМИ</p>
                        </div>
                        <div class="item__btn">
                            <button class="button button-primary" type="submit">Импортировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php

}

/**
 * Посты
 */
add_action('admin_action_get_posts', 'get_posts_admin_action');

function get_posts_admin_action()
{
    Posts::get_posts();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Таксономии
 */
add_action('admin_action_set_taxonomies', 'set_taxonomies_admin_action');

function set_taxonomies_admin_action()
{
    Topics::fill();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Проставить таксономии постам
 */
add_action('admin_action_set_post_tax', 'set_post_tax_admin_action');

function set_post_tax_admin_action()
{
    Posts::set_post_tax();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Импортировать адвокатов
 */
add_action('admin_action_get_lawyers', 'get_lawyers_admin_action');

function get_lawyers_admin_action()
{
    Posts::get_lawyers();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Импортировать адвокатов
 */
add_action('admin_action_get_services_catalog', 'get_services_catalog_admin_action');

function get_services_catalog_admin_action()
{
    Posts::get_services_catalog();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Импортировать книги
 */
add_action('admin_action_get_books', 'get_books_admin_action');

function get_books_admin_action()
{
    Posts::get_books();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Научные и учебно-методические труды
 */
add_action('admin_action_get_works', 'get_works_admin_action');

function get_works_admin_action()
{
    Posts::get_works();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Партнёры
 */
add_action('admin_action_get_partners', 'get_partners_admin_action');

function get_partners_admin_action()
{
    Posts::get_partners();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Реквизиты судов
 */
add_action('admin_action_get_court', 'get_court_admin_action');

function get_court_admin_action()
{
    Posts::get_court();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Реквизиты судов
 */
add_action('admin_action_get_for_lawyer', 'get_for_lawyer_admin_action');

function get_for_lawyer_admin_action()
{
    Posts::get_for_lawyer();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}

/**
 * Колонки СМИ
 */
add_action('admin_action_get_media_columns', 'get_media_columns_admin_action');

function get_media_columns_admin_action()
{
    Posts::get_media_columns();

    wp_redirect($_SERVER['HTTP_REFERER']);

    exit();
}
