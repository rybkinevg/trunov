<?php

## Фильтр элементо втаксономии для метабокса таксономий в админке.
## Позволяет удобно фильтровать (искать) элементы таксономии по назанию, когда их очень много
add_action('admin_print_scripts', 'my_admin_term_filter', 99);
function my_admin_term_filter()
{
    $screen = get_current_screen();

    if ('post' !== $screen->base) return; // только для страницы редактирвоания любой записи
?>
    <script>
        jQuery(document).ready(function($) {
            var $categoryDivs = $('.categorydiv');

            $categoryDivs.prepend('<input type="search" class="fc-search-field" placeholder="Поиск..." style="width:100%" />');

            $categoryDivs.on('keyup search', '.fc-search-field', function(event) {

                var searchTerm = event.target.value,
                    $listItems = $(this).parent().find('.categorychecklist li');

                if ($.trim(searchTerm)) {
                    $listItems.hide().filter(function() {
                        return $(this).text().toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
                    }).show();
                } else {
                    $listItems.show();
                }
            });
        });
    </script>
<?php
}


## Удаление табов "Все рубрики" и "Часто используемые" из метабоксов рубрик (таксономий) на странице редактирования записи.
add_action('admin_print_footer_scripts', 'hide_tax_metabox_tabs_admin_styles', 99);
function hide_tax_metabox_tabs_admin_styles()
{
    $cs = get_current_screen();
    if ($cs->base !== 'post' || empty($cs->post_type)) return; // не страница редактирования записи
?>
    <style>
        .postbox .fc-search-field {
            border-radius: 0;
            border: solid 1px #ddd;
            background-color: #fdfdfd;
        }

        .postbox div.tabs-panel {
            margin-top: 6px;
        }

        .category-tabs {
            display: none;
        }
    </style>
<?php
}
