<?php

/**
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

final class CSST_TMD_Theme_Mods {

	// This will hold the first copy of this class that get's called.
	static private $_instance = NULL;

	// Don't let any other code un-singleton this class.
	private function __construct() {}
	private function __clone() {}

	// This is the entry point for this class.
	static function get_instance() {
	
		// If it's the first grab, set the flag.	
		if( self::$_instance == NULL ) {
			self::$_instance = new CSST_TMD_Theme_Mods();
		}
		
		// Return the same copy of this class on each use.
		return self::$_instance;
	
	}

	function get_panels() {

		$formatting = CSST_TMD_Formatting::get_instance();

		// Start an annoyingly huge array to define our panels, sections, and settings.
		$out = array(

			// Define the body panel.
			'body' => array(

				'title'       => esc_html__( 'Body', 'csst_tmd' ),
				'description' => esc_html__( 'Theme Mods for the Page Body', 'csst_tmd' ),
				
				// The order within the customizer to output this panel.
				'priority'    => 20,
				
				// The body panel has a bunch of sections.
				'sections'    => array(

					// Define the colors section, which resides in the body panel.
					'colors' => array(

						'title'       => esc_html__( 'Colors', 'csst_tmd' ),
						'description' => esc_html__( 'Colors for the Page Body', 'csst_tmd' ),
						
						// The order within this panel to output this section.
						'priority'    => 10,
						
						// The colors section has a bunch of settings.
						'settings'    => array(

							// The setting ID for body background color.
							'background-color' => array(

								// The type of control for this setting in the customizer.
								'type'                 => 'color',

								// The header text for the control.
								'label'                => esc_html__( 'Body Background Color', 'csst_tmd' ),

								// The descriptive text for the control.
								'description'          => esc_html( 'The background color for the body element, on landscape screens smaller than 800px.', 'csst_tmd' ),
								
								// The order within this section for outputting this control.
								'priority'             => 10,

								// The default value for this setting.
								'default'              => '#000000',

								// A callback function for sanitizing the input.
								'sanitize_callback'    => 'sanitize_hex_color',
								'sanitize_js_callback' => 'sanitize_hex_color',

								// Do we want to use css from this setting in TinyMCE?
								'tinymce_css'          => TRUE,

								// Is this setting responsible for creating some css?
								'css'                  => array(
									
									// This array amounts to one css rule.  We could do several more right here.
									array(

										// Here's the selector string.
										'selector'  => 'body',

										// Here's the css property.
										'property'  => 'background-color',
								
										// Here are some media queries for this css.		
										'queries'   => array(
											'max-width'   => '800px',
											'orientation' => 'landscape',
										),
			
									// End this css rule.  We could start another one right here, perhaps to use this setting for a border-color on the body element, or whatever.					
									),
			
								// End the list of css rules (yeah, there's just one right now).
								),
							
							// End this setting.
							),

							'color' => array(
								'type'                 => 'color',
								'label'                => esc_html__( 'Body Text Color', 'csst_tmd' ),
								'description'          => esc_html( 'The font color for the body element.', 'csst_tmd' ),
								'priority'             => 20,
								'default'              => '#e18728',
								'sanitize_callback'    => 'sanitize_text_field',
								'sanitize_js_callback' => 'sanitize_text_field',
								'tinymce_css'          => TRUE,
								'css'                  => array(
									array(
										'selector'  => 'body',
										'property'  => 'color',
									),
								),
							),

						),

					),

					'layout' => array(

						'title'       => esc_html__( 'Layout Options', 'csst_tmd' ),
						'description' => esc_html__( 'Layout Options the Page Body', 'csst_tmd' ),
						'priority'    => 10,
						'settings'    => array(

							'max-width' => array(
								'type'                 => 'text',
								'label'                => esc_html__( 'Body Max Width', 'csst_tmd' ),
								'description'          => esc_html( 'The max-width for the body element.', 'csst_tmd' ),
								'priority'             => 10,
								'default'              => FALSE,
								'sanitize_callback'    => array( $formatting, 'sanitize_linear_css' ),
								'sanitize_js_callback' => array( $formatting, 'sanitize_linear_css' ),
								'css'                  => array(
									array(
										'selector'  => 'body',
										'property'  => 'max-width',
										'queries'   => array(
											'min-width' => '400px',
										),
									),
								),
							),

						),

					),
		
				),

			),
		);

		return $out;

	}

	function get_classes() {

		$classes = array();
		
		$settings = $this -> get_settings();

		$theme_mods = get_theme_mods();

		//wp_die( var_dump( $settings ) );

		// For each setting...
		foreach( $settings as $setting_id => $setting ) {

			// Grab the theme mod.
			$value = $theme_mods[ $setting_id ];

			// We made it!  Grab a body class.
			$class = sanitize_html_class( CSST_TMD . "-$setting_id-$value" );
		
			$classes[]= $class;

		}

		return $classes;

	}

	function get_settings( $include_empty = FALSE ) {

		$settings = array();

		$panels = $this -> get_panels();

		$theme_mods = get_theme_mods();

		// For each panel...
		foreach( $panels as $panel_id => $panel ) {

			// For each section...
			foreach( $panel['sections'] as $section_id => $section ) {

				// For each setting...
				foreach( $section['settings'] as $setting_id => $setting_definition ) {

					$setting_key = "$panel_id-$section_id-$setting_id";

					if( ! $include_empty ) {
						if( ! isset( $theme_mods[ $setting_key ] ) ) { continue; }
						if( empty( $theme_mods[ $setting_key ] ) ) { continue; }
					}

					$settings[ $setting_key ] = $setting_definition;

				}			

			}

		}

		return $settings;

	}

}