<?php

use rybkinevg\trunov\{Cats, Tags, Posts, Images, Taxonomy, Topics};

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
                            <button class="button button-primary" type="submit">Импортировать в базу</button>
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
                            <button class="button button-primary" type="submit">Импортировать в базу</button>
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
                            <button class="button button-primary" type="submit">Импортировать в базу</button>
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
