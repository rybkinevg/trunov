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