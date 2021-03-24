<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Дополнительные поля')
    ->show_on_post_type('certificates')
    ->add_fields(
        [
            Field::make('image', 'certificates-img-url', 'Скан свидетельства')
                ->set_value_type('url')
                ->set_help_text('Выберите скан свидетельства из медиатеки или загрузите новый')
        ]
    );
