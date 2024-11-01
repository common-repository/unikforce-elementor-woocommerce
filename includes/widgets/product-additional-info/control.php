<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WM_Product_Additional_Info_Element extends Widget_Base {

    public function get_name() {
        return 'wm-product-additional-information';
    }

    public function get_title() {
        return __( 'WM - Product Additional Information', 'unikforce' );
    }

    public function get_icon() {
        return 'eicon-product-info';
    }

    public function get_categories() {
        return array( 'unikforce' );
    }

    public function get_style_depends(){
        return [
            'unikforce-widgets',
        ];
    }

    public function get_keywords(){
        return ['additional','information','attributes'];
    }

    protected function _register_controls() {


        // Slider Button stle
        $this->start_controls_section(
            'addition_info_content',
            [
                'label' => __( 'Heading', 'unikforce' ),
            ]
        );
            
            $this->add_control(
                'wm_show_heading',
                [
                    'label' => __( 'Heading', 'unikforce' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'unikforce' ),
                    'label_off' => __( 'Hide', 'unikforce' ),
                    'render_type' => 'ui',
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'prefix_class' => 'wm-show-heading-',
                ]
            );

        $this->end_controls_section();

        // Heading Style
        $this->start_controls_section(
            'heading_style_section',
            array(
                'label' => __( 'Heading', 'unikforce' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'heading_color',
                [
                    'label' => __( 'Color', 'unikforce' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} h2' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'wm_show_heading!' => '',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'heading_typography',
                    'label' => __( 'Typography', 'unikforce' ),
                    'selector' => '.woocommerce {{WRAPPER}} h2',
                    'condition' => [
                        'wm_show_heading!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'heading_margin',
                [
                    'label' => __( 'Margin', 'unikforce' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section(
            'content_style_section',
            array(
                'label' => __( 'Content', 'unikforce' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'content_color',
                [
                    'label' => __( 'Color', 'unikforce' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '.woocommerce {{WRAPPER}} .shop_attributes' => 'color: {{VALUE}}',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    'label' => __( 'Typography', 'unikforce' ),
                    'selector' => '.woocommerce {{WRAPPER}} .shop_attributes',
                ]
            ); 

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        if ( Plugin::instance()->editor->is_edit_mode() ) {
            echo \UnikForce_Data::instance()->default( $this->get_name() );
        } else{
            global $product;
            $product = wc_get_product();
            if ( empty( $product ) ) {
                return;
            }
            wc_get_template( 'single-product/tabs/additional-information.php' );
        }

    }

}
Plugin::instance()->widgets_manager->register_widget_type( new WM_Product_Additional_Info_Element() );
