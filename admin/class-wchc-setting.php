<?php
defined( 'ABSPATH' ) || exit;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ( class_exists( 'WC_Settings_Hide_Cat', false ) ) {
	return new WC_Settings_Hide_Cat();
}

/**
 * WC_Settings_Emails.
 */
class WC_Settings_Hide_Cat extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'zwkhc_hide_cat';
		$this->label = __( 'Hide Category', '' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	// public function get_sections() {

	// 	$sections = array(
	// 		'' => __( 'Hide Cat', '' ),
	// 	);

	// 	return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	// }



	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
        $category = get_categories(
            array(
                'taxonomy'  => 'product_cat',
                'orderby'   => 'name',
                'empty'     => 1
            )
        );
		$category = wp_list_pluck($category, 'name', 'term_id');
		$post_cat = get_categories('post');
		$post_category = wp_list_pluck($post_cat, 'name', 'term_id');
		$post_category[0]="None";
		$category[0]="None";
		$settings =array(
			array(
				'title' => __( 'Hide Product Category', 'woocommerce' ),
				'type'  => 'title',
				
			),
			array(
				'title'=>__('Select Category'),
				'id'=>'zwhc_product_option',
				'type'=>'select',
                'class'    => 'wc-enhanced-select',
                'default' 	=> 'None',
                'options' => $category
                
            ),
            array('type' => 'sectionend', 'id'=>'genral_setting'),
            array(
                'title' => __( 'Hide Post Category', 'woocommerce' ),
				'type'  => 'title',
            ),
            array(
				'title'=>__('Select Category'),
				'id'=>'zwhc_post_option',
				'type'=>'select',
                'default' 	=> 'None',
                'class'    => 'wc-enhanced-select',
                'options' => $post_category
            ),
            
			
			array('type' => 'sectionend', 'id'=>'wc-save-settings')

		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}
	public function output() {
		$settings = $this->get_settings();
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();
        WC_Admin_Settings::save_fields(  $settings );		
	}
}

return new WC_Settings_Hide_Cat();
