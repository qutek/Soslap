<?php
/**
 * Hackgov_Theme Class.
 *
 * @class       Hackgov_Theme
 * @version		1.0
 * @author lafif <lafif@astahdziq.in>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Hackgov_Theme class.
 */
class Hackgov_Theme {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();

		add_action( 'after_setup_theme', array($this, 'hackgov_setup_theme') );
		add_action( 'widgets_init', array($this, 'hackgov_widget') );
	}

	public function hackgov_setup_theme(){
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on hackgov, use a find and replace
		 * to change 'hackgov' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'hackgov', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'hackgov' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'hackgov_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		$GLOBALS['content_width'] = apply_filters( 'hackgov_content_width', 640 );
	}

	public function hackgov_widget(){
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'hackgov' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

	public function includes(){

		include_once('frontend/pages.class.php');

		// undescores files
		include_once('frontend/custom-header.php');
		include_once('frontend/template-tags.php');
		include_once('frontend/extras.php');
		include_once('frontend/customizer.php');
		include_once('frontend/jetpack.php');
	}

}

return new Hackgov_Theme();