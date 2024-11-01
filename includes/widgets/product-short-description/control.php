<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WM_Product_Short_Description_Element extends Widget_Base {

    public function get_name() {
        return 'wm-single-product-short-description';
    }

    public function get_title() {
        return __( 'WM - Product Short Description', 'unikforce' );
    }

    public function get_icon() {
        return 'eicon-product-description';
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
        return ['short description','description','product short description'];
    }

    protected function _register_controls() {


        // Style
        $this->start_controls_section(
            'product_content_style_section',
            array(
                'label' => __( 'Style', 'unikforce' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_responsive_control(
                'text_align',
                [
                    'label' => __( 'Alignment', 'unikforce' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'unikforce' ),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'unikforce' ),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'unikforce' ),
                            'icon' => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'unikforce' ),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label' => __( 'Text Color', 'unikforce' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-product-details__short-description p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'text_typography',
                    'label' => __( 'Typography', 'unikforce' ),
                    'selector' => '{{WRAPPER}} .woocommerce-product-details__short-description,{{WRAPPER}} .woocommerce-product-details__short-description p',
                ]
            );

        $this->end_controls_section();

    }


    protected function render( $instance = [] ) {
        global $product;
        $product = wc_get_product();
        if ( Plugin::instance()->editor->is_edit_mode() ) {
            echo \UnikForce_Data::instance()->default( $this->get_name() );
        }else{
            if ( empty( $product ) ) {
                return;
            }
            wc_get_template( 'single-product/short-description.php' );
        }
    }

}
Plugin::instance()->widgets_manager->register_widget_type( new WM_Product_Short_Description_Element() );
