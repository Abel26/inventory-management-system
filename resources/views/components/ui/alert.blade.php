@props([
    'type' => 'info',
    'dismissible' => false,
])

@php
    $typeConfig = match($type) {
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-200',
            'icon' => 'ph-check-circle',
            'iconColor' => 'text-green-500',
            'textColor' => 'text-green-800',
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'icon' => 'ph-x-circle',
            'iconColor' => 'text-red-500',
            'textColor' => 'text-red-800',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-200',
            'icon' => 'ph-warning',
            'iconColor' => 'text-yellow-500',
            'textColor' => 'text-yellow-800',
        ],
        default => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'icon' => 'ph-info',
            'iconColor' => 'text-blue-500',
            'textColor' => 'text-blue-800',
        ],
    };
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="{{ $typeConfig['bg'] }} {{ $typeConfig['border'] }} border rounded-lg p-4"
    style="display: none;"
>
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="ph {{ $typeConfig['icon'] }} {{ $typeConfig['iconColor'] }} text-xl"></i>
        </div>
        <div class="ml-3 flex-1">
            <p class="{{ $typeConfig['textColor'] }} text-sm">
                {{ $message ?? $slot }}
            </p>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button @click="show = false" class="inline-flex rounded-md p-1.5 {{ $typeConfig['textColor'] }} hover:bg-white/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ $typeConfig['bg'] }}">
                    <i class="ph ph-x"></i>
                </button>
            </div>
        @endif
    </div>
</div>
