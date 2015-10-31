<?php

/**
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

class CSST_TMD_Mods {

	public function get_panels() {

		$out = array(
			'body' => array(

				'title'       => esc_html__( 'Body', 'csst_tmd' ),
				'description' => esc_html__( 'Theme Mods for the Page Body', 'csst_tmd' ),
				'priority'    => 10,
				'sections'    => array(

					'styles' => array(

						'title'       => esc_html__( 'Styles', 'csst_tmd' ),
						'description' => esc_html__( 'Styles for the Page Body', 'csst_tmd' ),
						'priority'    => 10,
						'settings'    => array(

							'background-color' => array(
								'type'                 => 'color',
								'label'                => esc_html__( 'Body Background Color', 'csst_tmd' ),
								'description'          => esc_html( 'The background color for the body element.', 'csst_tmd' ),
								'priority'             => 10,
								'default'              => 'orange',
								'sanitize_callback'    => 'sanitize_text_field',
								'sanitize_js_callback' => 'sanitize_text_field',
								'css'         => array(
									array(
										'logic'     => 'only',
										'mediatype' => 'screen',
										'selector'  => 'body',
										'property'  => 'background-color',
									),
								),
							),

							'color' => array(
								'type'                 => 'color',
								'label'                => esc_html__( 'Body Text Color', 'csst_tmd' ),
								'description'          => esc_html( 'The font color for the body element.', 'csst_tmd' ),
								'priority'             => 20,
								'default'              => 'black',
								'sanitize_callback'    => 'sanitize_text_field',
								'sanitize_js_callback' => 'sanitize_text_field',
								'css'                  => array(
									array(
										'logic'     => 'only',
										'mediatype' => 'screen',
										'selector'  => 'body',
										'property'  => 'color',
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

}