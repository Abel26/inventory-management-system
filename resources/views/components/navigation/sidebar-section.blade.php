@props([
    'title',
    'icon' => null,
    'collapsed' => false,
    'children' => [],
])

<div x-data="{ open: {{ request()->routeIs(collect($children)->pluck('active')->toArray()) ? 'true' : 'false' }} }">
    
    <!-- Section Header (Clickable) -->
    <button 
        @click="open = !open"
        class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group"
    >
        @if($icon)
            <i class="ph {{ $icon }} text-lg flex-shrink-0 {{ $collapsed ? 'mx-auto' : 'mr-3' }}"></i>
        @endif
        
        <span class="flex-1 text-left truncate {{ $collapsed ? 'hidden' : '' }}">{{ $title }}</span>
        
        @if(!$collapsed)
            <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
        @endif
    </button>
    
    <!-- Children Menu (Collapsible) -->
    <div 
        x-show="open"
        x-collapse
        class="mt-1 space-y-1 {{ $collapsed ? 'hidden' : '' }}"
    >
        @foreach($children as $child)
            <a 
                href="{{ route($child['route']) }}"
                class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                       {{ $child['active'] ?? false 
                            ? 'bg-ebara-500/20 text-ebara-400' 
                            : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}"
            >
                <span class="w-6 h-6 flex items-center justify-center mr-3">
                    <span class="w-1.5 h-1.5 rounded-full {{ $child['active'] ?? false ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                </span>
                <span class="truncate">{{ $child['title'] }}</span>
            </a>
        @endforeach
    </div>
    
</div>
