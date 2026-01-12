@props([
    'title' => null,
    'description' => null,
    'padding' => 'default',
])

@php
    $paddingClasses = match($padding) {
        'sm' => 'p-4',
        'lg' => 'p-6',
        'none' => '',
        default => 'p-5',
    };
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 {{ $paddingClasses }}">
    
    @if($title || $description)
        <div class="{{ $slot->isNotEmpty() ? 'mb-4' : '' }}">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @endif
            @if($description)
                <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
    @endif
    
    {{ $slot }}
    
</div>
