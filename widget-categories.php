<?php

function add_elementor_widget_categories($elements_manager)
{
    $elements_manager->add_category(
        'youtube-tutorial-widgets',
        [
            'title' => __('Youtube Tutorial Widgets', 'standalone-slideshow-nav'),
            'icon' => 'eicon-theme-builder',
        ]
    );
}

add_action('elementor/elements/categories_registered', 'add_elementor_widget_categories');
