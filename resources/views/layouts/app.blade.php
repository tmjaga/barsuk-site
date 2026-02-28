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
    <link rel="stylesheet" href="{{ asset('') }}assets/css/bootstrap.min.css">
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
                <a href="index.html"><img src="assets/img/logo.svg" alt="Wellnez"></a>
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
                        <a href="about.html">About Us</a>
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
            <div class="widget  ">
                <div class="footer-logo">
                    <img src="assets/img/logo.svg" alt="logo">
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
    <header class="vs-header header-layout1">
        <div class="header-top">
            <div class="container">
                <div class="row justify-content-center justify-content-md-between align-items-center">
                    <div class="col-auto text-center py-2 py-md-0">
                        <div class="header-links style-white">
                            <ul>
                                @if(settings()->address)
                                <li class="d-none d-xxl-inline-block"><i class="far fa-map-marker-alt"></i>{{ settings()->address }}</li>
                                @endif
                                <li><i class="far fa-phone-alt"></i><a href="tel:+25632542598">{{ settings()->formatted_phone }}</a></li>
                                <li><i class="far fa-envelope"></i><a href="mailto:{{ settings()->email }}">{{ settings()->email }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-auto d-none d-md-block">
                        <div class="social-style1">
                            @if(settings()->fb_link)
                                <a href="{{ settings()->fb_link }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if(settings()->inst_link)
                                <a href="{{ settings()->inst_link }}" target="_blank"><i class="fab fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-wrap">
            <div class="sticky-active">
                <div class="container">
                    <div class="row justify-content-between align-items-center gx-60">
                        <div class="col">
                            <div class="header-logo">
                                <a href="index.html"><img src="assets/img/logo.svg" alt="logo"></a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <nav class="main-menu menu-style1 d-none d-lg-block">
                                <ul>
                                    <li class="menu-item-has-children mega-menu-wrap">
                                        <a href="index.html">Demos</a>
                                        <ul class="menu-pages">
                                            <li>
                                                <a href="index.html">
                                                    <img src="assets/img/pages/index.jpg" alt="image">
                                                    Demo Style 1
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-2.html">
                                                    <img src="assets/img/pages/index-2.jpg" alt="image">
                                                    Demo Style 2
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-3.html">
                                                    <img src="assets/img/pages/index-3.jpg" alt="image">
                                                    Demo Style 3
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-4.html">
                                                    <img src="assets/img/pages/index-4.jpg" alt="image">
                                                    Demo Style 4
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-5.html">
                                                    <img src="assets/img/pages/index-5.jpg" alt="image">
                                                    Demo Style 5
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-6.html">
                                                    <img src="assets/img/pages/index-6.jpg" alt="image">
                                                    Demo Style 6
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-7.html">
                                                    <img src="assets/img/pages/index-7.jpg" alt="image">
                                                    Demo Style 7 <span class="new-label">New</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-8.html">
                                                    <img src="assets/img/pages/index-8.jpg" alt="image">
                                                    Demo Style 8 <span class="new-label">New</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-9.html">
                                                    <img src="assets/img/pages/index-9.jpg" alt="image">
                                                    Demo Style 9 <span class="new-label">New</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index-10.html">
                                                    <img src="assets/img/pages/index-10.jpg" alt="image">
                                                    Demo Style 10 <span class="new-label">New</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <img src="assets/img/pages/index-11.jpg" alt="image">
                                                    Demo Style 11 <span class="new-label">New</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <img src="assets/img/pages/index-12.jpg" alt="image">
                                                    Demo Style 12 <span class="new-label">New</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="about.html">About Us</a>
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
                                    <li class="menu-item-has-children mega-menu-wrap">
                                        <a href="#">Pages</a>
                                        <ul class="mega-menu">
                                            <li><a href="shop.html">Pagelist 1</a>
                                                <ul>
                                                    <li><a href="about.html">About Us</a></li>
                                                    <li><a href="blog.html">Blog One</a></li>
                                                    <li><a href="blog-2.html">Blog Two</a></li>
                                                    <li><a href="blog-details.html">Blog Details</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Pagelist 2</a>
                                                <ul>
                                                    <li><a href="appointment.html">Appointment</a></li>
                                                    <li><a href="price-plan.html">Price Plan</a></li>
                                                    <li><a href="service.html">Services</a></li>
                                                    <li><a href="service-details.html">Service Details</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Pagelist 3</a>
                                                <ul>
                                                    <li><a href="gallery.html">Portfolio</a></li>
                                                    <li><a href="gallery-details.html">Portfolio Details</a></li>
                                                    <li><a href="team.html">Team</a></li>
                                                    <li><a href="team-details.html">Team Details</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Pagelist 4</a>
                                                <ul>
                                                    <li><a href="shop.html">Shop</a></li>
                                                    <li><a href="shop-details.html">Shop Details</a></li>
                                                    <li><a href="contact.html">Contact Us</a></li>
                                                    <li><a href="error.html">Error Page</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="contact.html">Contact Us</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <div class="header-icons">
                                <!--
                                <button class="searchBoxTggler"><i class="far fa-search"></i></button>
                                -->
                                <a href="contact.html" class="vs-btn style2 d-none d-xl-inline-block rounded-4">Book</a>

                                <button class="bar-btn sideMenuToggler d-none d-xl-inline-block">
                                    <span class="bar"></span>
                                    <span class="bar"></span>
                                    <span class="bar"></span>
                                </button>
                                <button class="vs-menu-toggle d-inline-block d-lg-none" type="button"><i class="fal fa-bars"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>




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
