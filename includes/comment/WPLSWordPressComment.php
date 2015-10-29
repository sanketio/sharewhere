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

		add_action( 'wp_footer',  array( $this, 'wpls_google_map_div' ) );
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

	/*
		 * Google map div for modal popup
		 */
	public function wpls_google_map_div() {
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
				<a href="#" id="wpls-insert-button" class="button button-primary button-large"><?php echo __( 'Insert into comment', 'sharewhere' ); ?></a>
				<input type="hidden" id="wpls-store-location" />
				<input type="hidden" id="wpls-city" />
				<input type="hidden" id="wpls-state" />
				<input type="hidden" id="wpls-country" />
			</div>
		</div>
		<?php
	}

}