@extends('emails.layouts.admin')

@section('content')
    <!-- Thank You Message -->
    <div class="p-3 border-gray-200">
        <h2 class="text-xl font-semibold mb-2">@lang('You have new subscriber'): {{ $subscriber->email }}</h2>
        <p class="text-gray-600">@lang('Subscribed at'): {{ $subscriber->created_at->format('d.m.Y H:i') }}</p>
    </div>
@endsection
