@props([
    'variant' => 'primary',
    'size' => 'default',
    'type' => 'button',
])

@php
    $variantClasses = match($variant) {
        'primary' => 'bg-ebara-500 text-white hover:bg-ebara-600 focus:ring-ebara-500',
        'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-500',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
        default => 'bg-ebara-500 text-white hover:bg-ebara-600 focus:ring-ebara-500',
    };
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };
@endphp

<button 
    {{ $attributes->merge(['type' => $type, 'class' => "inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed {$variantClasses} {$sizeClasses}"]) }}
>
    {{ $slot }}
</button>
