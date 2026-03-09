<footer class="footer-wrapper footer-layout2">
    <div x-data="subscribe()" class="footer-top" data-bg-src="{{ asset('assets/img/bg/footer-2-1.png') }}">
        <form action="#" class="form-style3">
            <h2 class="form-title">{{ __('Subscribe for our newsletter') }}</h2>
            <div class="form-group  position-relative">
                <input x-model="email" type="text" class="form-control" placeholder="Enter your email" maxlength="150">
                <button @click.prevent="submitForm()" class="vs-btn style5 rounded-4 ms-2" type="submit">{{ __('Subscribe') }}</button>

                <!-- overlay loader -->
                <div x-show="loading" x-cloak class="position-absolute top-0 start-0 w-100 h-100 bg-white" style="z-index:999; opacity:.1;">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <div class="spinner-border"></div>
                    </div>
                </div>


            </div>
            <template x-if="$store.alert.show">
                    <div class="alert form-group mt-6"
                         :class="$store.alert.variant === 'error' ? 'alert-danger' : 'alert-success'"
                         x-text="$store.alert.message"></div>
            </template>
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

@push('footer_css')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .form-style3 input {
            color: var(--white-color) !important;

        }

        .form-style3 input:focus {
            background-color: #33393d !important;
            border-color: #273036 !important;
            color: var(--white-color) !important;
        }

        .form-style3 input:focus::placeholder {
            color: #33393d !important;
        }

        .form-style3 input:focus::-moz-placeholder {
            color: #33393d !important;
        }
    </style>
@endpush


@push('footer_scripts')
    <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('subscribe', () => ({
                    email: '',
                    loading: false,
                    formAction: "{{ route('subscribe') }}",

                    async submitForm() {
                    try {
                        this.loading = true;
                        const response = await axios({
                            url: this.formAction,
                            method: 'post',
                            data: {
                              email: this.email,
                            }
                        });

                        Alpine.store('alert').success(response?.data?.message);

                    } catch (error) {
                        this.isModalOpen = false;
                        Alpine.store('alert').error(error?.response?.data?.message);
                    } finally {
                        this.email = '';
                        this.loading = false;
                    }
                }
            }));
            });
    </script>
@endpush

