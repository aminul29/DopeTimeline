<?php
/**
 * Plugin Name: DopeTimeline for Elementor
 * Description: Premium timeline widget for Elementor based on Figma design.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: dope-timeline
 * Requires Plugins: elementor
 *
 * @category Plugin
 * @package  DopeTimeline
 * @author   Aminul Islam
 * @license  https://opensource.org/licenses/GPL-2.0 GPL-2.0+
 * @link     https://example.com
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Plugin Class
 *
 * @category Plugin
 * @package  DopeTimeline
 * @author   Antigravity <antigravity@example.com>
 * @license  https://opensource.org/licenses/GPL-2.0 GPL-2.0+
 * @link     https://example.com
 */
final class DopeTimeline_Plugin
{

    /**
     * Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * Instance
     *
     * @var DopeTimeline_Plugin
     */
    private static $_instance = null;

    /**
     * Get Instance
     *
     * @return DopeTimeline_Plugin
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'onPluginsLoaded']);
    }

    /**
     * Handle plugins_loaded action
     *
     * @return void
     */
    public function onPluginsLoaded()
    {
        if ($this->isCompatible()) {
            add_action(
                'elementor/elements/categories_registered',
                [$this, 'registerCategories']
            );
            add_action(
                'elementor/widgets/register',
                [$this, 'registerWidgets']
            );
            add_action(
                'wp_enqueue_scripts',
                [$this, 'enqueueAssets']
            );
        }
    }

    /**
     * Register Custom Categories
     *
     * @param object $elements_manager Elementor elements manager.
     *
     * @return void
     */
    public function registerCategories($elements_manager)
    {
        $elements_manager->add_category(
            'dope-category',
            [
                'title' => esc_html__('Dope Plugins', 'dope-timeline'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     * Check Compatibility
     *
     * @return bool
     */
    public function isCompatible()
    {
        if (!did_action('elementor/loaded')) {
            return false;
        }

        $min_elementor = self::MINIMUM_ELEMENTOR_VERSION;
        if (!defined('ELEMENTOR_VERSION')
            || !version_compare(ELEMENTOR_VERSION, $min_elementor, '>=')
        ) {
            return false;
        }

        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            return false;
        }

        return true;
    }

    /**
     * Enqueue Assets
     *
     * @return void
     */
    public function enqueueAssets()
    {
        wp_register_style(
            'dopetimeline-style',
            plugin_dir_url(__FILE__) . 'assets/css/style.css',
            [],
            self::VERSION
        );
        wp_register_script(
            'dopetimeline-script',
            plugin_dir_url(__FILE__) . 'assets/js/scripts.js',
            ['jquery'],
            self::VERSION,
            true
        );
    }

    /**
     * Register Widgets
     *
     * @param object $widgets_manager Elementor widgets manager.
     *
     * @return void
     */
    public function registerWidgets($widgets_manager)
    {
        $widget_file = __DIR__ . '/includes/widgets/timeline-widget.php';
        if (file_exists($widget_file)) {
            include_once $widget_file;
            if (class_exists('DopeTimeline_Widget')) {
                $widgets_manager->register(new \DopeTimeline_Widget());
            }
        }
    }
}

DopeTimeline_Plugin::instance();
