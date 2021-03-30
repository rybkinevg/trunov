jQuery(document).ready(function ($) {

    $('.main-news .slider').slick({
        appendArrows: $('.main-news .slider__arrows'),
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 700,
                settings: {
                    arrows: false,
                    dots: true
                }
            }
        ]
    });

    $('.news .slider').slick({
        appendArrows: $('.news .slider__arrows'),
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1300,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 900,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 700,
                settings: 'unslick'
            },
        ]
    });

    $('button.header__search').on('click', function () {
        $(this).next().toggleClass('open');
    })

    // AJAX loadmore

    $('button.show-more__btn').click(function () {

        // Нажатая кнопка
        const btn = $(this);

        // Блок postview
        const postviewBlock = btn.parent().parent().find('.postview');

        if (btn.data('ajax') == true) {

            btn.text('Загрузка');

            const postType = btn.data('posttype');
            const query = btn.data('query');
            const offset = btn.data('offset');

            const data = {
                'action': 'showmore',
                'query': query,
                'posttype': postType,
            };

            $.ajax({
                url: ajax.url,
                data: data,
                type: 'POST',
                success: function (data) {
                    if (data) {

                        btn.data('ajax', false);

                        btn.text('Свернуть').parent().prev().after("<div class='postview'><div class='row cols-" + offset + "'>" + data + "</div></div>");
                        btn.parent().parent().find('.postview').slideDown(300).addClass('open')
                    } else {

                        $('#true_loadmore').remove(); // если мы дошли до последней страницы постов, скроем кнопку
                    }
                }
            });
        } else {

            if (postviewBlock.hasClass('open')) {

                postviewBlock.slideUp(300).removeClass('open');
                btn.text('Показать больше');
            } else {

                postviewBlock.slideDown(300).addClass('open');
                btn.text('Свернуть');
            }
        }
    });

    $('button#posts_filter_submit').on('click', function (e) {

        console.log('start');

        e.preventDefault();

        const data = $('form#posts_filter_form').serialize();

        console.log(data + '&123213');

        $.ajax({
            url: ajax.url,
            data: data,
            type: 'POST',
            success: function (data) {

                $('ul.archive__list').html(data);

                console.log('done posts');
            }
        });
    });

});