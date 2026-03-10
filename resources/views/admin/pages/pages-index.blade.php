@extends('admin.layouts.app')

@section('content')
    @php
        $title = __('Pages');
    @endphp
    <x-common.page-breadcrumb pageTitle="{{ $title }}">
        <x-slot:breadcrumbs>
            <li>
                <span class="text-gray-700">
                    {{ $title }}
                </span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>
    @if (session('status'))
        <div class="mb-6">
            <x-ui.alert :duration="3" :variant="session('variant')" :message="session('status')" />
        </div>
    @endif
    <template x-if="$store.alert.show">
        <div class="mb-6">
            <x-ui.alert />
        </div>
    </template>

    <div x-data="listingpage()" class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <!-- loader (spinner)-->
        <x-common.loader :show="'loading'" style="display: none;" />
        <!-- search form-->
        <div class="flex flex-col gap-3 sm:flex-row items-center p-3">
            <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                {{ __('Add New') }}
            </a>
        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <!-- table header start -->
                <thead class="border-gray-100 border-y bg-gray-50">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap w-auto">
                        <div class="flex items-center">
                            <p class="font-bold text-gray-500 text-theme-xs">
                                {{ __('Title') }}/Slug
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap w-full">
                        <div class="flex items-center">
                            <p class="font-bold text-gray-500 text-theme-xs">
                                {{ __('Sections') }}
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap w-auto">
                        <div class="flex items-center justify-center">
                            <p class="font-bold text-gray-500 text-theme-xs">
                                {{ __('Action') }}
                            </p>
                        </div>
                    </th>
                </tr>
                </thead>
                <!-- table header end -->

                <!-- table body start -->
                <tbody class="divide-y divide-gray-100">
                <template x-if="data.length === 0">
                    <tr>
                        <td class="text-muted text-center py-4" colspan="100%">
                            {{ __('No Pages found') }}
                        </td>
                    </tr>
                </template>

                <template x-for="page in data" :key="page.id">
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p x-text="`${page.title} [${page.slug}]`" class="text-gray-700 text-theme-sm"></p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex flex-wrap gap-2">

                                <template x-for="section in page.sections" :key="section.id">
                                    <a :href="`{{ route('admin.pages.edit', ':id') }}`.replace(':id', page.id) + '#section-' + section.id"
                                        class="px-2 py-1 text-xs bg-gray-100 rounded hover:bg-gray-200 text-gray-700"
                                        x-text="section.key"
                                    ></a>
                                </template>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap items-center">
                            <div class="flex w-full items-center justify-center gap-2">
                                <a x-bind:href="`{{ route('admin.pages.edit', ':id') }}`.replace(':id', page.id)" data-tippy-content="{{ __('Edit Page') }}" class="text-gray-500 hover:text-gray-800">
                                    <x-heroicon-o-pencil-square class="stroke-2" width="24" height="24" />
                                </a>
                                <x-common.confirm-delete
                                    title="{{ __('Are you sure to Delete this Page?') }}"
                                    route-name="{{ route('admin.pages.destroy', ':id') }}">
                                    <!-- Trash icon -->
                                    <button @click="itemId = page.id" data-tippy-content="{{ __('Delete Page') }}" class="flex items-center justify-center text-gray-500 hover:text-error-500">
                                        <x-heroicon-o-trash class="stroke-2" width="22" height="22" />
                                    </button>
                                </x-common.confirm-delete>
                            </div>
                        </td>
                    </tr>
                </template>
                </tbody>
                <!-- table body end -->
            </table>
        </div>

        <!-- Pagination -->
        <x-common.pagination />
    </div>
@endsection

