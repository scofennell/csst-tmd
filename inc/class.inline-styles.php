<?php

/**
 * A class for affecting TinyMCE.
 *
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

function csst_tmd_inline_styles_init() {
	new CSST_TMD_Inline_Styles();
}
add_action( 'init' , 'csst_tmd_inline_styles_init', 1 );

/**
 * Our wrapper class for the WP theme customizer.
 */
class CSST_TMD_Inline_Styles {

	public function __construct() {

		// Inject our styles.
		add_action( 'wp_head', array( $this, 'inline_styles' ) );

	}

	public function inline_styles() {
		echo $this -> get_inline_styles();
	}


	public function get_inline_styles() {
		
		$out = '';

		// Get the top-level settings panels.
		$theme_mods_class = CSST_TMD_Theme_Mods::get_instance();
		$settings         = $theme_mods_class -> get_settings();

		$theme_mods = get_theme_mods();

		// For each setting...
		foreach( $settings as $setting_id => $setting ) {

			if( ! isset( $setting['css'] ) ) { continue; }

			$css_rules = $setting['css'];

			foreach( $css_rules as $css_rule ) {

				$selector  = $css_rule['selector'];
				$property  = $css_rule['property'];
				$value     = $theme_mods[ $setting_id ];

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

					$rule_string = "
					
						@media $query {
							$rule_string
						}

					";

				}

				$out .= $rule_string;

			}

		}			

		if( ! empty( $out ) ) {

			$class = __CLASS__;

			$out = "<!-- Added by $class --><style>$out</style>";
		}

		return $out;

	}

}