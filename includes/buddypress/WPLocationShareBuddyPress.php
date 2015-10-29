<?php

/**
 * Created by PhpStorm.
 * User: sanket
 * Date: 29/10/15
 * Time: 11:20 PM
 */
class WPLocationShareBuddyPress {

    public function __construct() {
        add_action( 'bp_activity_post_form_options', array( $this, 'wpls_add_map_button_buddypress' ) );
        add_action( 'bp_activity_allowed_tags', array( $this, 'wpls_add_target_blank_activity' ) );
    }

    public function wpls_add_map_button_buddypress() {
        $wpls_add_location_button_text = apply_filters( 'wpls_add_location_button_text', __( 'Add Location', 'sharewhere' ) );
        ?>
        <a href="#wpls-google-map-container" title="<?php echo $wpls_add_location_button_text; ?>" id="wp-location-share-map" class="button">
            <i class="dashicons dashicons-location"></i>
        </a>
        <?php
    }

    public function wpls_add_target_blank_activity( $allowedtags ) {
        $allowedtags[ 'a' ][ 'target' ] = array();

        return $allowedtags;
    }

}
