<?php

/**
 * A class for affecting TinyMCE.
 *
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

function csst_tmd_body_classes_init() {
	new CSST_TMD_Body_Classes();
}
add_action( 'init' , 'csst_tmd_body_classes_init', 1 );

/**
 * Our wrapper class for the WP theme customizer.
 */
class CSST_TMD_Body_Classes {

	public function __construct() {

		// Add some body classes based on the customizer.
		add_filter( 'body_class', array( $this, 'body_class' ), 999 );

		// Add some body classes based on the customizer.
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 999 );

	}

	public function body_class( $classes ) {
		 
		// Get the top-level settings panels.
		$theme_mods_class   = CSST_TMD_Theme_Mods::get_instance();
		$customizer_classes = $theme_mods_class -> get_classes();

		$classes = array_merge( $classes, $customizer_classes );

		return $classes;

	}

	public function admin_body_class( $classes ) {
		 
		// Get the top-level settings panels.
		$theme_mods_class   = CSST_TMD_Theme_Mods::get_instance();
		$customizer_classes = $theme_mods_class -> get_classes();

		$customizer_classes = implode( ' ', $customizer_classes );

		$classes = "$classes $customizer_classes";

		return $classes;

	}

}