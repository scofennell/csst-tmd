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
		add_action( 'wp_enqueue_scripts', array( $this, 'tinymce_localize' ), 999 );	

		// Add some classes to the post editor.
		add_filter( 'tiny_mce_before_init', array( $this, 'editor_class' ) );

	}

	public function editor_class( $init_array ) {

		// Grab all the classes, as a string.
		$theme_mods_class       = CSST_TMD_Mods::get_instance();
		$customizer_classes     = $theme_mods_class -> get_classes();
		$customizer_classes_str = implode( ' ', $customizer_classes );

		// Grab the classes currently in TinyMCE.
		$body_class = $init_array['body_class'];

		// Append our classes to the old classes and send it back.
		$body_class .= " $customizer_classes_str ";
		$init_array['body_class']= $body_class;

		return $init_array;

	}

	/**
	 * Add JS plugins to TinyMCE.
	 * 
	 * @param  array $plugins The array of plugins registered for tiny MCE.
	 * @return array The array of plugins reg'd for TinyMCE, plus our plugins.
	 */
	function plugin( $plugins ) {
		
		$plugins[ 'csstTmdCustomizer' ] = CSST_TMD_URL . 'js/tinymce_customizer.js';
		
		return $plugins;
	    
	}

	/**
	 * Output a bundle of JS to localize all our customization vars for TinyMCE.
	 */
	public function tinymce_localize() {

		if( ! $this -> is_tinymce() ) { return FALSE; }

		// Grab the TinyMCE style rules.
		$out = $this -> get_tinymce_styles();

		// Send the style rules to our JS.
		wp_localize_script( 'jquery', __CLASS__, $out );

	}

	public function is_tinymce() {

		// We would never be able to use this in the customizer.
		if( is_customize_preview() ) { return FALSE; }

		// We would never be able to use this on the front end.
		if( ! is_admin() ) { return FALSE; }

		$screen = get_current_screen();

		$base = $screen -> base;

		if( $base != 'post' ) { return FALSE; }

		return TRUE;

	}

	/**
	 * Grab the TinyMCE styles.
	 * 
	 * @return array An array of css rules for using in TinyMCE.
	 */
	public function get_tinymce_styles() {

		$out = array();

		// Get the top-level settings panels.
		$theme_mods_class = CSST_TMD_Mods::get_instance();
		$settings         = $theme_mods_class -> get_settings();

		// For each setting...
		foreach( $settings as $setting_id => $setting ) {

			if( ! isset( $setting['tinymce_css'] ) ) { continue; }

			$css = $setting['css'];

			foreach( $css as $css_rule ) {

				$selector = $css_rule['selector'];
				$property = $css_rule['property'];
				$value    = get_theme_mod( $setting_id );
	
				// Add it to the array of rules for this setting.
				$setting_rules = array( $selector, $property, $value );

				// Add all of the rules for this setting to the output.
				$out[]= $setting_rules;

			}

			return $out;

		}

	}

}