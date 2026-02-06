@extends('admin.layouts.app')

@section('content')
    @php
        $workFrom = $settings->work_from ? \Illuminate\Support\Carbon::parse($settings->work_from) : null;
        $workTo = $settings->work_to ? \Illuminate\Support\Carbon::parse($settings->work_to) : null;
    @endphp
    <x-common.page-breadcrumb pageTitle="{{ __('Settings') }}" :breadcrumbs="[
        ['label' => __('Settings'), 'url' => '#']
    ]" >
    </x-common.page-breadcrumb>
    @if (session('status'))
        <div class="mb-6">
            <x-ui.alert :duration="3" :variant="session('variant')" :message="session('status')" />
        </div>
    @endif
    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6">
            <form action="{{ route('admin.settings.index') }}" method="post">
                @csrf
                @method('PUT')
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <div class="w-full px-2.5">
                        <h4 class="border-b border-gray-200 pb-2 text-base font-medium text-gray-800">
                            @lang('Main Settings')
                        </h4>
                    </div>
                    <div class="w-1/3 px-2.5">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">
                            @lang('Contact Phone') <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input name="phone" value="{{ old('phone', $settings->phone) }}" maxlength="20" type="text" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                        </div>

                        @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="w-full px-2.5">
                        <h4 class="border-b border-gray-200 pb-2 text-base font-medium text-gray-800">
                            @lang('Working Hours')
                        </h4>
                    </div>
                    <div class="w-full px-2.5">
                        <div class="flex items-end gap-6">
                            <div class="w-56">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    @lang('Work From') <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input name="work_from" value="{{ old('work_from', $workFrom?->format('H:i')) }}" type="time" placeholder="12:00" onclick="this.showPicker()" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                    <span class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.99984C3.04175 6.15686 6.1571 3.0415 10.0001 3.0415C13.8431 3.0415 16.9584 6.15686 16.9584 9.99984C16.9584 13.8428 13.8431 16.9582 10.0001 16.9582C6.1571 16.9582 3.04175 13.8428 3.04175 9.99984ZM10.0001 1.5415C5.32867 1.5415 1.54175 5.32843 1.54175 9.99984C1.54175 14.6712 5.32867 18.4582 10.0001 18.4582C14.6715 18.4582 18.4584 14.6712 18.4584 9.99984C18.4584 5.32843 14.6715 1.5415 10.0001 1.5415ZM9.99998 10.7498C9.58577 10.7498 9.24998 10.4141 9.24998 9.99984V5.4165C9.24998 5.00229 9.58577 4.6665 9.99998 4.6665C10.4142 4.6665 10.75 5.00229 10.75 5.4165V9.24984H13.3334C13.7476 9.24984 14.0834 9.58562 14.0834 9.99984C14.0834 10.4141 13.7476 10.7498 13.3334 10.7498H10.0001H9.99998Z" fill=""></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="w-56">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    @lang('Work To') <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input name="work_to" value="{{ old('work_to', $workTo?->format('H:i')) }}" type="time" placeholder="12:00" onclick="this.showPicker()" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                    <span class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.99984C3.04175 6.15686 6.1571 3.0415 10.0001 3.0415C13.8431 3.0415 16.9584 6.15686 16.9584 9.99984C16.9584 13.8428 13.8431 16.9582 10.0001 16.9582C6.1571 16.9582 3.04175 13.8428 3.04175 9.99984ZM10.0001 1.5415C5.32867 1.5415 1.54175 5.32843 1.54175 9.99984C1.54175 14.6712 5.32867 18.4582 10.0001 18.4582C14.6715 18.4582 18.4584 14.6712 18.4584 9.99984C18.4584 5.32843 14.6715 1.5415 10.0001 1.5415ZM9.99998 10.7498C9.58577 10.7498 9.24998 10.4141 9.24998 9.99984V5.4165C9.24998 5.00229 9.58577 4.6665 9.99998 4.6665C10.4142 4.6665 10.75 5.00229 10.75 5.4165V9.24984H13.3334C13.7476 9.24984 14.0834 9.58562 14.0834 9.99984C14.0834 10.4141 13.7476 10.7498 13.3334 10.7498H10.0001H9.99998Z" fill=""></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @error('work_from')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('work_to')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full px-2.5 mt-5">
                        <h4 class="border-b border-gray-200 pb-2 text-base font-medium text-gray-800">
                            @lang('Booking Settings')
                        </h4>
                    </div>
                    <div class="w-full px-2.5">
                        <div class="flex items-end gap-6">
                            <div class="w-56">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    @lang('Technical Break (min)') <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input name="break_minutes" type="text" value="{{ old('break_minutes', $settings->break_minutes) }}" placeholder="30" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                </div>
                            </div>
                            <div class="w-56">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    @lang('Booking Slot Step (min)') <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input name="slot_step_minutes" type="text" value="{{ old('slot_step_minutes', $settings->slot_step_minutes) }}" placeholder="30" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                                </div>
                            </div>
                        </div>
                        @error('break_minutes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('slot_step_minutes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full px-2.5">
                    <div class="mt-1 flex items-center gap-3">
                        <button type="submit" class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white">
                            @lang('Save Changes')
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
