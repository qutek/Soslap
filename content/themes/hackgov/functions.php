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

			// default undescores
			wp_enqueue_style( 'hackgov-style', get_stylesheet_uri() );

			wp_enqueue_script( 'hackgov-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20120206', true );

			wp_enqueue_script( 'hackgov-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20130115', true );

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			// dari sidiq
			wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
			wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/css/animate.css' );
			wp_enqueue_style( 'icomoon', get_template_directory_uri() . '/assets/css/icomoon.css' );
			wp_enqueue_style( 'typography', get_template_directory_uri() . '/assets/css/typography.css' );
			wp_enqueue_style( 'style-default', get_template_directory_uri() . '/assets/css/style.css' );
			wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '', true );

			wp_register_script( 'hackgov', get_template_directory_uri() . '/assets/js/hackgov.js', array('jquery'), '', true );

			wp_register_script( 'map', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places', '', '', true );
			
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
		$this->define( 'CUZTOM_URL', $this->theme_url() . '/includes/cuztom/' );
		$this->define( 'CUZTOM_DIR', $this->theme_path() . '/includes/cuztom/' );
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
		include_once('includes/theme.class.php');
		include_once('includes/post-types.class.php');
		include_once('includes/votes.class.php');
		include_once('includes/share-count.class.php');
		include_once('includes/hackgov.functions.php');

		include_once( 'includes/frontend/upload-image.class.php' );

		if ( $this->is_request( 'admin' ) ) {
			include_once( 'includes/admin/admin.class.php' );
		}

		if ( $this->is_request( 'ajax' ) ) {
			// include_once( 'includes/ajax/..*.php' );
		}

		if ( $this->is_request( 'frontend' ) ) {
			// include_once( 'includes/virtual-page.class.php' );
		}
	}

	/**
	 * Get the theme url.
	 * @return string
	 */
	public function theme_url() {
		return untrailingslashit( get_template_directory_uri() );
	}

	/**
	 * Get the theme path.
	 * @return string
	 */
	public function theme_path() {
		return untrailingslashit( get_template_directory() );
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