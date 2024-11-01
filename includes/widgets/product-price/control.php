<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WM_Product_Price_Element extends Widget_Base {

    public function get_name() {
        return 'wm-single-product-price';
    }

    public function get_title() {
        return __( 'WM - Product Price', 'unikforce' );
    }

    public function get_icon() {
        return 'eicon-product-price';
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
        return ['price','product price'];
    }

    protected function _register_controls() {

        // Product Price Style
        $this->start_controls_section(
            'product_price_regular_style_section',
            array(
                'label' => __( 'Price', 'unikforce' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'product_price_color',
                [
                    'label'     => __( 'Price Color', 'unikforce' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .price' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'product_price_typography',
                    'label'     => __( 'Typography', 'unikforce' ),
                    'selector'  => '{{WRAPPER}} .price .amount',
                ]
            );

            $this->add_control(
                'price_margin',
                [
                    'label' => __( 'Margin', 'unikforce' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'product_price_sale_style_section',
            [
                'label' => __( 'Old Price', 'unikforce' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'product_sale_price_color',
                [
                    'label'     => __( 'Price Color', 'unikforce' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .price del' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'product_sale_price_typography',
                    'label'     => __( 'Typography', 'unikforce' ),
                    'selector'  => '{{WRAPPER}} .price del, {{WRAPPER}} .price del .amount',
                )
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();

        global $product;
        $product = wc_get_product();

        if( Plugin::instance()->editor->is_edit_mode() ){
            echo \UnikForce_Data::instance()->default( $this->get_name() );
        }else{
            if ( empty( $product ) ) { return; }
            woocommerce_template_single_price();
            woocommerce_single_variation();
        }

    }

}
Plugin::instance()->widgets_manager->register_widget_type( new WM_Product_Price_Element() );
