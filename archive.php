<?php

get_header();

if (have_posts()) {

    $post_type = get_post_type();

?>

    <section class="archive archive__<?= $post_type; ?>">

        <h2 class="archive__head">
            <?= trunov_archive_title($post_type); ?>
        </h2>

        <div class="row">
            <div class="col col-content">
                <div class="archive__item archive__content">

                    <ul class="archive__list">

                        <?php

                        while (have_posts()) {

                            the_post();

                            get_template_part('template-parts/archive/content', $post_type);
                        }

                        ?>

                    </ul>

                </div>

                <?php

                the_posts_pagination();

                ?>

            </div>
            <div class="col col-sidebar">
                <div class="archive__item archive__sidebar">
                    <div class="sidebar__item">
                        <form id="posts_filter_form" class="form">
                            <input type="hidden" name="action" value="filter_posts">

                            <?php

                            $taxonomies = get_taxonomies();

                            foreach ($taxonomies as $taxonomy) {

                                $tax_obj = get_taxonomy($taxonomy);

                                if (in_array('post', $tax_obj->object_type) && $taxonomy != 'post_format') {

                                    echo "<div class='form__item'>" . trunov_get_select_posts_filter($tax_obj->labels->name, $taxonomy) . '</div>';
                                }
                            }

                            ?>

                            <button id="posts_filter_submit" type="submit">Фильтр</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

<?php

} else {

    get_template_part('template-parts/archive/content', 'none');
}

get_footer();
