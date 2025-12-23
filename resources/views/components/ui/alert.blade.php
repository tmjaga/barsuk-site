{{-- resources/views/components/alert.blade.php --}}

@props([
    'variant' => 'info',
    'title' => '',
    'message' => '',
    'showLink' => false,
    'linkHref' => '#',
    'linkText' => 'Learn more',
    'duration' => 0, // duration in seconds
])

@php
    $variantClasses = [
        'success' => [
            'container' => 'border-green-500 bg-green-50',
            'icon' => 'text-green-500',
        ],
        'error' => [
            'container' => 'border-red-500 bg-red-50',
            'icon' => 'text-red-500',
        ],
        'warning' => [
            'container' => 'border-yellow-500 bg-yellow-50',
            'icon' => 'text-yellow-500',
        ],
        'info' => [
            'container' => 'border-blue-500 bg-blue-50',
            'icon' => 'text-blue-500',
        ],
    ];

    $icons = [
        'success' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z" fill=""></path>
      </svg>',
        'error' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
         <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
      </svg>',
        'warning' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M20.3499 12.0004C20.3499 16.612 16.6115 20.3504 11.9999 20.3504C7.38832 20.3504 3.6499 16.612 3.6499 12.0004C3.6499 7.38881 7.38833 3.65039 11.9999 3.65039C16.6115 3.65039 20.3499 7.38881 20.3499 12.0004ZM11.9999 22.1504C17.6056 22.1504 22.1499 17.6061 22.1499 12.0004C22.1499 6.3947 17.6056 1.85039 11.9999 1.85039C6.39421 1.85039 1.8499 6.3947 1.8499 12.0004C1.8499 17.6061 6.39421 22.1504 11.9999 22.1504ZM13.0008 16.4753C13.0008 15.923 12.5531 15.4753 12.0008 15.4753L11.9998 15.4753C11.4475 15.4753 10.9998 15.923 10.9998 16.4753C10.9998 17.0276 11.4475 17.4753 11.9998 17.4753L12.0008 17.4753C12.5531 17.4753 13.0008 17.0276 13.0008 16.4753ZM11.9998 6.62898C12.414 6.62898 12.7498 6.96476 12.7498 7.37898L12.7498 13.0555C12.7498 13.4697 12.414 13.8055 11.9998 13.8055C11.5856 13.8055 11.2498 13.4697 11.2498 13.0555L11.2498 7.37898C11.2498 6.96476 11.5856 6.62898 11.9998 6.62898Z" fill="#F04438"></path>
      </svg>',
        'info' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.6501 11.9996C3.6501 7.38803 7.38852 3.64961 12.0001 3.64961C16.6117 3.64961 20.3501 7.38803 20.3501 11.9996C20.3501 16.6112 16.6117 20.3496 12.0001 20.3496C7.38852 20.3496 3.6501 16.6112 3.6501 11.9996ZM12.0001 1.84961C6.39441 1.84961 1.8501 6.39392 1.8501 11.9996C1.8501 17.6053 6.39441 22.1496 12.0001 22.1496C17.6058 22.1496 22.1501 17.6053 22.1501 11.9996C22.1501 6.39392 17.6058 1.84961 12.0001 1.84961ZM10.9992 7.52468C10.9992 8.07697 11.4469 8.52468 11.9992 8.52468H12.0002C12.5525 8.52468 13.0002 8.07697 13.0002 7.52468C13.0002 6.9724 12.5525 6.52468 12.0002 6.52468H11.9992C11.4469 6.52468 10.9992 6.9724 10.9992 7.52468ZM12.0002 17.371C11.586 17.371 11.2502 17.0352 11.2502 16.621V10.9445C11.2502 10.5303 11.586 10.1945 12.0002 10.1945C12.4144 10.1945 12.7502 10.5303 12.7502 10.9445V16.621C12.7502 17.0352 12.4144 17.371 12.0002 17.371Z" fill=""></path>
      </svg>',
    ];

    $containerClass = $variantClasses[$variant]['container'] ?? $variantClasses['info']['container'];

    $iconClass = $variantClasses[$variant]['icon'] ?? $variantClasses['info']['icon'];
    $icon = $icons[$variant] ?? $icons['info'];
@endphp

<div x-data="{
        show: true,

        init() {
            const duration = {{ $duration }};

            // needed if not called from JS
            if (duration > 0) {
                setTimeout(() => {
                    this.show = false;
                    Alpine.store('alert').hideAlert();
                }, duration * 1000);
            }
        },

        variant: @js($title) || $store.alert.variant,
        title: @js($title) || $store.alert.title,
        message: @js($message) || $store.alert.message,
        get variantClass() {
            if (!this.variant) return '';
            // get PHP arrays
            let variantClasses = @js($variantClasses ?? []);
            let iconImages = @js($icons ?? []);

            let classContainer = variantClasses[this.variant]?.container || ''
            let classIcon = variantClasses[this.variant]?.icon || '';
            let icon = iconImages[this.variant] || '';

            return {
               containerClass: classContainer,
               iconClass: classIcon,
               iconImage: icon
            };
        }
    }"
    x-show="show"
    class="rounded-xl border p-4 {{ $containerClass }}"
    :class= "variantClass.containerClass"
>
    <div class="flex items-center gap-3">
        <div class="{{ $iconClass }}" :class= "variantClass.iconClass" x-html="variantClass.iconImage"></div>

        <div class="flex">
                <h4 x-text="title" class="mb-1 text-sm font-semibold text-gray-800"></h4>
                <p x-text="message" class="text-sm text-gray-500"></p>

            @if($showLink)
                <a
                    href="{{ $linkHref }}"
                    class="inline-block mt-3 text-sm font-medium text-gray-500 underline hover:text-gray-700"
                >
                    {{ $linkText }}
                </a>
            @endif

            {{-- Slot for custom content --}}
            {{ $slot }}
        </div>
    </div>
</div>
