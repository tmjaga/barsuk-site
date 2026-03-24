@extends('admin.layouts.app')

@section('content')
    @php
        $pageTitle = @isset($mailing) ? __('Edit Mailing: '). $mailing->subject : __('Create New Mailing');
        $pageLabel = @isset($mailing) ? __('Edit Mailing') : __('Create New Mailing');
    @endphp
    <x-common.page-breadcrumb pageTitle="{{ $pageTitle }}" :breadcrumbs="[
        ['label' => __('Mailing'), 'url' => route('admin.mailings.index')],
        ['label' => $pageLabel, 'url' => '#']
    ]">
    </x-common.page-breadcrumb>

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6">
            <form action="{{ isset($mailing) ? route('admin.mailings.update', $mailing->id) : route('admin.mailings.store') }}" method="POST">
                @csrf
                @isset($mailing)
                    @method('PUT')
                @endisset

                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <div class="w-full px-2.5">
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            {{ __('Subject') }} <span class="text-red-500">*</span>
                        </label>
                        <input name="subject" value="{{ old('title', $mailing->subject ?? '') }}" type="text"
                               class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                        @error('subject')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full px-2.5">
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            {{ __('Body Message') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" id="body-text" name="body" value="{{ old('body', $mailing->body ?? '') }}" />
                        <trix-editor input="body-text" class="bg-white border border-gray-300 rounded-lg" style="min-height:400px"></trix-editor>

                        @error('body')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full px-2.5">
                        <div class="mt-1 flex items-center gap-3">
                            <button type="submit"
                                    class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white">
                                {{ __('Save Changes') }}
                            </button>

                            <a href="{{ route('admin.mailings.index') }}"
                               class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


