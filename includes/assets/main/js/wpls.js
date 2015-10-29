var wpls_main_object;
var initialLocation;
var browserSupportFlag =  new Boolean();
var marker;
var geocoder;
var infowindow;
var wpls_main_magnific_popup;
var wpls_main_location_types = new Array();

( function( jq ) {

	wpls_main_object = {
		main_init: function() {
			wpls_main_object.wpls_main_load_google_map();
		},
		wpls_main_load_google_map: function() {
			jq( '#wp-location-share-map' ).magnificPopup( {
				type: 'inline',
				modal: false,
				callbacks: {
					open: function() {
						wpls_main_object.wpls_main_google_map_intialize();
					}
				}
			} );

			wpls_main_magnific_popup = jq.magnificPopup.instance;
		},
		wpls_main_google_map_intialize: function() {
            jq( '#wpls-location-type').removeClass( 'wpls-location-type-error' );

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
					wpls_main_object.wpls_main_markOutLocation( map, position.coords.latitude, position.coords.longitude );
				}, function() {
					wpls_main_object.wpls_main_handle_no_geo_location( browserSupportFlag, pune );
				} );
			}
			// Browser doesn't support Geolocation
			else {
				browserSupportFlag = false;

				wpls_main_object.wpls_main_handle_no_geo_location( browserSupportFlag, pune );
			}

			wpls_main_object.wpls_main_search_google_map( map );
		},
		wpls_main_handle_no_geo_location: function( wpls_browser_support_flag, wpls_location ) {
			if (wpls_browser_support_flag == true) {
				alert( wpls_main_strings.wpls_main_geolocation_service_failed );

				initialLocation = wpls_location;
			} else {
				alert( wpls_main_strings.wpls_main_geolocation_not_supported_browser );

				initialLocation = wpls_location;
			}

			map.setCenter( initialLocation );
		},
		wpls_main_markOutLocation: function( wpls_map, lattitude, longitude ) {
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

						infowindow.setContent( results[ 0 ].formatted_address );
						infowindow.open( wpls_map, marker );

						wpls_main_object.wpls_main_store_location( results[ 0 ] );

						google.maps.event.addListener( marker, 'dragend', function( event ) {
							wpls_main_object.wpls_main_geocodePosition( wpls_map, marker.getPosition() );
						} );

						google.maps.event.addListener( marker, 'click', function( event ) {
							wpls_main_object.wpls_main_geocodePosition( wpls_map, marker.getPosition() );
						} );

						google.maps.event.addListener( wpls_map, 'click', function( event ) {
							wpls_main_object.wpls_main_geocodePosition( wpls_map, event.latLng );
						} );
					} else {
						window.alert( wpls_main_strings.wpls_main_no_result_found );
					}
				} else {
					window.alert( wpls_main_strings.wpls_main_geocoder_failed + ' ' + status );
				}
			} );
		},
		wpls_main_geocodePosition: function( wpls_map, pos ) {
			geocoder.geocode( { latLng: pos }, function( responses ) {
				if( responses && responses.length > 0 ) {
					marker.formatted_address = responses[ 0 ].formatted_address;

					wpls_main_object.wpls_main_store_location( responses[ 0 ] );
				} else {
					marker.formatted_address = wpls_main_strings.wpls_main_not_determine_location;
				}

				wpls_map.setCenter( pos, 17 );

				marker.setPosition( pos );

				infowindow.setContent( marker.formatted_address );
				infowindow.open( wpls_map, marker );
			} );
		},
		wpls_main_search_google_map: function( wpls_map ) {
			var input 		 = ( document.getElementById( 'wpls-map-search' ) );
			var autocomplete = new google.maps.places.Autocomplete( input );

			autocomplete.bindTo('bounds', wpls_map);

			google.maps.event.addListener( autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace();

				if ( !place.geometry ) {
					window.alert( wpls_main_strings.wpls_main_autocomplete_no_geometry );

					return;
				}

				var lat    = place.geometry.location[ 'G' ];
				var lng    = place.geometry.location[ 'K' ];
				var latlng = new google.maps.LatLng( lat, lng );

				wpls_main_object.wpls_main_geocodePosition( wpls_map, latlng );
			} );
		},
		wpls_main_store_location: function( address ) {
			var wpls_city, wpls_state, wpls_country;

			for( var i =0; i < address.address_components.length; i++ ) {
				if( address.address_components[ i ].types[ 0 ] == 'locality' ) {
					wpls_city = address.address_components[ i ];
				} else if( address.address_components[ i ].types[ 0 ] == 'administrative_area_level_1' ) {
					wpls_state = address.address_components[ i ];
				} else if( address.address_components[ i ].types[ 0 ] == 'country' ) {
					wpls_country = address.address_components[ i ];
				}
			}

			jq( '#wpls-city' ).val( wpls_city.long_name );
			jq( '#wpls-state' ).val( wpls_state.long_name );
			jq( '#wpls-country' ).val( wpls_country.long_name );
			jq( '#wpls-store-location' ).val( address.formatted_address );
		},
		wpls_main_append_location: function( wpls_location_type ) {
			if( wpls_location_type == '' ) {
				var wpls_confirm = confirm( wpls_main_strings.wpls_main_full_address_confirmation );

				if( wpls_confirm ) {
					wpls_location_type = 'full';

                    jq( '#wpls-location-type').removeClass( 'wpls-location-type-error' );
				} else {
					jq( '#wpls-location-type').addClass( 'wpls-location-type-error' );

					return;
				}
			}

			var location = wpls_main_object.wpls_main_final_location( wpls_location_type );
			var link    = '<a class="wpls-map" title="' + location + '" href="https://google.com/maps?q=' + decodeURIComponent( location ) + '" target="_blank">' + location + '</a>';

			if( jQuery( 'textarea#comment' ).length > 0 ) {
				jQuery( 'textarea#comment' ).val( jQuery( 'textarea#comment' ).val() + ' ' + link );
			} else if( jQuery( 'textarea#whats-new' ).length > 0 ) {
				jQuery( 'textarea#whats-new' ).val( jQuery( 'textarea#whats-new' ).val() + ' ' + link );
			}

			jq( '#wpls-location-type' ).val( '' );

			wpls_main_magnific_popup.close();
		},
		wpls_main_final_location: function( wpls_location_type ) {
			var wpls_city = jq( '#wpls-city' ).val();
			var wpls_state = jq( '#wpls-state' ).val();
			var wpls_country = jq( '#wpls-country' ).val();
			var wpls_full_address = jq( '#wpls-store-location' ).val();

			wpls_main_location_types[ 'city' ] = wpls_city;
			wpls_main_location_types[ 'state' ] = wpls_state;
			wpls_main_location_types[ 'country' ] = wpls_country;
			wpls_main_location_types[ 'city-state' ] = wpls_city + ', ' + wpls_state;
			wpls_main_location_types[ 'city-country' ] = wpls_city + ', ' + wpls_country;
			wpls_main_location_types[ 'state-country' ] = wpls_state + ', ' + wpls_country;
			wpls_main_location_types[ 'city-state-country' ] = wpls_city + ', ' + wpls_state + ', ' + wpls_country;
			wpls_main_location_types[ 'full' ] = wpls_full_address;

			return wpls_main_location_types[ wpls_location_type ];
		}
	};

	jq( 'document' ).ready( function() {
		wpls_main_object.main_init();

		jq( '#wpls-insert-button' ).click( function( e ) {
			e.preventDefault();

			var wpls_location_type = jq( '#wpls-location-type' ).val();

			wpls_main_object.wpls_main_append_location( wpls_location_type );
		} );
	} );

} ) ( jQuery );
