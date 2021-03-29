<?php

$status = (carbon_get_post_meta(get_the_ID(), 'status') == 'head') ?: '';

?>

<li class="archive__list-item <?= ($status) ? 'archive__lawyers-head' : '' ?>">
    <article class="archive__article">
        <div class=" archive__info">

            <?php

            if ($status) {

            ?>

                <span class="archive__date">
                    <?= carbon_get_post_meta(get_the_ID(), 'position'); ?>
                </span>

            <?php

            }

            ?>

            <h3 class="archive__title">
                <a href="<?= get_the_permalink(); ?>">
                    <?= get_the_title(); ?>
                </a>
            </h3>

            <p class="archive__excerpt">
                <?= kama_excerpt(['maxchar' => 200, 'autop' => 0]); ?>
            </p>

        </div>
        <a href="<?= get_the_permalink(); ?>" class="archive__img">
            <?= trunov_get_thumbnail(); ?>
        </a>
    </article>
</li>