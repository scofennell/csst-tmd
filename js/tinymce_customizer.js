/**
 * A TinyMCE plugin for adding customizer styles to the post editor.
 *
 * @package WordPress
 * @subpackage lxb-apple-fritter
 * @since lxb-apple-fritter 0.1
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

				// Let's log this to see what's up.
				console.log( CSST_TMD_Tiny_MCE );
			
				// Add our style tag to the head.
				jQuery( CSST_TMD_Tiny_MCE ).appendTo( head );

			});
		},
	});

	// I guess this method ships with TinyMCE.
	tinymce.PluginManager.add( 'csstTmdCustomizer' , tinymce.plugins.csstTmdCustomizer );

})();