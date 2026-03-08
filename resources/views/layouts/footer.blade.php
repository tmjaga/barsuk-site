<footer class="footer-wrapper footer-layout2">
    <div class="footer-top" data-bg-src="{{ asset('assets/img/bg/footer-2-1.png') }}">
        <form action="#" class="form-style3">
            <h2 class="form-title">{{ __('Subscribe for our newsletter') }}</h2>
            <div class="form-group">
                <input type="email" class="form-control" placeholder="Enter your email">
                <button class="vs-btn style5 rounded-4 ms-2" type="submit">{{ __('Subscribe') }}</button>
            </div>
        </form>
    </div>
    <div class="footer-logo d-flex justify-content-center">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo/glosse_logo_ff.svg') }}" alt="logo">
        </a>
    </div>
    <div class="footer-number"><a href="tel:{{ settings()->phone }}">{{ settings()->formatted_phone }}</a> - {{ \Carbon\Carbon::createFromFormat('H:i:s', settings()->work_from)->format('H:i') }} – {{ \Carbon\Carbon::createFromFormat('H:i:s', settings()->work_to)->format('H:i') }}</div>
    <div class="copyright-menu">
        <ul>
            <li><a href="{{ route('home') }}">HOME</a></li>
            <li><a href="service.html">service</a></li>
            <li><a href="price-plan.html">Pricing</a></li>
            <li><a href="blog.html">blog</a></li>
            <li><a href="shop.html">shop</a></li>
            <li><a href="contact.html">contact</a></li>
        </ul>
    </div>
    <p class="copyright-text">Copyright <i class="fal fa-copyright"></i> {{ date('Y') }} <a href="{{ route('home') }}">Glosse</a>.</p>
</footer>

