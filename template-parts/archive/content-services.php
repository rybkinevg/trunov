<li class="archive__list-item">
    <article class="archive__article">
        <div class=" archive__info">
            <h3 class="archive__title">
                <a href="<?= get_the_permalink(); ?>">
                    <?= get_the_title(); ?>
                </a>
            </h3>
            <p class="archive__excerpt">
                <?= kama_excerpt(['maxchar' => 250, 'autop' => 0]); ?>
            </p>
        </div>
    </article>
</li>