<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Front End' }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>



    <!-- Alpine.js -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}


    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @stack('header_scripts')

</head>

<body>


<div class="min-h-screen xl:flex">


    <div class="flex-1 transition-all duration-300 ease-in-out"
         :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
        <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
            @yield('content')
        </div>
    </div>

</div>

@stack('footer_css')

@stack('footer_scripts')

</body>
</html>
