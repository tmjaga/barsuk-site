@extends('emails.layouts.admin')

@section('content')
    <!-- Contact You Message -->
    <div class="p-3 border-gray-200">
        <h2 class="text-xl font-semibold mb-2">{{ __('You have new Contact Message From') }}: {{ $data['name'] }}</h2>
        <p class="text-gray-600">{{ __('Email') }}: {{ $data['email'] }}</p>
        <p class="text-gray-600">{{ __('Subject') }}: {{ $data['subject'] }}</p>
        <div class="mt-3 p-3 border border-gray-200 rounded-md">
            {{ $data['message'] }}
        </div>
    </div>
@endsection
