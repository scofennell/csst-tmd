<?php

/**
 * A class for adding/removing panels, sections, and settings to/from the
 * customizer.
 * 
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

function csst_tmd_customizer_init() {
	new CSST_TMD_Customizer();
}
add_action( 'init' , 'csst_tmd_customizer_init' );

class CSST_TMD_Customizer {

	public function __construct() {

		// Register our custom settings and controls.
		add_action( 'customize_register' , array( $this, 'register' ), 970 );

		// Strip away unused customizer nodes.
		add_action( 'customize_register', array( $this, 'deregister' ), 999 );

	}

	/**
	 * Add our panels, sections, and settings to the customizer.
	 * 
	 * @param  object $wp_customize An instance of the WP_Customize_Manager class.
	 */
	public function register( $wp_customize ) {
		
		// Fire up our theme mods class.
		$theme_mods_class = new CSST_TMD_Theme_Mods;

		// Grab our panels, sections, and settings.
		$panels = $theme_mods_class -> get_panels();

		// For each panel...
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

					// Settings of the type 'color' get a special type of control.
					if( $setting['type'] == 'color' ) {

						$wp_customize -> add_control(
							
							// This ships with WordPress.  It's a color picker.
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

				// End this setting.
				}

			// End this section.
			}

		// End this panel.
		}

	}

	/**
	 * Remove stuff that WordPress adds to the customizer.
	 *
	 * param object $wp_customize An instance of WP_Customize_Manager.
	 */
	public function deregister( $wp_customize ) {

		// Remove the setting for blog description, AKA Site Tagline.
		$wp_customize -> remove_control( 'blogdescription' );

		// Remove the section for designating a static front page.
		$wp_customize -> remove_section( 'static_front_page' );

		// Remove the panel for handling nav menus.
		$wp_customize -> remove_panel( 'nav_menus' );			

	}

}