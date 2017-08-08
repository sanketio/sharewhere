<?php
/**
 * The public-facing functionality of the plugin for BuddyPress.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / public
 */

/**
 * The public-facing functionality of the plugin for BuddyPress.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / public
 */
class ShareWhere_BuddyPress {


	/**
	 * Holds the values to be used in the fields callbacks.
	 *
	 * @since 1.3.0
	 * @access private
	 * @var array $options Option values.
	 */
	private $options;


	/**
	 * Google map API key.
	 *
	 * @since 1.3.0
	 * @access private
	 * @var string $api_key Google map API key.
	 */
	private $api_key;


	/**
	 * Initialize the class.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function __construct() {

		$this->options = get_option( 'sharewhere_options', array() );
		$this->api_key = sw_get_google_map_api_key( $this->options );

		add_action( 'bp_activity_post_form_options', array( $this, 'add_map_button_buddypress' ), 1 );
		add_action( 'bp_activity_allowed_tags', array( $this, 'modify_allowed_tags' ) );
	}


	/**
	 * Add map button to include location in BuddyPress activity.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function add_map_button_buddypress() {

		if ( false === sw_check_google_map_enable( 'bp_activity', $this->options ) ) {
			return;
		}

		$add_location_button = apply_filters( 'sw_add_location_button_text', __( 'Add Location', 'sharewhere' ) );
		?>
		<a href="#sw-google-map-container" title="<?php echo esc_attr( $add_location_button ); ?>" id="sw-map" class="button">
			<i class="dashicons dashicons-location"></i>
		</a>
		<?php
	}


	/**
	 * Modify allowed tags for BuddyPress activity.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @param array $allowed_tags Allowed tags array.
	 *
	 * @return mixed
	 */
	public function modify_allowed_tags( $allowed_tags ) {

		$allowed_tags['a']['target'] = array();

		return $allowed_tags;
	}
}
