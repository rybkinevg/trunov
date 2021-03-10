<?php

use rybkinevg\trunov\Posts;

?>
<div class="wrap">

    <h2><?php echo get_admin_page_title() ?></h2>

    <style>
        .container {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
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

        .item__btn {
            text-align: right;
        }

        .item h2 {
            margin-top: 0;
        }
    </style>

    <div class="block">
        <h2>Адвокаты</h2>
        <div class="container">
            <div class="item">
                <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                    <input type="hidden" name="action" value="lawyers_get" />
                    <h3 class="item__title">Импорт адвокатов</h3>
                    <div class="item__btn">
                        <button class="button button-primary" type="submit">Импортировать</button>
                    </div>
                </form>
            </div>
            <div class="item">
                <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                    <input type="hidden" name="action" value="lawyers_set_thumbs" />
                    <h3 class="item__title">Скачать и установить миниатюры</h3>
                    <div class="item__btn">
                        <button class="button button-primary" type="submit">Установить</button>
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
                    <input type="hidden" name="action" value="services_get" />
                    <h3 class="item__title">Импорт услуг и их дочерних страниц</h3>
                    <div class="item__btn">
                        <button class="button button-primary" type="submit">Импортировать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="block">
        <h2>Новости</h2>
        <div class="container">
            <div class="item">
                <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                    <input type="hidden" name="action" value="news_get" />
                    <h3 class="item__title">Импорт новостей</h3>
                    <div class="item__btn">
                        <button class="button button-primary" type="submit">Импортировать</button>
                    </div>
                </form>
            </div>
            <div class="item">
                <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                    <input type="hidden" name="action" value="news_set_taxes" />
                    <h3 class="item__title">Заполнение таксономий</h3>
                    <div class="item__btn">
                        <button class="button button-primary" type="submit">Заполнить</button>
                    </div>
                </form>
            </div>
            <div class="item">
                <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                    <input type="hidden" name="action" value="news_set_post_tax" />
                    <h3 class="item__title">Привязать таксономии к постам</h3>
                    <div class="item__btn">
                        <button class="button button-primary" type="submit">Привязать</button>
                    </div>
                </form>
            </div>
            <div class="item">
                <form method="POST" action="<?php echo admin_url('admin.php'); ?>">
                    <input type="hidden" name="action" value="news_set_thumbs" />
                    <h3 class="item__title">Скачать и установить миниатюры</h3>
                    <div class="item__btn">
                        <button class="button button-primary" type="submit">Установить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>