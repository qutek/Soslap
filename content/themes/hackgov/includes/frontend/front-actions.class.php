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
		if(isset($_POST['hackgov_submit_new'])){
			$this->process_submit_new();
		}
	}

	public function process_submit_new(){
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		exit();
	}

	public function includes(){
		
	}

}

return new HackGov_Front_Actions();