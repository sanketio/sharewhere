var sw_object,
    marker,
    geocoder,
    infowindow,
    magnific_popup;

( function ( jq ) {

	sw_object = {

		init: function () {

			sw_object.load_google_map();

		},

		load_google_map: function () {
			jq( '#sw-map' ).magnificPopup( {
				type:      'inline',
				modal:     false,
				callbacks: {

					open: function () {

						sw_object.google_map_intialize();

					}
				}
			} );

			magnific_popup = jq.magnificPopup.instance;
		},

		google_map_intialize: function () {

			jq( '#sw-location-type' ).removeClass( 'sw-location-type-error' );

			var pune       = new google.maps.LatLng( 18.5204393, 73.8567347 ),
			    my_options = {
					zoom:              17,
					mapTypeId:         google.maps.MapTypeId.ROADMAP,
					streetViewControl: false
				},
			    map = new google.maps.Map( document.getElementById( 'sw-google-map' ), my_options );

			// Try W3C Geolocation (Preferred)
			if ( navigator.geolocation ) {

				navigator.geolocation.getCurrentPosition( function ( position ) {

					sw_object.mark_out_location( map, position.coords.latitude, position.coords.longitude );

				}, function () {

					sw_object.handle_no_geo_location( true, pune, map );

				} );
			} else { // Browser doesn't support Geolocation

				sw_object.handle_no_geo_location( false, pune, map );
			}

			sw_object.search_google_map( map );
		},

		handle_no_geo_location: function ( browser_support_flag, location, map ) {

			if ( true === browser_support_flag ) {

				alert( public_strings.geolocation_service_failed );

			} else {

				alert( public_strings.geolocation_not_supported_browser );
			}

			map.setCenter( location );
		},

		mark_out_location: function ( sw_map, lattitude, longitude ) {

			geocoder   = new google.maps.Geocoder();
			infowindow = new google.maps.InfoWindow();

			var lat_long = new google.maps.LatLng( lattitude, longitude );

			geocoder.geocode( { 'location': lat_long }, function ( results, status ) {

				if ( status === google.maps.GeocoderStatus.OK ) {

					if ( results[1] ) {

						sw_map.setCenter( lat_long, 17 );

						marker = new google.maps.Marker( {
							position:  lat_long,
							draggable: true,
							map:       sw_map
						} );

						infowindow.setContent( results[0].formatted_address );
						infowindow.open( sw_map, marker );

						sw_object.store_location( results[0] );

						google.maps.event.addListener( marker, 'dragend', function ( event ) {

							sw_object.geocode_position( sw_map, marker.getPosition() );

						} );

						google.maps.event.addListener( marker, 'click', function ( event ) {

							sw_object.geocode_position( sw_map, marker.getPosition() );

						} );

						google.maps.event.addListener( sw_map, 'click', function ( event ) {

							sw_object.geocode_position( sw_map, event.latLng );

						} );
					} else {

						window.alert( public_strings.no_result_found );
					}
				} else {

					window.alert( public_strings.geocoder_failed + ' ' + status );
				}
			} );
		},

		geocode_position: function ( sw_map, pos ) {

			geocoder.geocode( { latLng: pos }, function ( responses ) {

				if ( responses && responses.length > 0 ) {

					marker.formatted_address = responses[0].formatted_address;

					sw_object.store_location( responses[0] );

				} else {

					marker.formatted_address = public_strings.not_determine_location;
				}

				sw_map.setCenter( pos, 17 );

				marker.setPosition( pos );

				infowindow.setContent( marker.formatted_address );
				infowindow.open( sw_map, marker );
			} );
		},

		search_google_map: function ( sw_map ) {

			var auto_complete = new google.maps.places.Autocomplete( document.getElementById( 'sw-map-search' ) );

			auto_complete.bindTo( 'bounds', sw_map );

			google.maps.event.addListener( auto_complete, 'place_changed', function () {

				var place = auto_complete.getPlace();

				if ( ! place.geometry ) {

					window.alert( public_strings.auto_complete_no_geometry );

					return;
				}

				var lat     = place.geometry.location['G'];
				var lng     = place.geometry.location['K'];
				var lat_lng = new google.maps.LatLng( lat, lng );

				sw_object.geocode_position( sw_map, lat_lng );
			} );
		},

		store_location: function ( address ) {

			var city,
			    state,
			    country;

			for ( var i = 0; i < address.address_components.length; i++ ) {

				if ( 'locality' === address.address_components[ i ].types[0] ) {

					city = address.address_components[ i ];

				} else if ( 'administrative_area_level_1' === address.address_components[ i ].types[0] ) {

					state = address.address_components[ i ];

				} else if ( 'country' === address.address_components[ i ].types[0] ) {

					country = address.address_components[ i ];
				}
			}

			jq( '#sw-city' ).val( city.long_name );
			jq( '#sw-state' ).val( state.long_name );
			jq( '#sw-country' ).val( country.long_name );
			jq( '#sw-store-location' ).val( address.formatted_address );
		},

		append_location: function ( location_type ) {

			var sw_location_type = jq( '#sw-location-type' );

			if ( '' === location_type ) {

				if ( confirm( public_strings.full_address_confirmation ) ) {

					location_type = 'full';

					sw_location_type.removeClass( 'sw-location-type-error' );

				} else {

					sw_location_type.addClass( 'sw-location-type-error' );

					return;
				}
			}

			var location      = sw_object.final_location( location_type ),
			    link          = '<a class="sw-map" title="' + location + '" href="https://google.com/maps?q=' + decodeURIComponent( location ) + '" target="_blank">' + location + '</a>',
			    comment_box   = jq( 'textarea#comment' ),
			    whats_new_box = jq( 'textarea#whats-new' );

			if ( comment_box.length > 0 ) {

				comment_box.val( comment_box.val() + ' ' + link );

			} else if ( whats_new_box.length > 0 ) {

				whats_new_box.val( whats_new_box.val() + ' ' + link );
			}

			sw_location_type.val( '' );

			magnific_popup.close();
		},

		final_location: function ( location_type ) {
			var city           = jq( '#sw-city' ).val(),
			    state          = jq( '#sw-state' ).val(),
			    country        = jq( '#sw-country' ).val(),
			    full_address   = jq( '#sw-store-location' ).val(),
			    location_types = [];

			location_types['city']               = city;
			location_types['state']              = state;
			location_types['country']            = country;
			location_types['city-state']         = city + ', ' + state;
			location_types['city-country']       = city + ', ' + country;
			location_types['state-country']      = state + ', ' + country;
			location_types['city-state-country'] = city + ', ' + state + ', ' + country;
			location_types['full']               = full_address;

			return location_types[ location_type ];
		}
	};

	jq( 'document' ).ready( function () {

		sw_object.init();

		jq( '#sw-insert-button' ).click( function ( e ) {

			e.preventDefault();

			sw_object.append_location( jq( '#sw-location-type' ).val() );

		} );
	} );

})( jQuery );
