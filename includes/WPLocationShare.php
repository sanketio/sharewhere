<?php

if( !defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists( 'WPLocationShare' ) ) {

	class WPLocationShare {

		/*
		 * Variable declaration
		 */
		var $suffix;

		/*
		 * Constructor
		 */
		public function __construct() {
			$this->suffix = ( function_exists( 'wpls_get_script_style_suffix' ) ) ? wpls_get_script_style_suffix() : '.min';

			$this->wpls_load_translation();

			add_action( 'plugins_loaded', array( $this, 'wpls_create_admin_object' ) );
			add_action( 'plugins_loaded', array( $this, 'wpls_load_classes' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'wpls_includes_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wpls_includes_scripts' ), 11 );

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
		 * Creating instances of classes
		 */
		public function wpls_load_classes() {
			$wpls_class_construct = apply_filters( 'wpls_class_construct', array() );

			if( !empty( $wpls_class_construct ) ) {
				foreach( $wpls_class_construct as $key => $class ) {
					new $class();
				}
			}
		}

		/*
		 * Loading translations
		 */
		public function wpls_load_translation() {
			load_plugin_textdomain( 'wpls', false, basename( WP_LOCATION_SHARE_PATH ) . '/languages/' );
		}

		/*
		 * Loading styles for front-end
		 */
		public function wpls_includes_styles() {
			wp_enqueue_style( 'wpls-magnific-popup', WP_LOCATION_SHARE_URL . 'includes/assets/lib/magnific-popup/magnific-popup.css', false, WP_LOCATION_SHARE_VERSION );
			wp_enqueue_style( 'wpls-main', WP_LOCATION_SHARE_URL . 'includes/assets/main/css/wpls' . $this->suffix . '.css', false, WP_LOCATION_SHARE_VERSION );
		}

		/*
		 * Loading scripts file for front-end
		 */
		public function wpls_includes_scripts() {
			wp_enqueue_script( 'wpls-magnific-popup', WP_LOCATION_SHARE_URL . 'includes/assets/lib/magnific-popup/jquery.magnific-popup' . $this->suffix . '.js', array(), WP_LOCATION_SHARE_VERSION );
			wp_enqueue_script( 'wpls-main', WP_LOCATION_SHARE_URL . 'includes/assets/main/js/wpls' . $this->suffix . '.js', array(), WP_LOCATION_SHARE_VERSION );
			wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&sensor=false' );
			wp_enqueue_script( 'google-jsapi', 'https://www.google.com/jsapi' );

			$wpls_main_localize_string = array(
				'wpls_main_geolocation_service_failed' => __( 'Geolocation service failed.', 'sharewhere' ),
				'wpls_main_geolocation_not_supported_browser' => __( "Your browser doesn't support geolocation. We've placed you in Pune, India.", 'sharewhere' ),
				'wpls_main_no_result_found' => __( 'No results found', 'sharewhere' ),
				'wpls_main_geocoder_failed' => __( 'Geocoder failed due to:', 'sharewhere' ),
				'wpls_main_not_determine_location' => __( 'Cannot determine address at this location.', 'sharewhere' ),
				'wpls_main_autocomplete_no_geometry' => __( "Autocomplete's returned place contains no geometry", 'sharewhere' ),
				'wpls_main_full_address_confirmation' => __( "You haven't selected 'Location Type'.\n\nAre you sure you want to go with 'Full Address'?", 'sharewhere' )
			);

			wp_localize_script( "wpls-main", 'wpls_main_strings', $wpls_main_localize_string );
		}

	}

}
