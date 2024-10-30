<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              zworthkey.com/about-us
 * @since             1.0.0
 * @package           Wc_Hide_Category
 *
 * @wordpress-plugin
 * Plugin Name:       Hide Product & Post Categories
 * Plugin URI:        zworthkey.com
 * Description:       WC Hide Category is Open source Software. You can easily hide your Unnecessary/Private Category on the Shop page and Post Page.
 * Version:           1.0.0
 * Author:            Zworthkey
 * Author URI:        zworthkey.com/about-us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-hide-category
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WC_HIDE_CATEGORY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-hide-category-activator.php
 */
function activate_wc_hide_category() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-hide-category-activator.php';
	Wc_Hide_Category_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-hide-category-deactivator.php
 */
function deactivate_wc_hide_category() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-hide-category-deactivator.php';
	Wc_Hide_Category_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_hide_category' );
register_deactivation_hook( __FILE__, 'deactivate_wc_hide_category' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-hide-category.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_hide_category() {

	$plugin = new Wc_Hide_Category();
	$plugin->run();

}
run_wc_hide_category();
