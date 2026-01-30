@extends('layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto p-6">

        <h1 class="text-2xl font-bold mb-6">Booking Component</h1>

        <div x-data="booking()" x-cloak class="bg-white p-6 rounded-xl shadow space-y-6">

            <!-- step 1 -->
            <template x-if="!loading && step === 1">
                <div>
                    <h2 class="text-lg font-semibold mb-4">
                        @lang('Select Services')
                    </h2>
                    <div class="space-y-2">
                        <template x-for="service in services" :key="service.id">
                            <label class="flex items-center gap-3 p-3 border rounded hover:bg-gray-50">
                                <input type="checkbox"
                                       :checked="isSelected(service.id)"
                                       @change="toggleService(service)">
                                <span class="flex-1" x-text="service.title"></span>
                                <span class="text-sm text-gray-500" x-text="formatPrice(service.price) + ' &euro;'"></span>
                            </label>
                        </template>
                    </div>

                    <!-- base navigation -->
                    <button @click="next()" class="mt-4 px-4 py-2 bg-black text-white rounded" :disabled="!selectedServices.length">
                        @lang('Next')
                    </button>
                </div>
            </template>

            <!-- step2 — weeks dates times -->
            <template x-if="!slotsLoading && step === 2">
                <div>
                    <h2 class="text-lg font-semibold mb-4">
                        @lang('Select Date and Time')
                    </h2>

                    <div class="flex items-center gap-3 mb-4">
                        <!-- prev week button -->
                        <button @click="prevWeek()" class="p-2 rounded-full hover:bg-gray-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                        </button>

                        <template x-for="d in weekDays" :key="d.date">
                            <button
                                @click="selectDate(d)"
                                :disabled="d.disabled"
                                class="w-24 py-3 rounded border text-sm"
                                :class="{
                                'bg-teal-500 text-white border-teal-500': selectedDate === d.date,
                                'opacity-40 cursor-not-allowed': d.disabled
                            }">
                                <div x-text="d.label"></div>
                                <div class="font-semibold" x-text="d.day"></div>
                            </button>
                        </template>

                        <!-- next week button -->
                        <button @click="nextWeek()" class="p-2 rounded-full hover:bg-gray-200 transition">
                            <svg class="w-5 h-5"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.5"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5L15.75 12l-7.5 7.5"/>
                            </svg>
                        </button>
                    </div>

                    <!-- hours -->
                    <div class="grid grid-cols-8 gap-3">
                        <template x-for="slot in daySlots" :key="slot.time">
                            <button @click="selectedTime = slot.time"
                                :disabled="!slot.available"
                                class="py-2 rounded border shadow-sm text-sm"
                                :class="{
                                'bg-gray-200 cursor-not-allowed': !slot.available,
                                'hover:bg-gray-50 hover:text-black': slot.available,
                                'bg-teal-500 text-white': selectedTime === slot.time
                            }" x-text="slot.time"></button>
                        </template>
                    </div>

                    <!-- base navigation -->
                    <div class="flex gap-3 mt-6">
                        <button @click="prev()" class="px-4 py-2 border rounded">
                            @lang('Back')
                        </button>
                        <button @click="next()" class="px-4 py-2 bg-black text-white rounded" :disabled="!selectedTime">
                            @lang('Next')
                        </button>
                    </div>

                </div>
            </template>

            <!-- step 3 -->
            <template x-if="!loading && step === 3">
                <div>
                    <h3 class="text-lg font-semibold mb-4">
                        @lang('Confirmation')
                    </h3>
                    <ul class="space-y-1 mb-4">
                        <template x-for="s in selectedServices">
                            <li x-text="s.title + ' — ' + s.price + ' &euro;'"></li>
                        </template>
                    </ul>

                    <div class="space-y-1 mb-4 text-sm">
                        <div>@lang('Total Duration'): <strong x-text="totalDuration"></strong></div>
                        <div>@lang('Total Price'): <strong x-text="totalPrice + ' &euro;'"></strong></div>

                        <div>@lang('At Date'): <strong x-text="selectedDate"></strong></div>
                        <div>@lang('At Time'): <strong x-text="selectedTime"></strong></div>
                    </div>

                    <div class="space-y-2">
                        <input x-model="formData.names" placeholder="@lang('Name')" class="w-full border p-2 rounded">
                        <p x-show="$v.formData.names.$invalid && $v.$touch" class="text-red-500 text-sm">@lang('Please enter a valid Names (Min 8 characters)')</p>

                        <input x-model="formData.email" placeholder="@lang('Email')" class="w-full border p-2 rounded">
                        <p x-show="$v.formData.email.$invalid && $v.$touch" class="text-red-500 text-sm">@lang('Please enter a valid Email')</p>

                        <input x-model="formData.phone" placeholder="Phone" class="w-full border p-2 rounded">
                        <p x-show="$v.formData.phone.$invalid && $v.$touch" class="text-red-500 text-sm">@lang('Please enter a valid Phone Number')</p>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button @click="prev()" class="px-4 py-2 border rounded">@lang('Back')</button>
                        <button @click="submitForm()" class="px-4 py-2 bg-green-600 text-white rounded">
                            @lang('Make Order')
                        </button>
                    </div>
                </div>
            </template>

            <!-- step 4 results -->
            <x-common.loader :show="'loading'" style="display: none;" />
            <template x-if="!loading && step === 4">
                <div class="text-center">
                    <!-- success icon-->
                    <div x-show="!errors?.order" class="relative flex items-center justify-center z-1 mb-7">
                        <svg class="fill-success-50 dark:fill-success-500/15" width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M34.364 6.85053C38.6205 -2.28351 51.3795 -2.28351 55.636 6.85053C58.0129 11.951 63.5594 14.6722 68.9556 13.3853C78.6192 11.0807 86.5743 21.2433 82.2185 30.3287C79.7862 35.402 81.1561 41.5165 85.5082 45.0122C93.3019 51.2725 90.4628 63.9451 80.7747 66.1403C75.3648 67.3661 71.5265 72.2695 71.5572 77.9156C71.6123 88.0265 60.1169 93.6664 52.3918 87.3184C48.0781 83.7737 41.9219 83.7737 37.6082 87.3184C29.8831 93.6664 18.3877 88.0266 18.4428 77.9156C18.4735 72.2695 14.6352 67.3661 9.22531 66.1403C-0.462787 63.9451 -3.30193 51.2725 4.49185 45.0122C8.84391 41.5165 10.2138 35.402 7.78151 30.3287C3.42572 21.2433 11.3808 11.0807 21.0444 13.3853C26.4406 14.6722 31.9871 11.951 34.364 6.85053Z" fill="" fill-opacity=""></path>
                        </svg>
                        <span class="absolute -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
                            <svg class="fill-success-600 dark:fill-success-500" width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.9375 19.0004C5.9375 11.7854 11.7864 5.93652 19.0014 5.93652C26.2164 5.93652 32.0653 11.7854 32.0653 19.0004C32.0653 26.2154 26.2164 32.0643 19.0014 32.0643C11.7864 32.0643 5.9375 26.2154 5.9375 19.0004ZM19.0014 2.93652C10.1296 2.93652 2.9375 10.1286 2.9375 19.0004C2.9375 27.8723 10.1296 35.0643 19.0014 35.0643C27.8733 35.0643 35.0653 27.8723 35.0653 19.0004C35.0653 10.1286 27.8733 2.93652 19.0014 2.93652ZM24.7855 17.0575C25.3713 16.4717 25.3713 15.522 24.7855 14.9362C24.1997 14.3504 23.25 14.3504 22.6642 14.9362L17.7177 19.8827L15.3387 17.5037C14.7529 16.9179 13.8031 16.9179 13.2173 17.5037C12.6316 18.0894 12.6316 19.0392 13.2173 19.625L16.657 23.0647C16.9383 23.346 17.3199 23.504 17.7177 23.504C18.1155 23.504 18.4971 23.346 18.7784 23.0647L24.7855 17.0575Z" fill=""></path>
                            </svg>

                        </span>
                    </div>
                    <!-- error icon-->
                    <div x-show="errors?.order" class="relative flex items-center justify-center z-1 mb-7">
                        <svg class="fill-error-50 dark:fill-error-500/15" width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M34.364 6.85053C38.6205 -2.28351 51.3795 -2.28351 55.636 6.85053C58.0129 11.951 63.5594 14.6722 68.9556 13.3853C78.6192 11.0807 86.5743 21.2433 82.2185 30.3287C79.7862 35.402 81.1561 41.5165 85.5082 45.0122C93.3019 51.2725 90.4628 63.9451 80.7747 66.1403C75.3648 67.3661 71.5265 72.2695 71.5572 77.9156C71.6123 88.0265 60.1169 93.6664 52.3918 87.3184C48.0781 83.7737 41.9219 83.7737 37.6082 87.3184C29.8831 93.6664 18.3877 88.0266 18.4428 77.9156C18.4735 72.2695 14.6352 67.3661 9.22531 66.1403C-0.462787 63.9451 -3.30193 51.2725 4.49185 45.0122C8.84391 41.5165 10.2138 35.402 7.78151 30.3287C3.42572 21.2433 11.3808 11.0807 21.0444 13.3853C26.4406 14.6722 31.9871 11.951 34.364 6.85053Z" fill="" fill-opacity=""></path>
                        </svg>
                        <span class="absolute -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
                        <svg class="fill-error-600 dark:fill-error-500" width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.62684 11.7496C9.04105 11.1638 9.04105 10.2141 9.62684 9.6283C10.2126 9.04252 11.1624 9.04252 11.7482 9.6283L18.9985 16.8786L26.2485 9.62851C26.8343 9.04273 27.7841 9.04273 28.3699 9.62851C28.9556 10.2143 28.9556 11.164 28.3699 11.7498L21.1198 18.9999L28.3699 26.25C28.9556 26.8358 28.9556 27.7855 28.3699 28.3713C27.7841 28.9571 26.8343 28.9571 26.2485 28.3713L18.9985 21.1212L11.7482 28.3715C11.1624 28.9573 10.2126 28.9573 9.62684 28.3715C9.04105 27.7857 9.04105 26.836 9.62684 26.2502L16.8771 18.9999L9.62684 11.7496Z" fill=""></path>
                        </svg>
                    </span>
                    </div>
                    <h4 x-text="responseMessage" class="mb-2 text-2xl font-semibold sm:text-title-sm" :class="errors?.order ? 'text-red-600' : 'text-gray-800'"></h4>
                    <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                        <!-- additional text can be added here-->
                    </p>
                </div>

            </template>
        </div>
    </div>
