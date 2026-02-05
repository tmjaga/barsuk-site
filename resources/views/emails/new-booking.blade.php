@extends('emails.layouts.admin')

@section('content')
    <!-- Thank You Message -->
    <div class="p-3 border-b border-gray-200">
        <h2 class="text-xl font-semibold mb-2">@lang('You have new order at'): {{ $order->order_start->format('d.m.Y H:i') }}</h2>
        <p class="text-gray-600">@lang('Name'): {{ $order->names }}</p>
        <p class="text-gray-600">@lang('Email'): {{ $order->email }}</p>
    </div>

    <!-- Order Summary -->
    @php


 @endphp
    <div class="p-6 border-gray-200">
        <h3 class="text-lg font-semibold mb-4">@lang('Order Summary')</h3>
        <div class="space-y-4">
            <!-- Order Item -->
            @foreach($order->services as $service)
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <h4 class="font-medium">{{ $service->title }}</h4>
                    </div>
                    <p class="font-semibold">&euro; {{ $service->price }}</p>
                </div>
            @endforeach

            <!-- Order Total -->
            <div class="border-t pt-4 mt-4">

                <div class="flex justify-between font-semibold text-lg">
                    <span>@lang('Duration (min)')</span>
                    <span>{{ minutes_to_hhmm((int) $duration) }}</span>
                </div>
                <div class="flex justify-between font-semibold text-lg">
                    <span>@lang('Total')</span>
                    <span>&euro; {{ $total_price }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
