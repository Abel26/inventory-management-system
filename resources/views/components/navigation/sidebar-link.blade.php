@props([
    'title',
    'icon' => null,
    'route',
    'active' => false,
    'collapsed' => false,
])

@php
    $baseClasses = 'flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 group';
    $activeClasses = 'bg-ebara-500 text-white shadow-ebara';
    $inactiveClasses = 'text-slate-300 hover:bg-slate-800 hover:text-white';
    $classes = $active ? $activeClasses : $inactiveClasses;
@endphp

<a 
    href="{{ $route }}" 
    class="{{ $baseClasses }} {{ $classes }}"
    {{ $active ? 'aria-current="page"' : '' }}
>
    @if($icon)
        <i class="ph {{ $icon }} text-lg flex-shrink-0 {{ $collapsed ? 'mx-auto' : 'mr-3' }}"></i>
    @endif
    
    <span class="truncate {{ $collapsed ? 'hidden' : '' }}">{{ $title }}</span>
    
    @if($active)
        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
    @endif
</a>
