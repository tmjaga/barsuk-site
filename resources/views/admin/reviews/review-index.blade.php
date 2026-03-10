@extends('admin.layouts.app')

@section('content')
    @php
        $title = __('Reviews');
    @endphp
    <x-common.page-breadcrumb pageTitle="{{ $title }}">
        <x-slot:breadcrumbs>
            <li>
                <span class="text-gray-700 ">
                    {{ $title }}
                </span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <template x-if="$store.alert.show">
        <div class="mb-6">
            <x-ui.alert/>
        </div>
    </template>

    <div x-data="reviewModal()">
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
            </div>

            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="min-w-full">
                    <!-- table header start -->
                    <thead class="border-gray-100 border-y bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p class="font-bold text-gray-500 text-theme-xs">
                                    {{ __('Name') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-start justify-start">
                                <p class="items-start font-bold text-gray-500 text-theme-xs">
                                    {{ __('Email') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-start">
                                <p class="items-center font-bold text-gray-500 text-theme-xs">
                                    {{ __('Review Text') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="items-center font-bold text-gray-500 text-theme-xs">
                                    {{ __('Rating') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="items-center font-bold text-gray-500 text-theme-xs">
                                    {{ __('Creation Date') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="font-bold text-gray-500 text-theme-xs">
                                    {{ __('Status') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="font-bold text-gray-500 text-theme-xs">
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
                                {{ __('No Reviews found') }}
                            </td>
                        </tr>
                    </template>

                    <template x-for="review in data" :key="review.id">
                        <tr x-data="statusBadge">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p x-text="review.name" class="text-gray-700 text-theme-sm"></p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap ">
                                <div class="flex items-center">
                                    <p x-text="review.email" class="text-gray-700 text-theme-sm "></p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap ">
                                <div class="flex items-center">
                                    <p x-text="review.short_comment" class="text-gray-700 text-theme-sm "></p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap ">
                                <div class="flex gap-1 justify-center items-center">
                                    <template x-for="i in 5" :key="i">
                                        <x-heroicon-s-star
                                            x-bind:class="i <= review.rating ? 'w-4 h-4 text-yellow-400' : 'w-4 h-4 text-gray-300'"
                                        />
                                    </template>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap ">
                                <div class="flex items-center">
                                    <p x-text="review.formatted_creation_date" class="text-gray-700 text-theme-sm "></p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <span
                                    x-text="review.status !== undefined ? getBadge(review.status).text : getBadge().text"
                                    :class="review.status !== undefined ? getBadge(review.status).color : getBadge().color"
                                    class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium">
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap items-center">
                                <div class="flex w-full items-center justify-center gap-2">
                                    <a href="#" @click.prevent="openEditModal(review.id)" data-tippy-content="{{ __('Edit Review') }}" class="text-gray-500 hover:text-gray-800">
                                        <x-heroicon-o-pencil-square class="stroke-2" width="24" height="24" />
                                    </a>
                                    <x-common.confirm-delete
                                        title="{{ __('Are you sure to Delete this Review?') }}"
                                        route-name="{{ route('admin.reviews.destroy', ':id') }}">
                                        <!-- Trash icon -->
                                        <button @click="itemId = review.id"
                                                data-tippy-content="{{__('Delete Review') }}"
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
            <x-common.pagination />

            <!-- Add/Edit category modal -->
            <div x-show="isModalOpen"
                 class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999"
                 style="display: none;">
                <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
                <div @click.outside="isModalOpen = false"
                     class="relative w-full max-w-[584px] rounded-3xl bg-white p-6 lg:p-10">
                    <!-- close btn -->
                    <button @click="isModalOpen = false"
                            class="group absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-200 text-gray-500 transition-colors hover:bg-gray-300 hover:text-gray-500 sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                        <svg class="transition-colors fill-current group-hover:text-gray-600" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                                  fill=""></path>
                        </svg>
                    </button>

                    <form @submit.prevent="submitForm" method="POST" :action="formAction">
                        @csrf
                        <input type="hidden" name="_method" :value="formMethod">
                        <h4 x-text="modalTitle" class="mb-6 text-lg font-medium text-gray-800"></h4>

                        <div class="flex flex-wrap gap-x-5 gap-y-5">
                            <div class="w-full mb-5">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    {{ __('Name') }} <span class="text-red-500">*</span>
                                </label>
                                <input name="name" value="" x-model="formData.name" type="text"
                                       class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                <p x-show="$v.formData.name.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">{{ __('Please enter a valid Name') }}</p>
                            </div>

                            <div class="w-full mb-5">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    {{ __('Email') }} <span class="text-red-500">*</span>
                                </label>
                                <input name="Email" value="" x-model="formData.email" type="text"
                                       class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                <p x-show="$v.formData.email.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">{{ __('Please enter a valid Email') }}</p>
                            </div>

                            <div class="w-full">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    {{ __('Review Text') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea name="comment" x-model="formData.comment" placeholder="Enter a review text..." type="text" rows="6"
                                          class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">

                                </textarea>
                                <p x-show="$v.formData.comment.$invalid && $v.$touch" class="text-red-500 text-sm mt-1">{{ __('Please enter a valid Review Text') }}</p>
                            </div>

                            <div class="w-full">
                                <div class="w-20">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    {{ __('Rating') }}
                                </label>
                                <div x-data="{ isOptionSelected: formData.rating ?? false }" class="relative z-20 bg-transparent">
                                    <select x-model="formData.rating" name="rating"
                                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                            :class="isOptionSelected &amp;&amp; 'text-gray-800'" @change="isOptionSelected = true">
                                        <template x-for="i in 5" :key="i">
                                            <option x-bind:value="i" x-text="i"></option>
                                        </template>
                                    </select>
                                    <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500">
                                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            </div>

                            <div class="mt-6">
                                <x-forms.checkbox-status
                                    name="status"
                                    label="{{ __('Active') }}"
                                    id="review_id"
                                    model="formData.status"
                                ></x-forms.checkbox-status>
                            </div>

                            <div class="flex items-center justify-end w-full gap-3 mt-6">
                                <button type="submit"
                                        class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                                    {{ __('Save Changes') }}
                                </button>
                                <button @click="isModalOpen = false" type="button"
                                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition-colors hover:bg-gray-50 hover:text-gray-800 sm:w-auto">
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
    <script src="{{ asset('js/status-badge.js') }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reviewModal', () => ({
                isModalOpen: false,
                isLoading: false,
                formData: {
                    name: '',
                    email: '',
                    comment: '',
                    rating: 1,
                    status: 0
                },
                errors: {},
                formAction: '',
                formMethod: 'POST',
                modalTitle: '',
                routeTemplates: {
                    edit: "{{ route('admin.reviews.edit', ':id') }}",
                    update: "{{ route('admin.reviews.update', ':id') }}",
                    store: "{{ route('admin.reviews.store') }}",
                },
                validations: {
                    'formData.name': ['required', 'min:2'],
                    'formData.email': ['required', 'email'],
                    'formData.comment': ['required'],
                },

                init() {
                    this.$validation(this)

                },

                openCreateModal() {
                    this.resetForm();
                    this.formAction = this.routeTemplates.store;
                    this.formMethod = 'POST';
                    this.modalTitle = '{{ __("Add New Review") }}';

                    this.$dispatch('update-toggle', this.formData.status);

                    this.isModalOpen = true;
                },

                async openEditModal(reviewId) {
                    this.isLoading = true;

                    try {
                        const response = await axios.get(this.routeTemplates.edit.replace(':id', reviewId));
                        // TODO For test errors uncomment line below
                        // const response = await axios.get(this.routeTemplates.edit.replace(':id', 22));
                        const review = response.data;


                        this.formData.name = review.name;
                        this.formData.email = review.email;
                        this.formData.comment = review.comment;
                        this.formData.rating = review.rating;
                        this.formData.status = review.status;
                        this.formAction = this.routeTemplates.update.replace(':id', reviewId);
                        this.formMethod = 'PUT';
                        this.modalTitle = '{{ __("Edit Review") }}';

                        this.$dispatch('update-toggle', this.formData.status);

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
                        name: '',
                        email: '',
                        comment: '',
                        rating: 1,
                        status: 0
                    };

                    this.errors = {};

                    // reset validation
                    if (this.$v?.reset) {
                        this.$v.reset()
                    }
                },

                async submitForm() {
                    this.errors = {};

                    this.formData.name = this.formData.name.trim();
                    this.formData.comment = this.formData.comment.trim();
                    this.$v.validate();

                    if (this.$v.formData.name.$invalid || this.$v.formData.comment.$invalid) {
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
