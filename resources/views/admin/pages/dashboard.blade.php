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


    </div>
  </div>
@endsection
