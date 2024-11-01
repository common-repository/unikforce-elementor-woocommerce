<?php

/**
 *  Single Product Custom Layout
 */
class UnikForce_Woo_Custom_Template_Layout
{

    public static $wm_elementor_template = array();

    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct()
    {
        add_action('init', array($this, 'init'));
    }

    public function init()
    {

        // Manage Body classes
        add_filter('body_class', array($this, 'body_classes'));

        // Product details page
        add_filter('wc_get_template_part', array($this, 'wm_get_product_page_template'), 99, 3);
        add_filter('template_include', array($this, 'wm_get_product_elementor_template'), 100);
        add_action('unikforce_woocommerce_product_content', array($this, 'wm_get_product_content_elementor'), 5);
        add_action('unikforce_woocommerce_product_content', array($this, 'wm_get_default_product_data'), 10);

        // Product Archive Page
        add_action('template_redirect', array($this, 'unikforce_product_archive_template'), 999);
        add_filter('template_include', array($this, 'unikforce_redirect_product_archive_template'), 999);
        add_action('unikforce_woocommerce_archive_product_content', array($this, 'unikforce_archive_product_page_content'));
    }

    /**
     * [body_classes]
     * @param  [array] $classes
     * @return [array]
     */
    public function body_classes($classes)
    {

        $class_prefix = 'elementor-page-';

        if (is_product() && false !== $this->has_template('singleproductpage')) {

            $classes[] = $class_prefix . $this->has_template('singleproductpage');

        } elseif (is_checkout() && false !== $this->has_template('productcheckoutpage')) {

            $classes[] = $class_prefix . $this->has_template('productcheckoutpage');

        } elseif (is_shop() && false !== $this->has_template('productarchivepage')) {

            $classes[] = $class_prefix . $this->has_template('productarchivepage');

        } elseif (is_account_page()) {
            if (is_user_logged_in() && false !== $this->has_template('productmyaccountpage')) {
                $classes[] = $class_prefix . $this->has_template('productmyaccountpage');
            } else {
                if (false !== $this->has_template('productmyaccountloginpage')) {
                    $classes[] = $class_prefix . $this->has_template('productmyaccountloginpage');
                }
            }
        } else {
            if (is_cart() && !WC()->cart->is_empty() && false !== $this->has_template('productcartpage')) {
                $classes[] = $class_prefix . $this->has_template('productcartpage');
            } else {
                if (false !== $this->has_template('productemptycartpage')) {
                    $classes[] = $class_prefix . $this->has_template('productemptycartpage');
                }
            }
        }

        return $classes;

    }

    /**
     * [has_template]
     * @param  [string]  $field_key
     * @return boolean | int
     */
    public function has_template($field_key)
    {
        $template_id = unikforce_option($field_key);
        if ('0' !== $template_id) {
            return $template_id;
        } else {
            return false;
        }
    }

    public function wm_get_product_page_template($template, $slug, $name)
    {
        if ('content' === $slug && 'single-product' === $name) {
            if (UnikForce_Woo_Custom_Template_Layout::wm_custom_product_template()) {
                $template = UNIKFORCE_PL_PATH . 'woo-templates/single-product.php';
            }
        }
        return $template;
    }

    //Based on elementor template
    public function wm_get_product_elementor_template($template)
    {
        if (is_embed()) {
            return $template;
        }
        if (is_singular('product')) {
            if (UnikForce_Woo_Custom_Template_Layout::wm_custom_product_template()) {
                $templateid = get_page_template_slug(self::single_product_tmp_id());
                if ('elementor_header_footer' === $templateid) {
                    $template = UNIKFORCE_PL_PATH . 'woo-templates/single-product-fullwidth.php';
                } elseif ('elementor_canvas' === $templateid) {
                    $template = UNIKFORCE_PL_PATH . 'woo-templates/single-product-canvas.php';
                }
            }
        }
        return $template;
    }

