<?php

get_header();

while (have_posts()) {

    the_post();

?>

    <section class="section breadcrumbs">
        Главная / ...
    </section>

<?php

    get_template_part('template-parts/single/content', get_post_type());
}

get_footer();
