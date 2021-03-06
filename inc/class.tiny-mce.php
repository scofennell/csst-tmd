<?php

/**
 * A class for using our customizer data in TinyMCE.
 *
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

function csst_tmd_tiny_mce_init() {
	new CSST_TMD_Tiny_MCE();
}
add_action( 'init' , 'csst_tmd_tiny_mce_init', 1 );

/**
 * Our wrapper class for the WP theme customizer.
 */
class CSST_TMD_Tiny_MCE {

	public function __construct() {

		// Add Tiny MCE plugins.
		add_filter( 'mce_external_plugins', array( $this, 'plugin' ) );

	}

	/**
	 * Add JS plugins to TinyMCE.
	 * 
	 * @param  array $plugins The array of plugins registered for tiny MCE.
	 * @return array The array of plugins reg'd for TinyMCE, plus our plugins.
	 */
	function plugin( $plugins ) {
		
		// Our theme registers a TinyMCE plugin.  Add this plugin to TinyMCE.
		$plugins['csstTmdCustomizer'] = CSST_TMD_URL . 'js/tinymce_customizer.js';
		
		return $plugins;
	    
	}

}