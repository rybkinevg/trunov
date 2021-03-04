<?php

namespace rybkinevg\trunov;

use WP_Error;

class Taxonomy
{
    public $tax_name;

    public $tax_slug;

    public $term_name;

    public $term_id;

    public $post_type;

    public function __construct(string $tax_name, string $post_type = 'post', string $tax_slug = '')
    {
        $this->tax_name  = $tax_name;
        $this->post_type = $post_type;
        $this->tax_slug  = (empty($tax_slug)) ? sanitize_title($tax_name) : $tax_slug;
    }

    public function add(string $term_name)
    {
        $this->term_name = $term_name;

        add_action('init', function () {
            $args = [
                'description' => "Описание {$this->term_name}",
                'slug'        => sanitize_title($this->term_name),
                'parent'      => 0
            ];

            $term = wp_insert_term($this->term_name, $this->tax_slug, $args);

            if (is_wp_error($term))
                wp_die("Название термина: {$this->term_name}.<br/> Ошибка: {$term->get_error_message()}");
        });
    }
}
