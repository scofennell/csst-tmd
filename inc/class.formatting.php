<?php

/**
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */ 

final class CSST_TMD_Formatting {

	// This will hold the first copy of this class that get's called.
	static private $_instance = NULL;

	// Don't let any other code un-singleton this class.
	private function __construct() {}
	private function __clone() {}

	// This is the entry point for this class.
	static function get_instance() {
	
		// If it's the first grab, set the flag.	
		if( self::$_instance == NULL ) {
			self::$_instance = new CSST_TMD_Formatting();
		}
		
		// Return the same copy of this class on each use.
		return self::$_instance;
	
	}

	public function sanitize_linear_css( $string ) {

		$out = preg_replace( '/[^a-zA-Z0-9 +-_.()% ]/', '', $string );

		return $out;

	}

}