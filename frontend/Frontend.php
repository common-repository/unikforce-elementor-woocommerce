<?php

namespace UnikForce\UnikForce\Frontend;

/**
 * Class Frontend
 * @package UnikForce\UnikForce\Frontend
 */
class Frontend
{
    function __construct()
    {
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'elementor_load_style'), 10);
        add_action('elementor/frontend/after_register_scripts', array($this, 'elementor_load_script'), 10);
    }
    /**
     * Load Frontend Style
     *
     */
    public function elementor_load_style()
    {
        foreach (glob(UNIKFORCE_PLUGIN_DIR_NAME . 'assets/css/*.css') as $file) {
            $filename = substr($file, strrpos($file, '/') + 1);
            wp_enqueue_style($filename, UNIKFORCE_PL_URL . 'assets/css/' . $filename, array(), filemtime(UNIKFORCE_PLUGIN_DIR_NAME . 'assets/css/' . $filename), 'all');
        }
        //wp_enqueue_style( 'unikforce', UNIKFORCE_PL_URL . 'assets/css/unikforce.css', array(), UNIKFORCE_VERSION, 'all');

    }
    /**
     * Load Frontend Scripts
     *
     */
    public function elementor_load_script()
    {
        foreach (glob(UNIKFORCE_PLUGIN_DIR_NAME . 'assets/js/*.js') as $file) {
            $filename = substr($file, strrpos($file, '/') + 1);
            wp_enqueue_script($filename, UNIKFORCE_PL_URL . 'assets/js/' . $filename, array('jquery'), filemtime(UNIKFORCE_PLUGIN_DIR_NAME . 'assets/js/' . $filename), true);
        }
    }

}