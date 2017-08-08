<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / public
 */
class ShareWhere_Public {


	/**
	 * The ID of this plugin.
	 *
	 * @since 1.3.0
	 *
	 * @access private
	 *
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since 1.3.0
	 *
	 * @access private
	 *
	 * @var string $version The current version of this plugin.
	 */
	private $version;


	/**
	 * The suffix for CSS/JS files.
	 *
	 * @since 1.3.0
	 *
	 * @access private
	 *
	 * @var string $suffix The suffix for CSS/JS files.
	 */
	private $suffix;


	/**
	 * Holds the values to be used in the fields callbacks.
	 *
	 * @since 1.3.0
	 *
	 * @access private
	 *
	 * @var array $options Option values.
	 */
	private $options;


	/**
	 * Google map API key.
	 *
	 * @since 1.3.0
	 *
	 * @access private
	 *
	 * @var string $api_key Google map API key.
	 */
	private $api_key;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->suffix      = sw_get_script_style_suffix();
		$this->options     = get_option( 'sharewhere_options', array() );
		$this->api_key     = sw_get_google_map_api_key( $this->options );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'google_map_container' ) );
		add_filter( 'comment_form_field_comment', array( $this, 'comment_location_button' ) );
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function enqueue_styles() {

		if ( false === $this->api_key ) {
			return;
		}

		wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . "css/sharewhere-public{$this->suffix}.css", false, $this->version );
		wp_enqueue_style( $this->plugin_name . '-magnific-popup-public', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/assets/magnific-popup/magnific-popup.css', false, $this->version );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function enqueue_scripts() {

		if ( false === $this->api_key ) {
			return;
		}

		wp_enqueue_script( $this->plugin_name . '-magnific-popup-public', plugin_dir_url( dirname( __FILE__ ) ) . "includes/assets/magnific-popup/jquery.magnific-popup{$this->suffix}.js", array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . "js/sharewhere-public{$this->suffix}.js", array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-google-maps-public', "https://maps.googleapis.com/maps/api/js?key={$this->api_key}&libraries=places" );
		wp_enqueue_script( $this->plugin_name . '-google-jsapi-public', 'https://www.google.com/jsapi' );

		$sw_public_strings = array(
			'geolocation_service_failed'        => esc_html__( 'Geolocation service failed.', 'sharewhere' ),
			'geolocation_not_supported_browser' => esc_html__( "Your browser doesn't support geolocation. We've placed you in Pune, India.", 'sharewhere' ),
			'no_result_found'                   => esc_html__( 'No results found', 'sharewhere' ),
			'geocoder_failed'                   => esc_html__( 'Geocoder failed due to:', 'sharewhere' ),
			'not_determine_location'            => esc_html__( 'Cannot determine address at this location.', 'sharewhere' ),
			'auto_complete_no_geometry'         => esc_html__( "Autocomplete's returned place contains no geometry", 'sharewhere' ),
			'full_address_confirmation'         => esc_html__( "You haven't selected 'Location Type'.\n\nAre you sure you want to go with 'Full Address'?", 'sharewhere' ),
		);

		wp_localize_script( $this->plugin_name . '-public', 'public_strings', $sw_public_strings );
	}


	/**
	 * Add google map container in the admin footer.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function google_map_container() {

		?>
		<div id="sw-google-map-container" class="mfp-hide">
			<input id="sw-map-search" class="sw-place-search" type="text" placeholder="<?php esc_attr_e( 'Search Place', 'sharewhere' ); ?>" />
			<div id="sw-google-map"></div>
			<div id="sw-insert-map">
				<select id="sw-location-type">
					<option value=""><?php esc_html_e( 'Select Location Type', 'sharewhere' ); ?></option>
					<option value="city"><?php esc_html_e( 'City', 'sharewhere' ); ?></option>
					<option value="state"><?php esc_html_e( 'State', 'sharewhere' ); ?></option>
					<option value="country"><?php esc_html_e( 'Country', 'sharewhere' ); ?></option>
					<option value="city-state"><?php esc_html_e( 'City + State', 'sharewhere' ); ?></option>
					<option value="city-country"><?php esc_html_e( 'City + Country', 'sharewhere' ); ?></option>
					<option value="state-country"><?php esc_html_e( 'State + Country', 'sharewhere' ); ?></option>
					<option value="city-state-country"><?php esc_html_e( 'City + State + Country', 'sharewhere' ); ?></option>
					<option value="full"><?php esc_html_e( 'Full Address', 'sharewhere' ); ?></option>
				</select>
				<a href="" id="sw-insert-button" class="button button-primary button-large">
					<?php esc_html_e( 'Insert', 'sharewhere' ); ?>
				</a>
				<input type="hidden" id="sw-store-location" />
				<input type="hidden" id="sw-city" />
				<input type="hidden" id="sw-state" />
				<input type="hidden" id="sw-country" />
			</div>
		</div>
		<?php
	}


	/**
	 * Add location button for WordPress comments.
	 *
	 * @since 1.3.0
	 *
	 * @param string $field Comment form as a string.
	 *
	 * @return string
	 */
	public function comment_location_button( $field ) {

		if ( false === sw_check_google_map_enable( 'wp_comments', $this->options ) ) {
			return $field;
		}

		$add_location_button_text = apply_filters( 'sw_add_location_button_text', __( 'Add Location', 'sharewhere' ) );
		$add_location             = '<p class="sw-add-location-button">
                                    <a href="#sw-google-map-container" title="' . esc_attr( $add_location_button_text ) . '" id="sw-map" class="button">
                                    <i class="dashicons dashicons-location"></i>' . esc_html( $add_location_button_text ) . '</a>
                                    </p>';

		return $field . $add_location;
	}
}
