@props([
    'id' => '',
    'name' => '',
    'options' => [],
    'placeholder' => 'Select ...'
])

<div x-data="multiSelectComponent({
    options: @js($options)
})" id="{{ $id }}" class="relative" @click.away="openOptions = false">
    <input type="hidden" name="{{ $name }}" :value="selected.join(',')">
    <!-- Select box -->
    <div @click="openOptions = !openOptions" class="shadow-theme-xs flex min-h-11 cursor-pointer gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2">
        <div class="flex flex-1 flex-wrap items-center gap-2">
            <template x-for="id in selected">
                <div class="flex items-center rounded-full bg-gray-100 px-2 py-1 text-sm">
                    <span x-text="getLabel(id)"></span>
                    <button type="button" @click.stop="toggleOption(id)" class="ml-1 text-gray-500 hover:text-gray-700">✕</button>
                </div>
            </template>
            <span x-show="selected.length === 0" class="text-sm text-gray-500">{{ $placeholder }}</span>
        </div>
        <!-- Dropdown Arrow -->
        <div class="flex items-start pt-1.5">
            <svg class="h-5 w-5 shrink-0 text-gray-500 transition-transform dark:text-gray-400 rotate-180" :class="{ 'rotate-180': openOptions }"fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
    <!-- Dropdown -->
    <div x-show="openOptions" x-transition class="absolute z-50 mt-1 w-full rounded-lg border bg-white">
        <template x-for="option in options" :key="option.id">
            <div @click="toggleOption(option.id)" class="cursor-pointer px-4 py-2 hover:bg-gray-100" :class="{ 'font-semibold': selected.includes(option.id) }">
                <span x-text="option.title"></span>
            </div>
        </template>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('multiSelectComponent', ({ options, selected = [] }) => ({
            openOptions: false,
            options,
            selected,
            init() {
                this.$watch('selected', () => {
                    this.$dispatch('multiselect-changed')
                })
            },
            toggleOption(id) {
                this.isSelected(id)
                    ? this.selected = this.selected.filter(i => i !== id)
                    : this.selected.push(id)
            },
            isSelected(id) {
                return this.selected.includes(id)
            },
            getLabel(id) {
                return this.options.find(o => o.id === id)?.title ?? ''
            },
        }));
    });
</script>
