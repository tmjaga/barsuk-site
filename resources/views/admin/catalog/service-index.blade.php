@extends('admin.layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{__('Services')}}">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-brand-600 dark:text-gray-400 dark:hover:text-brand-500">
                    @lang('Services')
                </a>
            </li>
            <li>
                <span class="text-gray-700 dark:text-gray-400">
                    @lang('Services')
                </span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <template x-if="$store.alert.show">
        <div class="mb-6">
            <x-ui.alert />
        </div>
    </template>

    <div x-data="serviceModal()">
        <div x-data="listingpage()" class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- loader (spinner)-->
        <x-common.loader :show="'loading'" style="display: none;" />
        <!-- search form-->
        <div class="flex flex-col gap-3 sm:flex-row items-center p-3">
            <a href="#" @click.prevent="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                @lang('Add New')
            </a>

            <!-- search input-->
            <div class="relative ml-auto">
                <span class="absolute -translate-y-1/2 pointer-events-none top-1/2 left-4">
                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""></path>
                    </svg>
                </span>
                <input x-model.debounce.500ms="search" type="text" placeholder="Search..." class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>

            <!-- category select input-->
            <div x-data="{ isOptionSelected: {{ $category ? 'true' : 'false' }} }" class="relative z-20 bg-transparent">
                <select x-model="filters.category" @change="$dispatch('reload-items');" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" :class="isOptionSelected &amp;&amp; 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                        @lang('Select Category')
                    </option>
                    @foreach ($categories as $cat)
                        <option
                            value="{{ $cat->id }}"
                            @selected(optional($category)->id === $cat->id)>
                            {{ $cat->title }}
                        </option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </div>
        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <!-- table header start -->
                <thead class="border-gray-100 border-y bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                @lang('Title')
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-start justify-start">
                            <p class="items-start font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                @lang('Category')
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center justify-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                @lang('Status')
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center justify-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                @lang('Action')
                            </p>
                        </div>
                    </th>
                </tr>
                </thead>
                <!-- table header end -->

                <!-- table body start -->
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <template x-if="data.length === 0">
                    <tr>
                        <td class="text-muted text-center py-4" colspan="100%">
                            @lang('No Services found')
                        </td>
                    </tr>
                </template>

                <template x-for="service in data" :key="service.id">
                    <tr x-data="statusBadge">
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p x-text="service.title" class="text-gray-700 text-theme-sm dark:text-gray-400"></p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-start">
                            <a href="#" @click.prevent=" filters.category = service.category_id; $dispatch('reload-items')"
                               class="text-theme-sm text-blue-600 hover:underline">
                                <p x-text="service.category.title" class=""></p>
                            </a>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-center">
                            <span
                                x-text="service.active !== undefined ? getBadge(service.active).text : getBadge().text"
                                :class="service.active !== undefined ? getBadge(service.active).color : getBadge().color"
                                class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium">
                            </span>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap items-center">
                            <div class="flex w-full items-center justify-center gap-2">
                                <a href="#" @click.prevent="openEditModal(service.id)" data-tippy-content="@lang('Edit Service')" class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                    <svg class="fill-current" width="24" height="24" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z" fill=""></path>
                                    </svg>
                                </a>
                                <x-common.confirm-delete
                                    title="{{ __('Are you sure to Delete this Service?') }}"
                                    route-name="{{ route('admin.services.destroy', ':id') }}">
                                    <!-- Trash icon -->
                                    <button @click="itemId = service.id" data-tippy-content="@lang('Delete Service')" class="flex items-center justify-center text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500">
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
        <div x-show="totalPages > 1" class="border-t border-gray-100 py-4 pl-[18px] pr-4 dark:border-gray-800">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between">
                <div class="flex items-center justify-center gap-0.5 pb-4 xl:justify-normal xl:pt-0">
                    <button @click="prevPage()" class="mr-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]" :disabled="currentPage === 1" disabled="disabled">
                        Previous
                    </button>
                    <template x-if="currentPage &gt; 3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500">...</span>
                    </template>

                    <template x-for="page in pagesAroundCurrent" :key="page">
                        <button @click="goToPage(page)" :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700 dark:text-gray-400'" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500">
                            <span x-text="page"></span>
                        </button>
                    </template>
                    <template x-if="currentPage &lt; totalPages - 2">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-500/[0.08] hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500">...</span>
                    </template>
                    <button @click="nextPage()" class="ml-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]" :disabled="currentPage === totalPages">
                        Next
                    </button>
                </div>

                <p class="border-t border-gray-100 pt-3 text-center text-sm font-medium text-gray-500 dark:border-gray-800 dark:text-gray-400 xl:border-t-0 xl:pt-0 xl:text-left">
                    Showing <span x-text="startEntry">1</span> to
                    <span x-text="endEntry">10</span> of
                    <span x-text="totalEntries">30</span> entries
                </p>
            </div>
        </div>
            <!-- Add/Edit category modal -->
            <div x-show="isModalOpen" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999" style="display: none;">
                <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
                <div @click.outside="isModalOpen = false" class="relative w-full max-w-[584px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                    <!-- close btn -->
                    <button @click="isModalOpen = false" class="group absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition-colors hover:bg-gray-300 hover:text-gray-500 dark:bg-gray-800 dark:hover:bg-gray-700 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                        <svg class="transition-colors fill-current group-hover:text-gray-600 dark:group-hover:text-gray-200" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z" fill=""></path>
                        </svg>
                    </button>

                    <form @submit.prevent="submitForm" method="POST" :action="formAction">
                        @csrf
                        <input type="hidden" name="_method" :value="formMethod">
                        <h4 x-text="modalTitle" class="mb-6 text-lg font-medium text-gray-800 dark:text-white/90">
                            Modal Title
                        </h4>

                        <div class="w-full mb-5">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                @lang('Service Title') <span class="text-red-500">*</span>
                            </label>
                            <input name="title" value="{{ old('title', $service->title ?? '') }}" x-model="formData.title" type="text" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                            <p x-show="$v.formData.title.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Please enter a valid Title')</p>
                        </div>


                        <div class="w-full mb-5">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                @lang('Service Category') <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                <select x-model="formData.category_id" name="category_id" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" :class="isOptionSelected &amp;&amp; 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                        @lang('Select Category')
                                    </option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"> {{ $cat->title }} </option>
                                    @endforeach
                                </select>
                                <p x-show="$v.formData.category_id.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Please select a Category')</p>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                            </div>
                        </div>


                        <div class="w-full">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                @lang('Service Description')
                            </label>
                            <textarea name="description" x-model="formData.description" placeholder="Enter a description..." type="text" rows="6" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"></textarea>
                        </div>

                        <div class="mt-6">
                            <x-forms.checkbox-status
                                name="active"
                                label="{{ __('Active') }}"
                                id="service_id"
                                model="formData.active"
                            ></x-forms.checkbox-status>
                        </div>

                        <div class="flex items-center justify-end w-full gap-3 mt-6">
                            <button type="submit" class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                                @lang('Save Changes')
                            </button>
                            <button @click="isModalOpen = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:w-auto">
                                @lang('Cancel')
                            </button>
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('serviceModal', () => ({
                isModalOpen: false,
                isLoading: false,
                formData: {
                    title: '',
                    category_id: '',
                    description: '',
                    active: 1
                },
                errors: {},
                formAction: '',
                formMethod: 'POST',
                modalTitle: '',
                routeTemplates: {
                    edit: "{{ route('admin.services.edit', ':id') }}",
                    update: "{{ route('admin.services.update', ':id') }}",
                    store: "{{ route('admin.services.store') }}",
                },
                validations: {
                    'formData.title': ['required', 'min:3'],
                    'formData.category_id': ['required'],
                },

                init() {
                    this.$validation(this)
                },

                openCreateModal() {
                    this.resetForm();
                    this.formAction = this.routeTemplates.store;
                    this.formMethod = 'POST';
                    this.modalTitle = '@lang("Add New Service")';

                    this.$dispatch('update-toggle', this.formData.active);

                    this.isModalOpen = true;
                },

                async openEditModal(serviceId) {
                    this.isLoading = true;

                    try {
                        const response = await axios.get(this.routeTemplates.edit.replace(':id', serviceId));
                        // TODO For test errors uncomment line below
                        // const response = await axios.get(this.routeTemplates.edit.replace(':id', 22));
                        const service = response.data;

                        this.formData.title = service.title;
                        this.formData.category_id = service.category_id;
                        this.formData.active = service.active;
                        this.formData.description = service.description;
                        this.formAction = this.routeTemplates.update.replace(':id', serviceId);
                        this.formMethod = 'PUT';
                        this.modalTitle = '@lang("Edit Service")';

                        this.$dispatch('update-toggle', this.formData.active);

                        // reset validation
                        if (this.$v?.reset) {
                            this.$v.reset()
                        }

                        this.isModalOpen = true;
                    } catch (error) {
                        Alpine.store('alert').error(error?.response?.data?.message);
                    }
                },

                resetForm() {
                    this.formData = {
                        title: '',
                        category_id: '',
                        description: '',
                        active: 1
                    };

                    this.errors = {};

                    // reset validation
                    if (this.$v?.reset) {
                        this.$v.reset()
                    }
                },

                async submitForm() {
                    this.errors = {};

                    this.formData.title = this.formData.title.trim();
                    this.formData.description = this.formData.description.trim();
                    this.$v.validate();

                    if (this.$v.formData.title.$invalid || this.$v.formData.category_id.$invalid) {
                        return;
                    }

                    try {
                        const response = await axios({
                            url: this.formAction,
                            method: 'post',
                            data: this.formData,
                            headers: {
                                'X-HTTP-Method-Override': this.formMethod,
                            },
                        });

                        this.isModalOpen = false;
                        Alpine.store('alert').success(response?.data?.message);

                        this.$dispatch('reload-items');
                    } catch (error) {
                        this.isModalOpen = false;
                        Alpine.store('alert').error(error?.response?.data?.message);
                    } finally {
                        this.resetForm()
                    }
                }
            }));
        });
    </script>
@endpush
