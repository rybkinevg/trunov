<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', 'Дополнительные поля')
    ->show_on_post_type('partners')
    ->add_fields(
        [
            Field::make('text', 'partners_url', 'Ссылка на партнёра')
                ->set_help_text('Укажите ссылку на партнёра')
        ]
    );
