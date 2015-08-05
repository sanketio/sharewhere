var wpls_object;

( function( jq ) {

	wpls_object = {
		init: function() {
			wpls_object.wpls_load_google_map();
		},
		wpls_load_google_map: function() {
			jq( '#wp-location-share-map' ).magnificPopup( {
				type: 'inline',
				modal: false
			} );

			var wpls_map_canvas = document.getElementById( 'wpls-google-map' );
			var mapOptions = {
				center 	  : new google.maps.LatLng(44.5403, -78.5463),
				zoom      : 8,
				mapTypeId : google.maps.MapTypeId.ROADMAP
			}
			var map = new google.maps.Map( wpls_map_canvas, mapOptions );
		}
	};

	jq( 'document' ).ready( function() {
		wpls_object.init();
	} );

} ) ( jQuery );
