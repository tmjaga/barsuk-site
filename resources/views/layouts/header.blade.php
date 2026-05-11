@php
    $currentLocale = app()->getLocale();
    $languages = config('logat.languages');
@endphp

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
                            <li><i class="far fa-phone-alt"></i><a href="tel:{{ settings()->phone }}">{{ settings()->formatted_phone }}</a></li>
                            <li><i class="far fa-envelope"></i><a href="mailto:{{ settings()->email }}">{{ settings()->email }}</a></li>
                        </ul>

                    </div>
                </div>
                <div class="col-auto d-none d-md-block">
                    <div class="social-style1 d-flex align-items-center">
                        @if(settings()->fb_link)
                            <a href="{{ settings()->fb_link }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(settings()->inst_link)
                            <a href="{{ settings()->inst_link }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                            <!-- language selector -->
                            <div class="ms-4 dropdown">
                                <button class="d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="fi fi-{{ $languages[$currentLocale]['flag'] }}"></span>
                                    <span class="text-white">{{ strtoupper($currentLocale) }}</span>
                                </button>
                                <ul class="social-style1 social-style-custom dropdown-menu dropdown-menu-end shadow">
                                    @foreach($languages as $locale => $lang)
                                        <li>
                                            <a href="{{ route('fr.lang.switch', $locale) }}" class="dropdown-item d-flex align-items-center gap-2 {{ $currentLocale === $locale ? 'active fw-semibold' : '' }}">
                                                <span class="fi fi-{{ $lang['flag'] }}"></span>
                                                <span>{{ $lang['label'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
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
                            <a href="{{ route('home') }}"><img src="{{ asset('images/logo/glosse_logo.svg') }}" alt="logo"></a>
                        </div>
                    </div>
                    <div class="col-auto">
                        <!-- main menu -->
                        <nav class="main-menu menu-style1 d-none d-lg-block">
                            <ul>
                                <li>
                                    <a href="{{ route('about') }}">{{ __('About Us') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('catalog') }}">{{ __('Services') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}">{{ __('Contact Us Menu') }}</a>
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
