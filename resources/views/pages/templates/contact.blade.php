@php
    $contactEmail = settings()->email;
    $workFrom = \Carbon\Carbon::createFromFormat('H:i:s', settings()->work_from)->format('H:i');
    $workTo = \Carbon\Carbon::createFromFormat('H:i:s', settings()->work_to)->format('H:i');
@endphp

@extends('layouts.app')
@section('content')
    <!-- breadcumb -->
    <div class="breadcumb-wrapper " data-bg-src="{{ asset('assets/img/breadcumb/contact_bg.jpg') }}">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">{{ __('Contact Us') }}</h1>
                <div class="breadcumb-menu-wrap">
                    <ul class="breadcumb-menu">
                        <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                        <li>{{ __('Contact Us') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Area -->
    <section class=" space">
        <div class="container">
            <div class="row gx-70">
                <div x-data="sendMessage()" class="col-lg-6 mb-40 mb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="text-center text-lg-start">
                        <span class="sec-subtitle">{{ __('Experience') }}</span>
                        <h2 class="sec-title3 h1 text-uppercase mb-xxl-2 pb-xxl-1">{!! __('Get in :target', ['target' => '<span class="text-theme">'.__('Touch').'</span>']) !!}</h2>
                        <div class="col-xxl-10 pb-xl-3">
                            <p class="pe-xxl-4"> {!!  page('contact.contact_text_1') !!}</p>
                        </div>
                    </div>
                    <!-- overlay loader -->
                    <div x-show="loading" x-transition.opacity
                          class="position-absolute top-0 start-0 z-999999 flex w-100 h-100 items-center justify-center bg-white/70">
                        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-gray-300 border-t-transparent"></div>
                    </div>

                    <template x-if="$store.alert.show">
                        <div class="alert mt-2"
                             :class="$store.alert.variant === 'error' ? 'alert-danger' : 'alert-success'"
                             x-text="$store.alert.message"></div>
                    </template>

                    <form action="mail.php" method="POST" class="ajax-contact form-style6">
                        <div class="form-group">
                            <input type="text" name="name" x-model="formData.name" id="name" placeholder="{{ __('Your Name') }}*">
                            <p x-show="$v.formData.name.$invalid && $v.$touch" class="text-danger text-sm ps-1">{{ __('Please enter a Name') }}</p>
                        </div>
                        <div class="form-group">
                            <input type="text" name="email" x-model="formData.email" id="email" placeholder="{{ __('Your Email') }}*">
                            <p x-show="$v.formData.email.$invalid && $v.$touch" class="text-danger text-sm ps-1">{{ __('Please enter a valid Email') }}</p>
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" x-model="formData.subject" id="subject" placeholder="{{ __('Your Subject') }}*">
                            <p x-show="$v.formData.subject.$invalid && $v.$touch" class="text-danger text-sm ps-1">{{ __('Please enter a Subject') }}</p>
                        </div>
                        <div class="form-group">
                            <textarea name="message" id="message" x-model="formData.message" placeholder="{{ __('Message') }}*"></textarea>
                            <p x-show="$v.formData.message.$invalid && $v.$touch" class="text-danger text-sm ps-1">{{ __('Please enter a Message') }}</p>
                        </div>
                        <button @click.prevent="submitForm()" class="vs-btn style2 d-none d-xl-inline-block rounded-4" type="submit">{{ ('Send Message') }}</button>
                        <p class="form-messages"></p>
                    </form>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="contact-table">
                        <div class="tr">
                            <div class="tb-col">
                                <span class="th">{{ __('email') }} :</span>

                                <span class="td"><a href="mailto:{{ $contactEmail }}" class="text-inherit">{{ $contactEmail }}</a></span>
                            </div>
                            <div class="tb-col">
                                <span class="th">{{ __('time') }} : </span>
                                <span class="td">{{ $workFrom }} - {{ $workTo }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="h1">
                        <a href="tel:{{ settings()->phone }}" class="text-inherit"><i class="fal fa-headset me-3 text-theme"></i>{{ settings()->formatted_phone }}</a>
                    </span>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('footer_scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sendMessage', () => ({
                loading: false,
                formData: {
                    name: '',
                    email: '',
                    subject: '',
                    message: '',
                },
                validations: {
                    'formData.name': ['required', 'min:5'],
                    'formData.email': ['required', 'email'],
                    'formData.subject': ['required', 'min:5'],
                    'formData.message': ['required'],
                },
                formAction: "{{ route('contact-message') }}",

                init() {
                    this.$validation(this)
                },

                async submitForm() {
                    this.formData.name = this.formData.name.trim();
                    this.formData.subject = this.formData.subject.trim();
                    this.formData.message = this.formData.message.trim();
                    this.$v.validate();

                    if (this.$v.formData.name.$invalid ||
                        this.$v.formData.subject.$invalid ||
                        this.$v.formData.message.$invalid) {
                        return;
                    }

                    try {
                        this.loading = true;
                        const response = await axios({
                            url: this.formAction,
                            method: 'post',
                            data: this.formData,
                        });

                        Alpine.store('alert').success(response?.data?.message);

                    } catch (error) {
                        Alpine.store('alert').error(error?.response?.data?.message);
                    } finally {
                        this.resetForm();
                        this.loading = false;
                    }
                },
                resetForm() {
                    this.formData = {
                        name: '',
                        email: '',
                        subject: '',
                        message: ''
                    };

                    this.errors = {};

                    // reset validation
                    if (this.$v?.reset) {
                        this.$v.reset()
                    }
                },
            }));
        });
    </script>
@endpush
