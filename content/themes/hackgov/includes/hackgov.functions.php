<?php
/**
 * Get Votes element
 */
function get_votes($post_id=''){
	echo do_shortcode( '[hackgov_votes id="'.$post_id.'"]' );
}

function get_laporan_count($status='any'){
	
	$user_id = get_current_user_id();

	$laporan = get_laporan($user_id, $status);

	return count($laporan);
}

/**
 * Get All post authored by user
 * @param  string $user_id [description]
 * @return [type]          [description]
 */
function get_laporan($user_id='', $status='any'){
	if(empty($user_id))
		$user_id = get_current_user_id();

	$args = array(
		'author' => $user_id
	);

	switch ($status) {
		case 'publish':
			$args['post_status'] = 'publish';
			break;

		case 'pending':
			$args['post_status'] = 'pending';
			break;

		case 'on-going':
			$args['post_status'] = 'publish';
			$args['meta_query'] = array(
				array(
					'key' => '_category_status',
					'value' => 'on-going',
				)
			);
			break;

		case 'resolved':
			$args['post_status'] = 'publish';
			$args['meta_query'] = array(
				array(
					'key' => '_category_status',
					'value' => 'resolved',
				)
			);
			break;
	}


	$posts = get_posts($args);

	return $posts;
}

/**
 * Get comment for all post by user
 * @param  string $user_id [description]
 * @return [type]          [description]
 */
function hackgov_get_comments($user_id=''){
	if(empty($user_id))
		$user_id = get_current_user_id();

	$args = array(
		'post_author__in' => $user_id,
		'post_status' => 'publish'
	);

	$comments_query = new WP_Comment_Query;
	$comments = $comments_query->query( $args );

	return $comments;
}

/**
 * Get user notifications
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function get_notifications($user_id){
	$notifications = get_user_meta( $user_id, 'notifications', true );
	if(empty($notifications))
		$notifications = array();

	return $notifications;
}

/**
 * Add notification
 * @param [type] $user_id     [description]
 * @param [type] $notif_array [description]
 */
function add_notification($user_id, $type, $notif_array){
	$notifications = get_user_meta( $user_id, 'notifications', true );
	if(empty($notifications))
		$notifications = array();

	$notifications[$type] = $notif_array;
	$update = update_user_meta( $user_id, 'notifications', $notifications );

	return $update;
}

function hackgov_status(){
	$options = apply_filters( 'hackgov_status', array(
		'nothing' => __('Nothing', 'hackgov'),		
		'on-going' => __('On Going', 'hackgov'),		
		'resolved' => __('Resolved', 'hackgov'),		
	) );

	return $options;
}

/**
 * Category options
 * @return [type] [description]
 */
function hackgov_category(){
	$options = apply_filters( 'hackgov_category', array(
		'leaf' => __('Lingkungan', 'hackgov'),		
		'traffic' => __('Infrastruktur', 'hackgov'),		
	) );

	return $options;
}

/**
 * Category options
 * @return [type] [description]
 */
function hackgov_priority(){
	$options = apply_filters( 'hackgov_priority', array(
		'medium' 	=> __('Medium', 'hackgov'),		
		'high' 		=> __('High', 'hackgov'),		
	) );

	return $options;
}

function hackgov_get_priority($post_id){
	$priority = get_post_meta( $post_id, '_category_priority', true );
	return (isset($priority[0])) ? $priority[0] : false;
}

function hackgov_get_category($post_id){
	$category = get_post_meta( $post_id, '_category_category', true );
	return (isset($category[0])) ? $category[0] : false;
}

/**
 * Dynamic body id
 * @return [type] [description]
 */
function hackgov_body_id(){
	return 'dashboard';
}

function get_status_icon($post_id){
	$status_meta = str_replace('-', '', get_post_meta($post_id, '_category_status', true ) );

	switch ($status_meta) {
		case 'nothing':
			$status = '<span class="icon-sad"></span>';
			break;

		case 'ongoing':
			$status = '<span class="icon-neutral"></span>';
			break;

		case 'resolved':
			$status = '<span class="icon-happy"></span>';
			break;
	}

	return $status;
}

function get_share_count($post_id="") {
	if(empty($post_id))
		$post_id = get_queried_object_id();

	if(!is_singular( 'post' ) ){
		$shareCount = get_post_meta( $post_id, 'total_shares', true );
		return $shareCount;
	}

	$new_shares = 0;
	$real_shares_count = 0;
	
	if(function_exists('curl_version')) {
		$version = curl_version();
		$bitfields = array(
			'CURL_VERSION_IPV6', 
			'CURLOPT_IPRESOLVE'
		);

		foreach($bitfields as $feature) {
			if($version['features'] & constant($feature)) {
				$real_shares = new shareCount(get_permalink($post_id));
				
				$real_shares_count += $real_shares->get_tweets();
				$real_shares_count += $real_shares->get_fb();
				$real_shares_count += $real_shares->get_linkedin();
				$real_shares_count += $real_shares->get_plusones();
				$real_shares_count += $real_shares->get_pinterest();
				break;
			}
		}
	}
	
	$total_shares = $new_shares + $real_shares_count;

	update_post_meta( $post_id, 'total_shares', $total_shares );
	
	return $total_shares;
}

function get_short_url($post_id){
	// $url = add_query_arg(array( 'short' => $post_id ), home_url() );
	$url = get_permalink( $post_id );

	return $url;
}

function get_twitter_share_url($post_id){
	$args = array(
		'text' => get_the_title( $post_id ),
		'hashtags' => 'l'.$post_id,
		'url' => get_short_url($post_id)
	);

	return add_query_arg( $args, 'https://twitter.com/intent/tweet' );
}

function get_popular_posts($limit='0'){
	$args = array(
		'posts_per_page' => $limit,
		'post_status' => 'publish',
		'post_type' => 'post',
		'meta_key' => 'popularity',
		'orderby' => 'meta_value_num',
		'order' => 'desc'
 	);

 	return get_posts($args);
}