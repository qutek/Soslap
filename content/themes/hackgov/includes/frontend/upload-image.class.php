<?php

class Tj_Upload {

	static function go() {
		add_action( 'init', array( __CLASS__, 'check_and_enqueue' ) );

		// add_action ('init', array( __CLASS__, 'add_role_caps'));
	}

	// static function add_role_caps() {
	//     global $wp_roles;
	//     $role = get_role('contributor');
	//     $role->add_cap('delete_posts');
	// 	$role->remove_cap('delete_published_posts');
	// 	$role->add_cap('edit_posts');
	// 	$role->remove_cap('edit_published_posts');
	// 	$role->remove_cap('publish_posts');
	// 	$role->add_cap('read');
	// 	$role->remove_cap('upload_files');
	// }

	static function check_and_enqueue() {
		// if ( current_user_can( 'upload_files' ) && current_user_can( 'publish_posts' ) ) {
			add_action( 'wp_ajax_nopriv_tj_upload_image', array( __CLASS__, 'wp_ajax_tj_upload_image' ) );
			add_action( 'wp_ajax_tj_upload_image', array( __CLASS__, 'wp_ajax_tj_upload_image' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'wp_enqueue_scripts' ) );
		// }
	}

	static function wp_ajax_tj_upload_image() {
		global $content_width;

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'tj-upload-image_nonce' ) ) {
			wp_send_json( array( 'error' => __( 'Invalid or expired nonce.', 'funkmo' ) ) );
		}

		// if ( ! current_user_can( 'upload_files' ) || ! current_user_can( 'publish_posts' ) ) {
			// wp_send_json( array( 'error' => _x( 'Are you the Keymaster? I am The Gatekeeper.', 'Ghostbusters Reference', 'funkmo' ) ) );
		// }

		// $_POST['action'] = 'wp_handle_upload';

		// $image_id_arr    = array();
		// $image_error_arr = array();
		// $post_id_arr     = array();

		// $i = 0;

		// while ( isset( $_FILES['image_' . $i ] ) ) {

		// 	// Create attachment for the image.
		// 	$image_id = media_handle_upload( "image_$i", 0 );
		// 	$image_url = wp_get_attachment_image_src($image_id);

		// 	if ( is_wp_error( $image_id ) ) {
		// 		$error = array( $image_id, $image_id->get_error_message() );
		// 		array_push( $image_error_arr, $error );
		// 	} else {
		// 		array_push( $image_id_arr, $image_id );
		// 	}

		// 	$i++;

		// }

		// if ( $image_id_arr ) {

		// 	foreach ( $image_id_arr as $image_id ) {
		// 		$post = get_default_post_to_edit();

		// 		$meta = wp_get_attachment_metadata( $image_id );
		// 		$image_html = get_image_send_to_editor( $image_id, $meta['image_meta']['caption'], '', 'none', wp_get_attachment_url( $image_id ), '', 'full' );

		// 		$post->post_title    = basename( get_attached_file( $image_id ) );
		// 		$post->post_content  = $image_html;
		// 		$post->post_category = array();

		// 		$post_id = wp_insert_post( $post );

		// 		wp_update_post( array(
		// 			'ID'          => $image_id,
		// 			'post_parent' => $post_id
		// 		) );

		// 		set_post_format( $post_id, 'image' );
		// 		set_post_thumbnail( $post_id, $image_id );
		// 		wp_publish_post( $post_id );

		// 		array_push( $post_id_arr, $post_id );
		// 	}

		// }
		

		// Create attachment for the image.
		// $user_id = $_REQUEST['user_id'];
		$image_id = media_handle_upload( "image_0", 0 );
		// update_user_meta( $user_id, 'img_profile', $image_id );
		$image_url = wp_get_attachment_image_src($image_id);

		$data = array(
			'url'             => $image_url[0],
			'id_image'        => $image_id,
			'user_id'        => $user_id,
		);

		if ( $image_error_arr ) {
			$data['error'] = '';
			foreach ( $image_error_arr as $error ) {
				$data['error'] .= $error[1] . "\n";
			}
		}

		wp_send_json( $data );
	}


	static function wp_enqueue_scripts() {
		wp_enqueue_script( 'upload-image', HackGov()->theme_url() . '/assets/js/upload.js', array( 'jquery' ) );

		$options = array(
			'nonce'   => wp_create_nonce( 'tj-upload-image_nonce' ),
			'ajaxurl' => admin_url( 'admin-ajax.php?action=tj_upload_image' ),
			'user_id' => get_current_user_id(),
			'preloader' => HackGov()->theme_url() . '/assets/images/hourglass.gif',
			'labels'  => array(
				'dragging'      => __( 'Drop images to upload', 'funkmo' ),
				'uploading'     => __( 'Uploading…', 'funkmo' ),
				'processing'    => __( 'Processing…', 'funkmo' ),
				'unsupported'   => __( "Sorry, your browser isn't supported. Upgrade at browsehappy.com.", 'funkmo' ),
				'invalidUpload' => __( 'Only images can be uploaded here.', 'funkmo' ),
				'error'         => __( "Your upload didn't complete; try again later or cross your fingers and try again right now.", 'funkmo' ),
			)
		);

		wp_localize_script( 'upload-image', 'Tj_Upload_Opt', $options );

	}

}
Tj_Upload::go();
