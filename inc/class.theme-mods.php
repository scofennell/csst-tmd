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
								'default'              => 'orange',
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
								'default'              => 'black',
								'sanitize_callback'    => 'sanitize_text_field',
								'sanitize_js_callback' => 'sanitize_text_field',
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
								'sanitize_callback'    => array( $this, 'sanitize_linear_css' ),
								'sanitize_js_callback' => array( $this, 'sanitize_linear_css' ),
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

	public function sanitize_linear_css( $string ) {

		$out = preg_replace( '/[^a-zA-Z0-9 +-_.()% ]/', '', $string );

		return $out;

	}

}