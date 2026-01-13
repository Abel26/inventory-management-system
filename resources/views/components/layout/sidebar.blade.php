@props([
    'navigation' => null
])

@php
    // Default navigation menu structure
    $menu = $navigation ?? [
        [
            'title' => 'Dashboard',
            'icon' => 'ph-squares-four',
            'route' => 'dashboard',
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'title' => 'Products',
            'icon' => 'ph-package',
            'route' => 'products.index',
            'active' => request()->routeIs('products*'),
        ],
        [
            'title' => 'Assets',
            'icon' => 'ph-cube',
            'children' => [
                [
                    'title' => 'Materials',
                    'route' => 'assets.materials.index',
                    'active' => request()->routeIs('assets.materials*'),
                ],
                [
                    'title' => 'Tools',
                    'route' => 'assets.tools.index',
                    'active' => request()->routeIs('assets.tools*'),
                ],
                [
                    'title' => 'Models',
                    'route' => 'assets.models.index',
                    'active' => request()->routeIs('assets.models*'),
                ],
            ],
        ],
        [
            'title' => 'Inventory',
            'icon' => 'ph-warehouse',
            'children' => [
                [
                    'title' => 'Stock In',
                    'route' => 'inventory.stock-in',
                    'active' => request()->routeIs('inventory.stock-in'),
                ],
                [
                    'title' => 'Stock Out',
                    'route' => 'inventory.stock-out',
                    'active' => request()->routeIs('inventory.stock-out'),
                ],
                [
                    'title' => 'History',
                    'route' => 'inventory.history',
                    'active' => request()->routeIs('inventory.history'),
                ],
            ],
        ],
        [
            'title' => 'Reports',
            'icon' => 'ph-chart-bar',
            'children' => [
                [
                    'title' => 'Inventory Report',
                    'route' => 'reports.inventory',
                    'active' => request()->routeIs('reports.inventory'),
                ],
                [
                    'title' => 'Transactions',
                    'route' => 'reports.transactions',
                    'active' => request()->routeIs('reports.transactions'),
                ],
            ],
        ],
        [
            'title' => 'Settings',
            'icon' => 'ph-gear',
            'children' => [
                [
                    'title' => 'Profile',
                    'route' => 'profile.edit',
                    'active' => request()->routeIs('profile.edit'),
                ],
                [
                    'title' => 'System Settings',
                    'route' => 'settings.index',
                    'active' => request()->routeIs('settings*'),
                ],
            ],
        ],
    ];
@endphp

<!-- Sidebar Container -->
<div class="flex flex-col h-full">
    
    <!-- Logo Section -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-slate-700">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
            <!-- Ebara Logo -->
            <div class="flex-shrink-0">
                <x-brand.ebara-logo class="h-8 w-8 text-ebara-500" />
            </div>
            <!-- App Name (hidden when collapsed) -->
            <span class="text-lg font-semibold text-white whitespace-nowrap transition-opacity duration-300" x-show="$parent.sidebarOpen">
                Ebara IMS
            </span>
        </a>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        @foreach($menu as $item)
            @if(isset($item['children']))
                <!-- Menu Group with Children -->
                <x-navigation.sidebar-section 
                    :title="$item['title']" 
                    :icon="$item['icon'] ?? null"
                    :children="$item['children']"
                />
            @else
                <!-- Single Menu Item -->
                <x-navigation.sidebar-link 
                    :title="$item['title']"
                    :icon="$item['icon'] ?? null"
                    :route="$item['route']"
                    :active="$item['active'] ?? false"
                />
            @endif
        @endforeach
    </nav>
    
    <!-- User Info (Bottom Sidebar) -->
    <div class="border-t border-slate-700 p-4">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <x-user.user-avatar :user="Auth::user()" class="h-10 w-10" />
            </div>
            <div class="flex-1 min-w-0 overflow-hidden transition-opacity duration-300" x-show="$parent.sidebarOpen">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
    
</div>
