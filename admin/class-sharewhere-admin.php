<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / admin
 */

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / admin
 */
class ShareWhere_Admin {


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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->suffix      = sw_get_script_style_suffix();
		$this->options     = get_option( 'sharewhere_options', array() );
		$this->api_key     = sw_get_google_map_api_key( $this->options );

		// Enqueue admin styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'includes_styles' ) );

		// Enqueue admin scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'includes_scripts' ) );

		// Add location button in the new post/page or edit post/page pages.
		add_action( 'media_buttons', array( $this, 'add_location_button' ), 11 );

		// Add google map container in the admin footer.
		add_action( 'admin_footer', array( $this, 'google_map_container' ) );

		// Add ShareWhere submenu to Settings.
		add_action( 'admin_menu', array( $this, 'add_sharewhere_submenu' ) );

		// Register ShareWhere settings fields.
		add_action( 'admin_init', array( $this, 'register_sharewhere_settings' ) );
	}


	/**
	 * Enqueues style for admin side.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function includes_styles() {

		// If google map api key is not set then return.
		if ( false === $this->api_key ) {
			return;
		}

		wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . "css/sharewhere-admin{$this->suffix}.css", false, $this->version );
		wp_enqueue_style( $this->plugin_name . '-magnific-popup-admin', plugin_dir_url( dirname( __FILE__ ) ) . 'includes/assets/magnific-popup/magnific-popup.css', false, $this->version );
	}


	/**
	 * Enqueues scripts for admin side.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function includes_scripts() {

		// If google map api key is not set then return.
		if ( false === $this->api_key ) {
			return;
		}

		wp_enqueue_script( $this->plugin_name . '-magnific-popup-admin', plugin_dir_url( dirname( __FILE__ ) ) . "includes/assets/magnific-popup/jquery.magnific-popup{$this->suffix}.js", array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . "js/sharewhere-admin{$this->suffix}.js", array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-google-maps-admin', "https://maps.googleapis.com/maps/api/js?key={$this->api_key}&libraries=places" );
		wp_enqueue_script( $this->plugin_name . '-google-jsapi-admin', 'https://www.google.com/jsapi' );

		$sw_admin_strings = array(
			'geolocation_service_failed'        => esc_html__( 'Geolocation service failed.', 'sharewhere' ),
			'geolocation_not_supported_browser' => esc_html__( "Your browser doesn't support geolocation. We've placed you in Pune, India.", 'sharewhere' ),
			'no_result_found'                   => esc_html__( 'No results found', 'sharewhere' ),
			'geocoder_failed'                   => esc_html__( 'Geocoder failed due to:', 'sharewhere' ),
			'not_determine_location'            => esc_html__( 'Cannot determine address at this location.', 'sharewhere' ),
			'auto_complete_no_geometry'         => esc_html__( "Autocomplete's returned place contains no geometry", 'sharewhere' ),
			'full_address_confirmation'         => esc_html__( "You haven't selected 'Location Type'.\n\nAre you sure you want to go with 'Full Address'?", 'sharewhere' ),
		);

		wp_localize_script( $this->plugin_name . '-admin', 'admin_strings', $sw_admin_strings );
	}


	/**
	 * Add location button in add new or edit post/page pages.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function add_location_button() {

		// If google map is not set for WordPress posts, pages then return.
		if ( false === sw_check_google_map_enable( 'wp', $this->options ) ) {
			return;
		}

		$button_text = apply_filters( 'sw_add_location_button_text', __( 'Add Location', 'sharewhere' ) );
		?>
		<a href="#sw-google-map-container" title="<?php echo esc_attr( $button_text ); ?>" id="sw-map" class="button">
			<i class="dashicons dashicons-location"></i>
			<?php echo esc_html( $button_text ); ?>
		</a>
		<?php
	}


	/**
	 * Add google map container in the admin footer.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function google_map_container() {

		$page_flag = false;
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
		$post_id   = filter_input( INPUT_GET, 'post',      FILTER_VALIDATE_INT );

		if ( true === in_array( 'page', array( $post_type, get_post_type( $post_id ) ), true ) ) {
			$page_flag = true;
		}

		$button_text = true === $page_flag ? __( 'Insert into page', 'sharewhere' ) : __( 'Insert into post', 'sharewhere' );
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
				<a href="#" id="sw-insert-button" class="button button-primary button-large">
					<?php echo esc_html( $button_text ); ?>
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
	 * Add ShareWhere submenu to Settings.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function add_sharewhere_submenu() {

		add_options_page(
			__( 'ShareWhere', 'sharewhere' ),
			__( 'ShareWhere', 'sharewhere' ),
			'manage_options',
			'sharewhere',
			array( $this, 'sharewhere_settings' )
		);
	}


	/**
	 * Add ShareWhere settings fields.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function sharewhere_settings() {

		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'ShareWhere Settings', 'sharewhere' ); ?></h2>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'sharewhere' );
				do_settings_sections( 'sharewhere' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}


	/**
	 * Register ShareWhere settings.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function register_sharewhere_settings() {

		register_setting(
			'sharewhere',              // Option group
			'sharewhere_options',      // Option name
			array( $this, 'sanitize' ) // Sanitize.
		);

		add_settings_section(
			'sharewhere_section',                 // ID
			'',                                   // Title
			array( $this, 'print_section_info' ), // Callback
			'sharewhere'                          // Page.
		);

		add_settings_field(
			'google_map_api_key',                          // ID
			__( 'Google map API key', 'sharewhere' ),      // Title
			array( $this, 'google_map_api_key_callback' ), // Callback
			'sharewhere',                                  // Page
			'sharewhere_section'                           // Section.
		);

		add_settings_field(
			'google_map_components',                          // ID
			__( 'Components', 'sharewhere' ),                 // Title
			array( $this, 'google_map_components_callback' ), // Callback
			'sharewhere',                                     // Page
			'sharewhere_section'                              // Section.
		);
	}


	/**
	 * Sanitize each setting field as needed
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @param array $input Contains all settings fields as array keys.
	 *
	 * @return array
	 */
	public function sanitize( $input ) {

		$new_input = array();

		if ( isset( $input['google_map_api_key'] ) ) {

			$new_input['google_map_api_key'] = sanitize_text_field( $input['google_map_api_key'] );
		}

		if ( ! empty( $input['google_map_components'] ) && is_array( $input['google_map_components'] ) ) {

			$new_input['google_map_components'] = array_keys( $input['google_map_components'] );
		}

		return $new_input;
	}


	/**
	 * Print the Section text
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function print_section_info() {
		echo '';
	}


	/**
	 * Get google map api key field.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function google_map_api_key_callback() {

		printf( '<input type="text" id="google_map_api_key" name="sharewhere_options[google_map_api_key]" value="%s" />', isset( $this->options['google_map_api_key'] ) ? esc_attr( $this->options['google_map_api_key'] ) : '' );
	}


	/**
	 * Get components on which map will be enabled.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 */
	public function google_map_components_callback() {

		// Components on which map will be enabled.
		$components = array(
			'wp'          => __( 'WordPress ( Posts, Pages, etc. )', 'sharewhere' ),
			'wp_comments' => __( 'WordPress Comments', 'sharewhere' ),
			'bp_activity' => __( 'BuddyPress Activity', 'sharewhere' ),
		);

		// Checking saved components.
		$saved_components = ! empty( $this->options['google_map_components'] ) ? $this->options['google_map_components'] : array();
		?>
		<table class="sharewhere-components">
			<tr>
				<?php
				$i = 1;

				foreach ( $components as $key => $value ) :

					?>
					<td>
						<input type="checkbox" value="1" id="google_map_components_<?php echo esc_attr( $key ); ?>" name="sharewhere_options[google_map_components][<?php echo esc_attr( $key ); ?>]" <?php checked( in_array( $key, $saved_components, true ) ); ?> />&nbsp;
						<label for="google_map_components_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ) ?></label>
					</td>
					<?php
					if ( 0 === ( $i % 3 ) ) :

						?>
						</tr>
						<tr>
						<?php

					endif;

					$i ++;
				endforeach;
				?>
			</tr>
		</table>
		<?php
	}
}
