@props(['title', 'description'])

<div class="rounded-lg border border-gray-200 bg-white shadow-theme-xs">
    <div class="grid grid-cols-1 xl:grid-cols-[280px_1fr]">
        <!-- Settings Navigation -->
        <div class="border-b border-gray-200 xl:border-b-0 xl:border-r">
            <nav class="flex xl:flex-col p-1" x-data="{ active: '{{ request()->routeIs('admin.settings.profile.*') ? 'profile' : (request()->routeIs('admin.settings.password.*') ? 'password' : 'appearance') }}' }">
                <a href="{{ route('admin.settings.profile.edit') }}"
                   :class="active === 'profile' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50'"
                   class="flex items-center gap-3 rounded-md px-4 py-3 text-sm font-medium transition-colors">
                    Profile
                </a>
                <a href="{{ route('admin.settings.password.edit') }}"
                   :class="active === 'password' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50'"
                   class="flex items-center gap-3 rounded-md px-4 py-3 text-sm font-medium transition-colors">
                    Password
                </a>
            </nav>
        </div>

        <!-- Settings Content -->
        <div class="p-6 xl:p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
            </div>

            {{ $slot }}
        </div>
    </div>
</div>
