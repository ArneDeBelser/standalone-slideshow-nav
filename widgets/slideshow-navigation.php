<?php

class Slideshow_Navigation_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'slideshow-navigation';
    }

    public function get_title()
    {
        return __('Standalone Naviagation', 'standalone-slideshow-nav');
    }

    public function get_icon()
    {
        return 'eicon-navigation-horizontal';
    }

    public function get_categories()
    {
        return ['youtube-tutorial-widgets'];
    }

    public function get_script_depends()
    {
        return ['slideshow-navigation'];
    }

    public function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Settings', 'standalone-slideshow-nav'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'use_section_id',
            [
                'label' => __('Enter ID', 'standalone-slideshow-nav'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'standalone-slideshow-nav'),
                'label_off' => esc_html__('No', 'standalone-slideshow-nav'),
                'description0' => esc_html('You can enter a custom ID, or you could let the plugin find the nearest slider', 'standalone-slideshow-nav'),
                'frontend_available' => true,
                'return_value' => 'yes',
                'deafult' => 'no',
            ]
        );

        $this->add_control(
            'slider_id',
            [
                'label' => __('Section/Container ID', 'standalone-slideshow-nav'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __('header-image', 'standalone-slideshow-nav'),
                'description' => esc_html__('Enter the section/container ID, f.E. header-image', 'standalone-slideshow-nav'),
                'frontend_available' => true,
                'condition' => [
                    'use_section_id' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
?>
        <div class="elementor-image-carousel-wrapper standalone-swiper-container" dir="ltr">
            <div class="slideshow-navigation standalone-swiper-pagination standalone-swiper-pagination-clickable standalone-swiper-pagination-bullets"></div>
        </div>
    <?php
    }

    protected function content_template()
    {
    ?>
        <div class="elementor-image-carousel-wrapper standalone-swiper-container" dir="ltr">
            <div class="slideshow-navigation standalone-swiper-pagination standalone-swiper-pagination-clickable standalone-swiper-pagination-bullets"></div>
        </div>
<?php
    }
}
