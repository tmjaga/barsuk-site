@extends('admin.layouts.app')

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6 xl:col-span-12">

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800">

                    <x-heroicon-o-cube class="w-8 h-8" />
                </div>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">
                        {{ $totalOrders }}
                    </h3>
                    <p class="flex items-center gap-3 text-gray-500">
                        @lang('Total Orders')
                    </p>
                </div>
            </article>
            <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-success-200 text-gray-800">
                    <x-heroicon-o-currency-euro class="w-8 h-8" />
                </div>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">
                        {{ $completedOrders }}
                    </h3>
                    <p class="flex items-center gap-3 text-gray-500">
                        @lang('Completed Orders')
                        <span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium
                            {{ $ordersCompletedGrowth > 0 ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }}">
                            {{ $ordersCompletedGrowth > 0 ? '+' : '' }}{{ $ordersCompletedGrowth }}%
                        </span>
                    </p>
                </div>
            </article>
            <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-blue-200 text-gray-800">
                    <x-heroicon-o-wallet class="w-8 h-8" />
                </div>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">
                        &euro; {{ number_format($ordersProfit, 2) }}
                    </h3>
                    <p class="flex items-center gap-3 text-gray-500">
                        @lang('Orders Profit')
                        <span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium
                            {{ $profitGrowth > 0 ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }}">
                            {{ $profitGrowth > 0 ? '+' : '' }}{{ $profitGrowth }}%
                        </span>
                    </p>
                </div>
            </article>
        </div>


        <div class="col-span-12">

            <template x-if="$store.alert.show">
                <div class="mb-6">
                    <x-ui.alert />
                </div>
            </template>

            <div x-data="listingpage({
                url:'{{ route('admin.dashboard.recent-orders') }}',
                })" class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4">
                <!-- loader (spinner)-->
                <x-common.loader :show="'loading'" style="display: none;" />
                <div class="flex flex-col gap-5 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            @lang('Recent Orders')
                        </h3>
                    </div>

                    <!-- search form -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <div class="relative">
                                <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""></path>
                                    </svg>
                                </button>
                                <input x-model.debounce.500ms="search" type="text" placeholder="Search..."
                                       class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-10 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px]">
                            </div>

                            <!-- status select input-->
                            <div class="relative ml-auto z-20 bg-transparent">

                                <select x-model="filters.status" @change="$dispatch('reload-items');" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                    <option value="" class="text-gray-700">
                                        @lang('Select Status')
                                    </option>
                                    <template x-for="(status, key) in window.orderStatuses" :key="key">
                                        <option x-text="status" :value="key" />
                                    </template>
                                </select>
                                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </div>
                    </div>
                    <!-- end of search form -->
                </div>

                <div class="min-w-full overflow-x-auto custom-scrollbar">
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
                        <tbody>
                        <template x-if="data.length === 0">
                            <tr>
                                <td class="text-muted text-center py-4" colspan="100%">
                                    @lang('No Orders found')
                                </td>
                            </tr>
                        </template>
                        <template x-for="order in data" :key="order.id">
                            <tr x-data="orderStatusBadge" class="border-t border-gray-100 dark:border-gray-800">

                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <p x-text="$formatDate(order.order_start)" class="text-gray-700 text-theme-sm"></p>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <p x-text="order.names" class="text-gray-700 text-theme-sm"></p>
                                    <small class="block text-gray-500" x-html="`[${order.email}]`"></small>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <p x-text="order.phone" class="text-gray-700 text-theme-sm"></p>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
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
                                <td class="px-6 py-3.5 text-center whitespace-nowrap">
                                    <div  class="flex justify-center items-center">
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
                    </table>
                </div>
            </div>


        </div>

    </div>
  </div>
@endsection
@push('footer_scripts')
    <script src="{{ asset('js/status-badge.js') }}"></script>
    <script>
        window.orderStatuses = @json(\App\Enums\OrderStatus::titles());
    </script>
@endpush