@endsection


@push('footer_scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('booking', (orderId) => ({
                step: 1,
                services: [],
                selectedServices: [],
                selectedDate: null,
                selectedTime: null,
                weekStart: null,
                weekDays:[],
                daySlots:[],
                slots: [],
                today: new Date(),
                formData: {
                    names: '',
                    email: '',
                    phone: ''
                },
                errors: {},
                loading: true,
                slotsLoading: true,
                responseMessage: '',
                validations: {
                    'formData.names': ['required', 'min:10'],
                    'formData.email': ['required', 'email'],
                    'formData.phone':['required', 'regex:^\\+?[0-9]+$'],
                },
                async init() {
                    this.$validation(this)
                    try {
                        this.services = (await axios.get('{{route('booking.services')}}')).data.data;
                    } finally {
                        this.loading = false;
                    }

                    this.selectedDate = this.formatDate(this.today);
                    this.weekStart = this.startOfWeek(this.today);
                    this.buildWeek();
                    this.$watch('selectedServices', async () => {
                        if (this.step === 2) {
                            await this.loadDaySlots();
                        }
                    });
                },
                async next() {
                    this.step++;

                    if (this.step === 2 && this.selectedServices.length) {
                        await this.loadDaySlots();
                    }
                },
                prev() {
                    if (this.step) {
                        this.step--;
                    }
                },
                toggleService(service) {
                    const exists = this.selectedServices.find(s => s.id === service.id)
                    exists
                        ? this.selectedServices = this.selectedServices.filter(s => s.id !== service.id)
                        : this.selectedServices.push(service);
                },
                isSelected(id) {
                    return this.selectedServices.some(s => s.id === id);
                },
                async loadDaySlots() {
                    if (!this.selectedServices.length) return;
                    if (!this.selectedDate) return;

                    this.slotsLoading = true;

                    try {
                        this.daySlots = (await axios.post('{{route('booking.slots')}}', {
                            date: this.selectedDate,
                            service_ids: this.selectedServices.map(s=>s.id)
                        })).data.data;
                    } finally {
                        this.slotsLoading = false;
                    }
                },
                async selectDate(d){
                    if (d.disabled) return;
                    this.selectedDate = d.date;
                    this.selectedTime = null;
                    await this.loadDaySlots();
                },
                startOfWeek(d) {
                    const date = new Date(d)
                    const day = date.getDay() || 7
                    if (day !== 1) date.setDate(date.getDate() - day + 1)
                    return date
                },
                buildWeek() {
                    this.weekDays = []
                    const today = new Date().setHours(0,0,0,0)

                    for (let i=0; i<7; i++) {
                        const d = new Date(this.weekStart)
                        d.setDate(d.getDate()+i)

                        this.weekDays.push({
                            date: this.formatDate(d),
                            label: d.toLocaleDateString([], { weekday: 'short' }),
                            day: d.getDate(),
                            disabled: d < today
                        })
                    }
                    this.selectedTime = null;
                },
                prevWeek() {
                    this.weekStart.setDate(this.weekStart.getDate()-7);
                    this.buildWeek();
                    //this.selectedTime = null;
                },
                nextWeek() {
                    this.weekStart.setDate(this.weekStart.getDate()+7);
                    this.buildWeek();
                    //this.selectedTime = null;
                },
                formatDate(d) {
                    const day = String(d.getDate()).padStart(2, '0');
                    const month = String(d.getMonth() + 1).padStart(2, '0'); // месяцы 0–11
                    const year = d.getFullYear();

                    return `${day}.${month}.${year}`;
                },
                formatPrice(price) {
                    return  price.toFixed(2).replace('.', ',');
                },
                get totalPrice() {
                    const totalPrice = this.selectedServices.reduce((total, service) => {
                        return total + (service.price || 0);
                    }, 0);

                    return  this.formatPrice(totalPrice);
                },
                async submitForm() {
                    this.formData.names = this.formData.names.trim();
                    this.formData.email = this.formData.email.trim();
                    this.$v.validate();

                    if (this.$v.formData.names.$invalid ||
                        this.$v.formData.email.$invalid ||
                        this.$v.formData.phone.$invalid) {
                        return;
                    }
                    try {
                        this.loading = true;
                        const response = await axios.post('{{route('booking.order')}}', {
                            ...this.formData,
                            order_date: this.selectedDate,
                            order_time: this.selectedTime,
                            services: this.selectedServices.map(s => s.id),
                            status: {{ App\Enums\OrderStatus::PENDING }},
                        });
                        this.responseMessage = response?.data?.message;
                    } catch (error) {
                        this.errors.order = error?.response?.data?.message;
                        this.responseMessage = this.errors.order;
                    } finally {
                        this.loading = false;
                        this.step ++;
                    }
                },
                get totalDuration() {
                    const totalMinutes = this.selectedServices.reduce((total, service) => {
                        const [hours, minutes] = service.duration.split(':').map(Number);

                        return total + (hours * 60 + minutes);
                    }, 0);

                   const hours = Math.floor(totalMinutes / 60);
                   const minutes = totalMinutes % 60;

                   return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                },
            }));
        });
    </script>

@endpush

