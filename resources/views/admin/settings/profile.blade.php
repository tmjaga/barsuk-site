@extends('admin.layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Profile">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-brand-600 dark:text-gray-400 dark:hover:text-brand-500">Dashboard</a>
            </li>
            <li>
                <span class="text-gray-700 dark:text-gray-400">Profile</span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <x-layouts.settings title="Profile" description="Update your name and email address">
        @if (session('status'))
            <div class="mb-6">
                <x-ui.alert variant="success" :message="session('status')" />
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Input -->
            <div>
                <x-forms.input
                    name="name"
                    label="Name"
                    type="text"
                    :value="$user->name"
                    required
                    autofocus
                />
            </div>

            <!-- Email Input -->
            <div>
                <x-forms.input
                    name="email"
                    label="Email"
                    type="email"
                    :value="$user->email"
                    required
                />
            </div>

            <!-- Save Button -->
            <div>
                <x-ui.button type="submit" variant="primary">
                    Save
                </x-ui.button>
            </div>
        </form>

    </x-layouts.settings>
@endsection
