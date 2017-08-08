<?php
/**
 * The file that defines the core plugin class
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-facing site hooks and public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current version of the plugin.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / includes
 */
class ShareWhere {


	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 1.3.0
	 *
	 * @access protected
	 *
	 * @var string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;


	/**
	 * The current version of the plugin.
	 *
	 * @since 1.3.0
	 *
	 * @access protected
	 *
	 * @var string $version The current version of the plugin.
	 */
	protected $version;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin & public-facing side of the site.
	 *
	 * @since 1.3.0
	 *
	 * @access  public
	 */
	public function __construct() {

		$this->plugin_name = 'sharewhere';
		$this->version     = '1.3.0';

		$this->load_dependencies();
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - ShareWhere_i18n.  Defines internationalization functionality.
	 * - ShareWhere_Admin  Defines all hooks for the admin area.
	 * - ShareWhere_Public Defines all hooks for the public facing side.
	 *
	 * Create an instance of the loader which will be used to register the hooks with WordPress.
	 *
	 * @since 1.2.0
	 *
	 * @access private
	 */
	private function load_dependencies() {

		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/sharewhere-functions.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sharewhere-i18n.php';

		new ShareWhere_i18n();

		// The class responsible for defining admin-specific functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sharewhere-admin.php';

		new ShareWhere_Admin( $this->get_plugin_name(), $this->get_version() );

		// The class responsible for defining public-specific functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sharewhere-public.php';

		new ShareWhere_Public( $this->get_plugin_name(), $this->get_version() );

		// The class responsible for defining public-specific functionality of the plugin for BuddyPress.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sharewhere-buddypress.php';

		new ShareWhere_BuddyPress();
	}


	/**
	 * The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {

		return $this->plugin_name;
	}


	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string The version number of the plugin.
	 */
	public function get_version() {

		return $this->version;
	}
}
