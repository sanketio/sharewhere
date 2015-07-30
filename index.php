<?php
/*
	Plugin Name : WordPress Location Share
	Plugin URI	: http://ps.com/wp-location-share/?utm_source=dashboard&utm_medium=plugin&utm_campaign=wp-location-share
	Description	: This plugin shares location.
	Version		: 1.0
	Author		: Pranali, Sanket
	Text Domain	: wplocation
	Author URI	: http://ps.com/?utm_source=dashboard&utm_medium=plugin&utm_campaign=wp-location-share
	License		: GPLv2 or Later
	License URI	: https://www.gnu.org/licenses/gpl-2.0.html
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
	define( 'WP_LOCATION_SHARE_PATH', plugin_dir_path( __FILE__ ) );
}

/*
 * URL to plugin directory
 */
if( !defined( 'WP_LOCATION_SHARE_URL' ) ) {
	define( 'WP_LOCATION_SHARE_URL', plugin_dir_url( __FILE__ ) );
}

/*
 * Plugin basename
 */
if( !defined( 'WP_LOCATION_SHARE_BASE_NAME' ) ) {
	define( 'WP_LOCATION_SHARE_BASE_NAME', plugin_basename( __FILE__ ) );
}

/*
 * Plugin Version
 */
if( !defined( 'WP_LOCATION_SHARE_VERSION' ) ) {
	define( 'WP_LOCATION_SHARE_VERSION', '1.0' );
}
