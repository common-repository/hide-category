<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       zworthkey.com/about-us
 * @since      1.0.0
 *
 * @package    Wc_Hide_Category
 * @subpackage Wc_Hide_Category/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Hide_Category
 * @subpackage Wc_Hide_Category/public
 * @author     Zworthkey <sales@zworthkey.com>
 */
class Wc_Hide_Category_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'woocommerce_product_query', array($this,'zwkhv_product_hide') );
		add_action( 'pre_get_posts', array($this, 'zwkhv_post_hide') );
		add_filter("get_terms",[ $this,"hide_category_widget"],10,3);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Hide_Category_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Hide_Category_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-hide-category-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Hide_Category_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Hide_Category_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-hide-category-public.js', array( 'jquery' ), $this->version, false );

	}
	public function zwkhv_product_hide( $q ) {
	
		if( is_shop() || is_home() || is_singular() ) { // set conditions here
			
			$tax_query = (array) $q->get( 'tax_query' );
			$cat_id = get_option('zwhc_product_option');
			if($cat_id != 0 || $cat_id !=''){
				$ids = wc_get_products(array(
					'post_status' => 'publish',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $cat_id,
                            'operator' => 'IN',
                        ),
					)
				));
			}
			$products = array();
			if(isset($ids) || !empty($ids)){
				foreach($ids as $id){
					array_push($products, $id->get_id());
				}
			}
			
			$q->set( 'post__not_in', $products );
			
		}

	}
	/**
		 * Hide category on widget
		 *
	*/
	public function hide_category_widget( $terms, $taxonomies, $args ) {

		$hide_category 	= get_option('zwhc_product_option'); 
		if(is_shop() && isset($hide_category) && $hide_category!=0){
			foreach ( $terms as $key => $term ) {
				if(is_object($term)){
					if($hide_category == $term->term_id && $term->taxonomy = 'product_cat'){
						unset($terms[$key]);
					}	
				}
			}
		}
		return $terms;
	}
	
	public function zwkhv_post_hide($q){
		error_reporting(0);
		global $wpdb;
		// ini_set('memory_limit','10MB');
	
		if(is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()){
			
			$cat_id = get_option('zwhc_post_option');
			$query = $wpdb->prepare("SELECT ID FROM wp_posts
			LEFT JOIN wp_term_relationships ON
			(wp_posts.ID = wp_term_relationships.object_id)
			LEFT JOIN wp_term_taxonomy ON
			(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
			WHERE wp_posts.post_type = 'post'
			AND wp_term_taxonomy.taxonomy = 'category'
			AND wp_term_taxonomy.term_id = $cat_id
			ORDER BY post_date DESC");
			$result = $wpdb->get_results($query);
			$posts_id = wp_list_pluck($result, 'ID');
			
		}
		$q->set( 'post__not_in', $posts_id );
	}


}
