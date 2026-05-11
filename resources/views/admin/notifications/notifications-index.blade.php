@extends('admin.layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="{{ __('Notifications') }}">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-brand-600">
                    {{ __('Dashboard') }}
                </a>
            </li>
            <li>
                <span class="text-gray-700">
                    {{ __('Notifications') }}
                </span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <template x-if="$store.alert.show">
        <div class="mb-6">
            <x-ui.alert />
        </div>
    </template>

    <div x-data="listingpage()" class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <x-common.loader :show="'loading'" style="display: none;" />
        <div x-data="markAllRead" class="p-4 border-b border-gray-100 flex items-center justify-start">

            <button @click="setAllRead" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                {{ __('Mark All as Read') }}
                <x-heroicon-o-check class="stroke-2" width="18" height="18" />
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3 whitespace-nowrap font-medium text-gray-500 text-theme-xs">
                            {{ __('Notification') }}
                        </th>
                        <th class="text-center px-6 py-3 whitespace-nowrap font-medium text-gray-500 text-theme-xs">
                            {{ __('Type') }}
                        </th>
                        <th class="text-center px-6 py-3 whitespace-nowrap font-medium text-gray-500 text-theme-xs">
                            {{ __('Date') }}
                        </th>
                        <th class="text-end px-6 py-3 whitespace-nowrap font-medium text-gray-500 text-theme-xs">
                            {{ __('Action') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="data.length === 0">
                        <tr>
                            <td class="text-muted text-center py-4" colspan="100%">
                                {{ __('No Notifications found') }}
                            </td>
                        </tr>
                    </template>
                    <template x-for="notification in data" :key="notification.id">
                        <tr :class="!notification.read_at ? 'bg-orange-50/50' : ''">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                <span x-text="notification.data.message"></span>
                            </td>
                            <td class="text-center px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full"
                                      :class="notification.type === 'order' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'"
                                      x-text="notification.type === 'order' ? 'Order' : 'Subscriber'">
                                </span>
                            </td>
                            <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="$formatDate(notification.created_at)"></span>
                            </td>
                            <td class="text-center px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <template x-if="!notification.read_at">
                                         <div x-data="changeReadStatus(currentPage)">
                                            <button @click="markAsRead(notification.id)" class="text-gray-400" data-tippy-content="{{ __('Mark as Read') }}">
                                                <x-heroicon-o-check class="stroke-2" width="18" height="18" />
                                            </button>
                                         </div>
                                    </template>
                                    <x-common.confirm-delete
                                        title="{{ __('Are you sure you want to delete this notification?') }}"
                                        route-name="{{ route('admin.notifications.destroy', ':id') }}">
                                        <button @click="itemId = notification.id" data-tippy-content="{{ __('Delete Notification')}}" class="flex items-center justify-center text-gray-500 hover:text-error-500">
                                            <x-heroicon-o-trash class="stroke-2" width="22" height="22" />
                                        </button>
                                    </x-common.confirm-delete>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-common.pagination />
    </div>
@endsection

@push('footer_scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('changeReadStatus', (currentPage = 1) => ({
                currentPage: currentPage,
                async markAsRead(id) {
                    try {
                        const response = await axios.put('{{ route('admin.notifications.mark-as-read', ':id') }}'.replace(':id', id));

                        Alpine.store('alert').success(response?.data?.message);
                        this.$dispatch('reload-items', { page: this.currentPage });
                    } catch (error) {
                        this.isModalOpen = false;
                        Alpine.store('alert').error(error?.response?.data?.message);
                    }
                }
            }));

            Alpine.data('markAllRead', () => ({
                async setAllRead() {
                    try {
                        const response = await axios.post('{{ route('admin.notifications.mark-all-read') }}');

                        Alpine.store('alert').success(response?.data?.message);
                        this.$dispatch('reload-items', { page: this.currentPage });
                    } catch (error) {
                        this.isModalOpen = false;
                        Alpine.store('alert').error(error?.response?.data?.message);
                    }
                }
            }));
        });
    </script>
@endpush

