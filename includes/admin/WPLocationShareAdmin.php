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

			$wpls_admin_localize_string = array(
				'wpls_geolocation_service_failed' => __( 'Geolocation service failed.', 'sharewhere' ),
				'wpls_geolocation_not_supported_browser' => __( "Your browser doesn't support geolocation. We've placed you in Pune, India.", 'sharewhere' ),
				'wpls_no_result_found' => __( 'No results found', 'sharewhere' ),
				'wpls_geocoder_failed' => __( 'Geocoder failed due to:', 'sharewhere' ),
				'wpls_not_determine_location' => __( 'Cannot determine address at this location.', 'sharewhere' ),
				'wpls_autocomplete_no_geometry' => __( "Autocomplete's returned place contains no geometry", 'sharewhere' ),
				'wpls_full_address_confirmation' => __( "You haven't selected 'Location Type'.\n\nAre you sure you want to go with 'Full Address'?", 'sharewhere' )
			);

			wp_localize_script( "wpls-admin", 'wpls_admin_strings', $wpls_admin_localize_string );
		}

		/*
		 * Adding location button next to media button
		 */
		public function wpls_add_location_button() {
			$wpls_add_location_button_text = apply_filters( 'wpls_add_location_button_text', __( 'Add Location', 'sharewhere' ) );
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
			} else if( isset( $_GET ) && isset( $_GET[ 'post' ] ) && ( get_post_type( $_GET[ 'post' ] ) == 'page' ) ) {
				$wpls_page_flag = true;
			}

			$wpls_insert_button_text = ( $wpls_page_flag ) ? __( 'Insert into page', 'sharewhere' ) : __( 'Insert into post', 'sharewhere' ) ;
			?>
			<div id="wpls-google-map-container" class="mfp-hide">
				<input id="wpls-map-search" class="wpls-place-search" type="text" placeholder="<?php echo __( 'Search Place', 'sharewhere' ); ?>" />
				<div id="wpls-google-map"></div>
				<div id="wpls-insert-map">
					<select id="wpls-location-type">
						<option value=""><?php _e( "Select Location Type", 'sharewhere' ); ?></option>
						<option value="city"><?php _e( "City", 'sharewhere' ); ?></option>
						<option value="state"><?php _e( "State", 'sharewhere' ); ?></option>
						<option value="country"><?php _e( "Country", 'sharewhere' ); ?></option>
						<option value="city-state"><?php _e( "City + State", 'sharewhere' ); ?></option>
						<option value="city-country"><?php _e( "City + Country", 'sharewhere' ); ?></option>
						<option value="state-country"><?php _e( "State + Country", 'sharewhere' ); ?></option>
						<option value="city-state-country"><?php _e( "City + State + Country", 'sharewhere' ); ?></option>
						<option value="full"><?php _e( "Full Address", 'sharewhere' ); ?></option>
					</select>
					<a href="#" id="wpls-insert-button" class="button button-primary button-large"><?php echo $wpls_insert_button_text; ?></a>
					<input type="hidden" id="wpls-store-location" />
					<input type="hidden" id="wpls-city" />
					<input type="hidden" id="wpls-state" />
					<input type="hidden" id="wpls-country" />
				</div>
			</div>
			<?php
		}
	}

}
