@extends('admin.layouts.app')

@section('content')
    @php
        $languages = config('logat.languages');
    @endphp

    <x-common.page-breadcrumb pageTitle="{{ __('Categories') }}">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-brand-600">
                    {{ __('Dashboard') }}
                </a>
            </li>
            <li>
                <span class="text-gray-700">
                    {{ __('Categories') }}
                </span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <template x-if="$store.alert.show">
        <div class="mb-6">
            <x-ui.alert />
        </div>
    </template>

    <div x-data="categoryModal">
        <div x-data="listingpage()" class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <!-- loader (spinner)-->
        <x-common.loader :show="'loading'" style="display: none;" />
        <!-- add new -->
        <div class="flex flex-col gap-3 sm:flex-row items-center p-3">
            <a href="" @click.prevent="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                {{ __('Add New') }}
            </a>
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
                                {{ __('No Categories found') }}
                            </td>
                        </tr>
                    </template>

                    <template x-for="category in data" :key="category.id">
                    <tr x-data="statusBadge">
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p x-text="category.title_localized" class="text-gray-700 text-theme-sm"></p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-center">
                            <span
                                x-text="category.active !== undefined ? getBadge(category.active).text : getBadge().text"
                                :class="category.active !== undefined ? getBadge(category.active).color : getBadge().color"
                                class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium">
                            </span>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap items-center">
                            <div class="flex w-full items-center justify-center gap-2">
                                <!-- edit icon -->
                                <a href="#"  @click.prevent="openEditModal(category.id)" data-tippy-content="{{ __('Edit Category') }}" class="text-gray-500 hover:text-gray-800">
                                    <x-heroicon-o-pencil-square class="stroke-2" width="24" height="24" />
                                </a>
                                <x-common.confirm-delete
                                    title="{{ __('Are you sure to Delete this Category?') }}"
                                    text="{{ __('Warning! All Services in this Category will be deleted also.') }}"
                                    route-name="{{ route('admin.categories.destroy', ':id') }}">
                                    <!-- delete trash icon -->
                                    <button @click="itemId = category.id" data-tippy-content="{{ __('Delete Category')}}" class="flex items-center justify-center text-gray-500 hover:text-error-500">
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

        <!-- Add/Edit category modal -->
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
                    <h4 x-text="modalTitle" class="mb-3 text-lg font-medium text-gray-800"></h4>

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
                    <div class="w-full mt-3">
                        @foreach($languages as $code => $lang)
                            <div x-show="activeTab === '{{ $code }}'">
                                <div class="w-full">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                        {{ __('Category Title') }} [{{ strtoupper($code) }}] <span class="text-red-500">*</span>
                                    </label>
                                    <input name="title[{{ $code }}]" value="" x-model.trim="formData.title['{{ $code }}']" type="text"
                                           class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                    <p x-show="$v.formData.title.{{ $code }}.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">
                                        {{ __('Please enter a valid Title') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <x-forms.checkbox-status
                            name="active"
                            label="{{ __('Active') }}"
                            id="category_id"
                            model="formData.active"
                        ></x-forms.checkbox-status>
                    </div>

                    <div class="flex items-center justify-end w-full gap-3 mt-6">
                        <button type="submit" class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                            @lang('Save Changes')
                        </button>
                        <button @click="isModalOpen = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 sm:w-auto">
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
    <script>
        document.addEventListener('alpine:init', () => {
            const defaultFormData = () => ({
                title: @js(array_fill_keys(array_keys($languages), '')),
                active: 1
            });

            Alpine.data('categoryModal', () => ({
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
                    edit: "{{ route('admin.categories.edit', ':id') }}",
                    update: "{{ route('admin.categories.update', ':id') }}",
                    store: "{{ route('admin.categories.store') }}",
                },
                validations: {
                    @foreach($languages as $code => $lang)
                    'formData.title.{{ $code }}': ['required', 'min:2'],
                    @endforeach
                },

                init() {
                    this.$validation(this)
                },

                openCreateModal() {
                    this.resetForm();
                    this.formAction = this.routeTemplates.store;
                    this.formMethod = 'POST';
                    this.modalTitle = '@lang("Add New Category")';

                    this.$dispatch('update-toggle', this.formData.active);

                    this.isModalOpen = true;
                },

                async openEditModal(categoryId) {
                    this.isLoading = true;
                    this.activeTab = @js(array_key_first($languages));

                    try {
                        const response = await axios.get(this.routeTemplates.edit.replace(':id', categoryId));
                        const category = response.data;

                        this.formData.title = category.title;
                        this.formData.active = category.active;
                        this.formAction = this.routeTemplates.update.replace(':id', categoryId);
                        this.formMethod = 'PUT';
                        this.modalTitle = '@lang("Edit Category")';

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

                    if (this.$v.formData.title.$invalid) {
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
