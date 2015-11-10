<?php

/**
 * Enqueue scripts and styles for our theme.
 * 
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */ 

function csst_enqueue_init() {
	new CSST_TMD_Enqueue();
}
add_action( 'init', 'csst_enqueue_init' );

class CSST_TMD_Enqueue {

	public function __construct() {
	
		add_action( 'wp_enqueue_scripts', array( $this, 'style' ) );
	
	}

	/**
	 * Grab our theme stylesheet.
	 */
	public function style() {

		/**
		 * The first arg matches a call to wp_add_inline_style()
		 * elsewhere in the theme, for calling our customizer styles.
		 */
		wp_enqueue_style( CSST_TMD, CSST_TMD_URL . 'style.css', FALSE ); 

	}

}