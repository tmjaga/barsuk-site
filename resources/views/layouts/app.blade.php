<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Glosse - Nail Studio</title>
    <meta name="author" content="Vecuro">
    <meta name="description" content="Glosse - Nail Studio">
    <meta name="keywords" content="beauty, beauty salon, beauty shop, beauty spa, cosmetics, hairdresser, health, lifestyle, massage, salon, spa, spa booking, wellness, wellness template, yoga">
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">

    <!--google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Marcellus&display=swap" rel="stylesheet">

    <!--all css files -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- <link rel="stylesheet" href="assets/css/app.min.css"> -->
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- Layerslider -->
    <link rel="stylesheet" href="{{ asset('assets/css/layerslider.min.css') }}">
    <!-- jQuery DatePicker -->
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
    <!-- Slick Slider -->
    <link rel="stylesheet" href="{{ asset('assets/css/slick.min.css') }}">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .closeButton {
            width: 40px !important;
            height: 40px !important;
            line-height: 40px !important;
            top: 10px !important;
        }

        .footer-top {
            margin: 0 0 10px 0 !important;
            padding: 40px 0 !important;
        }

        .social-style-custom {
            padding: 10px 5px !important;
        }

        .social-style-custom a:hover {
            background-color: #3d4952ff !important;
            color: #fff !important;

        }

        .social-style-custom .dropdown-item.active,
        .social-style-custom .dropdown-item:active {
            background-color: #3d4952ff;
            color: #fff;
        }
        .header-logo {
            max-width: none !important;
        }
    </style>
</head>


