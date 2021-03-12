<?php

namespace rybkinevg\trunov;

class Vacancies extends Transfer
{
    static $post_type = 'vacancies';

    protected static function get(): array
    {
        $posts = [
            [
                'ID'      => '22593',
                'date'    => '2019-04-09',
                'name'    => 'Коллегия адвокатов "Трунов, Айвар и партнеры" г. Москвы приглашает адвокатов города Москвы и Московской области',
                'content' => '
                <p><strong>Наша коллегия открыта для вступления новых членов.</strong></p>
                <p><strong>Мы предлагаем:</strong></p>
                <ul>
                    <li>современный, обустроенный, оборудованный офис;</li>
                    <li>возможность использования офисного оборудования, необходимого для исполнения поручений;</li>
                    <li>оборудованные рабочие места;</li>
                    <li>комнаты для переговоров;</li>
                    <li>бухгалтерское обслуживание адвокатской деятельности;</li>
                    <li>секретарь коллегии;</li>
                    <li>стабильный размер членских взносов.</li>
                </ul>
                <p>Ежемесячный взнос на содержание коллеги составляет 10000 рублей .</p>
                <p>Для адвокатов, недавно получивших статус, имеется возможность обмена опытом.</p>
                <p>Главными критериями приема в коллегию являются: честность, порядочность и профессионализм.</p>
                <p>Если вы хотите стать частью успешно работающей, динамично развивающейся, дружной команды профессионалов, мы готовы предложить все вышеуказанные условия, способствующие вашему профессиональному и карьерному росту.</p>
                <p>Плата за выдачу ордера отсутствует.</p>
                <p><strong>Все интересующие вопросы Вы можете уточнить по телефонам:</strong></p>
                <ul>
                    <li>+7(499)158-29-17</li>
                    <li>+7(499)158-85-81</li>
                    <li>+7(499)158-65-66</li>
                </ul>'
            ],
            [
                'ID'      => '22594',
                'date'    => '2019-04-09',
                'name'    => 'В Коллегии адвокатов "Трунов, Айвар и партнеры" г. Москвы открыты вакансии помощника и стажёра адвоката',
                'content' => '
                <p><strong>Требования:</strong></p>
                <ul>
                    <li>рассматриваются студенты последних курсов, выпускники высших учебных заведений обучающихся по специальности юриспруденция.</li>
                </ul>
                <p><strong>Обязанности:</strong></p>
                <ul>
                    <li>работа в области юриспруденции</li>
                    <li>получение опыта работы в адвокатуре с последующим получением статуса адвоката</li>
                    <li>полный рабочий день</li>
                </ul>
                <p><strong>Условия:</strong></p>
                <ul>
                    <li>резюме по электронной почте, предварительное собеседование</li>
                    <li>устройство по результату собеседования</li>
                    <li>связь по адресу и телефонам указанным на сайте</li>
                </ul>
                '
            ]
        ];

        return $posts;
    }

    public static function set()
    {
        $status = parent::get_status(self::$post_type);

        if ($status)
            parent::show_error(null, 'Данные типа записи "' . self::$post_type . '" уже импортированы');

        $posts = self::get();

        foreach ($posts as $post) {

            $data = [
                'post_type'    => self::$post_type,
                'import_id' => $post['ID'],
                'post_title' => $post['name'],
                'post_content' => $post['content'],
                'post_date' => parent::check_date($post['date']),
                'post_name' => $post['ID'],
                'post_author' => 1,
                'post_status' => 'publish'
            ];

            $inserted = wp_insert_post($data, true);

            if (is_wp_error($inserted)) {

                parent::show_error($inserted, "<p>ID поста: {$post['ID']}</p>");
            }
        }

        $updated = parent::set_status(self::$post_type, 'Выполнено');

        if (!$updated)
            parent::show_error(null, var_dump($status));
    }

    public static function actions()
    {
        add_action('admin_action_' . self::$post_type . '_get', function () {

            self::set();

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });

        add_action('admin_action_' . self::$post_type . '_delete', function () {

            parent::delete(self::get(), self::$post_type);

            wp_redirect($_SERVER['HTTP_REFERER']);

            exit();
        });
    }

    public static function page_block()
    {
        $data = [
            'title' => 'Вакансии',
            'status' => parent::get_status(self::$post_type),
            'forms' => [
                [
                    'title'  => 'Импорт',
                    'desc'   => 'Импорт вакансий',
                    'btn'    => 'Импортировать',
                    'action' => self::$post_type . '_get'
                ],
                [
                    'title'  => 'Очистка',
                    'desc'   => 'Удаление записей, а так же привязки к таксономиям, комментариям и мета-полям',
                    'btn'    => 'Удалить',
                    'action' => self::$post_type . '_delete'
                ]
            ]
        ];

        return $data;
    }
}
