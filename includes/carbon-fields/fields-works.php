<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Дополнительные поля')
    ->show_on_post_type('works')
    ->add_fields(
        [
            Field::make('text', 'works-url', 'Ссылка на публикацию')
                ->set_help_text('Укажите ссылку на публикацию')
        ]
    );
