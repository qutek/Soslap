<?php
/**
 * HackGov_Post_Types Class.
 *
 * @class       HackGov_Post_Types
 * @version		1.0
 * @author lafif <lafif@astahdziq.in>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * HackGov_Post_Types class.
 */
class HackGov_Post_Types {

	public $cpt_post;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();

		add_action( 'init', array($this, 'hackgov_post_types') );
		// add_action( 'admin_footer-post.php', array($this, 'jc_append_post_status_list'));
		add_action( 'add_meta_boxes', array($this, 'hackgov_add_metabox') );
		add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts') );
		add_action( 'save_post', array($this, 'save_metaboxes'), 10, 1 );
	}

	public function admin_scripts(){
		global $post;

		if($post->post_type != 'post')
			return;

		wp_enqueue_script( 'map', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places', '', '', true );
		wp_enqueue_script( 'location-admin', get_template_directory_uri() . '/assets/js/location.admin.js', array('jquery', 'map'), '', true );
		wp_localize_script( 'location-admin', 'location_obj', 
			array( 
				'img_url' => get_template_directory_uri() . '/assets/images/',
			) 
		);
	}

	public function hackgov_post_types(){

		$this->cpt_post = register_cuztom_post_type( 'Post' );

		$this->cpt_post->add_meta_box(
		    'post',
		    'Recommendation',
		    array(
		        array(
		            'name'          => 'recommendation',
		            'label'         => 'Recommendation',
		            'description'   => 'Recommendation content',
		            'type'          => 'wysiwyg'
		        )
		    )
		);

		$this->cpt_post->add_meta_box(
		    'category',
		    'Recommendation',
		    array(
		        array(
		            'name'          => 'status',
		            'label'         => 'Status',
		            'description'   => 'Problem Status',
		            'type'          => 'select',
		            'options'		=> hackgov_status()
		        ),
		        array(
		            'name'          => 'category',
		            'label'         => 'Category',
		            'description'   => 'Category problem',
		            'type'          => 'radios',
		            'options'		=> hackgov_category()
		        ),
		        array(
		            'name'          => 'priority',
		            'label'         => 'Priority',
		            'description'   => 'Priority problem',
		            'type'          => 'radios',
		            'options'		=> hackgov_priority()
		        )
		    ),
		    'side'
		);

		// // register custom post status
		// register_post_status( 'nothing', array(
		// 	'label'                     => _x( 'Nothing', 'post' ),
		// 	'public'                    => true,
		// 	'exclude_from_search'       => false,
		// 	'show_in_admin_all_list'    => true,
		// 	'show_in_admin_status_list' => true,
		// 	'label_count'               => _n_noop( 'Nothing <span class="count">(%s)</span>', 'Nothing <span class="count">(%s)</span>' ),
		// ) );

		// // register custom post status
		// register_post_status( 'on-going', array(
		// 	'label'                     => _x( 'On Going', 'post' ),
		// 	'public'                    => true,
		// 	'exclude_from_search'       => false,
		// 	'show_in_admin_all_list'    => true,
		// 	'show_in_admin_status_list' => true,
		// 	'label_count'               => _n_noop( 'On Going <span class="count">(%s)</span>', 'On Going <span class="count">(%s)</span>' ),
		// ) );

		// // register custom post status
		// register_post_status( 'resolved', array(
		// 	'label'                     => _x( 'Resolved', 'post' ),
		// 	'public'                    => true,
		// 	'exclude_from_search'       => false,
		// 	'show_in_admin_all_list'    => true,
		// 	'show_in_admin_status_list' => true,
		// 	'label_count'               => _n_noop( 'Resolved <span class="count">(%s)</span>', 'Resolved <span class="count">(%s)</span>' ),
		// ) );

		// remove post tag and category
		register_taxonomy('category', array());
		register_taxonomy('post_tag', array());
	}

	// public function jc_append_post_status_list(){
 //     	global $post;
 //     	$complete = '';
 //     	$label = '';
 //     	if($post->post_type == 'post'){

 //     		$new_statuses = array(
 //     			'nothing' => 'Nothing',
 //     			'on-going' => 'On Going',
 //     			'resolved' => 'Resolved',
 //     		);

 //          	if( in_array($post->post_status, $new_statuses) ){
	//                $complete = ' selected="selected"';
	//                $label = "<span id='post-status-display'> ".$new_statuses[$post->post_status]."</span>";
	//                $is_checked = '$("select#post_status").val("'.$post->post_status.'");';
 //          	}

 //          	$option = "<option value='nothing'>Nothing</option>";
 //          	$option .= "<option value='on-going'>On Going</option>";
 //          	$option .= "<option value='resolved'>Resolved</option>";

 //          	echo '
	//           <script>
	//           alert("'.$post->post_status.'");
	//           jQuery(document).ready(function($){
	//                $("select#post_status").append("'.$option.'");
	//                $(".misc-pub-section label").append("'.$label.'");
	//                '.$is_checked.'
	//           });
	//           </script>
 //          	';
 //     	}
	// }

	public function hackgov_add_metabox(){
		remove_meta_box( 'categorydiv', 'post', 'side' );
		remove_meta_box( 'tagsdiv-post_tag', 'post', 'side' );
		add_meta_box( 'location-data', __( 'Lokasi', 'hackgov' ), array($this, 'map_data_metabox'), 'post', 'normal', 'default' );
	}

	public function map_data_metabox(){
		global $post_id;

		$latitude = get_post_meta( $post_id, '_latitude', true );
		$longitude = get_post_meta( $post_id, '_longitude', true );
		$location_name = get_post_meta( $post_id, '_location', true );
	?>
		<div class="container-input">
			<input type="hidden" id="latitude" value="<?php echo $latitude; ?>" name="latitude" placeholder="latitude">
			<input type="hidden" id="longitude" value="<?php echo $longitude; ?>" name="longitude" placeholder="longitude">
			<input class="postcode form-control" id="location_address" name="location_address" type="text" placeholder="Tulis alamat" autocomplete="off" style="width:100%;" value="<?php echo $location_name; ?>">
		</div>
		<div id="map" style="height:300px;"></div>
		<?php wp_nonce_field( 'hackgov_save_metabox', 'map_metabox' ); ?>
	<?php
	}

	public function save_metaboxes($post_id){
		
		if(!wp_verify_nonce( $_POST['map_metabox'], 'hackgov_save_metabox' ) )
			return;

		$defaults = array(
            'latitude' => '',
            'longitude' => '',
            'location_address' => ''
        );

        $data = (object) wp_parse_args( $_POST, $defaults );

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // exit();

        update_post_meta( $post_id, '_latitude', $data->latitude );
		update_post_meta( $post_id, '_longitude', $data->longitude );
		update_post_meta( $post_id, '_location', $data->location_address );
	}

	public function includes(){
		include_once('cuztom/cuztom.php');
	}

}

return new HackGov_Post_Types();