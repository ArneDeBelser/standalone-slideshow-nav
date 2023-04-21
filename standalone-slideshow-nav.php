<?php

/**
 * Plugin Name: Standalone Slideshow
 * Description: When you apply a slideshow to an elementor container you can't set navigation bullets, this plugins tries to solve that
 * Plugin URI:  https://www.arnedebelser.be
 * Version:     1.0.0
 * Author:      De Belser Arne
 * Author URI:  https://www.arnedebelser.be
 * Text Domain: standalone-slideshow-nav
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

include('widget-categories.php');

final class StandAloneSlideshowNav
{
    const VERSION = "1.0.0";

    const MINIMUM_ELEMENTOR_VERSION = "3.0.0";

    const MINIMUM_PHP_VERSION = "7.2";

    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
    }

    public function i18n()
    {
        load_plugin_textdomain('standalone-slideshow-nav');
    }

    public function on_plugins_loaded()
    {
        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    public function init()
    {
        $this->i18n();

        // Add Plugin Actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
        // add_action('elementor/widgets/controls_registered', [$this, 'init_controls']);

        // Register widget styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);

        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
    }

    public function init_widgets()
    {
        require(__DIR__ . '/widgets/slideshow-navigation.php');

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Slideshow_Navigation_Widget());
    }

    public function widget_styles()
    {
        wp_enqueue_style('slideshow-navigation', plugins_url('css/slideshow-navigation.css', __FILE__));
    }

    public function widget_scripts()
    {
        wp_register_script('slideshow-navigation', plugin_dir_url(__FILE__) . 'build/index.js', ['jquery', 'elementor-frontend'], self::VERSION, true);
    }

    public function is_compatible()
    {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        return true;
    }

    public function admin_notice_missing_main_plugin()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-standalone-slideshow-navigation'),
            '<strong>' . esc_html__('Elementor Test Extension', 'elementor-standalone-slideshow-navigation') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-standalone-slideshow-navigation') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-standalone-slideshow-navigation'),
            '<strong>' . esc_html__('Elementor Test Extension', 'elementor-standalone-slideshow-navigation') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-standalone-slideshow-navigation') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_php_version()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-standalone-slideshow-navigation'),
            '<strong>' . esc_html__('Elementor Test Extension', 'elementor-standalone-slideshow-navigation') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-standalone-slideshow-navigation') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}

StandAloneSlideshowNav::instance();
