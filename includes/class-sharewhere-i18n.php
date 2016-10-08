<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since           1.0.0
 *
 * @package         ShareWhere
 * @subpackage      ShareWhere / includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since           1.0.0
 *
 * @package         ShareWhere
 * @subpackage      ShareWhere / includes
 */
class ShareWhere_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since       1.0.0
	 *
	 * @access      public
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sharewhere',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
