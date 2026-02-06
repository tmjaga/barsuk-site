@extends('emails.layouts.customer')

@section('content')
    <!-- Thank You Message -->
    <div class="p-3 border-b border-gray-200">
        <h2 class="text-xl font-semibold mb-2">@lang('Dear ') {{ $order->names }},<br>
            @lang('We regret to inform you that your order has been') <span class="font-bold">@lang('cancelled')</span>.
        </h2>
        <p class="text-gray-600">@lang('If you have any questions or need further assistance, please contact us at:') {{ settings()->phone }}</p>
    </div>

    <!-- Order Summary -->
    <div class="p-6 border-gray-200">
        <h3 class="text-lg font-semibold">@lang('Order Summary')</h3>
        <h6 class="text-md text-gray-600 font-semibold mb-4">@lang('Date: ') {{ $order->order_start->format('d.m.Y H:i') }}</h6>
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
