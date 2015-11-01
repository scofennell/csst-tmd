<?php

/**
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

function csst_tmd_customizer_init() {
	new CSST_TMD_Customizer();
}
add_action( 'init' , 'csst_tmd_customizer_init' );

/**
 * Our wrapper class for the WP theme customizer.
 */
class CSST_TMD_Customizer {

	public $panels = array();

	public function __construct() {

		$this -> set_panels();

		// Expose our customization array to our JS in wp_admin.
		add_action( 'admin_enqueue_scripts', array( $this, 'tinymce_localize' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'tinymce_localize' ), 999 );	

		// Add some body classes based on the customizer.
		add_filter( 'body_class', array( $this, 'body_class' ), 999 );

		// Register our custom settings and controls.
		add_action( 'customize_register' , array( $this, 'register' ), 970 );

		// Strip away unused customizer nodes.
		add_action( 'customize_register', array( $this, 'deregister' ), 999 );

		// Add some classes to the post editor.
		add_filter( 'tiny_mce_before_init', array( $this, 'editor_class' ) );

		// Inject our styles.
		add_action( 'wp_head', array( $this, 'inline_styles' ) );

		// Expose our customization array to our JS in the live editor.
		add_action( 'wp_footer', array( $this, 'live_update' ), 999 );

	}

	public function set_panels() {
		$theme_mods     = new CSST_TMD_Mods;
		$this -> panels = $theme_mods -> get_panels();
	}

	public function get_classes() {

		$classes = array();

		$panels = $this -> panels;

		$theme_mods = get_theme_mods();		

		foreach ( $panels as $panel_id => $panel ) {
	
			// For each section...
			foreach( $panel['sections'] as $section_id => $section ) {

				// For each setting...
				foreach( $section['settings'] as $setting_id => $setting ) {

					if( ! isset( $theme_mods[ "$panel_id-$section_id-$setting_id" ] ) ) { continue; }

					// Grab the theme mod.
					$value = $theme_mods[ "$panel_id-$section_id-$setting_id" ];			
								
					// We don't care about empty values.
					if( empty( $value ) ) { continue; }

					// We made it!  Grab a body class.
					$classes[]= sanitize_html_class( CSST_TMD . "-$panel_id-$section_id-$setting_id-$value" );
				
				}

			}

		}

		return $classes;

	}

	public function register( $wp_customize ) {
		
		$panels = $this -> panels;

		foreach ( $panels as $panel_id => $panel ) {

			// Add this panel to the UI.
			$wp_customize -> add_panel(
				$panel_id,
				array(
					'title'       => $panel['title'],
					'description' => $panel['description'],
					'priority'    => $panel['priority'],
				)
			);

			// For each section in this panel, add it to the UI and add settings to it.
			foreach( $panel['sections'] as $section_id => $section ) {

				// Add this section to the UI.
				$wp_customize -> add_section(
					$panel_id . '-' . $section_id,
					array(
						'title'       => $section['title'],
						'description' => $section['description'],
						'priority'    => $section['priority'],
						'panel'       => $panel_id,
					)
				);

				// For each setting in this section, add it to the UI.
				foreach( $section['settings'] as $setting_id => $setting ) {

					// Start building an array of args for adding the setting.
					$setting_args = array(
						'default'              => $setting['default'],
						'sanitize_callback'    => $setting['sanitize_callback'],
						'sanitize_js_callback' => $setting['sanitize_js_callback'],
					);

					// Some settings get live preview.
					if( $setting['type'] == 'color' ) {
						$setting_args['transport']= 'postMessage';
					}

					// Register the setting.
					$wp_customize -> add_setting(
						$panel_id . '-' . $section_id . '-' . $setting_id,
						$setting_args
					);
					
					// Start building an array of args for adding the control.
					$control_args = array(
						'label'       => $setting['label'],
						'section'     => $panel_id . '-' . $section_id,
						'type'        => $setting['type'],
						'description' => $setting['description'],
					);

					if( $setting['type'] == 'color' ) {

						$wp_customize -> add_control(
							new WP_Customize_Color_Control(
								$wp_customize,
								$panel_id . '-' . $section_id . '-' . $setting_id,
								$control_args
							)
						);

					// Else, WordPress will use a default control.
					} else {

						$wp_customize -> add_control(
							$panel_id . '-' . $section_id . '-' . $setting_id,
							$control_args
						);

					}

				}

			}

		}

	}

	public function deregister( $wp_customize ) {
		
		$wp_customize -> remove_control( 'blogdescription' );

		$wp_customize -> remove_section( 'static_front_page' );
		
		$wp_customize -> remove_panel( 'nav_menus' );			

	}	

	public function body_class( $classes ) {
		 
		$customizer_classes = $this -> get_classes();

		$classes = array_merge( $classes, $customizer_classes );

		return $classes;

	}

