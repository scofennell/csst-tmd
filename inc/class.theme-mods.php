<?php

/**
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

final class CSST_TMD_Mods {

	// This will hold the first copy of this class that get's called.
	static private $_instance = NULL;

	// Don't let any other code un-singleton this class.
	private function __construct() {}
	private function __clone() {}

	// This is the entry point for this class.
	static function get_instance() {
	
		// If it's the first grab, set the flag.	
		if( self::$_instance == NULL ) {
			self::$_instance = new CSST_TMD_Mods();
		}
		
		// Return the same copy of this class on each use.
		return self::$_instance;
	
	}

	public function get_panels() {

		$formatting = CSST_TMD_Formatting::get_instance();

		$out = array(
			'body' => array(

				'title'       => esc_html__( 'Body', 'csst_tmd' ),
				'description' => esc_html__( 'Theme Mods for the Page Body', 'csst_tmd' ),
				'priority'    => 20,
				'sections'    => array(

					'colors' => array(

						'title'       => esc_html__( 'Colors', 'csst_tmd' ),
						'description' => esc_html__( 'Colors for the Page Body', 'csst_tmd' ),
						'priority'    => 10,
						'settings'    => array(

							'background-color' => array(
								'type'                 => 'color',
								'label'                => esc_html__( 'Body Background Color', 'csst_tmd' ),
								'description'          => esc_html( 'The background color for the body element.', 'csst_tmd' ),
								'priority'             => 10,
								'default'              => '#000000',
								'sanitize_callback'    => 'sanitize_text_field',
								'sanitize_js_callback' => 'sanitize_text_field',
								'tinymce_css'          => TRUE,
								'css'                  => array(
									array(
										'selector'  => 'body',
										'property'  => 'background-color',
										'queries'   => array(
											'max-width'   => '800px',
											'orientation' => 'landscape',
										),
									),
								),
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

public function get_classes() {

		$classes = array();
		
		// Get the top-level settings panels.
		$theme_mods_class = CSST_TMD_Mods::get_instance();
		$panels     = $theme_mods_class -> get_panels();

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


}