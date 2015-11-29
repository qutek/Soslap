<?php
/**
 * HackGov_Admin Class.
 *
 * @class       HackGov_Admin
 * @version		1.0
 * @author lafif <lafif@astahdziq.in>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * HackGov_Admin class.
 */
class HackGov_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();
	}

	public function includes(){
		
	}

}

return new HackGov_Admin();