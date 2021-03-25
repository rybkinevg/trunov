<section class="single single__<?= get_post_type(); ?>">

    <div class="row">
        <div class="col col-content">
            <div class="single__item single__content">
                ФИО, Фото, Медиа (фото, видео), биография, образвание, соц. сети, награды, регалии
            </div>
            <div class="single__item single__content">
                <?= get_the_content(); ?>
            </div>
        </div>
        <div class="col col-sidebar">
            <div class="single__item single__sidebar">321</div>
        </div>
    </div>

</section>