	public function editor_class( $init_array ) {

		// Grab all the classes, as a string.
		$customizer_classes = $this -> get_classes();
		$customizer_classes_str = implode( ' ', $customizer_classes );

		// Grab the classes currently in TinyMCE.
		$body_class = $init_array['body_class'];

		// Append our classes to the old classes and send it back.
		$body_class .= " $customizer_classes_str ";
		$init_array['body_class']= $body_class;

		return $init_array;

	}

	public function inline_styles() {
		
		$out = '';

		$theme_mods = get_theme_mods();

		// Get the top-level settings panels.
		$panels = $this -> panels;

		// For each panel...
		foreach( $panels as $panel_id => $panel ) {

			// For each section...
			foreach( $panel['sections'] as $section_id => $section ) {

				// For each setting...
				foreach( $section['settings'] as $setting_id => $setting ) {

					if( ! isset( $theme_mods[ "$panel_id-$section_id-$setting_id" ] ) ) { continue; }

					$css = $setting['css'];

					foreach( $css as $css_rule ) {

						$selector  = $css_rule['selector'];
						$property  = $css_rule['property'];
						$value     = $theme_mods[ "$panel_id-$section_id-$setting_id" ];

						$rule = "$selector { $property : $value ; }";

						if( isset( $css_rule['queries'] ) ) {

							$queries = $css_rule['queries'];
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

							$rule = "
							
								@media $query {
									$rule
								}
	
							";

						}

						$out .= $rule;

					}

				}			

			}

		}

		if( ! empty( $out ) ) {

			$class = __CLASS__;

			$out = "<!-- Added by $class --><style>$out</style>";
		}

		echo $out;

	}

	public function live_update() {
		
		if( ! is_customize_preview() ) { return FALSE; }

		$out = '';

		$theme_mods = get_theme_mods();

		// Get the top-level settings panels.
		$panels = $this -> panels;

		// For each panel...
		foreach( $panels as $panel_id => $panel ) {

			// For each section...
			foreach( $panel['sections'] as $section_id => $section ) {

				// For each setting...
				foreach( $section['settings'] as $setting_id => $setting ) {

					if( $setting['type'] != 'color' ) { continue; }

					if( ! isset( $theme_mods[ "$panel_id-$section_id-$setting_id" ] ) ) { continue; }

					$css = $setting['css'];

					foreach( $css as $css_rule ) {

						$selector = $css_rule['selector'];
						$property = $css_rule['property'];
						$value    = $theme_mods[ "$panel_id-$section_id-$setting_id" ];

						//Update site title color in real time...
						$out .= "
							wp.customize( '$panel_id-$section_id-$setting_id', function( value ) {
								value.bind( function( newval ) {
									jQuery( '$selector' ).css( '$property', newval );
								} );
							} );
						";

					}

				}

			}
	
		}

		if( ! empty( $out ) ) {

			$class = __CLASS__;

			$out = "
				<!-- Added by $class -->
				<script>
					jQuery( document ).ready( function() {
						$out
					});
				</script>
			";
		}

		echo $out;					

	}

	/**
	 * Output a bundle of JS to localize all our customization vars for TinyMCE.
	 */
	public function tinymce_localize() {

		// We would never be able to use this in the customizer.
		if( is_customize_preview() ) { return FALSE; }

		// We would never be able to use this on the front end.
		if( ! is_admin() ) { return FALSE; }

		// Grab the TinyMCE style rules.
		$out = $this -> get_tinymce_styles();

		$out = array( 'hello' => 'world' );

		// Send the style rules to our JS.
		wp_localize_script( 'jquery', __CLASS__, $out );

	}

	/**
	 * Grab the TinyMCE styles.
	 * 
	 * @return array An array of css rules for using in TinyMCE.
	 */
	public function get_tinymce_styles() {

		$out = array();

		$theme_mods = get_theme_mods();

		// Get the top-level settings panels.
		$panels = $this -> panels;

		// For each panel...
		foreach( $panels as $panel_id => $panel ) {

			// For each section...
			foreach( $panel['sections'] as $section_id => $section ) {

				// For each setting...
				foreach( $section['settings'] as $setting_id => $setting ) {

					if( ! isset( $setting['tinymce_css'] ) ) { continue; }

					if( ! isset( $theme_mods[ "$panel_id-$section_id-$setting_id" ] ) ) { continue; }
		
					// No need to send empty styles.
					if( empty( $theme_mods[ "$panel_id-$section_id-$setting_id" ] ) ) { continue; }

					$css = $setting['css'];

					foreach( $css as $css_rule ) {

						$selector = $css_rule['selector'];
						$property = $css_rule['property'];
						$value    = $theme_mods[ "$panel_id-$section_id-$setting_id" ];
	
						// Add it to the array of rules for this setting.
						$setting_rules = array( $selector, $property, $value );

						// Add all of the rules for this setting to the output.
						$out[]= $setting_rules;

					}

				}

			}

		}

		return $out;

	}

}