    public static function wm_get_product_content_elementor()
    {
        if (UnikForce_Woo_Custom_Template_Layout::wm_custom_product_template()) {
            $wmtemplateid = self::single_product_tmp_id();
            echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display($wmtemplateid);
        } else {
            the_content();
        }
    }

    // product data
    public function wm_get_default_product_data()
    {
        WC()->structured_data->generate_product_data();
    }

    public static function single_product_tmp_id()
    {
        $wmtemplateid = unikforce_option('singleproductpage');
        $wmindividualid = get_post_meta(get_the_ID(), '_selectproduct_layout', true) ? get_post_meta(get_the_ID(), '_selectproduct_layout', true) : '0';
        if ($wmindividualid != '0') {
            $wmtemplateid = $wmindividualid;
        }
        return $wmtemplateid;
    }

    public static function wm_custom_product_template()
    {
        $templatestatus = false;
        if (is_product()) {
            if (!empty(self::single_product_tmp_id()) && '0' !== self::single_product_tmp_id()) {
                $templatestatus = true;
            }
        }
        return apply_filters('wm_custom_product_template', $templatestatus);
    }

    /*
    * Archive Page
    */
    public function unikforce_product_archive_template()
    {
        $archive_template_id = 0;
        if (defined('WOOCOMMERCE_VERSION')) {
            $termobj = get_queried_object();
            $get_all_taxonomies = unikforce_get_taxonomies();

            if (is_shop() || (is_tax('product_cat') && is_product_category()) || (is_tax('product_tag') && is_product_tag()) || (isset($termobj->taxonomy) && is_tax($termobj->taxonomy) && array_key_exists($termobj->taxonomy, $get_all_taxonomies))) {
                $product_shop_custom_page_id = unikforce_option('productarchivepage');

                // Archive Layout Control
                $wmtermlayoutid = 0;
                if ((is_tax('product_cat') && is_product_category()) || (is_tax('product_tag') && is_product_tag())) {

                    $product_archive_custom_page_id = unikforce_option('productallarchivepage');

                    // Get Meta Value
                    $wmtermlayoutid = get_term_meta($termobj->term_id, 'unikforce_selectcategory_layout', true) ? get_term_meta($termobj->term_id, 'unikforce_selectcategory_layout', true) : '0';

                    if (!empty($product_archive_custom_page_id) && $wmtermlayoutid == '0') {
                        $wmtermlayoutid = $product_archive_custom_page_id;
                    }

                }
                if ($wmtermlayoutid != '0') {
                    $archive_template_id = $wmtermlayoutid;
                } else {
                    if (!empty($product_shop_custom_page_id)) {
                        $archive_template_id = $product_shop_custom_page_id;
                    }
                }
                return $archive_template_id;
            }

            return $archive_template_id;
        }
    }

    public function unikforce_redirect_product_archive_template($template)
    {
        $archive_template_id = $this->unikforce_product_archive_template();
        $templatefile = array();
        $templatefile[] = 'woo-templates/archive-product.php';
        if ($archive_template_id != '0') {
            $template = locate_template($templatefile);
            if (!$template || (!empty($status_options['template_debug_mode']) && current_user_can('manage_options'))) {
                $template = UNIKFORCE_PL_PATH . 'woo-templates/archive-product.php';
            }
            $page_template_slug = get_page_template_slug($archive_template_id);
            if ('elementor_header_footer' === $page_template_slug) {
                $template = UNIKFORCE_PL_PATH . 'woo-templates/archive-product-fullwidth.php';
            } elseif ('elementor_canvas' === $page_template_slug) {
                $template = UNIKFORCE_PL_PATH . 'woo-templates/archive-product-canvas.php';
            }
        }
        return $template;
    }

    // Element Content
    public function unikforce_archive_product_page_content($post)
    {
        $archive_template_id = $this->unikforce_product_archive_template();
        if ($archive_template_id != '0') {
            echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display($archive_template_id);
        } else {
            the_content();
        }
    }

}

UnikForce_Woo_Custom_Template_Layout::instance();