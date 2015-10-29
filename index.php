<?php
/*
* Plugin Name: ShareWhere
* Plugin URI: https://github.com/sanketio/sharewhere?utm_source=dashboard&utm_medium=plugin&utm_campaign=wp-location-share
* Description: This plugin shares location.
* Version: 1.2
* Author: littlemonks
* Text Domain: wpls
* Author URI: https://github.com/sanketio/sharewhere?utm_source=dashboard&utm_medium=plugin&utm_campaign=wp-location-share
* License: GPLv2 or Later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/*
 * The WordPress Location Share Plugin
 *
 * @package    WPLocationShare
 * @subpackage Main
 */

/*
 * The server file system path to the plugin directory
 */
if( !defined( 'WP_LOCATION_SHARE_PATH' ) ) {
	define( 'WP_LOCATION_SHARE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

/*
 * URL to plugin directory
 */
if( !defined( 'WP_LOCATION_SHARE_URL' ) ) {
	define( 'WP_LOCATION_SHARE_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

/*
 * Plugin basename
 */
if( !defined( 'WP_LOCATION_SHARE_BASE_NAME' ) ) {
	define( 'WP_LOCATION_SHARE_BASE_NAME', trailingslashit( plugin_basename( __FILE__ ) ) );
}

/*
 * Plugin Version
 */
if( !defined( 'WP_LOCATION_SHARE_VERSION' ) ) {
	define( 'WP_LOCATION_SHARE_VERSION', '1.2' );
}

/**
 * Register the autoloader function into spl_autoload
 */
spl_autoload_register( 'wp_location_share_autoloader' );

/**
 * Auto Loader Function
 *
 * Autoloads classes on instantiation. Used by spl_autoload_register.
 *
 * @param string $class_name The name of the class to autoload
 */
function wp_location_share_autoloader( $class_name ) {
	$wp_location_share_path = array(
		'includes/' . $class_name . '.php',
		'includes/admin/' . $class_name . '.php',
		'includes/comment/' . $class_name . '.php',
		'includes/buddypress/' . $class_name . '.php'
	);

	foreach( $wp_location_share_path as $path ) {
		$path = WP_LOCATION_SHARE_PATH . $path;

		if( file_exists( $path ) ) {
			include $path;

			break;
		}
	}
}

/*
 * Creating instances of the classes
 */
add_filter( 'wpls_class_construct', 'wpls_class_construct' );

function wpls_class_construct( $wpls_class_construct ) {
	array_push( $wpls_class_construct, 'WPLSWordPressComment' );
	array_push( $wpls_class_construct, 'WPLocationShareBuddyPress' );

	return $wpls_class_construct;
}

/*
 * Executing WPLocationShare class
 */
new WPLocationShare();
