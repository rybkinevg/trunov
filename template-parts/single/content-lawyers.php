<?php

$position = carbon_get_post_meta(get_the_ID(), 'position') ?: '';
$info = carbon_get_post_meta(get_the_ID(), 'info') ?: '';

?>

<section class="single single__<?= get_post_type(); ?>">

    <div class="row">
        <div class="col col-content">
            <div class="single__item single__content">
                <h2 class="single__title"><?= get_the_title(); ?></h2>

                <?php

                if ($position) {

                ?>

                    <span class="single__props"><?= $position; ?></span>

                    <?php

                }

                if ($info) {

                    foreach ($info as $item) {

                    ?>

                        <section class="single__section">
                            <h3 class="single__section-title"><?= $item['title'] ?></h3>
                            <div class="single__section-content"><?= str_replace("\n", '<br>', $item['text']); ?></div>
                        </section>

                    <?php
                    }
                } else {

                    ?>

                    <div class="single__body"><?= get_the_content(); ?></div>

                <?php

                }

                ?>

            </div>
        </div>
        <div class="col col-sidebar">
            <div class="single__item single__sidebar">321</div>
        </div>
    </div>

</section>