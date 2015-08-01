<?php

if( !defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'WPLocationShareAdmin' ) ) {

	class WPLocationShareAdmin {

		/*
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'wpls_includes_script_styles' ) );
			add_action( 'media_buttons', array( $this, 'wpls_add_location_button' ), 11 );
		}

		/*
		 * Enqueue Admin scripts & styles
		 */
		public function wpls_includes_script_styles() {
			$suffix = ( function_exists( 'wpls_get_script_style_suffix' ) ) ? wpls_get_script_style_suffix() : '.min';

			wp_enqueue_style( 'wpls-admin', WP_LOCATION_SHARE_URL . 'includes/assets/admin/css/admin.min.css', false, WP_LOCATION_SHARE_VERSION );
		}

		/*
		 * Adding location button next to media button
		 */
		public function wpls_add_location_button() {
			$wpls_add_location_button_text = apply_filters( 'wpls_add_location_button_text', __( 'Add Location', 'wplocation' ) );

			echo '<a href="#" id="wp-location-share-map" class="button">';
			echo '<i class="dashicons dashicons-location"></i>';
			echo $wpls_add_location_button_text;
			echo '</a>';
		}

	}

}
