/**
 * A TinyMCE plugin for adding customizer styles to the post editor.
 *
 * @package WordPress
 * @subpackage CSS_Tricks_Theme_Mod_Demo
 * @since CSS_Tricks_Theme_Mod_Demo 1.0
 */

( function() {

	// This matches the name reg'd in typekit.php.
	tinymce.create( 'tinymce.plugins.csstTmdCustomizer', {
	   
		// I guess this var ships with TinyMCE.
		init: function( ed, url ) {

			// I guess this hook ships with TinyMCE.
			ed.on( 'PreInit', function( e ) {

				// Get the DOM document object for the IFRAME.
				var doc = ed.getDoc();
 
 				// Find the <head> of tinyMCE.
				var head = jQuery( doc ).find( 'head' );

				/**
				 * Elsewhere in the theme, we passed this to our JS via localize_script,
				 * but let's just log it to make sure it made the trip okay.
				 */
				console.log( csst_tmd );
			
				// Add our style tag to the head.
				jQuery( csst_tmd ).appendTo( head );

			});
		},
	});

	// I guess this method ships with TinyMCE.
	tinymce.PluginManager.add( 'csstTmdCustomizer' , tinymce.plugins.csstTmdCustomizer );

})();