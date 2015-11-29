<?php
/**
 * HackGov_Votes Class.
 *
 * @class       HackGov_Votes
 * @version		1.0
 * @author lafif <lafif@astahdziq.in>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * HackGov_Votes class.
 */
class HackGov_Votes {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();
		add_shortcode( 'hackgov_votes', array($this, 'shortcode_callback') );

		add_action('wp_ajax_votes', array($this, 'hackgov_ajax_votes') );

		// add_action( 'hackgov_success_vote_up', array($this, 'send_notifications_vote_up'), 10, 2 );
		// add_action( 'hackgov_success_vote_down', array($this, 'send_notifications_vote_down'), 10, 2 );
	}

	public function hackgov_ajax_votes(){

		if ( !isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'votes_nonce' ) ){
			echo json_encode(array('status' => 'error', 'message' => 'Nonce not match'));
			die();
		}

		$data = array();
		$post_id = $_POST['post_id'];
		$user_id = $_POST['user_id'];
		$type 	 = $_POST['type'];

 		$vote_up_users = get_post_meta( $post_id, '_vote_up_users', true );
 		$vote_down_users = get_post_meta( $post_id, '_vote_down_users', true );

		if(empty($vote_up_users))
			$vote_up_users= array();
				
		if(empty($vote_down_users))
			$vote_down_users = array();

		$data['is_voted_up'] = '';
		$data['is_voted_down'] = '';

		switch ($type) {
			case 'voteup':
				
				if(isset($vote_up_users[$user_id])){
					unset($vote_up_users[$user_id]);

					$data['status'] = 'success';
					$data['count'] = count($vote_up_users);
				} else {

					// check 
					if(isset($vote_down_users[$user_id])){
						unset($vote_down_users[$user_id]);
						update_post_meta( $post_id, '_vote_down_users', $vote_down_users );
					}

					$vote_up_users[$user_id] = current_time( 'mysql' );
					update_post_meta( $post_id, '_vote_up_users', $vote_up_users );

					// add hooks
					do_action( 'hackgov_success_vote_up', $post_id, $user_id );

					$data['status'] = 'success';
					// $data['count'] = count($vote_up_users);
					$data['is_voted_up'] = true;
				}

				break;

			case 'votedown':

				if(isset($vote_down_users[$user_id])){

					unset($vote_down_users[$user_id]);
					$data['status'] = 'success';
					// $data['count'] = count($vote_down_users);
				} else {

					// check 
					if(isset($vote_up_users[$user_id])){
						unset($vote_up_users[$user_id]);
						update_post_meta( $post_id, '_vote_up_users', $vote_up_users );
						$data['is_voted_up'] = false;
					}

					$vote_down_users[$user_id] = current_time( 'mysql' );
					update_post_meta( $post_id, '_vote_down_users', $vote_down_users );

					// add hooks
					do_action( 'hackgov_success_vote_down', $post_id, $user_id );

					$data['status'] = 'success';
					// $data['count'] = count($vote_down_users);
					$data['is_voted_down'] = true;
				}

				break;
		}


		$data['voteup_val'] = count($vote_up_users);
		$data['votedown_val'] = count($vote_down_users);

		update_post_meta( $post_id, 'total_vote_up', count($vote_up_users) );
		update_post_meta( $post_id, 'total_vote_down', count($vote_down_users) );
		$popularity = count($vote_up_users) - count($vote_down_users);

		update_post_meta( $post_id, 'popularity', $popularity );

		echo json_encode($data);
		die();
	}

	public function shortcode_callback($atts){
		
		wp_enqueue_script( 'hackgov' );
		wp_localize_script( 'hackgov', 'votes_obj', 
			array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'user_id' => get_current_user_id(),
				'login_url' => home_url( 'login' ),
				'nonce' => wp_create_nonce( 'votes_nonce' ),
			) 
		);

		$attr = shortcode_atts( array(
			'id' => '',
		), $atts );

		$id = esc_attr($attr['id']);

		if(empty($id))
			return;

		$is_voted_up = '';
		$is_voted_down = '';
		$user_id = get_current_user_id();

		$vote_up_users = get_post_meta( $id, '_vote_up_users', true );
		if(empty($vote_up_users)){
			$vote_up_users= array();
		} else {
			if(isset($vote_up_users[$user_id]))
				$is_voted_up = 'voted';
		}

		$vote_down_users = get_post_meta( $id, '_vote_down_users', true );
		if(empty($vote_down_users)){
			$vote_down_users = array();
		} else {
			if(isset($vote_down_users[$user_id]))
				$is_voted_down = 'voted';
		}

		$vote_up_val = count($vote_up_users);
		$vote_down_val = count($vote_down_users);

		$btns = array();
		// $btns[] = '<div class="vote-container">';
		$btns[] = sprintf('<li><a href="javascript:;" data-id="%d" data-type="voteup" class="voteup hackgov-votes %s"><span class="icon-thumb-up"></span><span class="vote-val">%d</span></a></li>', $id, $is_voted_up, $vote_up_val );
		$btns[] = sprintf('<li><a href="javascript:;" data-id="%d" data-type="votedown" class="votedown hackgov-votes %s"><span class="icon-thumb-down"></span><span class="vote-val">%d</span></a></li>', $id, $is_voted_down, $vote_down_val );
		// $btns[] = '</div>';

		return implode("\n", $btns);
	}

	// public function send_notifications_vote_up($post_id, $user_id){
	// 	$vote_up_users = get_post_meta( $post_id, '_vote_up_users', true );
	// 	if(!empty($vote_up_users)){
			
	// 	}
	// }

	// public function send_notifications_vote_down($post_id, $user_id){
	// 	$vote_down_users = get_post_meta( $post_id, '_vote_down_users', true );
	// }

	public function includes(){
		
	}

}

return new HackGov_Votes();