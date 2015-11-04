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

	public function __construct() {

		// Register our custom settings and controls.
		add_action( 'customize_register' , array( $this, 'register' ), 970 );

		// Strip away unused customizer nodes.
		add_action( 'customize_register', array( $this, 'deregister' ), 999 );

		// Expose our customization array to our JS in the live editor.
		add_action( 'wp_footer', array( $this, 'live_update' ), 999 );

	}

	public function register( $wp_customize ) {
		
		// Get the top-level settings panels.
		$theme_mods_class = CSST_TMD_Theme_Mods::get_instance();
		$panels     = $theme_mods_class -> get_panels();

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

	public function live_update() {
		
		if( ! is_customize_preview() ) { return FALSE; }

		$out = '';

		$theme_mods = get_theme_mods();

		// Get the top-level settings panels.
		$theme_mods_class = CSST_TMD_Theme_Mods::get_instance();
		$panels     = $theme_mods_class -> get_panels();

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

}