<body>
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Preloader -->
    <div class="preloader  ">
        <button class="vs-btn preloaderCls">Cancel</button>
        <div class="preloader-inner">
            <div class="loader"></div>
        </div>
    </div>
    <svg viewBox="0 0 150 150" class="svg-hidden">
        <path id="textPath" d="M 0,75 a 75,75 0 1,1 0,1 z"></path>
    </svg>

    <!-- Mobile Menu -->
    <div class="vs-menu-wrapper">
        <div class="vs-menu-area text-center">
            <button class="vs-menu-toggle"><i class="fal fa-times"></i></button>
            <div class="mobile-logo">
                <a href="{{ route('home') }}"><img src="{{ asset('images/logo/glosse_logo.svg') }}" alt="Glosse"></a>
            </div>
            <div class="vs-mobile-menu">
                <ul>
                    <li class="menu-item-has-children">
                        <a href="index.html">Demos</a>
                        <ul class="sub-menu">
                            <li><a href="index.html">Demo Style 1</a></li>
                            <li><a href="index-2.html">Demo Style 2</a></li>
                            <li><a href="index-3.html">Demo Style 3</a></li>
                            <li><a href="index-4.html">Demo Style 4</a></li>
                            <li><a href="index-5.html">Demo Style 5</a></li>
                            <li><a href="index-6.html">Demo Style 6</a></li>
                            <li><a href="index-7.html">Demo Style 7<span class="new-label">New</span></a></li>
                            <li><a href="index-8.html">Demo Style 8<span class="new-label">New</span></a></li>
                            <li><a href="index-9.html">Demo Style 9<span class="new-label">New</span></a></li>
                            <li><a href="index-10.html">Demo Style 10<span class="new-label">New</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href=" {{ route('about') }}">{{ __('About Us') }}</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="match.html">Service</a>
                        <ul class="sub-menu">
                            <li><a href="service.html">Services</a></li>
                            <li><a href="service-details.html">Service Details</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="blog.html">Blog</a>
                        <ul class="sub-menu">
                            <li><a href="blog.html">Blog One</a></li>
                            <li><a href="blog-2.html">Blog Two</a></li>
                            <li><a href="blog-details.html">Blog Details</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">Pages</a>
                        <ul class="sub-menu">
                            <li><a href="shop.html">Shop</a></li>
                            <li><a href="shop-details.html">Shop Details</a></li>
                            <li><a href="gallery.html">Portfolio</a></li>
                            <li><a href="gallery-details.html">Portfolio Details</a></li>
                            <li><a href="appointment.html">Appointment</a></li>
                            <li><a href="price-plan.html">Price Plan</a></li>
                            <li><a href="contact.html">Contact Us</a></li>
                            <li><a href="error.html">Error Page</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="contact.html">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- sidemenu -->
    <div class="sidemenu-wrapper d-none d-lg-block  ">
        <div class="sidemenu-content">
            <button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>
            <div class="widget mt-3">
                <div class="footer-logo">
                    <img src="{{ asset('images/logo/glosse_logo.svg') }}" alt="logo">
                </div>
                <div class="info-media1">
                    <div class="media-icon"><i class="fal fa-map-marker-alt"></i></div>
                    <span class="media-label">Centerl Park West La, New York</span>
                </div>
                <div class="info-media1">
                    <div class="media-icon"><i class="far fa-phone-alt "></i></div>
                    <span class="media-label"><a href="tel:+01234567890" class="text-inherit">+0 123 456 7890</a></span>
                </div>
                <div class="info-media1">
                    <div class="media-icon"><i class="fal fa-envelope"></i></div>
                    <span class="media-label"><a class="text-inherit" href="mailto:info@example.com">info@example.com</a></span>
                </div>
            </div>
            <div class="widget  ">
                <h3 class="widget_title">Latest post</h3>
                <div class="recent-post-wrap">
                    <div class="recent-post">
                        <div class="media-img">
                            <a href="blog-details.html"><img src="assets/img/widget/recent-post-1-1.jpg" alt="Blog Image"></a>
                        </div>
                        <div class="media-body">
                            <h4 class="post-title"><a class="text-inherit" href="blog-details.html">Skinscent Experience Oskarsson</a></h4>
                            <div class="recent-post-meta">
                                <a href="blog.html"><i class="fas fa-calendar-alt"></i>march 10, 2023</a>
                            </div>
                        </div>
                    </div>
                    <div class="recent-post">
                        <div class="media-img">
                            <a href="blog-details.html"><img src="assets/img/widget/recent-post-1-2.jpg" alt="Blog Image"></a>
                        </div>
                        <div class="media-body">
                            <h4 class="post-title"><a class="text-inherit" href="blog-details.html">Lorem ipsum is placeholder recent popular</a></h4>
                            <div class="recent-post-meta">
                                <a href="blog.html"><i class="fas fa-calendar-alt"></i>Augest 10, 2023</a>
                            </div>
                        </div>
                    </div>
                    <div class="recent-post">
                        <div class="media-img">
                            <a href="blog-details.html"><img src="assets/img/widget/recent-post-1-3.jpg" alt="Blog Image"></a>
                        </div>
                        <div class="media-body">
                            <h4 class="post-title"><a class="text-inherit" href="blog-details.html">From its medieval origins health custom</a></h4>
                            <div class="recent-post-meta">
                                <a href="blog.html"><i class="fas fa-calendar-alt"></i>July 11, 2023</a>
                            </div>
                        </div>
                    </div>
                    <div class="recent-post">
                        <div class="media-img">
                            <a href="blog-details.html"><img src="assets/img/widget/recent-post-1-4.jpg" alt="Blog Image"></a>
                        </div>
                        <div class="media-body">
                            <h4 class="post-title"><a class="text-inherit" href="blog-details.html">Letraset's dry-transfer sheets later</a></h4>
                            <div class="recent-post-meta">
                                <a href="blog.html"><i class="fas fa-calendar-alt"></i>March 05, 2023</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- popup search box -->
    <!--
    <div class="popup-search-box d-none d-lg-block  ">
        <button class="searchClose"><i class="fal fa-times"></i></button>
        <form action="#">
            <input type="text" class="border-theme" placeholder="What are you looking for">
            <button type="submit"><i class="fal fa-search"></i></button>
        </form>
    </div>
    -->

    <!-- header area -->
    @include('layouts.header')

    <!-- main content -->
    @yield('content')
    <!-- end main content -->

    <!-- footer-->
    @include('layouts.footer')

    <!-- Scroll To Top -->
    <a href="#" class="scrollToTop scroll-btn"><i class="far fa-arrow-up"></i></a>

    <!-- all js files -->
    <!-- Jquery -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

    <!-- jQuery UI -->
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <!-- Slick Slider -->
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <!-- <script src="assets/js/app.min.js"></script> -->

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Parallax Scroll -->
    <script src="{{ asset('assets/js/universal-parallax.min.js') }}"></script>
    <!-- Wow.js Animation -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <!-- jQuery Datepicker -->
    <script src="{{ asset('assets/js/jquery.datetimepicker.min.js') }}"></script>
    <!-- Magnific Popup -->
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Isotope Filter -->
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <!-- Main Js File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
