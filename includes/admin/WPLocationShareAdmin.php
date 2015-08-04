<?php

if( !defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'WPLocationShareAdmin' ) ) {

	class WPLocationShareAdmin {

		/*
		 * Variable declaration
		 */
		var $suffix;

		/*
		 * Constructor
		 */
		public function __construct() {
			$this->suffix = ( function_exists( 'wpls_get_script_style_suffix' ) ) ? wpls_get_script_style_suffix() : '.min';

			add_action( 'admin_enqueue_scripts', array( $this, 'wpls_includes_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wpls_includes_scripts' ) );
			add_action( 'media_buttons', array( $this, 'wpls_add_location_button' ), 11 );
			add_action( 'admin_footer',  array( $this, 'wpls_google_map_div' ) );
		}

		/*
		 * Enqueue Admin Styles
		 */
		public function wpls_includes_styles() {
			wp_enqueue_style( 'wpls-admin', WP_LOCATION_SHARE_URL . 'includes/assets/admin/css/wpls-admin' . $this->suffix . '.css', false, WP_LOCATION_SHARE_VERSION );
		}

		/*
		 * Enqueue Admin Scripts
		 */
		public function wpls_includes_scripts() {
			wp_enqueue_script( 'wpls-admin', WP_LOCATION_SHARE_URL . 'includes/assets/admin/js/wpls-admin' . $this->suffix . '.js', array(), WP_LOCATION_SHARE_VERSION );
			wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBuU_0_uLMnFM-2oWod_fzC0atPZj7dHlU&sensor=false' );
			wp_enqueue_script( 'google-jsapi', 'https://www.google.com/jsapi' );
		}

		/*
		 * Adding location button next to media button
		 */
		public function wpls_add_location_button() {
			$wpls_add_location_button_text = apply_filters( 'wpls_add_location_button_text', __( 'Add Location', 'wplocation' ) );
			?>
			<a href="#TB_inline?width=752&inlineId=wpls-google-map-popup" title="<?php echo $wpls_add_location_button_text; ?>" id="wp-location-share-map" class="thickbox button">
				<i class="dashicons dashicons-location"></i>
				<?php echo $wpls_add_location_button_text; ?>
			</a>
			<?php
		}

		/*
		 * Google map div for modal popup
		 */
		public function wpls_google_map_div() {
			?>
			<div id="wpls-google-map-popup" style="display: none;">
				<div class="wrap" id="wpls-google-map">Hi</div>
			</div>
			<?php
		}

	}

}
