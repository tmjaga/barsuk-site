<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Sleek Newsletter</title>
    @vite(['resources/css/app.css'])
</head>
<body>
<div class="w-3/5 mx-auto py-4">

    <!-- header -->
    <div class="relative bg-brand-950 p-5 overflow-hidden">
        <img src="{{ asset('/images/shape/grid-01.svg') }}" alt=""
             class="pointer-events-none absolute right-0 top-0 h-full max-w-[250px] xl:max-w-[450px] object-contain" />
        <img src="{{ asset('images/logo/auth-logo.svg') }}" alt="Logo" />
    </div>
    <!-- end header -->

    <!-- content -->
    <div class="p-4 bg-white">
        @yield('content')
    </div>
    <!-- end content -->
    <!-- footer-->
    <div class="text-sm text-gray-400 relative bg-brand-950 text-center p-1">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
    <!-- emd footer-->

</div>

</body>


