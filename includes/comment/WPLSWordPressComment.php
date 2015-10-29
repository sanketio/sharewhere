<?php

/**
 * Created by PhpStorm.
 * User: sanket
 * Date: 24/8/15
 * Time: 9:26 PM
 */
class WPLSWordPressComment {

	/*
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'comment_form_field_comment', array( $this, 'wpls_wp_comment_location_button' ) );
	}

	/*
	 * Adding 'Add Location' button for WordPress comment
	 */
	public function wpls_wp_comment_location_button( $args ) {
		$wpls_add_location_button_text = apply_filters( 'wpls_add_location_button_text', __( 'Add Location', 'sharewhere' ) );
		$wpls_add_location = '<p class="wpls-add-location-button">
				<a href="#wpls-google-map-container" title="'. $wpls_add_location_button_text . '" id="wp-location-share-map" class="button">
					<i class="dashicons dashicons-location"></i>
					' . $wpls_add_location_button_text . '
				</a>
			</p>';

		return $args . $wpls_add_location;
	}

}