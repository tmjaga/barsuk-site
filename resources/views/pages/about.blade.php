@extends('layouts.app')
@section('content')
    <!-- breadcumb -->
    <div class="breadcumb-wrapper " data-bg-src="{{ asset('assets/img/breadcumb/about_bg.jpg') }}">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">{{ __('About Us') }}</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li>{{ __('About Us') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- About Area -->
    <section class=" space-top space-extra-bottom">
        <div class="shape-mockup jump-img d-none d-xl-block" data-left="34%" data-bottom="1%"><img src="{{ asset('assets/img/shape/leaf-1-6.png') }}" alt="shape"></div>
        <div class="container">
            <div class="row justify-content-between gx-0 ">
                <div class="col-md-10">
                    <span class="sec-subtitle">{{ __('welcome') }}</span>
                    <h2 class="h3 pe-xxl-5 me-xxl-5 mb-md-5 pb-xl-3">
                        {!!  page('about.about_text_1') !!}
                    </h2>
                </div>
                <div class="col-auto mb-5 mb-md-0">
                    <div class="pt-1 mt-2">
                        <div class="circle-btn style2">
                            <a href="service.html" class="btn-icon"><i class="far fa-arrow-right"></i></a>
                            <div class="btn-text">
                                <svg viewBox="0 0 150 150">
                                    <text>
                                        <textPath href="#textPath">{{ __('to check our glosse top rated services') }}</textPath>
                                    </text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vs-carousel mb-30 pb-1 wow fadeInUp" data-wow-delay="0.2s" data-fade="true">
                <div><img src="{{ asset('assets/img/about/ab-4-1.jpg') }}" alt="about" class="w-100"></div>
                <div><img src="{{ asset('assets/img/about/ab-4-2.jpg') }}" alt="about" class="w-100"></div>
                <div><img src="{{ asset('assets/img/about/ab-4-3.jpg') }}" alt="about" class="w-100"></div>
            </div>
            <p class="fs-22 font-title text-title mb-4 mb-lg-5">
                {!!  page('about.about_text_2') !!}
            </p>
            <div class="row justify-content-between">
                <div class="col-xl-4 mb-3 mb-xl-0">
                    <h4 class="fw-medium fs-26 font-body mt-n1">{!! page('about.about_text_3') !!}</h4>
                    <p>{!! page('about.about_text_4') !!}</p>
                </div>
                <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="row gx-60">
                        <div class="col-auto">
                            <span class="about-number">01</span>
                        </div>
                        <div class="col">
                            <h4 class="fw-medium fs-26 font-body mt-n1 mb-lg-3 pb-lg-1">{!! page('about.list_head_1') !!}</h4>
                            <div class="list-style1">
                                {!! page('about.list_1') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="row gx-60">
                        <div class="col-auto">
                            <span class="about-number">02</span>
                        </div>
                        <div class="col">
                            <h4 class="fw-medium fs-26 font-body mt-n1 mb-lg-3 pb-lg-1">{!! page('about.list_head_2') !!}</h4>
                            <div class="list-style1">
                                {!! page('about.list_2') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Area -->
    <section class=" space-top space-extra-bottom">
        <div class="parallax" data-parallax-image="{{ asset('assets/img/bg/testi-bg-2-1.jpg') }}"></div>
        <div class="shape-mockup jump-reverse d-none d-xxl-block" data-top="12%" data-right="6%"><img src="{{ asset('assets/img/shape/leaf-1-1.png') }}" alt="shape"></div>
        <div class="shape-mockup jump  d-none d-xxl-block" data-top="35%" data-left="17.5%"><img src="{{ asset('assets/img/shape/leaf-1-8.png') }}" alt="shape"></div>
        <div class="container">
            <div class="title-area text-center">
                <span class="sec-subtitle">{{ __('client testimonial') }}</span>
                <h2 class="sec-title">{{ __('Glosse Clients') }}</h2>
            </div>
            <div class="pb-1px"></div>
            <div class="testi-style2" >
                <span class="vs-icon d-inline-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/icon/quote-1-1.png') }}" alt="icon">
                </span>
                <div class="vs-carousel" data-slide-show="1" data-fade="true" data-arrows="true" data-ml-arrows="true" data-xl-arrows="true" data-lg-arrows="true" data-prev-arrow="fal fa-long-arrow-left" data-next-arrow="fal fa-long-arrow-right">
                    @foreach($reviews as $review)
                    <div>
                        <p class="testi-text">“{{ $review->comment }}”</p>
                        <div class="arrow-shape"><i class="arrow"></i><i class="arrow"></i><i class="arrow"></i><i class="arrow"></i></div>
                        <h3 class="testi-name h5">{{ $review->name }}</h3>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
