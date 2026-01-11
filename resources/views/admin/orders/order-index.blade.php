@extends('admin.layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{__('Orders')}}">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-brand-600">
                    @lang('Dashboard')
                </a>
            </li>
            <li>
                <span class="text-gray-700 ">
                    @lang('Orders')
                </span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <template x-if="$store.alert.show">
        <div class="mb-6">
            <x-ui.alert />
        </div>
    </template>

    <div x-data="orderModal()">
        <div x-data="listingpage()" class="rounded-2xl border border-gray-200 bg-white">
        <!-- loader (spinner)-->
        <x-common.loader :show="'loading'" style="display: none;" />
        <!-- search form-->
        <div class="flex flex-col sm:flex-row justify-end p-3">
            <!-- status select input-->
            <div x-data="{ isOptionSelected: {{ $status ? 'true' : 'false' }} }" class="relative z-20 bg-transparent">
                <select x-model="filters.status" @change="$dispatch('reload-items');" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" :class="isOptionSelected &amp;&amp; 'text-gray-800'" @change="isOptionSelected = true">
                    <option value="" class="text-gray-700">
                        @lang('Select Status')
                    </option>
                    @foreach ($orderStatuses as $key => $orderStatus)
                        <option
                            value="{{ $key }}"
                            @selected(optional($status) === $key)>
                            {{ $orderStatus }}
                        </option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </div>
        </div>

        <div class="max-w-full custom-scrollbar">
            <table class="min-w-full">
                <!-- table header start -->
                <thead class="border-gray-100 border-y bg-gray-50">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs">
                                @lang('Date/time')
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs">
                                @lang('Name')
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-start justify-start">
                            <p class="items-start font-medium text-gray-500 text-theme-xs">
                                @lang('Services')
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center justify-center">
                            <p class="font-medium text-gray-500 text-theme-xs">
                                @lang('Status')
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center justify-center">
                            <p class="font-medium text-gray-500 text-theme-xs">
                                @lang('Action')
                            </p>
                        </div>
                    </th>
                </tr>
                </thead>
                <!-- table header end -->

                <!-- table body start -->
                <tbody class="divide-y divide-gray-100">
                <template x-if="data.length === 0">
                    <tr>
                        <td class="text-muted text-center py-4" colspan="100%">
                            @lang('No Orders found')
                        </td>
                    </tr>
                </template>

                <template x-for="order in data" :key="order.id">
                    <tr x-data="orderStatusBadge">
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p x-text="$formatDate(order.order_date)" class="text-gray-700 text-theme-sm"></p>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex flex-col">
                                <p x-text="order.names" class="text-gray-700 text-theme-sm"></p>
                                <small class="block text-gray-500" x-html="`[${order.email}]`"></small>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-start">
                            <template x-for="service in order.services" :key="service.id">
                                <div class="text-gray-700 text-theme-sm" x-text="service.title"></div>
                            </template>

                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-center">
                            <span
                                x-text="order.status !== undefined ? getBadge(order.status).text : getBadge().text"
                                :class="order.status !== undefined ? getBadge(order.status).color : getBadge().color"
                                class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium">
                            </span>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap items-center">
                            <div class="flex w-full items-center justify-center gap-2">
                                <!-- change status -->
                                <div x-data="orderStatusDropdown(order.id)" class="relative inline-block">
                                    <a  href="#" @click.prevent="openDropDown = !openDropDown" data-tippy-content="@lang('Change Status')"
                                        class="text-gray-500 hover:text-gray-800">
                                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M12 6h.01M12 12h.01M12 18h.01"/></svg>
                                    </a>
                                    <div x-show="openDropDown" x-transition @click.outside="openDropDown = false"
                                         class="absolute top-full z-50 mt-1 w-48 right-0 max-w-[90vw] md:left-0 md:right-auto md:max-w-none rounded-xl border border-gray-200 bg-white p-2 shadow-lg">
                                        <ul class="flex flex-col">
                                            <template x-for="(orderStatusTitle, orderStatus) in statuses;" :key="orderStatus">
                                                <li>
                                                    <button x-text="orderStatusTitle" @click="updateStatus(orderStatus)"
                                                            class="flex w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-gray-700 hover:bg-gray-100">
                                                    </button>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                <!-- edit order -->
                                <a href="#" @click.prevent="openEditModal(order.id)" data-tippy-content="@lang('Edit Order')" class="text-gray-500 hover:text-gray-800">
                                    <svg class="fill-current" width="24" height="24" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z" fill=""></path>
                                    </svg>
                                </a>
                                <x-common.confirm-delete
                                    title="{{ __('Are you sure to Delete this Order?') }}"
                                    route-name="{{ route('admin.orders.destroy', ':id') }}">
                                    <!-- Trash icon -->
                                    <button @click="itemId = order.id" data-tippy-content="@lang('Delete Order')" class="flex items-center justify-center text-gray-500 hover:text-error-500">
                                        <svg class="fill-current" width="24" height="24" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503V9.25033Z" fill=""></path>
                                        </svg>
                                    </button>
                                </x-common.confirm-delete>
                            </div>
                        </td>
                    </tr>
                </template>
                </tbody>
                <!-- table body end -->
            </table>
        </div>

        <!-- Pagination -->
        <div x-show="totalPages > 1" class="border-t border-gray-100 py-4 pl-[18px] pr-4">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between">
                <div class="flex items-center justify-center gap-0.5 pb-4 xl:justify-normal xl:pt-0">
                    <button @click="prevPage()" class="mr-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50" :disabled="currentPage === 1" disabled="disabled">
                        Previous
                    </button>
                    <template x-if="currentPage &gt; 3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-blue-500/[0.08] hover:text-brand-500">...</span>
                    </template>

                    <template x-for="page in pagesAroundCurrent" :key="page">
                        <button @click="goToPage(page)" :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700'" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500">
                            <span x-text="page"></span>
                        </button>
                    </template>
                    <template x-if="currentPage &lt; totalPages - 2">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-500/[0.08] hover:text-brand-500">...</span>
                    </template>
                    <button @click="nextPage()" class="ml-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50" :disabled="currentPage === totalPages">
                        Next
                    </button>
                </div>

                <p class="border-t border-gray-100 pt-3 text-center text-sm font-medium text-gray-500 xl:border-t-0 xl:pt-0 xl:text-left">
                    Showing <span x-text="startEntry">1</span> to
                    <span x-text="endEntry">10</span> of
                    <span x-text="totalEntries">30</span> entries
                </p>
            </div>
        </div>
            <!-- Edit Order modal -->
            <div x-show="isModalOpen" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999" style="display: none;">
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
                            <div class="w-full px-2.5 xl:w-1/2" >
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
                            <div class="w-full px-2.5 xl:w-1/2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                @lang('Name'):
                            </label>
                            <p x-text="formData.name" class="text-gray-700 text-theme-sm"></p>
                        </div>
                            <div class="w-full px-2.5 xl:w-1/2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                @lang('Email'):
                            </label>
                            <p x-text="formData.email" class="text-gray-700 text-theme-sm"></p>
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
            <!-- End of Add/Edit category modal -->
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script src="{{ asset('js/status-badge.js') }}"></script>

    <script src="{{ asset('js/status-badge.js') }}"></script>
    <script>
        window.orderStatuses = @json(\App\Enums\OrderStatus::titles());

        document.addEventListener('alpine:init', () => {
            Alpine.data('orderModal', () => ({
                isModalOpen: false,
                isLoading: false,
                statuses: window.orderStatuses,
                formData: {
                    name: '',
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
                    update: "{{ route('admin.orders.update', ':id') }}"
                },
                validations: {
                    'formData.orderTime': ['required'],
                },

                init() {
                    this.$validation(this)
                    this.$el.addEventListener('date-change', (event) => {
                        this.dateRef = event.detail.instance.element;
                        this.validateDate();
                    });

                },

                async openEditModal(orderId) {
                    this.isLoading = true;
                    try {
                        const response = await axios.get(this.routeTemplates.edit.replace(':id', orderId));
                        // TODO For test errors uncomment line below
                        // const response = await axios.get(this.routeTemplates.edit.replace(':id', 22));
                        const order = response.data;

                        this.formData.name = order.names;
                        this.formData.email = order.email;
                        this.formData.phone = order.phone;
                        this.formData.orderDate = order.order_date.split('T')[0];
                        this.formData.orderTime = order.order_date.split('T')[1].slice(0, 5);

                        // set order date value in to the date picker
                        const datePicker = document.querySelector('#order_date').closest('[x-data]');
                        if (datePicker && datePicker._x_dataStack) {
                            datePicker._x_dataStack[0].setDate(new Date(this.formData.orderDate));
                        }

                        this.formData.status = order.status,
                        this.formData.services = order.services,
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

                async submitForm() {
                    this.errors = {};

                    this.formData.orderTime = this.formData.orderTime.trim();
                    this.$v.validate();

                    if (!this.validateDate()) {
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
                                'order_date': this.dateRef.value,
                                'order_time': this.formData.orderTime,
                                'status': this.formData.status,
                            },
                            headers: {
                                'X-HTTP-Method-Override': this.formMethod,
                            },
                        });

                        //this.isModalOpen = false;
                        Alpine.store('alert').success(response?.data?.message);

                        this.$dispatch('reload-items');
                    } catch (error) {
                        //this.isModalOpen = false;
                        Alpine.store('alert').error(error?.response?.data?.message);
                    } finally {
                        this.isModalOpen = false;
                        this.resetForm()
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
                        console.log(error);
                        Alpine.store('alert').error(error?.response?.data?.message);
                    } finally {
                        this.openDropDown = false
                    }
                }
            }))
        });
    </script>
@endpush
