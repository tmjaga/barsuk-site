@extends('admin.layouts.app')

@section('content')
    @php
        $pageTitle = @isset($page) ? __('Edit Page').': '. $page->title : __('Create New Page');
        $pageLabel = @isset($page) ? __('Edit Page') : __('Create New Page');
        $languages = config('logat.languages');
        $templates = getPageTemplates();
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
                    <input name="slug" @input="event.target.value = event.target.value.replace(/[^A-Za-z0-9_-]/g,'')" value="{{ old('slug', $page->slug ?? '') }}"
                        type="text"
                        :disabled="{{ isset($page) ? 'true' : 'false' }}"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden">
                    @error('slug')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div x-data="pageTemplate()" class="w-ful max-w-sm mb-4">
                    <label for="checkboxLabelTwo" class="flex mb-1.5 cursor-pointer items-center text-sm font-bold text-gray-700">
                        <div class="relative">
                            <input name="custom_template" value="1" type="checkbox" id="checkboxLabelTwo" class="sr-only" @change="useTemplate()" {{ old('custom_template', isset($page) ? $page->custom_template : false) ? 'checked' : '' }}>
                            <div :class="checkboxToggle ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300'" class="hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] border-brand-500 bg-brand-500">
                                <span :class="checkboxToggle ? '' : 'opacity-0'" class="">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        {{__('Use Custom Template')}} <span class="text-red-500">*</span>
                    </label>
                    <!-- <div class="flex gap-2 items-center"> -->
                        <div class="relative z-20 bg-transparent">
                            <select :disabled="!checkboxToggle" name="template" x-model="template" class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" :class="isOptionSelected &amp;&amp; 'text-gray-800'" @change="isOptionSelected = true">
                            <option value="" class="text-gray-700 ">
                                {{ __('Selet Template') }}
                            </option>
                            @foreach($templates as $key => $template)
                                <option value="{{ $key }}" class="text-gray-700">
                                    {{ $template }}
                                </option>
                            @endforeach
                            </select>
                            <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                        </span>
                    </div>
                        {{-- TODO: uncomment to showScheme functionality and check select width
                        <a x-show="template" @click="showScheme()" href="javascript:;" class="text-sm font-normal underline text-blue-light-500">{{ __('Show Template Scheme') }}</a>
                        --}}
                        @error('template')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    <!-- </div> -->
                </div>

                <!-- sections component -->
                <x-common.sections :existing-sections="$existingSections ?? []" :errors="$errors" />

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
@push('footer_scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pageTemplate', () => ({
                isOptionSelected: false,
                checkboxToggle: {{ old('custom_template', isset($page) ? $page->custom_template : false) ? 'true' : 'false' }},
                template: '{{ old('template', $page->template ?? null) }}',
                useTemplate() {
                   if (this.checkboxToggle) {
                       this.template = null;
                   }

                   return this.checkboxToggle = !this.checkboxToggle;
                },
                showScheme() {
                    alert('test');
                }
            }));
        });
    </script>
@endpush



