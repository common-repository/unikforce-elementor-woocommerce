<?php
/**
 * Plugin Name: UnikForce Elementor WooCommerce Builder
 * Description: UnikForce Elementor WooCommerceCommerce elements library for Elementor page builder plugin for WordPress.
 * Plugin URI:  https://wordpress.org/plugins/unikforce-elementor-woocommerce/
 * Version:     1.0.0
 * Author:      UnikForce IT
 * Author URI:  https://unikforce.com/
 * License:     GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: unikforce
 * WC tested up to: 5.5.2
 * Elementor tested up to: 3.3.1
 * Elementor Pro tested up to: 3.3.4
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Autoloader File
require_once (__DIR__ . '/vendor/autoload.php');
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * Class UnikForce
 */
final class UNIKFORCE
{
    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '1.0.0';

    /** Singleton *************************************************************/

    private function __construct()
    {

        $this->define_constants();

        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    /**
     * Main UnikForce Instance
     *
     * Insures that only one instance of UnikForce exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     */

    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @return mixed
     */
    public function define_constants()
    {
        define('UNIKFORCE_VERSION', self::VERSION);
        define('UNIKFORCE_PL_ROOT', __FILE__);
        define('UNIKFORCE_PL_URL', plugins_url('/', UNIKFORCE_PL_ROOT));
        define('UNIKFORCE_PL_PATH', plugin_dir_path(UNIKFORCE_PL_ROOT));
        define('UNIKFORCE_DIR_URL', plugin_dir_url(UNIKFORCE_PL_ROOT));
        define('UNIKFORCE_PLUGIN_BASE', plugin_basename(UNIKFORCE_PL_ROOT));
        define('UNIKFORCE_PLUGIN_DIR_NAME', dirname(__FILE__) . '/');
        define('UNIKFORCE_ITEM_NAME', 'UnikForce Elementor WooCommerce Builder');
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin()
    {

        new UnikForce\UnikForce\Admin\Admin();
        new UnikForce\UnikForce\Frontend\Frontend();
        new UnikForce\UnikForce\Includes\Includes();

    }

    /**
     * Activate Log to database
     */
    public function activate()
    {

        $installed = get_option('unikforce_installed');

        if (!$installed) {
            update_option('unikforce_installed', time());
        }

        update_option('unikforce_version', UNIKFORCE_VERSION);
    }

    /**
     * Throw error on object clone
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'unikforce'), '1.0.0');
    }

    /**
     * Disable unserializing of the class
     *
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'unikforce'), '1.0.0');
    }

}

function UNIKFORCE_INIT()
{
    return UNIKFORCE::init();
}

// Get UnikForce Elementor WooCommerce  Running
UNIKFORCE_INIT();