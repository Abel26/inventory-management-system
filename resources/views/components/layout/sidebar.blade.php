<!--
    Sidebar Component
    Off-canvas on mobile, fixed on desktop
    Z-Index: 30 (Below mobile header, above main content)
-->
<div class="flex flex-col h-full">
    <!-- Logo Section -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-slate-700">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
            <!-- Ebara Logo -->
            <div class="flex-shrink-0">
                <x-brand.ebara-logo class="h-8 w-8 text-ebara-500" />
            </div>
            <!-- App Name -->
            <span class="text-lg font-semibold text-white whitespace-nowrap">EBARA IMS</span>
        </a>

        <!-- Desktop Collapse Button -->
        <button @click="$dispatch('toggle-sidebar')"
                class="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition"
                aria-label="Toggle sidebar">
            <i class="ph ph-caret-left transition-transform duration-200"
               x-data="{ sidebarOpen: true }"
               @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
               :class="sidebarOpen ? 'rotate-0' : 'rotate-180'"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-ebara-600 text-white' : '' }}">
            <i class="ph ph-squares-four text-lg flex-shrink-0 mr-3"></i>
            <span class="flex-1 text-left truncate">Dashboard</span>
        </a>

        <!-- Assets Dropdown Group -->
        <div x-data="{ open: @json(request()->routeIs('assets.*')) }" class="space-y-1">
            <!-- Section Header (Clickable) -->
            <button @click="open = !open"
                    class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group">
                <i class="ph ph-cube text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">Assets</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                <!-- Materials -->
                <a href="{{ route('assets.materials.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('assets.materials*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.materials*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Materials</span>
                </a>

                <!-- Tools -->
                <a href="{{ route('assets.tools.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('assets.tools*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.tools*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Peralatan</span>
                </a>

                <!-- Models -->
                <a href="{{ route('assets.models.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('assets.models*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.models*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Model / Cetakan</span>
                </a>
            </div>
        </div>

        <!-- Manajemen Role -->
        <a href="{{ route('roles.index') }}"
           class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group {{ request()->routeIs('roles.*') ? 'bg-ebara-600 text-white' : '' }}">
            <i class="ph ph-shield-check text-lg flex-shrink-0 mr-3"></i>
            <span class="flex-1 text-left truncate">Manajemen Role</span>
        </a>

        <!-- Laporan Masalah Dropdown Group -->
        <div x-data="{ open: @json(request()->routeIs('reports.*')) }">
            <!-- Section Header (Clickable) -->
            <button @click="open = !open"
                    class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group">
                <i class="ph ph-clipboard-text text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">Laporan Masalah</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                <!-- Laporan Masalah (Direct Link to Index) -->
                <a href="{{ route('reports.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('reports.index') || request()->routeIs('reports.show') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.index') || request()->routeIs('reports.show') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Daftar Laporan</span>
                </a>

                <!-- Scan QR Code -->
                <a href="{{ route('reports.scan') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('reports.scan') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.scan') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Scan QR Code</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- User Info (Bottom Sidebar) -->
    <div class="border-t border-slate-700 p-4">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <x-user.user-avatar :user="Auth::user()" class="h-10 w-10" />
            </div>
            <div class="flex-1 min-w-0 overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</div>
