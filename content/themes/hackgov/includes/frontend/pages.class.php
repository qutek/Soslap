<?php
/**
 * Hackgov_Pages Class.
 *
 * @class       Hackgov_Pages
 * @version		1.0
 * @author lafif <lafif@astahdziq.in>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Hackgov_Pages class.
 */
class Hackgov_Pages {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();
		$this->virtual_pages();

		// add_filter( 'login_url', array($this, 'wplogin_filter'), 10, 2 );
		add_action( 'wsl_render_auth_widget_end', array($this, 'add_register_or_login_form') );
		add_action('wp_ajax_nopriv_register_user', array($this, 'havkgov_register') );

		add_action( 'wp_enqueue_scripts', array($this, 'page_scripts') );
	}

	public function page_scripts(){
		if(is_page('submit')){
			wp_enqueue_script( 'map' );
			wp_enqueue_script( 'location', get_template_directory_uri() . '/assets/js/location.js', array('jquery', 'map'), '', true );
			wp_localize_script( 'location', 'location_obj', 
				array( 
					'img_url' => get_template_directory_uri() . '/assets/images/',
				) 
			);
		}
	}

	public function add_register_or_login_form(){

		wp_enqueue_script( 'hackgov' );
		wp_localize_script( 'hackgov', 'hackgov_obj', 
			array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'redirect_url' => home_url( 'dashboard' ),
			) 
		);

		$form = '<div class="hackgov-registration-form">

			<div id="hackgov-messages" class="alert-box animated start-anim fadeIn" style="display:none;"></div>
			
			<form id="hackgov_register" method="post" class="register">
			   	<p class="form-row form-row-wide">
			      <label for="reg_email">Email <span class="required">*</span></label>
			      <input type="email" class="input-text" name="email" id="reg_email" value="" />
			   	</p>
			   	<p class="form-row form-row-wide">
			      <label for="reg_password">Password <span class="required">*</span></label>
			      <input type="password" class="input-text" name="password" id="reg_password" />
			   	</p>
				'. wp_nonce_field('hackgov_new_user','hackgov_new_user_nonce', true, false ) .'

				<input type="submit" class="button" name="register" value="Masuk" />
			</form>
		</div>';

		echo $form;
	}

	public function havkgov_register(){
		// Verify nonce
	  	if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'hackgov_new_user' ) ) 
	  		die( 'Ooops, terjadi kesalahan.' );
		
		// print_r($_POST);
		$email = sanitize_text_field( $_POST['email'] );
		$pass = sanitize_text_field( $_POST['pass'] );

		// another way
		// $user_id = register_new_user($email, $email); 
		// if ( !is_wp_error($user_id) ) {
		// 	$status = 'success';
		// } else {
		// 	$status = $user_id->get_error_message();
		// }
		
		if( null == username_exists( $email ) ) {

		  	// Generate the password and create the user
		  	$password = (empty($pass)) ? wp_generate_password( 12, false ) : $pass;
		  	$user_id = wp_create_user( $email, $password, $email );
		  	if( !is_wp_error($user_id) ) {
		  		// set auth cookies
		  		wp_set_auth_cookie($user_id);
		  		// To make sure that the login cookies are already set, we double check.
	            foreach($_COOKIE as $name => $value) {
	                
	                // Find the cookie with prefix starting with "wordpress_logged_in_"
	                if(substr($name, 0, strlen('wordpress_logged_in_')) == 'wordpress_logged_in_') {
	                
	                    $status = 'success';
	                    
	                } else {
	                
	                    $status = 'success';
	                        
	                }
	            }
			} else {
				$status = $user_id->get_error_message();
			}
		} else {
			$status = 'Email telah terdaftar!';
		}

  		wp_die($status);
	}

	public function virtual_pages(){

		$pages = array(
			'login' => array(
				'title' => 'Login',
		        'template' =>  get_template_directory() . '/login.php',
			),
			'dashboard' => array(
				'title' => 'Dashboard',
		        'template' =>  get_template_directory() . '/dashboard.php',
			),
			'submit' => array(
				'title' => 'Submit',
		        'template' =>  get_template_directory() . '/submit.php',
			),
			'infrastruktur' => array(
				'title' => 'Infrastruktur',
		        'template' =>  get_template_directory() . '/infrastruktur.php',
			),
			'lingkungan' => array(
				'title' => 'Lingkungan',
		        'template' =>  get_template_directory() . '/lingkungan.php',
			),
		);

		foreach ($pages as $slug => $args) {
			$page_args = array(
		        'slug' => $slug,
		        'title' => $args['title'],
		        'template' =>  $args['template'],
			);

			new FunkmoVirtualPage($page_args);
		}
	}

	// public function wplogin_filter($login_url, $redirect){
	// 	return home_url( '/login/?redirect_to=' . $redirect );
	// }

	public function includes(){
		// virtual page class
		include_once( 'virtual-page.class.php' );
		include_once( 'front-actions.class.php' );
	}

}

return new Hackgov_Pages();