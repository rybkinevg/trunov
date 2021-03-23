<?php

get_header();

get_template_part('index-page-parts/main-news');
get_template_part('index-page-parts/management');
get_template_part('index-page-parts/news');
get_template_part('index-page-parts/offices');

?>

<section class="section lawyers">
    <div class="section__header">
        <h2 class="section__title">Адвокаты</h2>
    </div>
    <div class="section__inner">

        <?php

        function get_lawyers_offices_terms()
        {
            $terms = get_terms(['offices']);

            $sort = [];

            foreach ($terms as $term) {

                $sort[] = $term->term_id;
            }

            asort($sort);

            return $sort;
        }

        $offices = get_lawyers_offices_terms();

        ?>

        <div class="preview">
            <div class="row cols-6">

                <?php

                foreach ($offices as $office) {

                    $args = [
                        'post_type'      => 'lawyers',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'tax_query'      => [
                            'lawyers_tax' => [
                                'taxonomy' => 'lawyers_tax',
                                'field'    => 'slug',
                                'terms'    => 'advocat'
                            ],
                            'offices_tax' => [
                                'taxonomy' => 'offices',
                                'field'    => 'term_id',
                                'terms'    => $office
                            ]
                        ]
                    ];

                    $query = new WP_Query($args);

                    if ($query->have_posts()) {

                        while ($query->have_posts()) {

                            $query->the_post();

                            get_template_part('index-page-parts/content', get_post_type());
                        }
                    }

                    wp_reset_postdata();
                }

                ?>

            </div>

            <div class="show-more">
                <button class="show-more__btn" type="button" data-load="ajax" data-offset="6" data-posttype="lawyers">Показать больше (ajax)</button>
            </div>

            <?php

            ?>


</section>

<?php

get_template_part('index-page-parts/services');
get_template_part('index-page-parts/information');
get_template_part('index-page-parts/about');

get_footer();
