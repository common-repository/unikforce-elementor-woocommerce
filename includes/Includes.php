<?php

namespace UnikForce\UnikForce\Includes;

/**
 * Class Includes
 * @package UnikForce\UnikForce\Includes
 */
class Includes
{
    function __construct()
    {
        add_action('elementor/widgets/widgets_registered', array($this, 'elementor_load_widgets'));
    }

    /**
     * Include required files
     *
     */
    public function elementor_load_widgets()
    {
        foreach (glob(UNIKFORCE_PL_PATH . 'includes/widgets/*/control.php') as $file) {
            include_once $file;
        }
    }
}