@extends('admin.layouts.app')

@section('content')
    @php
        $pageTitle = @isset($page) ? __('Edit Page').': '. $page->title : __('Create New Page');
        $pageLabel = @isset($page) ? __('Edit Page') : __('Create New Page');
        $languages = config('logat.languages');
    @endphp
    <x-common.page-breadcrumb pageTitle="{{ $pageTitle }}" :breadcrumbs="[
        ['label' => __('Pages'), 'url' => route('admin.pages.index')],
        ['label' => $pageLabel, 'url' => '#']
    ]" >
    </x-common.page-breadcrumb>

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6">

            <form action="{{ isset($page) ? route('admin.pages.update', $page->id) : route('admin.pages.store') }}" method="POST">
                @csrf
                @isset($page)
                    @method('PUT')
                @endisset
                <div class="w-ful max-w-sm mb-4">
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">
                        {{__('Page Title')}} <span class="text-red-500">*</span>
                    </label>

                    <input name="title" value="{{ old('title', $page->title ?? '') }}" type="text" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">

                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-ful max-w-sm mb-4">
                    <label class="mb-1.5 block text-sm font-bold text-gray-700">
                        {{__('Page Slug')}} <span class="text-red-500">*</span>
                    </label>

                    <input
                        name="slug"
                        @input="event.target.value = event.target.value.replace(/[^A-Za-z0-9_-]/g,'')"
                        value="{{ old('slug', $page->slug ?? '') }}"
                        type="text"
                        :disabled="{{ isset($page) ? 'true' : 'false' }}"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">

                    @error('slug')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- sections component -->
                <x-common.sections
                    :existing-sections="$existingSections ?? []"
                    :errors="$errors"
                />
                <hr class="my-6 border-gray-300">

                <div class="mt-1 flex items-center gap-3">
                    <button type="submit" class="bg-brand-500 hover:bg-brand-600 flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white">
                        {{__('Save Changes')}}
                    </button>

                    <a href="{{ route('admin.pages.index') }}" class="flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800">
                        {{__('Cancel')}}
                    </a>
                </div>

            </form>
        </div>
    </div>
@endsection



