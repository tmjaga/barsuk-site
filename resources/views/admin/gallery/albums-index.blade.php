@extends('admin.layouts.app')

@section('content')
    @php
        $title = __('Albums');
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
            <a href="{{ route('admin.albums.create') }}" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                {{ __('Add New') }}
            </a>

            <div class="relative ml-auto">
                <span class="absolute -translate-y-1/2 pointer-events-none top-1/2 left-4">
                    <svg class="fill-gray-500" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""></path>
                    </svg>
                </span>
                <input x-model.debounce.500ms="search" type="text" placeholder="Search..." class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-10 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px]">
            </div>

        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <!-- table header start -->
                <thead class="border-gray-100 border-y bg-gray-50">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <p class="font-bold text-gray-500 text-theme-xs">
                                {{ __('Title') }}
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
                        <div class="flex items-center justify-center">
                            <p class="font-bold text-gray-500 text-theme-xs">
                                {{ __('Status') }}
                            </p>
                        </div>
                    </th>
                    <th class="px-6 py-3 whitespace-nowrap">
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
                            {{ __('No Albums found') }}
                        </td>
                    </tr>
                </template>

                <template x-for="album in data" :key="album.id">
                    <tr x-data="statusBadge">
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <p x-text="album.title" class="text-gray-700 text-theme-sm"></p>
                            </div>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap text-center">
                            <span
                                x-text="album.active !== undefined ? getBadge(album.active).text : getBadge().text"
                                :class="album.active !== undefined ? getBadge(album.active).color : getBadge().color"
                                class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium">
                            </span>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap items-center">
                            <div class="flex w-full items-center justify-center gap-2">
                                <a x-bind:href="`{{ route('admin.albums.edit', ':id') }}`.replace(':id', album.id)" data-tippy-content="Edit Album" class="text-gray-500 hover:text-gray-800">
                                    <x-heroicon-o-pencil-square class="stroke-2" width="24" height="24" />
                                </a>
                                <x-common.confirm-delete
                                    title="{{ __('Are you sure to Delete this Album?') }}"
                                    route-name="{{ route('admin.albums.destroy', ':id') }}">
                                    <!-- Trash icon -->
                                    <button @click="itemId = album.id" data-tippy-content="{{__('Delete Album')}}" class="flex items-center justify-center text-gray-500 hover:text-error-500">
                                        <x-heroicon-o-trash class="stroke-2" width="22" height="22" />
                                    </button>
                                </x-common.confirm-delete>
                                <a x-bind:href="`{{ route('admin.albums.media.index', ':id') }}`.replace(':id', album.id)" data-tippy-content="{{__('View Photos')}}" class="text-gray-500 hover:text-gray-800">
                                    <x-heroicon-o-camera class="stroke-2" width="22" height="22" />
                                </a>
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

@push('footer_scripts')
    <script src="{{ asset('js/status-badge.js') }}"></script>
@endpush
