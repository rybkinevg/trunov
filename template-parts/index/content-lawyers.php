<div class="col">
    <a href="<?= get_the_permalink() ?>" class="lawyers__item">
        <div class="lawyers__img">
            <img src="<?= get_the_post_thumbnail_url(); ?>" alt="" class="img img--cover">
        </div>
        <div class="lawyers__name">
            <?= get_the_title(); ?>
        </div>
    </a>
</div>