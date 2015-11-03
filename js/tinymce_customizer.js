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
 
 				// We'll loop through and append CSS to this string, rule by rule from the customizer.
				var rules = '';

				// Start a style tag.
				var style = jQuery( "<style>" );

				// Find the head of tinyMCE.
				var head = jQuery( doc ).find( 'head' );

				console.log( CSST_TMD_Tiny_MCE );

				// For each customizer setting...
				for( setting in CSST_TMD_Tiny_MCE ) {
		
					// For each rule in this setting, carve out the selector, property and value, in order to build a CSS rule.
					//for( rule in CSST_TMD_Tiny_MCE[ setting ] ) {

						// It's just a numerically indexed array.
						var selector = CSST_TMD_Tiny_MCE[ setting ][ 0 ];
						var prop     = CSST_TMD_Tiny_MCE[ setting ][ 1 ];
						var value    = CSST_TMD_Tiny_MCE[ setting ][ 2 ];

					//}

					rules += ' ' + selector + ' { ' + prop + ':' + value + ' } '; 

				}

				console.log( rules );

				// Add our rules to the style tag.
				jQuery( style ).text( rules );

				// Add our style tag to the head.
				jQuery( style ).appendTo( head );

			});
		},
	});

	// I guess this method ships with TinyMCE.
	tinymce.PluginManager.add( 'csstTmdCustomizer' , tinymce.plugins.csstTmdCustomizer );

})();