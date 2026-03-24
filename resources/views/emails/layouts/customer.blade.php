<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Sleek Newsletter</title>
    <style>
        progress {
            display: none;
        }

        figcaption {
            display: none;
        }

        ul {
            list-style: disc;
            padding-left: 20px; }
        ol {
            list-style: decimal;
            padding-left: 20px;
        }
    </style>

</head>
<body>
<div class="w-3/5 mx-auto py-4">

    <!-- header -->
    <div class="relative bg-[#fde7da] p-5 overflow-hidden">
        <img src="{{ asset('/images/shape/grid-01.svg') }}" alt=""
             class="pointer-events-none absolute right-0 top-0 h-full max-w-[250px] xl:max-w-[450px] object-contain" />
        <img src="{{ asset('images/logo/glosse_gray_sm.svg') }}" alt="Logo" />
    </div>
    <!-- end header -->

    <!-- content -->
    <div class="p-4 bg-white">
        @yield('content')
    </div>
    <!-- end content -->

    <div class=" bg-white">
        @yield('before-footer-content')
    </div>

    <!-- footer-->
    <div class="text-sm text-gray-800 relative bg-[#fde7da] text-center p-1">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
    <!-- emd footer-->
</div>

</body>


