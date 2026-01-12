@props(['user', 'size' => 'md'])

@php
    $sizeClasses = match($size) {
        'sm' => 'h-6 w-6 text-xs',
        'lg' => 'h-12 w-12 text-lg',
        default => 'h-9 w-9 text-sm',
    };
@endphp

<div class="{{ $sizeClasses }} rounded-full bg-ebara-100 text-ebara-600 flex items-center justify-center font-medium">
    {{ strtoupper(substr($user->name, 0, 1)) }}
</div>
