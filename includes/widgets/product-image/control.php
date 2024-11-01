<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WM_Product_Image_Element extends Widget_Base {

    public function get_name() {
        return 'wm-single-product-image';
    }

    public function get_title() {
        return __( 'WM - Product Image', 'unikforce' );
    }

    public function get_icon() {
        return 'eicon-product-images';
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
        return ['image','product image','product thumbnail'];
    }

    protected function _register_controls() {

        // Product Image Style
        $this->start_controls_section(
            'product_image_style_section',
            array(
                'label' => __( 'Image', 'unikforce' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_image_border',
                    'selector' => '{{WRAPPER}} .woocommerce-product-gallery__trigger + .woocommerce-product-gallery__wrapper,
                    {{WRAPPER}} .flex-viewport',
                ]
            );

            $this->add_responsive_control(
                'product_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'unikforce' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-product-gallery__trigger + .woocommerce-product-gallery__wrapper,
                        {{WRAPPER}} .flex-viewport' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_margin',
                [
                    'label' => __( 'Margin', 'unikforce' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .flex-viewport:not(:last-child)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Thumbnails Style
        $this->start_controls_section(
            'product_thumbnails_image_style_section',
            array(
                'label' => __( 'Thumbnails', 'unikforce' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_thumbnails_border',
                    'label' => __( 'Thumbnails Border', 'unikforce' ),
                    'selector' => '{{WRAPPER}} .flex-control-thumbs img',
                ]
            );

            $this->add_responsive_control(
                'product_thumbnails_border_radius',
                [
                    'label' => __( 'Border Radius', 'unikforce' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .flex-control-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'product_thumbnails_spacing',
                [
                    'label' => __( 'Spacing', 'unikforce' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .flex-control-thumbs li' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-bottom: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .flex-control-thumbs' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
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
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );
        }

    }

}
Plugin::instance()->widgets_manager->register_widget_type( new WM_Product_Image_Element() );
