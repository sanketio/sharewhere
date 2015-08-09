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
			wp_enqueue_style( 'wpls-magnific-popup-admin', WP_LOCATION_SHARE_URL . 'includes/assets/lib/magnific-popup/magnific-popup.css', false, WP_LOCATION_SHARE_VERSION );
		}

		/*
		 * Enqueue Admin Scripts
		 */
		public function wpls_includes_scripts() {
			wp_enqueue_script( 'wpls-magnific-popup-admin', WP_LOCATION_SHARE_URL . 'includes/assets/lib/magnific-popup/jquery.magnific-popup' . $this->suffix . '.js', array(), WP_LOCATION_SHARE_VERSION );
			wp_enqueue_script( 'wpls-admin', WP_LOCATION_SHARE_URL . 'includes/assets/admin/js/wpls-admin' . $this->suffix . '.js', array(), WP_LOCATION_SHARE_VERSION );
			wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false' );
			wp_enqueue_script( 'google-jsapi', 'https://www.google.com/jsapi' );
		}

		/*
		 * Adding location button next to media button
		 */
		public function wpls_add_location_button() {
			$wpls_add_location_button_text = apply_filters( 'wpls_add_location_button_text', __( 'Add Location', 'wplocation' ) );
			?>
			<a href="#wpls-google-map-container" title="<?php echo $wpls_add_location_button_text; ?>" id="wp-location-share-map" class="button">
				<i class="dashicons dashicons-location"></i>
				<?php echo $wpls_add_location_button_text; ?>
			</a>
			<?php
		}

		/*
		 * Google map div for modal popup
		 */
		public function wpls_google_map_div() {
			$wpls_page_flag = false;

			if( isset( $_GET ) && isset( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] == 'page' ) {
				$wpls_page_flag = true;
			}

			$wpls_insert_button_text = ( $wpls_page_flag ) ? __( 'Insert into page', 'wplocation' ) : __( 'Insert into post', 'wplocation' ) ;
			?>
			<div id="wpls-google-map-container" class="mfp-hide">
				<input id="wpls-map-search" class="wpls-place-search" type="text" placeholder="<?php echo __( 'Search Place', 'wplocation' ); ?>" />
				<div id="wpls-google-map"></div>
				<div id="wpls-insert-map">
					<a href="#" id="wpls-insert-button" class="button button-primary button-large"><?php echo $wpls_insert_button_text; ?></a>
					<input type="hidden" id="wpls-store-location" />
				</div>
			</div>
			<?php
		}
	}

}
