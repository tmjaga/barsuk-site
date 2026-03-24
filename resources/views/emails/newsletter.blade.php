@extends('emails.layouts.customer')

@section('content')
    <div class="p-3 border-b border-gray-200">
        <h2 class="text-xl font-semibold mb-2">{{ $mailSubject }}</h2>
    </div>
    <div class="p-6 border-gray-200">
        {!! $mailBody !!}
    </div>

@endsection

@section('before-footer-content')
    <div class="text-center text-sm font-normal text-gray-500 mb-1">
        {{ __('We hope you are enjoying our newsletter, to unsubscribe, ') }}
        <a href="{{ route('unsubscribe', "__TOKEN__") }}" class="text-sm font-normal underline text-brand-500">{{ __('Click Here') }}</a>
    </div>
@endsection
