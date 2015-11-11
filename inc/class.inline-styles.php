<?php

/**
 * A class for adding inline styles.
 *
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

function csst_tmd_inline_styles_init() {
	new CSST_TMD_Inline_Styles();
}
add_action( 'init' , 'csst_tmd_inline_styles_init' );

class CSST_TMD_Inline_Styles {

	public function __construct( $output_for = 'front_end' ) {

		// Add our styles to the front end of the blog.
		add_action( 'wp_enqueue_scripts', array( $this, 'front_end_styles' ) );
		
		// Expose our customization array to our JS in wp_admin.
		add_action( 'admin_enqueue_scripts', array( $this, 'localize' ), 999 );
	

	}

	/**
	 * Output a bundle of JS to localize all our customizer styles.
	 */
	public function localize() {

		// Grab the syles that pertain to tinymce, wrapped in a style tag.
		$out = $this -> get_inline_styles( 'wrapped', 'tinymce' );

		// Send the style rules to our JS.
		wp_localize_script( 'jquery', CSST_TMD, $out );

	}

	/**
	 * Append our customizer styles to the <head> whenever our main stylesheet is called.
	 */
	public function front_end_styles() {

		// Grab the styles that pertain to the front end, but don't wrap them in a style tag.
		$styles = $this -> get_inline_styles( 'unwrapped', 'front_end' );

		// Attach our customizer styles to our stylesheet.  When it gets called, so do our customizer styles.
		wp_add_inline_style( CSST_TMD, $styles );

	}

	/**
	 * Loop through our theme mods and build a string of CSS rules.
	 * 
	 * @param  string $wrapped    Whether or not to wrap the styles in a style tag. Expects 'wrapped' or 'unwrapped'.
	 * @param  string $output_for The context for these styles. Expects 'front_end' or 'tinymce'.
	 * @return string CSS, either wrapped in a style tag, or not.
	 */
	public function get_inline_styles( $wrapped = 'wrapped', $output_for = 'front_end' ) {

		// This will hold all of our customizer styles.
		$out = '';

		// If we are outputting for the front end...
		if( $output_for == 'front_end' ) {
		
			// Skip any settings that don't pertain to css.
			$exclude_if_empty = array( 'css' );
		
		// Else if we are outputting for tinymce...
		} elseif( $output_for == 'tinymce' ) {
		
			// ... Skip any settings that don't pertain to tinymce.
			$exclude_if_empty = array( 'tinymce_css' );
		
		}

		// Fire up our theme mods class.
		$theme_mods_class = new CSST_TMD_Theme_Mods;

		// Get the theme mods, but skip theme mods according to $exclude_if_empty. 
		$settings = $theme_mods_class -> get_settings( $exclude_if_empty );

		// For each setting...
		foreach( $settings as $setting_id => $setting ) {

			// Grab the css for this setting.
			$css_rules = $setting['css'];

			// Grab the current value for this setting.
			$value = $setting['value'];

			// For each css rule...
			foreach( $css_rules as $css_rule ) {

				// The css selector.
				$selector = $css_rule['selector'];
				
				// The css property.
				$property = $css_rule['property'];

				// Build this into a CSS rule.
				$rule_string = "$selector { $property : $value ; }";

				// Does this css rule have meai queries?
				if( isset( $css_rule['queries'] ) ) {

					$queries = $css_rule['queries'];
					
					// How many media queries?
					$query_count = count( $queries );
					
					// Will hold the media query string.
					$query = '';
					
					$i = 0;
					foreach( $queries as $query_key => $query_value ) {

						$i++;

						// Add the media query key and value.
						$query .= "( $query_key : $query_value )";

						// If this isn't the last query, add the "and" operator.
						if( $i < $query_count ) {
							$query .= ' and ';
						}

					}

					// Wrap the rule string in the media query.
					$rule_string = " @media $query { $rule_string } ";

				}

				// Add the rule, which might be wrapped in a media query, to the output.
				$out .= $rule_string;

			}

		}	
	
		// Didn't find any?  Bail.
		if( empty( $out ) ) { return FALSE; }

		$class = __CLASS__;

		// Grab our class to add a helpful debug comment.
		if( $wrapped == 'wrapped' ) {
			
			$out = "<!-- Added by $class --><style>$out</style>";

		} elseif( $wrapped == 'unwrapped' ) {

			$out = "/* Added by $class */ $out";
		}

		return $out;

	}

}