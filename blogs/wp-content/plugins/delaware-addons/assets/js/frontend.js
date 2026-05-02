(function ( $ ) {
	'use strict';

	var delaware = delaware || {};
	delaware.init = function () {
		delaware.$body = $( document.body ),
			delaware.$window = $( window ),
			delaware.$header = $( '#masthead' );
		this.portfolioisotope();
		this.portfolioCarousel();
		this.delawareCounter();
		this.videoLightBox();
		this.blogCarousel();
		this.gmaps();
		//Slider stick testi
		this.testimorialStick();
		this.testimorialStick2();
		this.iconboxCarousel();

	};
	delaware.portfolioisotope = function () {


		$( '#delaware_portfolio_vs_grid' ).imagesLoaded( function () {
			$( '#delaware_portfolio_vs_grid' ).isotope( {
				itemSelector      : '.element-item',
				transitionDuration: '0.7s'
			} );
		} );


		$( '#filters' ).on( 'click', 'span', function () {
			var filterValue = $( this ).attr( 'data-filter' );
			// use filterFn if matches value
			$( '#delaware_portfolio_vs_grid' ).isotope( { filter: filterValue } );
		} );

		$( '.button-group' ).each( function ( i, buttonGroup ) {
			var $buttonGroup = $( buttonGroup );
			$buttonGroup.on( 'click', 'span', function () {
				$buttonGroup.find( '.active' ).removeClass( 'active' );
				$( this ).addClass( 'active' );
			} );
		} );

	}

	delaware.portfolioCarousel = function () {

		var $counter = $( '.delaware-portfolio-carousel' );
		if ( $counter.length === 0 ) {
			return;
		}

		var number = parseFloat( $counter.data( 'number' ) );

		$counter.not( '.slick-initialized' ).slick( {
			dots          : true,
			infinite      : true,
			slidesToShow  : number,
			slidesToScroll: number,
			responsive    : [
				{
					breakpoint: 1200,
					settings  : {
						arrows: false
					}
				},
				{
					breakpoint: 992,
					settings  : {
						arrows      : false,
						slidesToShow: number > 3 ? 3 : number,
						slidesToScroll: number > 3 ? 3 : number
					}
				},
				{
					breakpoint: 601,
					settings  : {
						arrows      : false,
						slidesToShow: number > 2 ? 2 : number,
						slidesToScroll: number > 2 ? 2 : number
					}
				},
				{
					breakpoint: 481,
					settings  : {
						arrows      : false,
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			]
		} );
	};

	/**
	 * Init post carousel
	 */
	delaware.blogCarousel = function () {
		if ( delawareShortCode.length === 0 || typeof delawareShortCode.post === 'undefined' ) {
			return;
		}

		$.each( delawareShortCode.post, function ( id, postData ) {
			if ( postData.is_carousel == 1 ) {
				var $sliders = $( document.getElementById( id ) );
				$sliders.not( '.slick-initialized' ).slick( {
					rtl           : delawareShortCode.isRTL === "1",
					dots          : postData.dot,
					autoplay      : postData.autoplay,
					autoplaySpeed : postData.autoplay_speed,
					arrows        : postData.nav,
					infinite      : true,
					slidesToShow  : 3,
					slidesToScroll: 3,
					responsive    : [
						{
							breakpoint: 1200,
							settings  : {
								arrows: false
							}
						},
						{
							breakpoint: 768,
							settings  : {
								arrows      : false,
								slidesToShow: 2
							}
						},
						{
							breakpoint: 481,
							settings  : {
								arrows      : false,
								slidesToShow: 1
							}
						}
					]
				} );
			}
		} );

	};

	/**
	 * Init Google maps
	 */
	delaware.gmaps = function () {

		if ( delawareShortCode.length === 0 || typeof delawareShortCode.map === 'undefined' ) {
			return;
		}

		var mapOptions = {
				scrollwheel       : false,
				draggable         : true,
				zoom              : 10,
				mapTypeId         : google.maps.MapTypeId.ROADMAP,
				panControl        : false,
				zoomControl       : true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL
				},
				scaleControl      : false,
				streetViewControl : false

			},
			customMap;

		var bounds = new google.maps.LatLngBounds();
		var infoWindow = new google.maps.InfoWindow();

        $.each(delawareShortCode.map, function (id, mapData) {
            var map_color = mapData.map_color;

            var styles =
                [
                    {
                        'featureType': 'administrative',
                        'elementType': 'labels.text.fill',
                        'stylers': [{'color': '#444444'}]
                    },
                    {
                        'featureType': 'landscape',
                        'elementType': 'all',
                        'stylers': [{'color': '#f2f2f2'}]
                    },
                    {
                        'featureType': 'landscape',
                        'elementType': 'geometry.fill',
                        'stylers': [{'color': '#f2f2f2'}]
                    },
                    {
                        'featureType': 'landscape',
                        'elementType': 'geometry.stroke',
                        'stylers': [{'color': '#000000'}]
                    },
                    {
                        'featureType': 'poi',
                        'elementType': 'all',
                        'stylers': [{'visibility': 'off'}]
                    },
                    {
                        'featureType': 'road',
                        'elementType': 'all',
                        'stylers': [{'saturation': -100}, {'lightness': 45}]
                    },
                    {
                        'featureType': 'road.highway',
                        'elementType': 'all',
                        'stylers': [{'visibility': 'simplified'}]
                    },
                    {
                        'featureType': 'road.arterial',
                        'elementType': 'labels.icon',
                        'stylers': [{'visibility': 'off'}]
                    },
                    {
                        'featureType': 'road.local',
                        'elementType': 'geometry.fill',
                        'stylers': [{'color': '#e6e6e6'}]
                    },
                    {
                        'featureType': 'transit',
                        'elementType': 'all',
                        'stylers': [{'visibility': 'off'}]
                    },
                    {
                        'featureType': 'water',
                        'elementType': 'all',
                        'stylers': [{'visibility': 'on'}, {'color': map_color}]
                    }
                ];

            customMap = new google.maps.StyledMapType(styles,
                {name: 'Styled Map'});

            if (mapData.number > 1) {
                mutiMaps(infoWindow, bounds, mapOptions, mapData, id, styles, customMap);
            } else {
                singleMap(mapOptions, mapData, id, styles, customMap);
            }

        });

    };

	function singleMap( mapOptions, mapData, id, styles, customMap ) {
		var map,
			marker,
			location = new google.maps.LatLng( mapData.lat, mapData.lng );

		// Update map options
		mapOptions.zoom = parseInt( mapData.zoom, 10 );
		mapOptions.center = location;
		mapOptions.mapTypeControlOptions = {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP]
		};

		// Init map
		map = new google.maps.Map( document.getElementById( id ), mapOptions );

		// Create marker options
		var markerOptions = {
			map     : map,
			position: location
		};
		if ( mapData.marker ) {
			markerOptions.icon = {
				url: mapData.marker
			};
		}

		map.mapTypes.set( 'map_style', customMap );
		map.setMapTypeId( 'map_style' );

		// Init marker
		marker = new google.maps.Marker( markerOptions );

		if ( mapData.info ) {
			var infoWindow = new google.maps.InfoWindow( {
				content : '<div class="info-box mf-map">' + mapData.info + '</div>',
				maxWidth: 600
			} );

			google.maps.event.addListener( marker, 'click', function () {
				infoWindow.open( map, marker );
			} );
		}
	}

	function mutiMaps( infoWindow, bounds, mapOptions, mapData, id, styles, customMap ) {

		// Display a map on the page
		mapOptions.zoom = parseInt( mapData.zoom, 10 );
		mapOptions.mapTypeControlOptions = {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP]
		};

		var map = new google.maps.Map( document.getElementById( id ), mapOptions );
		map.mapTypes.set( 'map_style', customMap );
		map.setMapTypeId( 'map_style' );
		for ( var i = 0; i < mapData.number; i++ ) {
			var lats = mapData.lat,
				lng = mapData.lng,
				info = mapData.info;

			var position = new google.maps.LatLng( lats[i], lng[i] );
			bounds.extend( position );

			// Create marker options
			var markerOptions = {
				map     : map,
				position: position
			};
			if ( mapData.marker ) {
				markerOptions.icon = {
					url: mapData.marker
				};
			}

			// Init marker
			var marker = new google.maps.Marker( markerOptions );

			// Allow each marker to have an info window
			googleMaps( infoWindow, map, marker, info[i] );

			// Automatically center the map fitting all markers on the screen
			map.fitBounds( bounds );
		}
	}

	function googleMaps( infoWindow, map, marker, info ) {
		google.maps.event.addListener( marker, 'click', function () {
			infoWindow.setContent( info );
			infoWindow.open( map, marker );
		} );
	}

	delaware.delawareCounter = function () {

		var $counter = $( ".counter-value .counter-number" );
		if ( $counter.length < 1 ) {
			return;
		}

		var duaration = parseFloat( $counter.data( 'duaration' ) );
		duaration = duaration * 1000;
		$counter.counterUp( {
			delay: 10,
			time : duaration
		} );

	};


	/*
	 * Toggle video banner play button
	 */
	delaware.videoLightBox = function () {
		var $images = $( '.mf-video-banner' );

		if ( !$images.length ) {
			return;
		}

		var $links = $images.find( 'a.photoswipe' ),
			items = [];

		$links.each( function () {
			var $a = $( this );

			items.push( {
				html: $a.data( 'href' )
			} );

		} );

		$images.on( 'click', 'a.photoswipe', function ( e ) {
			e.preventDefault();

			var index = $links.index( $( this ) ),
				options = {
					index              : index,
					bgOpacity          : 0.85,
					showHideOpacity    : true,
					mainClass          : 'pswp--minimal-dark',
					barsSize           : { top: 0, bottom: 0 },
					captionEl          : false,
					fullscreenEl       : false,
					shareEl            : false,
					tapToClose         : true,
					tapToToggleControls: false
				};

			var lightBox = new PhotoSwipe( document.getElementById( 'pswp' ), window.PhotoSwipeUI_Default, items, options );
			lightBox.init();

			lightBox.listen( 'close', function () {
				$( '.mf-video-wrapper' ).find( 'iframe' ).each( function () {
					$( this ).attr( 'src', $( this ).attr( 'src' ) );
				} );
			} );
		} );
	};

	delaware.testimorialStick = function () {
		$.each( delawareShortCode.testimonialsCarousel, function ( id, imagesData ) {
			var $sliders = $( document.getElementById( id ) );
			$sliders.not( '.slick-initialized' ).slick( {
				slidesToShow  : imagesData.slide,
				slidesToScroll: 1,
				infinite      : false,
				arrows        : imagesData.nav,
				autoplay      : imagesData.autoplay,
				autoplaySpeed : imagesData.speed,
				dots          : imagesData.dot,
				prevArrow     : '<div class="delaware-left-arrow"><span class="dl-icon-next svg-icon"><i class="fa fa-angle-left"></i></span></div>',
				nextArrow     : '<div class="delaware-right-arrow"><span class="dl-icon-next svg-icon"><i class="fa fa-angle-right"></i></span></div>',
				responsive    : [
					{
						breakpoint: 1200,
						settings  : {
							arrows: false,
							dots  : true
						}
					},
					{
						breakpoint: 992,
						settings  : {
							arrows      : false,
							dots        : true,
							slidesToShow: 1
						}
					},
					{
						breakpoint: 481,
						settings  : {
							arrows      : false,
							dots        : true,
							slidesToShow: 1
						}
					}
				]
			} );
		} );
	};
	delaware.testimorialStick2 = function () {
		$.each( delawareShortCode.testimonialsCarousel2, function ( id, imagesData ) {
			var $sliders = $( document.getElementById( id ) );
			$sliders.not( '.slick-initialized' ).slick( {
				slidesToShow  : 1,
				slidesToScroll: 1,
				infinite      : true,
				arrows        : imagesData.nav,
				autoplay      : imagesData.autoplay,
				autoplaySpeed : imagesData.speed,
				dots          : imagesData.dot,
				prevArrow     : $( '.testi-2-prev' ),
				nextArrow     : $( '.testi-2-next' ),
				//appendArrows  : $container,
				responsive    : [
					{
						breakpoint: 1200,
						settings  : {
							arrows: false,
							dots  : true
						}
					},
					{
						breakpoint: 768,
						settings  : {
							arrows: false,
							dots  : true
						}
					},
					{
						breakpoint: 480,
						settings  : {
							arrows: false,
							dots  : true
						}
					}
				]
			} );
		} );
	};

    delaware.iconboxCarousel = function () {
        $.each(delawareShortCode.iconboxCarousel, function (id, imagesData) {
            var $sliders = $(document.getElementById(id));
            $sliders.not('.slick-initialized').slick({
                infinite     : true,
                arrows       : false,
                autoplay     : imagesData.autoplay,
                autoplaySpeed: imagesData.speed,
                dots         : imagesData.dot,
                cssEase      : 'ease-out',
                prevArrow    : '',
                nextArrow    : '',
                slidesToShow : 3,
                slidesToScroll: 1,
                responsive   : [
                    {
                        breakpoint: 991,
                        settings  : {
                            arrows: false,
                            dots  : true
                        }
                    },
                    {
                        breakpoint: 768,
                        settings  : {
                            arrows: false,
                            dots  : true,
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings  : {
                            arrows: false,
                            dots  : true,
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });
    };


    /**
	 * Document ready
	 */
	$( function () {
		delaware.init();
	} );

})( jQuery );