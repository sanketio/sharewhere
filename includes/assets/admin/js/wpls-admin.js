var wpls_object;
var initialLocation;
var browserSupportFlag =  new Boolean();
var marker;
var geocoder;
var infowindow;
var wpls_magnific_popup;

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

			wpls_magnific_popup = jq.magnificPopup.instance;
		},
		wpls_google_map_intialize: function() {
			var pune 	  = new google.maps.LatLng( 18.5204393, 73.8567347 );
			var myOptions = {
				zoom	  		  : 17,
				mapTypeId 		  : google.maps.MapTypeId.ROADMAP,
				streetViewControl : false
			};
			var map = new google.maps.Map( document.getElementById( "wpls-google-map" ), myOptions );

			// Try W3C Geolocation (Preferred)
			if( navigator.geolocation ) {
				browserSupportFlag = true;

				navigator.geolocation.getCurrentPosition( function( position ) {
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

			wpls_object.wpls_search_google_map( map );
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
			geocoder 	= new google.maps.Geocoder();
			infowindow  = new google.maps.InfoWindow();
			var latLong = new google.maps.LatLng( lattitude, longitude );

			geocoder.geocode( { 'location': latLong }, function( results, status ) {
				if( status == google.maps.GeocoderStatus.OK ) {
					if( results[ 1 ] ) {
						wpls_map.setCenter( latLong, 17 );

						marker = new google.maps.Marker( {
							position: latLong,
							draggable: true,
							map: wpls_map
						} );

						infowindow.setContent( results[ 1 ].formatted_address );
						infowindow.open( wpls_map, marker );

						wpls_object.wpls_store_location( results[ 1 ].formatted_address );

						google.maps.event.addListener( marker, 'dragend', function( event ) {
							wpls_object.wpls_geocodePosition( wpls_map, marker.getPosition() );
						} );

						google.maps.event.addListener( marker, 'click', function( event ) {
							wpls_object.wpls_geocodePosition( wpls_map, marker.getPosition() );
						} );

						google.maps.event.addListener( wpls_map, 'click', function( event ) {
							wpls_object.wpls_geocodePosition( wpls_map, event.latLng );
						} );
					} else {
						window.alert( 'No results found' );
					}
				} else {
					window.alert( 'Geocoder failed due to: ' + status );
				}
			} );
		},
		wpls_geocodePosition: function( wpls_map, pos ) {
			geocoder.geocode( { latLng: pos }, function( responses ) {
				if( responses && responses.length > 0 ) {
					marker.formatted_address = responses[ 0 ].formatted_address;
					wpls_object.wpls_store_location( marker.formatted_address );
				} else {
					marker.formatted_address = 'Cannot determine address at this location.';
				}

				wpls_map.setCenter( pos, 17 );

				marker.setPosition( pos );

				infowindow.setContent( marker.formatted_address );
				infowindow.open( wpls_map, marker );
			} );
		},
		wpls_search_google_map: function( wpls_map ) {
			var input 		 = ( document.getElementById('wpls-map-search'));
			var autocomplete = new google.maps.places.Autocomplete(input);

			autocomplete.bindTo('bounds', wpls_map);

			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace();

				if ( !place.geometry ) {
					window.alert("Autocomplete's returned place contains no geometry");

					return;
				}

				var lat    = place.geometry.location[ 'G' ];
				var lng    = place.geometry.location[ 'K' ];
				var latlng = new google.maps.LatLng( lat, lng );

				wpls_object.wpls_geocodePosition( wpls_map, latlng );
			} );
		},
		wpls_store_location: function( address ) {
			jq( '#wpls-store-location' ).val( address );
		},
		wpls_append_location: function() {
			var location = jq( '#wpls-store-location').val();
			var link 	 = '<a title="' + location + '" href="https://google.com/maps?q=' + decodeURIComponent( location ) + '" target="_blank">' + location + '</a>';

			jq( link ).appendTo( jq( '#wp-content-editor-container iframe' ).contents().find( 'body' ) );

			wpls_magnific_popup.close();
		}
	};

	jq( 'document' ).ready( function() {
		wpls_object.init();

		jq( '#wpls-insert-button' ).click( function() {
			wpls_object.wpls_append_location();
		} );
	} );

} ) ( jQuery );
