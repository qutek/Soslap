<?php
/**
 * HackGov_Front_Actions Class.
 *
 * @class       HackGov_Front_Actions
 * @version		1.0
 * @author lafif <lafif@astahdziq.in>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * HackGov_Front_Actions class.
 */
class HackGov_Front_Actions {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();
		add_action( 'template_redirect', array($this, 'parse_actions') );
	}

	public function parse_actions(){

		if(isset($_GET['short']) && !empty($_GET['short'])){
			wp_redirect( get_permalink( $_GET['short'] ) );
			die();
		}

		if(isset($_POST['hackgov_submit_new'])){
			$this->process_submit_new();
		}
	}

	public function process_submit_new(){

		if (! wp_verify_nonce( $_POST['hackgov_submit_new'], 'hackgov_submit_new_nonce' ) ) 
			wp_die( 'not auth' );

		$defaults = array(
            'featured_image' => '',
            'title' => '',
            'problem' => '',
            'recommendation' => '',
            'priority' => '',
            'category' => '',
            'latitude' => '',
            'longitude' => '',
            'location_address' => ''
        );

        $data = (object) wp_parse_args( $_POST, $defaults );

        // Create post object
		$new_post = array(
		  'post_title'    => $data->title,
		  'post_content'  => $data->problem,
		  'post_status'   => 'pending',
		  'post_author'   => get_current_user_id(),
		  // 'post_category' => array(8,39)
		);


		global $hackgov_messages;
		if(empty($hackgov_messages))
			$hackgov_messages = array();

		// Insert the post into the database
		$post_id = wp_insert_post( $new_post );
		if($post_id){

			// set the post thumbnail
			if(!empty($data->featured_image))
				set_post_thumbnail( $post_id, $data->featured_image );

			// set meta type
			update_post_meta( $post_id, '_post_recommendation', $data->recommendation );
			update_post_meta( $post_id, '_category_priority', $data->priority );
			update_post_meta( $post_id, '_category_category', $data->category );
			update_post_meta( $post_id, '_latitude', $data->latitude );
			update_post_meta( $post_id, '_longitude', $data->longitude );
			update_post_meta( $post_id, '_location', $data->location_address );

			do_action( 'hackgov_success_create_pending_post', $post_id, $data );

			$hackgov_messages[] = array(
				'type' => 'success',
				'message' => __('Konten berhasil di kirim.', 'hackgov')
			);
		} else {
			do_action( 'hackgov_failed_create_pending_post' );

			$hackgov_messages[] = array(
				'type' => 'error',
				'message' => __('Konten gagal di kirim.', 'hackgov')
			);
		}
	}

	public function includes(){
		
	}

}

return new HackGov_Front_Actions();