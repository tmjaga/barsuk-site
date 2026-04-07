@extends('layouts.app')
@section('content')
    <!-- breadcumb -->
    <div class="breadcumb-wrapper " data-bg-src="{{ asset('assets/img/breadcumb/services_bg.jpg') }}">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">{!! __('Service :target', ['target' => '<span class="text-theme">'.__('Details').'</span>']) !!}</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li>{{ __('Service Details') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Details -->
    <section class=" space-top space-extra-bottom">
        <div class="container">
            <div class="row flex-row-reverse gx-50">
                <div class="col-lg-8 col-xl mb-30 mb-lg-0">
                    <div class="mb-30">
                        <div class="mega-hover"><img src="{{ asset('assets/img/categories/category_head.jpg') }}" alt="thumbnail"></div>
                    </div>
                    <h2 class="text-uppercase">Custom {!! page('categories.category_header') !!}</h2>
                    <p class="pb-1">{!! page('categories.category_text_1') !!}</p>


                    <div class="row">
                        <div class="col-6 mb-30">
                            <div class="mega-hover"><img src="assets/img/service/s-d-1-2.jpg" alt="shape" class="w-100"></div>
                        </div>
                        <div class="col-6 mb-30">
                            <div class="mega-hover"><img src="assets/img/service/s-d-1-3.jpg" alt="shape" class="w-100"></div>
                        </div>
                    </div>
                    <p class="pb-1">{!! page('categories.category_text_2') !!}</p>
                    {{--
                    <h3 class="h4">Replenish female give him</h3>
                    <p class="pb-1">We think your skin should look and refshed matter Nourish your outer inner beauty with our essential oil infused beauty products Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt labore et dolore magna aliqua. Ut enim adminim veniam. Lorem ipsum dolor sit amet, consectetur adipiscing Curabitur ut iaculis arcu. Proin tincidunt, ipsum nec vehicula euismod, neque nibh pretium.</p>
                    --}}
                </div>
                <div class="col-lg-4 col-xl-auto">
                    <aside>
                        <div class="service-box">
                            <x-common.catalog-menu :categories="$categories" :current-category="$currentSlug" />
                        </div>
                        <!-- Promotion block -->
                        {{--
                        <span class="sec-subtitle4">Try some</span>
                        <div class="img-box3 style3">
                            <div class="img-2 jump-img"><img src="assets/img/shape/leaf-1-7.png" alt="about"></div>
                            <div class="img-product">
                                <a href="shop-details.html"><img src="assets/img/about/price-2-1-1.png" alt="about"></a>
                                <p class="product-title"><a href="shop-details.html" class="text-inherit">face vitamin</a></p>
                                <p class="product-price">$12.00</p>
                            </div>
                        </div>
                        --}}
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
