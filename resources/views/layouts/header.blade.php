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
                                    <a href="{{ route('about') }}">{{ __('About Us') }}</a>
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
                                                <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
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
                                    <a href="{{ route('contact') }}">{{ __('Contact Us') }}</a>
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
