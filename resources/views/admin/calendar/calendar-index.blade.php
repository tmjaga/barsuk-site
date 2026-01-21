@extends('admin.layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{__('Calendar')}}">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-brand-600">
                    @lang('Dashboard')
                </a>
            </li>
            <li>
                <span class="text-gray-700">
                    @lang('Calendar')
                </span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <!-- calendar area-->
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="custom-calendar">
            <div id="calendar" class="min-h-screen"></div>
        </div>
    </div>
    @include('admin.partial.edit-order-modal')
@endsection

@push('footer_css')
    <style>
        .fc-timegrid-event-harness > .fc-timegrid-event {
            position: static !important;
        }

        .fc-timegrid-slot-label-cushion {
            padding: 0 8px !important;
            white-space: nowrap !important;
        }

        .fc .fc-timegrid-slot-label-frame {
            padding: 0 4px !important;
        }

        .fc-daygrid-event {
            white-space: wrap !important;
        }

        .fc-event-main {
            color: inherit !important;
        }

        .fc-event-time {
            font-size: inherit !important;
        }
    </style>
@endpush
@push('footer_scripts')
    <script>
        window.orderStatuses = @json(\App\Enums\OrderStatus::titles());
        window.allServices = @json($allServices);
    </script>
@endpush
