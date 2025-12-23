@props([
    'name',
    'label',
    'value' => 1,
    'checked' => true,
    'id' => 'toggle_' . uniqid(),
    'model' => ''
])

<div x-data="{ switcherToggle: {{ $checked ? 'true' : 'false' }} }"

     @update-toggle.window="switcherToggle = $event.detail"

>
    <label for="{{ $id }}" class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none">
        <div class="relative">
            <input
                name="{{ $name }}"
                value="{{ $value }}"
                type="checkbox"
                id="{{ $id }}"
                class="sr-only"
                :checked="switcherToggle"
                @change="switcherToggle = !switcherToggle"
                x-model="{{ $model }}"
            >
            <div
                class="block h-6 w-11 rounded-full duration-300 ease-linear"
                :class="switcherToggle ? 'bg-green-500' : 'bg-red-500'"
            ></div>
            <div
                class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear"
                :class="switcherToggle ? 'translate-x-full' : 'translate-x-0'"
            ></div>
        </div>

        <span>{{ $label }}</span>
    </label>
</div>
