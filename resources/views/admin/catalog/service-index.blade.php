@extends('admin.layouts.app')

@section('content')
    @php
        $languages = config('logat.languages');
    @endphp

    <x-common.page-breadcrumb pageTitle="{{ __('Services') }}">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-brand-600">
                    {{ __('Dashboard') }}
                </a>
            </li>
            <li>
                <span class="text-gray-700 ">
                    {{ __('Services') }}
                </span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <template x-if="$store.alert.show">
        <div class="mb-6">
            <x-ui.alert/>
        </div>
    </template>

    <div x-data="serviceModal()">
        <div x-data="listingpage()" class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <!-- loader (spinner)-->
            <x-common.loader :show="'loading'" style="display: none;"/>
            <!-- search form-->
            <div class="flex flex-col gap-3 sm:flex-row items-center p-3">
                <a href="#" @click.prevent="openCreateModal()"
                   class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    {{ __('Add New') }}
                </a>

                <!-- search input-->
                <div class="relative ml-auto">
                <span class="absolute -translate-y-1/2 pointer-events-none top-1/2 left-4">
                    <svg class="fill-gray-500" width="20" height="20" viewBox="0 0 20 20" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                              fill=""></path>
                    </svg>
                </span>
                    <input x-model.debounce.500ms="search" type="text" placeholder="Search..."
                           class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-10 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px]">
                </div>

                <!-- category select input-->
                <div x-data="{ isOptionSelected: {{ $category ? 'true' : 'false' }} }"
                     class="relative z-20 bg-transparent">
                    <select x-model="filters.category" @change="$dispatch('reload-items');"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                            :class="isOptionSelected &amp;&amp; 'text-gray-800'" @change="isOptionSelected = true">
                        <option value="" class="text-gray-700">
                            {{ __('Select Category') }}
                        </option>
                        @foreach ($categories as $cat)
                            <option
                                value="{{ $cat->id }}"
                                @selected(optional($category)->id === $cat->id)>
                                {{ $cat->title_localized }}
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

            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="min-w-full">
                    <!-- table header start -->
                    <thead class="border-gray-100 border-y bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="font-medium text-gray-500 text-theme-xs">
                                    {{ __('Title') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-start justify-start">
                                <p class="items-start font-medium text-gray-500 text-theme-xs">
                                    {{ __('Category') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="items-center font-medium text-gray-500 text-theme-xs">
                                    {{ __('Price') }} &euro;
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="items-center font-medium text-gray-500 text-theme-xs">
                                    {{ __('Duration (min)') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="font-medium text-gray-500 text-theme-xs">
                                    {{ __('Status') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="font-medium text-gray-500 text-theme-xs">
                                    {{ __('Action') }}
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
                                {{ __('No Services found') }}
                            </td>
                        </tr>
                    </template>

                    <template x-for="service in data" :key="service.id">
                        <tr x-data="statusBadge">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p x-text="service.title_localized" class="text-gray-700 text-theme-sm"></p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-start">
                                <a href="#"
                                   @click.prevent=" filters.category = service.category_id; $dispatch('reload-items')"
                                   class="text-theme-sm text-blue-600 hover:underline">
                                    <p x-text="service.category.title_localized" class=""></p>
                                </a>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap ">
                                <div class="flex justify-center items-center">
                                    <p x-text="service.price" class="text-gray-700 text-theme-sm "></p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap ">
                                <div class="flex justify-center items-center">
                                    <p x-text="service.duration" class="text-gray-700 text-theme-sm "></p>
                                </div>
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
                                    <a href="#" @click.prevent="openEditModal(service.id)"
                                       data-tippy-content="{{ __('Edit Service') }}"
                                       class="text-gray-500 hover:text-gray-800">
                                        <x-heroicon-o-pencil-square class="stroke-2" width="24" height="24" />
                                    </a>
                                    <x-common.confirm-delete
                                        title="{{ __('Are you sure to Delete this Service?') }}"
                                        route-name="{{ route('admin.services.destroy', ':id') }}">
                                        <!-- Trash icon -->
                                        <button @click="itemId = service.id"
                                                data-tippy-content="{{ __('Delete Service') }}"
                                                class="flex items-center justify-center text-gray-500 hover:text-error-500">
                                            <x-heroicon-o-trash class="stroke-2" width="22" height="22" />
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
                        <button @click="prevPage()"
                                class="mr-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50"
                                :disabled="currentPage === 1" disabled="disabled">
                            Previous
                        </button>
                        <template x-if="currentPage &gt; 3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-blue-500/[0.08] hover:text-brand-500">...</span>
                        </template>

                        <template x-for="page in pagesAroundCurrent" :key="page">
                            <button @click="goToPage(page)"
                                    :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700'"
                                    class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        <template x-if="currentPage &lt; totalPages - 2">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-500/[0.08] hover:text-brand-500">...</span>
                        </template>
                        <button @click="nextPage()"
                                class="ml-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50"
                                :disabled="currentPage === totalPages">
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

            <!-- Add/Edit category modal -->
            <div x-show="isModalOpen" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999" style="display: none;">
                <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
                <div @click.outside="isModalOpen = false" class="relative w-full max-w-5xl rounded-3xl bg-white p-6 lg:p-10">
                    <!-- close btn -->
                    <button @click="isModalOpen = false"
                            class="group absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition-colors hover:bg-gray-300 hover:text-gray-500 sm:right-6 sm:top-6 sm:h-10 sm:w-10">

                        <x-heroicon-c-x-mark width="30" height="30" />
                    </button>

                    <form @submit.prevent="submitForm" method="POST" :action="formAction">
                        @csrf
                        <input type="hidden" name="_method" :value="formMethod">
                        <h4 x-text="modalTitle" class="mb-6 text-lg font-medium text-gray-800"></h4>
                        <div  class="flex flex-wrap gap-x-5 gap-y-5">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-2 overflow-x-auto">
                                    @foreach($languages as $code => $lang)
                                        <button type="button" @click="activeTab='{{ $code }}'"
                                                :class="activeTab === '{{ $code }}' ? 'text-blue-500 border-blue-500' : 'text-gray-500 border-transparent'"
                                                class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium">
                                            <span class="fi fi-{{ $lang['flag'] }}"></span>
                                            {{ $lang['label'] }}
                                        </button>
                                    @endforeach
                                </nav>
                            </div>
                            <div class="w-full">
                                @foreach($languages as $code => $lang)
                                    <div x-show="activeTab === '{{ $code }}'">
                                        <div class="w-1/2 mb-3">
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                                {{ __('Service Title') }} <span class="text-red-500">*</span>
                                            </label>
                                            <input name="title[{{ $code }}]" value="" x-model.trim="formData.title['{{ $code }}']" type="text"
                                                   class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                            <p x-show="$v.formData.title.{{ $code }}.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">
                                                {{ __('Please enter a valid Title') }}
                                            </p>
                                        </div>
                                        <div class="w-full">
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                                {{ __('Service Description') }}
                                            </label>
                                            <textarea  :key="'desc-{{ $code }}'" name="description[{{ $code }}]" x-model.trim="formData.description['{{ $code }}']" placeholder="Enter a description..." type="text" rows="6"
                                                      class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                            </textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                            <div class="w-full flex gap-5">
                                <!-- categories select -->
                                <div class="w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        {{ __('Service Category') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                                        <select x-model="formData.category_id" name="category_id" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                                :class="isOptionSelected &amp;&amp; 'text-gray-800'"
                                                @change="isOptionSelected = true">
                                            <option value="" class="text-gray-700">
                                                {{ __('Select Category') }}
                                            </option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"> {{ $cat->title_localized }} </option>
                                            @endforeach
                                        </select>
                                        <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500">
                                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                    </div>
                                    <p x-show="$v.formData.category_id.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Please select a Category')</p>
                                </div>
                                <!-- pages select -->
                                <div class="w-1/2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        {{ __('Use Page With Custom Template') }}
                                    </label>
                                    <div x-data="{ isOptionSelectedPage: false }" class="relative z-20 bg-transparent">
                                        <select x-model="formData.page_id" name="page_id" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                                :class="isOptionSelectedPage &amp;&amp; 'text-gray-800'"
                                                @change="isOptionSelectedPage = true">
                                            <option value="" class="text-gray-700">
                                                {{ __('Select Page with Template') }}
                                            </option>
                                            @foreach ($pages as $page)
                                                <option value="{{ $page->id }}"> {{ $page->title }} </option>
                                            @endforeach
                                        </select>
                                        <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500">
                                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                    </div>
                                    <p x-show="$v.formData.category_id.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Please select a Category')</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-5">
                                <!-- time field -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        {{ __('Duration') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <!-- Hours -->
                                        <input x-model="formData.hours" type="number" name="hours" placeholder="HH"
                                               class="w-24 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                        <span class="text-gray-500 font-semibold">:</span>
                                        <!-- Minutes -->
                                        <input x-model="formData.minutes" type="number" name="minutes" placeholder="MM"
                                               class="w-24 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                    </div>
                                    <p x-show="$v.formData.hours.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Only digits for Hours from 00 to 05 allowed')</p>
                                    <p x-show="$v.formData.minutes.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">@lang('Only digits for Minutes from 00 to 59 allowed')</p>
                                </div>
                                <!-- price field -->
                                <div class="flex-1 min-w-[200px]">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        {{ __('Price') }} &euro; <span class="text-red-500">*</span>
                                    </label>
                                    <input name="price" value=""placeholder="0.00" x-model="formData.price" type="text" class="w-1/2 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                    <p x-show="$v.formData.price.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">{{ __('Please enter a valid Price') }}</p>
                                </div>
                            </div>
                            <!-- active checkbox -->
                            <div class="mt-2 block w-full">
                                <x-forms.checkbox-status name="active" label="{{ __('Active') }}" id="service_id" model="formData.active"></x-forms.checkbox-status>
                            </div>

                            <!-- submit and cancel buttons -->
                            <div class="flex items-center w-full gap-3 mt-6">
                                <button type="submit" class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                                    {{ __('Save Changes') }}
                                </button>
                                <button @click="isModalOpen = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 sm:w-auto">
                                    {{ __('Cancel') }}
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
    <script>
        document.addEventListener('alpine:init', () => {
            const defaultFormData = () => ({
                title: @js(array_fill_keys(array_keys($languages), '')),
                description: @js(array_fill_keys(array_keys($languages), '')),
                category_id: '',
                page_id: '',
                hours: '00',
                minutes: '10',
                price: null,
                active: 1
            });

            Alpine.data('serviceModal', () => ({
                isModalOpen: false,
                isLoading: false,
                activeTab: @js(array_key_first($languages)),
                formData: defaultFormData(),
                errors: {},
                formAction: '',
                formMethod: 'POST',
                modalTitle: '',
                languages: @js(array_fill_keys(array_keys($languages), '')),
                routeTemplates: {
                    edit: "{{ route('admin.services.edit', ':id') }}",
                    update: "{{ route('admin.services.update', ':id') }}",
                    store: "{{ route('admin.services.store') }}",
                },
                validations: {
                    @foreach($languages as $code => $lang)
                    'formData.title.{{ $code }}': ['required', 'min:2'],
                    @endforeach
                    'formData.category_id': ['required'],
                    'formData.hours': ['required', 'regex:^(0[0-5])$'],
                    'formData.minutes': ['required', 'regex:^(0[0-9]|[1-5][0-9])$'],
                    'formData.price': ['required', 'regex:^\\d{1,8}(\.\\d{1,2})?$'],
                },

                init() {
                    this.$validation(this);
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
                    this.activeTab = @js(array_key_first($languages));
                    try {
                        const response = await axios.get(this.routeTemplates.edit.replace(':id', serviceId));
                        const service = response.data;
                        const [hours, minutes] = service.duration.split(':');

                        this.formData.title = service.title;
                        this.formData.category_id = service.category_id;
                        this.formData.hours = hours.padStart(2, '00');
                        this.formData.minutes = minutes.padStart(2, '00');
                        this.formData.active = service.active;
                        this.formData.price = service.price;
                        this.formData.page_id = service.page_id;

                        // to get nullable translatable values
                        this.formData.description = Object.keys(service.description ?? {}).length ? service.description : this.languages;

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
                    this.activeTab = @js(array_key_first($languages));
                    this.formData = defaultFormData();
                    this.errors = {};

                    // reset validation
                    if (this.$v?.reset) {
                        this.$v.reset()
                    }
                },

                async submitForm() {
                    this.errors = {};

                    this.$v.validate();

                    const languages = @js(array_keys($languages));
                    const invalidTitleTab = languages.find(code =>
                        this.$v.formData.title[`${code}`]?.$invalid
                    );

                    if (this.$v.formData.title.$invalid ||
                        this.$v.formData.hours.$invalid ||
                        this.$v.formData.minutes.$invalid ||
                        this.$v.formData.price.$invalid) {

                        if (invalidTitleTab) {
                            this.activeTab = invalidTitleTab;
                        }

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
