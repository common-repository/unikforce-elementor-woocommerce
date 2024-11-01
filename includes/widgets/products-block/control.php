<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class products_block extends Widget_Base {

   public function get_name() {
      return 'sf-product-block';
   }

   public function get_title() {
      return __( 'WM - Product Block', 'swnofall' );
   }
    public function get_categories() {
		return [ 'unikforce' ];
	}
   public function get_icon() { 
        return 'eicon-posts-group';
   }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Slider', 'swnofall' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'query_type',
            [
                'label' => __('Query type', 'swnofall'),
                'type' => Controls_Manager::SELECT,
                'default' => 'individual',
                'options' => [
                    'category' => __('Category', 'swnofall'),
                    'individual' => __('Individual', 'swnofall'),
                ],
            ]
        );

        $this->add_control(
            'cat_query',
            [
                'label' => __('Select Product Category', 'swnofall'),
                'type' => Controls_Manager::SELECT2,
                'options' => unikforce_category_lists('product_cat'),
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_type' => 'category',
                ],
                'default' => __( 'swnofall', 'swnofall' ),
            ]
        );

        $this->add_control(
            'id_query',
            [
                'label' => __('Select Products', 'swnofall'),
                'type' => Controls_Manager::SELECT2,
                'options' => unikforce_post_lists('product'),
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'query_type' => 'individual',
                ],
            ]
        );
        $this->add_control(
            'number_product',
            [
                'label' => __( 'Total Product', 'unikforce' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 6,
                ],
            ]
        );
        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

       require dirname(__FILE__). '/view.php';
}
    protected function _content_template() {}

    protected function content_template() {}

   public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register_widget_type( new products_block() );