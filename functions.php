<?php

/**
 * CSS-Tricks Theme Mod Demo functions.
 *
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

// Define our theme namespace.
define( 'CSST_TMD', 'csst_tmd' );

// Establish a value for plugin version to bust file caches.
define( 'CSST_TMD_VERSION', '1.0' );

// A constant to define the paths to our plugin folders.
define( 'CSST_TMD_FILE', __FILE__ );
define( 'CSST_TMD_PATH', trailingslashit( get_template_directory( LXB_AF_FILE ) ) );
define( 'CSST_TMD_INC_PATH', LXB_AF_PATH . 'inc/' );

// A constant to define the urls to our plugin folders.
define( 'CSST_TMD_URL', trailingslashit( get_template_directory_uri( LXB_AF_FILE ) ) );
define( 'CSST_TMD_INC_URL', CSST_TMD_URL . 'inc/' );

/**
 * Require files for both wp-admin & front end.
 */

// A class for affecting admin menus.
require_once( LXB_AF_INC_PATH . 'class.setup.php' );

require_once( LXB_AF_INC_PATH . 'class.customizer.php' );

require_once( LXB_AF_INC_PATH . 'class.setting.php' );

require_once( LXB_AF_INC_PATH . 'class.setting.body_background_color.php' );

require_once( LXB_AF_INC_PATH . 'class.setting.body_color.php' );

/*
function twentysixteen_setup() {
	load_theme_textdomain( 'csst-tmd', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'twentysixteen_setup' );

function twentysixteen_scripts() {
	wp_enqueue_style( 'csst-tmd', get_stylesheet_uri()  );
}
add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );
*/