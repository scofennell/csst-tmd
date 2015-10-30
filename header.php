<?php
/**
 * The template for displaying the header
 *
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

?><!DOCTYPE html>
<html>
	<head>
		
		<title>
			<?php echo __( 'This is just a placeholder theme!', 'csst-tmd' ); ?>
		</title>

		<?php wp_head(); ?>

	</head>

	<body>

		<div class='csst_tmd-page'>

			<h1>
				<?php bloginfo( 'name' ); ?>
			</h1>

			<h2>
				<?php bloginfo( 'description' ); ?>
			</h2>