<?php
/**
 * The main template file
 *
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

get_header(); ?>

	<?php

		if( has_site_icon() ) {
			echo "<img src='" . esc_url( get_site_icon_url() ) . "' alt='" . esc_attr( get_bloginfo( 'name' ) ) . "'>";
		}

	?>

<?php get_footer(); ?>