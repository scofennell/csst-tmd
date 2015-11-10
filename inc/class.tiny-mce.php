<?php

/**
 * A class for affecting TinyMCE.
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

		// Expose our customization array to our JS in wp_admin.
		add_action( 'admin_enqueue_scripts', array( $this, 'tinymce_localize' ), 999 );
	
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

	/**
	 * Output a bundle of JS to localize all our customization vars for TinyMCE.
	 */
	public function tinymce_localize() {

		// Fire out our styles class, but only grad the styles that relate to tinymce.
		$styles_class = new CSST_TMD_Inline_Styles( 'tinymce' );

		// Grab the TinyMCE style rules.
		$out = $styles_class -> get_inline_styles();

		// Send the style rules to our JS.
		wp_localize_script( 'jquery', __CLASS__, $out );

	}

}