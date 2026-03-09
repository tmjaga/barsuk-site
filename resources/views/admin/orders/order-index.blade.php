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

    <div>
        <div x-data="listingpage()" class="rounded-2xl border border-gray-200 bg-white">
        <!-- loader (spinner)-->
        <x-common.loader :show="'loading'" style="display: none;" />
        <!-- search form-->
        <div class="flex flex-col gap-3 sm:flex-row items-center p-3">
            <a href="#" @click.prevent="$store.orderModal.instance?.openCreateModal()" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                @lang('Add New')
            </a>
            <!-- status select input-->
            <div x-data="{ isOptionSelected: {{ $status ? 'true' : 'false' }} }" class="relative ml-auto z-20 bg-transparent">
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
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs">
                                @lang('Phone')
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
                                <p x-text="$formatDate(order.order_start)" class="text-gray-700 text-theme-sm"></p>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex flex-col">
                                <p x-text="order.names" class="text-gray-700 text-theme-sm"></p>
                                <small class="block text-gray-500" x-html="`[${order.email}]`"></small>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex flex-col">
                                <p x-text="order.phone" class="text-gray-700 text-theme-sm"></p>
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
                                <a href="#" @click.prevent="$store.orderModal.instance?.openEditModal(order.id)" data-tippy-content="@lang('Edit Order')" class="text-gray-500 hover:text-gray-800">
                                    <x-heroicon-o-pencil-square class="stroke-2" width="24" height="24" />
                                </a>
                                <x-common.confirm-delete
                                    title="{{ __('Are you sure to Delete this Order?') }}"
                                    route-name="{{ route('admin.orders.destroy', ':id') }}">
                                    <!-- Trash icon -->
                                    <button @click="itemId = order.id" data-tippy-content="@lang('Delete Order')" class="flex items-center justify-center text-gray-500 hover:text-error-500">
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
        </div>
    </div>
    @include('admin.partial.edit-order-modal')
@endsection

@push('footer_scripts')
    <script src="{{ asset('js/status-badge.js') }}"></script>
    <script>
        window.orderStatuses = @json(\App\Enums\OrderStatus::titles());
        window.allServices = @json($allServices);
    </script>
@endpush

