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
define( 'CSST_TMD_PATH', trailingslashit( get_template_directory( CSST_TMD_FILE ) ) );
define( 'CSST_TMD_INC_PATH', CSST_TMD_PATH . 'inc/' );

// A constant to define the urls to our plugin folders.
define( 'CSST_TMD_URL', trailingslashit( get_template_directory_uri( CSST_TMD_FILE ) ) );
define( 'CSST_TMD_INC_URL', CSST_TMD_URL . 'inc/' );

// Our class for adding customizer data as body classes.
require_once( CSST_TMD_INC_PATH . 'class.body-classes.php' );

// Our class for adding and removing panels, sections, and settings to the customizer.
require_once( CSST_TMD_INC_PATH . 'class.customizer.php' );

// Our class for enqueueing assets.
require_once( CSST_TMD_INC_PATH . 'class.enqueue.php' );

// Our singleton for formatting strings.
require_once( CSST_TMD_INC_PATH . 'class.formatting.php' );

// Our class for outputting inline styles based on customizer data.
require_once( CSST_TMD_INC_PATH . 'class.inline-styles.php' );

// Our class for defining our panels, sections, and settings.
require_once( CSST_TMD_INC_PATH . 'class.theme-mods.php' );

// Our class for using our customizer data in tinymce.
require_once( CSST_TMD_INC_PATH . 'class.tiny-mce.php' );