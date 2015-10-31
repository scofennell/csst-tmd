<?php

/**
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */ 

function csst_setup_init() {
	new CSST_Setup();
}
add_action( 'init', 'csst_setup_init' );

class CSST_Setup {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'style' ) );
	}

	public function style() {
		wp_enqueue_style( CSST_TMD, CSST_TMD_URL . 'style.css', FALSE ); 
	}

}