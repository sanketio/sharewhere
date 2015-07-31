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
			add_action( 'media_buttons', array( $this, 'add_my_media_button' ), 11 );
		}

		public function add_my_media_button() {
			echo '<a href="#" id="wp-location-share-map" class="button"><i class="dashicons dashicons-location"></i>' . __( 'Add Location', WP_LOCATION_SHARE_TEXTDOMAIN ) . '</a>';
		}

	}

}
