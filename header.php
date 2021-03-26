<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Международная Юридическая фирма «Трунов, Айвар и партнёры»</title>

    <?php wp_head(); ?>
</head>

<body class="body">

    <div class="site-wrapper">

        <header class="header">
            <div class="header__top">
                <div class="container">
                    <a href="#" class="header__brand">
                        <div class="header__logo">
                            <img class="img logo" src="http://trunov.com/images/logo2.png" alt="">
                        </div>
                        <div class="header__about">
                            <h1 class="header__name">
                                «Трунов, Айвар и партнёры»
                            </h1>
                            <p class="header__desc">
                                Международная Юридическая фирма, основаная в 2001 году
                            </p>
                        </div>
                    </a>
                    <div class="header__contacts">
                        <ul>
                            <li class="header__phone">8 499 158 29 17</li>
                            <li class="header__phone">8 499 158 29 17</li>
                            <li class="header__phone">8 499 158 29 17</li>
                        </ul>
                        <div class="header__mail">
                            info@trunov.com
                        </div>
                    </div>
                </div>
            </div>
            <div class="header__bottom">
                <div class="container">
                    <?php get_template_part('template-parts/nav-menu'); ?>
                    <button class="header__search">Поиск</button>
                    <div class="header__search-block">
                        <div class="container">
                            <input type="search" name="seach" id="">
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="main">
            <div class="container">