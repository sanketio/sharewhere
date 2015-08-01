<?php

if( !defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'WPLocationShare' ) ) {

	class WPLocationShare {

		/*
		 * Constructor
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'wpls_create_admin_object' ) );
			add_action( 'plugins_loaded', array( $this, 'wpls_load_translation' ), 11 );

			// Including functions file
			require_once WP_LOCATION_SHARE_PATH . 'includes/functions/wpls-functions.php';
		}

		/*
		 * Creating WPLocationShareAdmin object
		 */
		public function wpls_create_admin_object() {
			new WPLocationShareAdmin();
		}

		/*
		 * Loading translations
		 */
		public function wpls_load_translation() {
			load_plugin_textdomain( 'wplocation', false, basename( WP_LOCATION_SHARE_PATH ) . '/languages/' );
		}

	}

}
