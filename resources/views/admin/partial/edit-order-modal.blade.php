<div x-cloak x-data="orderModal()" x-show="isModalOpen" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999" style="display: none;">
    <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
    <div @click.outside="isModalOpen = false" class="relative w-full max-w-[584px] rounded-3xl bg-white p-6 lg:p-10">
        <!-- close btn -->
        <button @click="isModalOpen = false" class="group absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition-colors hover:bg-gray-300 hover:text-gray-500 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
            <svg class="transition-colors fill-current group-hover:text-gray-600" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z" fill=""></path>
            </svg>
        </button>

        <form @submit.prevent="submitForm" method="POST" :action="formAction">
            @csrf
            <input type="hidden" name="_method" :value="formMethod">
            <h4 x-text="modalTitle" class="mb-6 text-lg font-medium text-gray-800"></h4>

            <div class="-mx-2.5 flex flex-wrap gap-y-5">
                <!-- names field -->
                <div class="w-full px-2.5">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        @lang('Person Name'): <span class="text-red-500">*</span>
                    </label>
                    <input name="names" value="" x-model="formData.names" type="text" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                    <p x-show="$v.formData.names.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Please enter a valid Names')</p>
                </div>
                <!-- email field -->
                <div class="w-full px-2.5">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        @lang('Email'): <span class="text-red-500">*</span>
                    </label>
                    <input name="email" value="" x-model="formData.email" type="text" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                    <p x-show="$v.formData.email.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Please enter a valid Email')</p>
                    <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1"></p>
                </div>
                <!-- phone field -->
                <div class="w-full px-2.5">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        @lang('Phone'): <span class="text-red-500">*</span>
                    </label>
                    <input name="phone" value="" x-model="formData.phone" type="text" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                    <p x-show="$v.formData.phone.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Please enter a valid Phone Number')</p>
                    <p x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-sm mt-1"></p>
                </div>
                <!-- date field -->
                <div class="w-full px-2.5 xl:w-1/2" >
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        @lang('Order Date'): <span class="text-red-500">*</span>
                    </label>
                    <x-forms.date-picker
                        id="order_date"
                        name="order_date"
                        format="d.m.Y" />

                    <p x-show="errors.orderDate" class="text-red-500 text-sm mt-1">@lang('Please enter a valid Date')</p>
                </div>
                <!-- time field -->
                <div class="w-full px-2.5 xl:w-1/2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        @lang('Order Time'): <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input name="order_time" x-model="formData.orderTime" type="time" placeholder="12:00" onclick="this.showPicker()" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                        <span class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500">
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.99984C3.04175 6.15686 6.1571 3.0415 10.0001 3.0415C13.8431 3.0415 16.9584 6.15686 16.9584 9.99984C16.9584 13.8428 13.8431 16.9582 10.0001 16.9582C6.1571 16.9582 3.04175 13.8428 3.04175 9.99984ZM10.0001 1.5415C5.32867 1.5415 1.54175 5.32843 1.54175 9.99984C1.54175 14.6712 5.32867 18.4582 10.0001 18.4582C14.6715 18.4582 18.4584 14.6712 18.4584 9.99984C18.4584 5.32843 14.6715 1.5415 10.0001 1.5415ZM9.99998 10.7498C9.58577 10.7498 9.24998 10.4141 9.24998 9.99984V5.4165C9.24998 5.00229 9.58577 4.6665 9.99998 4.6665C10.4142 4.6665 10.75 5.00229 10.75 5.4165V9.24984H13.3334C13.7476 9.24984 14.0834 9.58562 14.0834 9.99984C14.0834 10.4141 13.7476 10.7498 13.3334 10.7498H10.0001H9.99998Z" fill=""></path>
                            </svg>
                        </span>
                    </div>
                </div>
                <!-- services multi select field -->
                <div class="w-full px-2.5">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        @lang('Services'): <span class="text-red-500">*</span>
                    </label>
                    <x-forms.multi-select
                        name="services"
                        id="services"
                        :options="$allServices"
                        :placeholder="__('Select Services')"
                    />
                    <p x-show="errors.orderServices && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Please Select at least One Service')</p>
                </div>
                <div class="w-full px-2.5">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        @lang('Status'):
                    </label>
                    <!-- status -->
                    <div class="flex">
                        <template x-for="(label, value) in statuses" :key="value">
                            <label class="mr-3 relative flex cursor-pointer items-center gap-1 text-sm font-medium select-none"
                                   :class="formData.status == value ? 'text-gray-700' : 'text-gray-500'">
                                <input type="radio" name="status" class="sr-only" :value="value" x-model="formData.status">

                                <span class="flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]"
                                      :class="formData.status == value ? 'border-brand-500 bg-brand-500' : 'border-gray-300 bg-transparent'">
                                                <span class="h-2 w-2 rounded-full bg-white" :class="formData.status == value ? 'block' : 'hidden'"></span>
                                            </span>
                                <span x-text="label"></span>
                            </label>
                        </template>
                    </div>
                </div>
                <div class="flex items-center justify-end w-full gap-3 mt-6">
                    <button type="submit" class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                        @lang('Save Changes')
                    </button>
                    <button @click="isModalOpen = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 sm:w-auto">
                        @lang('Cancel')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        // add component instance to store to get access from listing
        Alpine.store('orderModal', {
            instance: null,
        });

        Alpine.data('orderModal', () => ({
            multiSelect: null,
            isModalOpen: false,
            openServices: false,
            isLoading: false,
            allServices: @js($allServices),
            statuses: window.orderStatuses,
            formData: {
                names: '',
                email: '',
                phone: '',
                orderDate: new Date().toISOString().slice(0, 10),
                orderTime: new Date().toTimeString().slice(0, 5),
                status: 0,
                services: []
            },
            errors: {},
            dateRef: null,
            formAction: '',
            formMethod: 'POST',
            modalTitle: '',
            routeTemplates: {
                edit: "{{ route('admin.orders.edit', ':id') }}",
                update: "{{ route('admin.orders.update', ':id') }}",
                store: "{{ route('admin.orders.store') }}",
            },
            validations: {
                'formData.names': ['required', 'min:10'],
                'formData.email': ['required', 'email'],
                'formData.orderTime': ['required'],
                'formData.phone':['required', 'regex:^\\+?[0-9]+$'],
                'formData.services': ['required'],
            },
            init() {
                Alpine.store('orderModal').instance = this;
                this.$validation(this);
                this.$el.addEventListener('date-change', (event) => {
                    this.dateRef = event.detail.instance.element;
                    this.validateDate();
                });
                this.$el.addEventListener('multiselect-changed', (event) => {
                    this.validateServices();
                });
            },
            openCreateModal() {
                this.errors = {};
                this.resetForm();
                this.formAction = this.routeTemplates.store;
                this.formMethod = 'POST';
                this.modalTitle = '@lang("Add New Order")';

                // set order date value in to the date picker component
                let datePicker = document.querySelector('#order_date').closest('[x-data]');
                if (datePicker) {
                    datePicker = Alpine.$data(datePicker);
                    datePicker.setDate(new Date(this.formData.orderDate));
                }

                // set selected options in to the multi select component
                this.multiSelect = document.querySelector('#services').closest('[x-data]');
                if (this.multiSelect ) {
                    this.multiSelect = Alpine.$data(this.multiSelect);
                    this.multiSelect.selected = [];
                }

                this.isModalOpen = true;
            },
            async openEditModal(orderId) {
                this.isLoading = true;
                this.errors = {};
                try {
                    const response = await axios.get(this.routeTemplates.edit.replace(':id', orderId));
                    const order = response.data;

                    this.formData.names = order.names;
                    this.formData.email = order.email;
                    this.formData.phone = order.phone;
                    this.formData.orderDate = order.order_start.split(' ')[0];
                    this.formData.orderTime = order.order_start.split(' ')[1].slice(0, 5);
                    this.formData.services = order.services.map(s => s.id);

                    // set order date value in to the date picker component
                    let datePicker = document.querySelector('#order_date').closest('[x-data]');
                    if (datePicker) {
                        datePicker = Alpine.$data(datePicker);
                        datePicker.setDate(new Date(this.formData.orderDate));
                    }

                    // set selected options in to the multi select component
                    this.multiSelect = document.querySelector('#services').closest('[x-data]');
                    if (this.multiSelect ) {
                        this.multiSelect = Alpine.$data(this.multiSelect);
                        this.multiSelect.selected = [...this.formData.services];
                    }

                    this.formData.status = order.status,
                    this.formAction = this.routeTemplates.update.replace(':id', orderId);
                    this.formMethod = 'PUT';
                    this.modalTitle = '@lang("Edit Order")';
                    this.isModalOpen = true;
                } catch (error) {
                    Alpine.store('alert').error(error?.response?.data?.message);
                }
            },

            resetForm() {
                this.errors = {};
                this.dateRef = null;
                this.formData = {
                    names: '',
                    email: '',
                    phone: '',
                    orderDate: new Date().toISOString().slice(0, 10),
                    orderTime: new Date().toTimeString().slice(0, 5),
                    status: 0,
                    services: []
                };

                // reset validation
                if (this.$v?.reset) {
                    this.$v.reset()
                }
            },
            validateDate() {
                delete this.errors.orderDate;

                if (this.dateRef &&  !this.dateRef.value.trim()) {
                    this.errors.orderDate = true;

                    return false;
                }

                return true
            },
            validateServices() {
                delete this.errors.orderServices;

                if (this.multiSelect && !this.multiSelect.selected.length) {
                    this.errors.orderServices = true;
                    return false;
                }

                return true;
            },
            async submitForm() {
                this.errors = {};
                this.formData.names = this.formData.names.trim();
                this.formData.email = this.formData.email.trim();
                this.formData.orderTime = this.formData.orderTime.trim();
                this.$v.validate();

                if (!this.validateDate() ||
                    !this.validateServices() ||
                    this.$v.formData.names.$invalid ||
                    this.$v.formData.email.$invalid ||
                    this.$v.formData.phone.$invalid) {
                    return;
                }

                if (this.$v.formData.orderTime.$invalid) {
                    return;
                }

                try {
                    const response = await axios({
                        url: this.formAction,
                        method: 'post',
                        data: {
                            'names': this.formData.names,
                            'email': this.formData.email,
                            'phone': this.formData.phone,
                            'order_date': this.dateRef.value,
                            'order_time': this.formData.orderTime,
                            'status': this.formData.status,
                            'services': this.multiSelect.selected,
                        },
                        headers: {
                            'X-HTTP-Method-Override': this.formMethod,
                        },
                    });

                    Alpine.store('alert').success(response?.data?.message);

                    this.resetForm();

                    // dispatch event to window object to change listing
                    this.$dispatch('reload-items');

                    // dispatch event to calendar element
                    document.getElementById('calendar')?.dispatchEvent(new Event('calendar-refresh'));
                } catch (error) {
                    if (error.status === 500) {
                        Alpine.store('alert').error(error?.response?.data?.message);
                    } else {
                        this.errors = error.response.data.errors;
                    }
                } finally {
                    this.isModalOpen = false;
                }
            }
        }));

        Alpine.data('orderStatusDropdown', (orderId) => ({
            openDropDown: false,
            statuses: window.orderStatuses,
            updateUrl : "{{ route('admin.orders.update-status', ':id') }}",
            async updateStatus(status) {
                try {
                    const response = await axios.patch(this.updateUrl.replace(':id', orderId), {
                        status: status
                    });

                    Alpine.store('alert').success(response?.data?.message);

                    this.$dispatch('reload-items');
                } catch (error) {
                    Alpine.store('alert').error(error?.response?.data?.message);
                } finally {
                    this.openDropDown = false
                }
            }
        }))
    });
</script>
