<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since 1.0.0
 *
 * @package ShareWhere
 *
 * @wordpress-plugin
 * Plugin Name: ShareWhere
 * Plugin URI:  https://github.com/sanketio/sharewhere/
 * Description: This plugin shares location.
 * Version:     1.3.0
 * Author:      Pranali, Sanket
 * Author URI:  http://sanketio.in
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: sharewhere
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// The core plugin class that is used to define internationalization, admin-facing site hooks and public-facing site hooks.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-sharewhere.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_sharewhere() {

	new ShareWhere();
}

run_sharewhere();
