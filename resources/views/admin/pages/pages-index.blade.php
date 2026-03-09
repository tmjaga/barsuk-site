@extends('admin.layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{ __('Pages') }}">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-brand-600">
                    {{ __('Dashboard') }}
                </a>
            </li>
            <li>
                <span class="text-gray-700">
                    {{ __('Pages') }}
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
                                    <x-heroicon-o-pencil class="stroke-2" width="22" height="22" />
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
        <div x-show="totalPages > 1" class="border-t border-gray-100 py-4 pl-[18px] pr-4">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between">
                <div class="flex items-center justify-center gap-0.5 pb-4 xl:justify-normal xl:pt-0">
                    <button @click="prevPage()" class="mr-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50" :disabled="currentPage === 1" disabled="disabled">
                        Previous
                    </button>
                    <template x-if="currentPage &gt; 3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-blue-500/[0.08] hover:text-brand-500">...</span>
                    </template>

                    <template x-for="page in pagesAroundCurrent" :key="page">
                        <button @click="goToPage(page)" :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700'" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500">
                            <span x-text="page"></span>
                        </button>
                    </template>
                    <template x-if="currentPage &lt; totalPages - 2">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-500/[0.08] hover:text-brand-500">...</span>
                    </template>
                    <button @click="nextPage()" class="ml-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50" :disabled="currentPage === totalPages">
                        Next
                    </button>
                </div>

                <p class="border-t border-gray-100 pt-3 text-center text-sm font-medium text-gray-500 xl:border-t-0 xl:pt-0 xl:text-left">
                    Showing <span x-text="startEntry">1</span> to
                    <span x-text="endEntry">10</span> of
                    <span x-text="totalEntries">30</span> entries
                </p>
            </div>
        </div>
    </div>
@endsection

