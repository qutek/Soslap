<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'HackGov' ) ) :

/**
 * Main HackGov Class
 *
 * @class HackGov
 * @version	1.0
 */
final class HackGov {

	/**
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * @var HackGov The single instance of the class
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Main HackGov Instance
	 *
	 * Ensures only one instance of HackGov is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 * @return HackGov - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * HackGov Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'hackgov_loaded' );
	}

	/**
	 * Hook into actions and filters
	 * @since  2.3
	 */
	private function init_hooks() {

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'init' ), 0 );

		register_uninstall_hook( __FILE__, 'uninstall' );
	}

	/**
	 * All install stuff
	 * @return [type] [description]
	 */
	public function install() {
	
		do_action( 'on_hackgov_install' );
	}

	/**
	 * All uninstall stuff
	 * @return [type] [description]
	 */
	public function uninstall() {

		do_action( 'on_hackgov_uninstall' );
	}

	/**
	 * Init HackGov when WordPress Initialises.
	 */
	public function init() {
		// Before init action
		do_action( 'before_hackgov_init' );

		$this->options = get_option( 'hackgov' );

		// register all scripts
		$this->register_scripts();

		// Init action
		do_action( 'after_hackgov_init' );
	}

	/**
	 * Register all scripts to used on our pages
	 * @return [type] [description]
	 */
	public function register_scripts(){

		if ( $this->is_request( 'admin' ) ){
			wp_register_script( 'hackgov-admin', plugins_url( '/includes/admin/assets/js/hackgov-admin.js', __FILE__ ), array('jquery'), '', true );
		}

		if ( $this->is_request( 'frontend' ) ){
			// wp_register_style( 'hackgov', plugins_url( '/assets/css/hackgov.css', __FILE__ ) );
			// wp_register_script( 'hackgov', plugins_url( '/assets/js/hackgov.js', __FILE__ ), array('jquery'), '', true );
			
			do_action( 'after_hackgov_register_main_assets' );
			// $hackgov_object_vars = apply_filters('hackgov_object_vars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			// wp_localize_script( 'hackgov', 'hackgov_obj', $hackgov_object_vars );
			
			//todo use custom style
			// wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		}
 	}

	/**
	 * Define HackGov Constants
	 */
	private function define_constants() {

		$this->define( 'HACKGOV_PLUGIN_FILE', __FILE__ );
		$this->define( 'HACKGOV_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'HACKGOV_VERSION', $this->version );
		// $this->define( 'CUZTOM_URL', $this->plugin_url() . '/includes/cuztom/' );
		// $this->define( 'CUZTOM_DIR', $this->plugin_path() . '/includes/cuztom/' );
	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	public function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		// all public includes

		if ( $this->is_request( 'admin' ) ) {
			// include_once( 'includes/admin/admin.class.php' );
		}

		if ( $this->is_request( 'ajax' ) ) {
			// include_once( 'includes/ajax/..*.php' );
		}

		if ( $this->is_request( 'frontend' ) ) {
			include_once( 'includes/upload-image.class.php' );
			// include_once( 'includes/virtual-page.class.php' );
		}
	}

	/**
	 * Get the plugin url.
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get Ajax URL.
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	public function get_options($optname=''){
		$hackgov_opt = $this->options;

		if(!empty($optname))
			return (isset($hackgov_opt[$optname])) ? $hackgov_opt[$optname] : false;

		return (object) $hackgov_opt;
	}

}

endif;

/**
 * Returns the main instance of HackGov to prevent the need to use globals.
 *
 * @since  1.0
 * @return HackGov
 */
function HackGov() {
	return HackGov::instance();
}

// Global for backwards compatibility.
HackGov();