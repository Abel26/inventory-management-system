@props([
    'columns' => '1', // '1', '2', or '3'
    'gap' => '4', // Tailwind spacing value
    'className' => ''
])

@php
    $gridClasses = 'grid grid-cols-1 gap-' . $gap;
    
    if ($columns === '2') {
        $gridClasses .= ' md:grid-cols-2';
    } elseif ($columns === '3') {
        $gridClasses .= ' md:grid-cols-2 lg:grid-cols-3';
    }
    
    $finalClasses = trim($gridClasses . ' ' . $className);
@endphp

<div class="{{ $finalClasses }}">
    {{ $slot }}
</div>
