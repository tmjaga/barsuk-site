<div class="relative" x-data="notificationsView()" @click.away="closeDropdown()">

    <!-- Notification Button -->
    <button @click="toggleDropdown()"
            class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 hover:text-gray-700"
            type="button">

        <!-- Badge -->
        <span x-show="unreadCount > 0" class="absolute top-0.5 right-0.5 z-10 flex h-2.5 w-2.5">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"></span>
            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-orange-500"></span>
        </span>

        <!-- Bell Icon -->
        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z" fill=""/>
        </svg>
    </button>

    <!-- Dropdown -->
    <div x-show="dropdownOpen"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute -right-[240px] mt-[17px] flex h-[480px] w-[350px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg"
         style="display: none;">

        <!-- Header -->
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100">
            <h5 class="text-lg font-semibold text-gray-800">
                {{ __('Notifications') }}
                <span x-show="unreadCount > 0" class="ml-1 text-sm font-normal text-orange-500" x-text="'(' + unreadCount + ')'"></span>
            </h5>
            <button @click="closeDropdown()" class="text-gray-500" type="button">
                <x-heroicon-o-x-mark width="22" height="22" />
            </button>
        </div>

        <!-- Loading -->
        <div x-show="loading" class="flex items-center justify-center h-full">
            <svg class="animate-spin h-6 w-6 text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
        </div>

        <!-- Notification List -->
        <ul x-show="!loading" class="flex flex-col h-auto overflow-y-auto custom-scrollbar">

            <!-- Empty state -->
            <template x-if="notifications.length === 0">
                <li class="flex flex-col items-center justify-center h-full py-10 text-gray-400">
                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="text-sm">{{ __('No notifications') }}</span>
                </li>
            </template>

            <!-- Items -->
            <template x-for="notification in notifications" :key="notification.id">
                <li>
                    <a href="#" class="flex gap-3 rounded-lg border-b border-gray-100 px-3 py-3 hover:bg-gray-100"
                       :class="!notification.read ? 'bg-orange-50' : ''">

                        <!-- Icon -->
                        <span class="flex items-center justify-center w-10 h-10 rounded-full shrink-0"
                              :class="notification.type === 'order' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600'">
                            <template x-if="notification.type === 'order'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </template>
                            <template x-if="notification.type !== 'order'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </template>
                        </span>

                        <!-- Content -->
                        <span class="block">
                            <span class="mb-1 block text-sm text-gray-800" x-text="notification.message"></span>
                            <span class="flex items-center gap-2 text-xs text-gray-500">
                                <span x-text="notification.type === 'order' ? 'Order' : 'Subscriber'"></span>
                                <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                <span x-text="notification.time"></span>
                            </span>
                        </span>
                    </a>
                </li>
            </template>
        </ul>

        <!-- Footer -->
        <div x-show="!loading" class="mt-3 flex gap-2">
            <a href="{{ route('admin.notifications.index') }}"
               class="flex-1 flex justify-center rounded-lg border border-gray-300 bg-white p-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50">
                {{ __('View All') }}
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationsView', () => ({
            dropdownOpen: false,
            loading:      false,
            notifications: [],
            unreadCount:  0,
            pollInterval: null,

            init() {
                this.fetchNotifications();
                // Polling каждые 30 секунд
                this.pollInterval = setInterval(() => this.fetchNotifications(), 30000);
            },

            async fetchNotifications() {
                try {
                    const res = await axios.get('{{ route('admin.notifications.fetch') }}');
                    this.notifications = res.data.notifications;
                    this.unreadCount   = res.data.unread_count;
                } catch (e) {
                    console.error('Failed to fetch notifications', e);
                }
            },

            toggleDropdown() {
                this.dropdownOpen = !this.dropdownOpen;
                if (this.dropdownOpen) {
                    this.loading = true;
                    this.fetchNotifications().finally(() => this.loading = false);
                }
            },

            closeDropdown() {
                this.dropdownOpen = false;
            },
        }));
    });
</script>
