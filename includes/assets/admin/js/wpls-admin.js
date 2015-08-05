var wpls_object;
var initialLocation;
var browserSupportFlag =  new Boolean();
var marker;
var geocoder;
var infowindow;

( function( jq ) {

	wpls_object = {
		init: function() {
			wpls_object.wpls_load_google_map();
		},
		wpls_load_google_map: function() {
			jq( '#wp-location-share-map' ).magnificPopup( {
				type: 'inline',
				modal: false,
				callbacks: {
					open: function() {
						wpls_object.wpls_google_map_intialize();
					}
				}
			} );
		},
		wpls_google_map_intialize: function() {
			var pune = new google.maps.LatLng( 18.5204393, 73.8567347 );

			var myOptions = {
				zoom	  : 15,
				mapTypeId : google.maps.MapTypeId.ROADMAP,
				streetViewControl: false
			};
			var map = new google.maps.Map( document.getElementById( "wpls-google-map" ), myOptions );

			// Try W3C Geolocation (Preferred)
			if( navigator.geolocation ) {
				browserSupportFlag = true;

				navigator.geolocation.getCurrentPosition( function( position ) {
					//initialLocation = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );
					//
					//map.setCenter( initialLocation );
					wpls_object.wpls_markOutLocation( map, position.coords.latitude, position.coords.longitude );
				}, function() {
					wpls_object.wpls_handle_no_geo_location( browserSupportFlag, pune );
				} );
			}
			// Browser doesn't support Geolocation
			else {
				browserSupportFlag = false;

				wpls_object.wpls_handle_no_geo_location( browserSupportFlag, pune );
			}
		},
		wpls_handle_no_geo_location: function( wpls_browser_support_flag, wpls_location ) {
			if (wpls_browser_support_flag == true) {
				alert( "Geolocation service failed." );

				initialLocation = wpls_location;
			} else {
				alert( "Your browser doesn't support geolocation. We've placed you in Pune, India." );

				initialLocation = wpls_location;
			}
			map.setCenter( initialLocation );
		},
		wpls_markOutLocation: function( wpls_map, lattitude, longitude ) {
			geocoder = new google.maps.Geocoder();
			infowindow = new google.maps.InfoWindow();
			var latLong = new google.maps.LatLng( lattitude, longitude );

			geocoder.geocode( { 'location': latLong }, function( results, status ) {
				if( status == google.maps.GeocoderStatus.OK ) {
					if( results[ 1 ] ) {
						wpls_map.setZoom( 15 );
						marker = new google.maps.Marker( {
							position: latLong,
							draggable: true,
							map: wpls_map
						} );
						infowindow.setContent(results[ 1 ].formatted_address );
						infowindow.open( wpls_map, marker );

						google.maps.event.addListener( marker, 'dragend', function( event ) {
							wpls_object.wpls_geocodePosition( wpls_map, marker.getPosition() );
						} );

						google.maps.event.addListener( marker, 'click', function( event ) {
							wpls_object.wpls_geocodePosition( wpls_map, marker.getPosition() );
						} );

						google.maps.event.addListener( wpls_map, 'click', function( event ) {
							wpls_object.wpls_geocodePosition( wpls_map, event.latLng );
						});
					} else {
						window.alert( 'No results found' );
					}
				} else {
					window.alert( 'Geocoder failed due to: ' + status );
				}
			} );

			wpls_map.setCenter( latLong, 15 );
		},
		wpls_geocodePosition: function( wpls_map, pos ) {
			geocoder.geocode( { latLng: pos }, function( responses ) {
				if( responses && responses.length > 0 ) {
					marker.formatted_address = responses[ 0 ].formatted_address;
				} else {
					marker.formatted_address = 'Cannot determine address at this location.';
				}
				marker.setPosition( pos );
				infowindow.setContent( marker.formatted_address );
				infowindow.open( wpls_map, marker );
			} );
		}
	};

	jq( 'document' ).ready( function() {
		wpls_object.init();
	} );

} ) ( jQuery );
