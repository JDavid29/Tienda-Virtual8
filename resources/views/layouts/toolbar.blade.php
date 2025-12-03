<!doctype html>
<html class="no-js" lang="zxx">

<!-- index-431:41-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Tienda Virtual Version 2 || ECommerce Para Tienda De Productos informaticos Bootstrap 4 Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon o otro de una tienda virtual -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/icons8-tienda-online-100.png') }}">
    <!-- Material Design Iconic Font-V2.2.0 -->
    <link rel="stylesheet" href="{{ asset('css/material-design-iconic-font.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <!-- Font Awesome Stars-->
    <link rel="stylesheet" href="{{ asset('css/fontawesome-stars.css') }}">
    <!-- Meanmenu CSS -->
    <link rel="stylesheet" href="{{ asset('css/meanmenu.css') }}">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <!-- Jquery-ui CSS -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
    <!-- Venobox CSS -->
    <link rel="stylesheet" href="{{ asset('css/venobox.css') }}">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <!-- Bootstrap V4.1.3 Fremwork CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Helper CSS -->
    <link rel="stylesheet" href="{{ asset('css/helper.css') }}">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <!-- Modernizr js -->
    <script src="{{ asset('js/vendor/modernizr-2.8.3.min.js') }}"></script>
    @livewireStyles
</head>

<body>
    <!--[if lt IE 8]>
  <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
 <![endif]-->
    <!-- Begin Body Wrapper -->
    <div class="body-wrapper">
        <!-- Begin Header Area -->
        {{-- HEADER ---> Encabezamiento --}}
        <header class="li-header-4">
            {{-- TOOLBAR --> BARRA DE HERRAMIENTAS --}}
            @livewire('toolbar-component')
            {{-- FIN TOOLBAR --}}

            {{-- MENU PRINCIPAL --}}
            {{-- @include("layouts.menu-main") --}}
            {{-- FIN MENU PRINCIPAL --}}

        </header>
        <!-- Header Area End Here -->
        <!-- Begin Slider With Banner Area -->

        <!-- Slider With Banner Area End Here | El carrusel con área de banner termina aquí. -->

        <!-- product-area start -->
        <div class="product-area">
            <div class="container">
                {{-- contenido dinámico --}}
                @yield('content')
                {{-- cortado para mostrar en secciones --}}

            </div>
        </div>
        <!-- product-area end -->
        <!--comenzar el área del pie de página -  Begin Footer Area -->
        @include("layouts.footer")

        <!-- Quick View | Modal Area rendered by Livewire -->
        @livewire('quick-view')
    </div>
    <!-- Body Wrapper End Here -->
    <!-- jQuery-V1.12.4 -->
    <script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
    <!-- Popper js SE ESTA COMENTANDO POR LOS PROBLEMAS DE CARGA DE CONETNIDO EN CHROME-->
    <script src="{{ asset('js/vendor/popper.min.js') }}"></script>
    <!-- Bootstrap V4.1.3 Fremwork js -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Ajax Mail js -->
    <script src="{{ asset('js/ajax-mail.js') }}"></script>
    <!-- Meanmenu js -->
    <script src="{{ asset('js/jquery.meanmenu.min.js') }}"></script>
    <!-- Wow.min js -->
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <!-- Slick Carousel js -->
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <!-- Owl Carousel-2 js -->
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <!-- Magnific popup js -->
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Isotope js -->
    <script src="{{ asset('js/isotope.pkgd.min.js') }}"></script>
    <!-- Imagesloaded js -->
    <script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>
    <!-- Mixitup js -->
    <script src="{{ asset('js/jquery.mixitup.min.js') }}"></script>
    <!-- Countdown -->
    <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
    <!-- Counterup -->
    <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
    <!-- Waypoints -->
    <script src="{{ asset('js/waypoints.min.js') }}"></script>
    <!-- Barrating -->
    <script src="{{ asset('js/jquery.barrating.min.js') }}"></script>
    <!-- Jquery-ui -->
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <!-- Venobox -->
    <script src="{{ asset('js/venobox.min.js') }}"></script>
    <!-- Nice Select js -->
    <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
    <!-- ScrollUp js -->
    <script src="{{ asset('js/scrollUp.min.js') }}"></script>
    <!-- Main/Activator js -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Google Map -->
    <!-- NOTE: The Maps API requires billing enabled for full functionality. If billing is not enabled the API will return an error (BillingNotEnabledMapError).
         We load the script with async/defer and protect the initialization with feature checks and try/catch so map failures don't break the whole page. -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry&amp;v=weekly&amp;key=AIzaSyChs2QWiAhnzz0a4OEhzqCXwx_qA9ST_lE"></script>

    <script>
        // Safe map initialization: guard for missing API or errors so the page doesn't break.
        window.addEventListener('load', function () {
            try {
                // Only attempt to initialize if the API loaded and the map container exists
                if (!window.google || !google.maps) {
                    console.warn('Google Maps API not available (billing may be disabled or API blocked).');
                    return;
                }
                // If the map container is not present on the current page, skip initialization
                var mapElement = document.getElementById('google-map');
                if (!mapElement) return;

                // Basic options for a simple Google Map
                var mapOptions = {
                    zoom: 12,
                    scrollwheel: false,
                    center: new google.maps.LatLng(-22.002372, -63.674233),
                    styles: [{
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#e9e9e9"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 29
                            },
                            {
                                "weight": 0.2
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 18
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#dedede"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [{
                                "saturation": 36
                            },
                            {
                                "color": "#333333"
                            },
                            {
                                "lightness": 40
                            }
                        ]
                    },
                    {
                        "elementType": "labels.icon",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f2f2f2"
                            },
                            {
                                "lightness": 19
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [{
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 17
                            },
                            {
                                "weight": 1.2
                            }
                        ]
                    }
                ]
            };

                // Create the Google Map using our element and options defined above
                var map = new google.maps.Map(mapElement, mapOptions);

                // Add a marker (note: google.maps.Marker is deprecated; consider migrating to AdvancedMarkerElement)
                try {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(40.740610, -73.935242),
                        map: map,
                        title: 'Limupa',
                        animation: google.maps.Animation.BOUNCE
                    });
                } catch (markerErr) {
                    console.warn('Marker creation failed (deprecated API or unsupported).', markerErr);
                }
            } catch (err) {
                console.error('Google Maps initialization failed:', err);
            }
        });
    </script>

    @livewireScripts
</body>

<!-- index-431:47-->

</html>
