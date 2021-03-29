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
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
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

        const btn = $(this);

        btn.text('Загружаю...'); // изменяем текст кнопки, вы также можете добавить прелоадер

        const offset = btn.data('offset');
        const posttype = btn.data('posttype');

        const data = {
            'action': 'loadmore',
            'query': loadmore[posttype],
            'offset': offset,
            'posttype': posttype,
        };

        $.ajax({
            url: ajax.url,
            data: data,
            type: 'POST',
            success: function (data) {
                if (data) {

                    btn.text('Свернуть').parent().prev().find('.row').append(data); // вставляем новые посты
                } else {

                    $('#true_loadmore').remove(); // если мы дошли до последней страницы постов, скроем кнопку
                }
            }
        });
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

    // const showMoreBtn = $(".show-more__btn");
    // showMoreBtn.on("click", showHiddenContent);
    // function showHiddenContent() {

    //     let hiddenContent = $(this).parent().prev();
    //     hiddenContent.slideToggle({
    //         duration: 300,
    //         start: function () {
    //             $(this).css("display", "flex");
    //         }
    //     });
    // }

});