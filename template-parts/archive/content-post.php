<li class="archive__list-item">
    <article class="archive__article">
        <div class=" archive__info">
            <span class="archive__date">
                <?= get_the_date('j F Y'); ?>
            </span>
            <h3 class="archive__title">
                <a href="<?= get_the_permalink(); ?>">
                    <?= get_the_title(); ?>
                </a>
            </h3>
            <p class="archive__excerpt">
                <?= kama_excerpt(['maxchar' => 250, 'autop' => 0]); ?>
            </p>
        </div>
        <a href="<?= get_the_permalink(); ?>" class="archive__img">
            <?= trunov_get_thumbnail(); ?>
        </a>
    </article>
</li>