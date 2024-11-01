<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WM_Product_Rating_Element extends Widget_Base {

    public function get_name() {
        return 'wm-single-product-rating';
    }

    public function get_title() {
        return __( 'WM - Product Rating', 'unikforce' );
    }

    public function get_icon() {
        return 'eicon-product-rating';
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
        return ['product rating','rating'];
    }

    protected function _register_controls() {

        // Product Rating Style
        $this->start_controls_section(
            'product_rating_style_section',
            array(
                'label' => __( 'Style', 'unikforce' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            $this->add_control(
                'product_rating_color',
                [
                    'label'     => __( 'Star Color', 'unikforce' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .star-rating' => 'color: {{VALUE}} !important;',
                        '{{WRAPPER}} .woocommerce-product-rating' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'product_rating_text_color',
                [
                    'label'     => __( 'Link Color', 'unikforce' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.woocommerce-review-link' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'product_rating_link_typography',
                    'label'     => __( 'Link Typography', 'unikforce' ),
                    'selector'  => '{{WRAPPER}} a.woocommerce-review-link',
                )
            );

            $this->add_control(
                'rating_margin',
                [
                    'label' => __( 'Margin', 'unikforce' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-product-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        global $product;
        $product = wc_get_product();

        if( Plugin::instance()->editor->is_edit_mode() ){
            echo \UnikForce_Data::instance()->default( $this->get_name() );
        } else{
            if ( empty( $product ) ) { return; }
            woocommerce_template_single_rating();
        }

    }

}
Plugin::instance()->widgets_manager->register_widget_type( new WM_Product_Rating_Element() );