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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .header-logo {
            max-width: none !important;
        }
    </style>
</head>


<body>
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <div class="flex flex-col items-center justify-center min-h-screen">

        <div class="w-full text-center mb-8">
            <a href="{{ route('home') }}">
                <img class="mx-auto" src="{{ asset('images/logo/glosse_logo.svg') }}" alt="logo">
            </a>
        </div>

        <div class="w-1/2 text-center">
            <h3 class="text-3xl font-bold">{{ __('Your subscription has been successfully canceled.') }}</h3>
            <a href="{{ route('home') }}" class="vs-btn style2 d-none d-xl-inline-block rounded-4">{{ __('Back To Home') }}</a>
        </div>

    </div>





    <!-- header area -->


    <!-- main content -->
    @yield('content')
    <!-- end main content -->

    <!-- footer-->





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

    @stack('footer_scripts')
</body>
</html>
