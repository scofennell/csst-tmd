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

	public $output_for = FALSE;

	public function __construct( $output_for = 'front_end' ) {

		$this -> output_for = $output_for;

		// Inject our styles.
		if( $this -> output_for == 'front_end' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'front_end_styles' ) );
		}

	}

	public function front_end_styles() {

		if( is_admin() ) { return FALSE; } 

		$data = $this -> get_inline_styles( FALSE );

		wp_add_inline_style( CSST_TMD, $data );

	}

	/**
	 * Echo our styles.
	 */
	public function inline_styles() {

		// Grab the styles, wrapped in style tags.
		$out = $this -> get_inline_styles();

		echo $out;

	}

	/**
	 * Loop through our theme mods and build a string of CSS rules.
	 * 
	 * @return [type] [description]
	 */
	public function get_inline_styles( $wrap = TRUE ) {

		$out = '';

		$exclude_if_empty = array( 'css' );
		
		if( $this -> output_for == 'tinymce' ) {
			$exclude_if_empty = array( 'tinymce_css' );
		}

		// Get the top-level settings panels.
		$theme_mods_class = new CSST_TMD_Theme_Mods;
		$settings         = $theme_mods_class -> get_settings( FALSE, $exclude_if_empty );

		// For each setting...
		foreach( $settings as $setting_id => $setting ) {

			$css_rules = $setting['css'];

			$value     = $setting['value'];

			foreach( $css_rules as $css_rule ) {

				$selector  = $css_rule['selector'];
				$property  = $css_rule['property'];

				$rule_string = "$selector { $property : $value ; }";

				if( isset( $css_rule['queries'] ) ) {

					$queries     = $css_rule['queries'];
					$query_count = count( $queries );
					$i = 0;
					$query = '';

					foreach( $queries as $query_key => $query_value ) {

						$i++;

						$query .= "( $query_key : $query_value )";

						if( $i < $query_count ) {
							$query .= ' and ';
						}

					}

					$rule_string = " @media $query { $rule_string } ";

				}

				$out .= $rule_string;

			}

		}	
	
		// Didn't find any?  Bail.
		if( empty( $out ) ) { return FALSE; }

		$class = __CLASS__;

		if( $wrap ) {

			// Grab our class to add a helpful debug comment.
			
			$out = "<!-- Added by $class --><style>$out</style>";


		} else {

			$out = "/* Added by $class */ $out";

		}

		return $out;

	}

}