<?php

/**
 * A class for defining our theme mods, and for retrieving
 * them in various forms.
 * 
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

class CSST_TMD_Theme_Mods {

	/**
	 * Define our panels, sections, and settings.
	 * 
	 * @return array An array of panels, containing sections, containing settings.
	 */
	function get_panels() {

		// A handy class for formatting strings.
		$formatting = CSST_TMD_Formatting::get_instance();

		// Start an annoyingly huge array to define our panels, sections, and settings.
		$out = array();

		// Define the body panel.
		$body = array(

			// The title for this panel in the customizer UI.
			'title'       => esc_html__( 'Body', 'csst_tmd' ),
			
			// The description for this panel in the customizer UI.
			'description' => esc_html__( 'Theme Mods for the Page Body', 'csst_tmd' ),
				
			// The order within the customizer to output this panel.
			'priority'    => 20,
				
			// The body panel has a bunch of sections.
			'sections'    => array(),

		);
		$out['body'] = $body;

		// Define the colors section, which resides in the body panel.
		$out['body']['sections']['colors'] = array(

			// The title for this section in the customizer UI.
			'title'       => esc_html__( 'Colors', 'csst_tmd' ),

			// The description for this section in the customizer UI.
			'description' => esc_html__( 'Colors for the Page Body', 'csst_tmd' ),
			
			// The order within this panel to output this section.
			'priority'    => 10,
			
			// The colors section has a bunch of settings.
			'settings'    => array(),

		);

		// The setting for body background color.
		$out['body']['sections']['colors']['settings']['background_color'] = array(

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
			'tinymce_css'          => FALSE,

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
		);

		// The setting for body text color.
		$out['body']['sections']['colors']['settings']['color'] = array(

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

		);

		// A new section within the body panel, called "layout".
		$out['body']['sections']['layout'] = array(

			'title'       => esc_html__( 'Layout Options', 'csst_tmd' ),
			'description' => esc_html__( 'Layout Options for the Page Body', 'csst_tmd' ),
			'priority'    => 20,
			'settings'    => array(),

		);	

		// A setting for max-width for the body.
		$out['body']['sections']['layout']['settings']['max_width'] = array(

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

		);

		return $out;

	}

	/**
	 * Get all of our customizer settings and their values.
	 * 
	 * This is a helpful function because it does the work of looping through our
	 * massive array that defines all the panels and sections.
	 * 
	 * @param  boolean $include_empty Whether to include settings whose values are empty.
	 * @return array   An array of settings and their values.
	 */
	function get_settings( $include_empty = FALSE, $exclude_if_empty = array(), $caller = 'dunno' ) {

		// Will hold all of our customizer settings.
		$out = array();

		// Start by grabbing the panels.  We'll loop through them to find our settings.
		$panels = $this -> get_panels();

		// For each panel...
		foreach( $panels as $panel_id => $panel ) {

			// For each section...
			foreach( $panel['sections'] as $section_id => $section ) {

				// For each setting...
				foreach( $section['settings'] as $setting_id => $setting_definition ) {

					$skip = FALSE;
					foreach( $exclude_if_empty as $exclude ) {

						if( ! isset( $setting_definition[ $exclude ] ) ) { $skip = TRUE; }
						if( empty( $setting_definition[  $exclude  ] ) ) { $skip = TRUE; }
					
					}

					if( $skip ) { continue; }

					// I like hyphens between pieces.
					$setting_key = "$panel_id-$section_id-$setting_id";

					// Grab the value for this setting.
					$value = get_theme_mod( $setting_key );

					// Read the value into this array member.
					$setting_definition['value'] = $value;

					// Do we want to exclude empty settings?
					if( ! $include_empty ) {
						
						// If so, now's the time to bail.
						if( empty( $value ) ) { continue; }
					
					}

					// Add this setting to the output.
					$out[ $setting_key ] = $setting_definition;

				}			

			}

		}

		return $out;

	}

}