@extends('admin.layouts.app')

@section('content')
    @php
        $title = __('Subscribers');
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

    <div>
        <div x-data="listingpage()" class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <!-- loader (spinner)-->
            <x-common.loader :show="'loading'" style="display: none;"/>
            <!-- search form-->
            <div class="flex flex-col gap-3 sm:flex-row items-center p-3">
                <!-- delete selected button-->
                <button x-show="selectedRows.length"
                    @click="deleteSelected('{{ route('admin.subscribers.delete-selected')}}')"
                    class="ml-2 rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                    {{ __('Delete selected') }}
                </button>

                <!-- search input-->
                <div class="relative ml-auto">
                <span class="absolute -translate-y-1/2 pointer-events-none top-1/2 left-4">
                    <svg class="fill-gray-500" width="20" height="20" viewBox="0 0 20 20" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""></path>
                    </svg>
                </span>
                    <input x-model.debounce.500ms="search" type="text" placeholder="Search..." class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-10 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px]">
                </div>
            </div>


            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="min-w-full">
                    <!-- table header start -->
                    <thead class="border-gray-100 border-y bg-gray-50">
                    <tr>
                        <th class="w-[1%] px-5 py-3 whitespace-nowrap">
                            <button data-tippy-content="{{ __('Select All') }}" @click="handleSelectAll()" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-1 py-1 text-sm bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50" type="button">
                                <x-heroicon-o-check width="14" height="14" />
                            </button>
                            <button data-tippy-content="{{ __('Unselect All') }}" @click="handleUnselectAll()" class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-1 py-1 text-sm bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50" type="button">
                                <x-heroicon-o-minus width="14" height="14" />
                            </button>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-start justify-start">
                                <p class="items-start font-bold text-gray-500 text-theme-xs">
                                    {{ __('Email') }}
                                </p>
                            </div>
                        </th>

                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="items-center font-bold text-gray-500 text-theme-xs">
                                    {{ __('Verified') }}
                                </p>
                            </div>
                        </th>
                        <th class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <p class="items-center font-bold text-gray-500 text-theme-xs">
                                    {{ __('Subscription Date') }}
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
                                {{ __('No Subscribers found') }}
                            </td>
                        </tr>
                    </template>

                    <template x-for="subscriber in data" :key="subscriber.id">

                        <tr x-data="statusBadge">
                            <td class="flex px-6 py-3 text-center justify-center">
                                <div @click="handleRowSelect(subscriber.id)" class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                    :class="selectedRows.includes(subscriber.id) ? 'border-blue-500 bg-blue-500' : 'bg-white border-gray-300'">
                                    <svg x-show="selectedRows.includes(subscriber.id)" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                        <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <p x-text="subscriber.email" class="text-gray-700 text-theme-sm"></p>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <div x-data="changeStatus(currentPage)">
                                    <span data-tippy-content="{{ __('Change Verified') }}" @click="setStatus(subscriber.id, subscriber.is_verified)"
                                        x-text="subscriber.is_verified !== undefined ? getBadge(subscriber.is_verified).text : getBadge().text"
                                        :class="subscriber.is_verified !== undefined ? getBadge(subscriber.is_verified).color : getBadge().color"
                                        class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium cursor-pointer">
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center w-auto">
                                <div class="flex justify-center">
                                    <p x-text="subscriber.formatted_creation_date" class="text-gray-700 text-theme-sm "></p>
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
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script src="{{ asset('js/status-badge.js') }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('changeStatus', (currentPage = 1) => ({
                currentPage: currentPage,

                async setStatus(id, currentStatus) {
                    const newStatus = currentStatus == 1 ? 0 : 1;
                    console.log(this.currentPage);

                    try {
                        const response = await axios.put('{{ route('admin.subscribers.change-status', ':id') }}'.replace(':id', id), {
                            'is_verified': newStatus,
                        });

                        Alpine.store('alert').success(response?.data?.message);

                        this.$dispatch('reload-items', { page: this.currentPage });
                    } catch (error) {
                        this.isModalOpen = false;
                        Alpine.store('alert').error(error?.response?.data?.message);
                    }
                }
            }));
        });
    </script>
@endpush
