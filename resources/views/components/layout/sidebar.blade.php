<!--
    Sidebar Component
    Off-canvas on mobile, fixed on desktop
    Z-Index: 30 (Below mobile header, above main content)
-->
<div class="flex flex-col h-full">
    <!-- Logo Section -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-slate-700">
        <a href="{{ Auth::user()->isRegularUser() ? route('work-logs.my-work') : route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
            <!-- Ebara Logo -->
            <div class="flex-shrink-0">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Ebara Inventory Logo" class="h-8 w-auto">
            </div>
            <!-- App Name -->
            <span class="text-lg font-semibold text-white whitespace-nowrap">EBARA IMS</span>
        </a>

        <!-- Mobile Close Button (X) — visible only on mobile -->
        <button @click="$dispatch('toggle-sidebar')"
                class="flex lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition"
                aria-label="Close sidebar">
            <i class="ph ph-x text-xl"></i>
        </button>

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
        <!-- Dashboard - All roles can access -->
        @can('view dashboard')
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-ebara-600 text-white' : '' }}">
            <i class="ph ph-squares-four text-lg flex-shrink-0 mr-3"></i>
            <span class="flex-1 text-left truncate">{{ __('sidebar.dashboard') }}</span>
        </a>
        @endcan

        <!-- Work Logs Dropdown Group - All roles can access -->
        @canany(['View own work logs', 'View all work logs'])
        <div x-data="{ open: @json(request()->routeIs('work-logs.*')) }" class="space-y-1">
            <!-- Section Header (Clickable) -->
            <button @click="open = !open"
                    class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group">
                <i class="ph ph-clipboard-text text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">{{ __('sidebar.work_logs') }}</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                <!-- My Work -->
                @can('View own work logs')
                <a href="{{ route('work-logs.my-work') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('work-logs.my-work') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('work-logs.my-work') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">{{ __('sidebar.my_work') }}</span>
                </a>
                @endcan
                
                <!-- All Work Logs - Admin only -->
                @can('View all work logs')
                <a href="{{ route('work-logs.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('work-logs.index') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('work-logs.index') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">{{ __('sidebar.all_work_logs') }}</span>
                </a>
                @endcan
            </div>
        </div>
        @endcanany

        <!-- Assets Dropdown Group - All roles can view -->
        @can('view assets')
        <div x-data="{ open: @json(request()->routeIs('assets.*')) }" class="space-y-1">
            <!-- Section Header (Clickable) -->
            <button @click="open = !open"
                    class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group">
                <i class="ph ph-cube text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">{{ __('sidebar.assets') }}</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                <!-- Materials -->
                @can('view materials')
                <a href="{{ route('assets.materials.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('assets.materials*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.materials*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">{{ __('sidebar.materials') }}</span>
                </a>
                @endcan

                <!-- Tools -->
                @can('view tools')
                <a href="{{ route('assets.tools.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('assets.tools*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.tools*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">{{ __('sidebar.tools') }}</span>
                </a>
                @endcan

                <!-- Models -->
                @can('view models')
                <a href="{{ route('assets.models.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('assets.models*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.models*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">{{ __('sidebar.models') }}</span>
                </a>
                @endcan
            </div>
        </div>
        @endcan

        <!-- Master Data Dropdown Group - Only Super Admin and Admin -->
        @canany(['view dashboard', 'create dashboard', 'edit dashboard'])
        <div x-data="{ open: @json(request()->routeIs('master-data.*')) }" class="space-y-1">
            <!-- Section Header (Clickable) -->
            <button @click="open = !open"
                    class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group">
                <i class="ph ph-database text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">{{ __('sidebar.master_data') }}</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                <!-- Gedungs -->
                <a href="{{ route('master-data.gedungs.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('master-data.gedungs*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                       <span class="w-6 h-6 flex items-center justify-center mr-3">
                           <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('master-data.gedungs*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                       </span>
                       <span class="truncate">{{ __('sidebar.gedung') }}</span>
                   </a>
                <!-- Satuan -->
                <a href="{{ route('master-data.satuans.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('master-data.satuans*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                       <span class="w-6 h-6 flex items-center justify-center mr-3">
                           <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('master-data.satuans*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                       </span>
                       <span class="truncate">{{ __('sidebar.satuan') }}</span>
                   </a>
               </div>
        </div>
        @endcanany

        <!-- Pengaturan Akses Dropdown Group - Only Super Admin and Admin (view only for Admin) -->
        @if(Auth::user()->canViewUserManagement() || Auth::user()->canManageRoles())
        <div x-data="{ open: @json(request()->routeIs('users.*') || request()->routeIs('roles.*')) }" class="space-y-1">
            <!-- Section Header (Clickable) -->
            <button @click="open = !open"
                    class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group">
                <i class="ph ph-gear text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">{{ __('sidebar.access_settings') }}</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                <!-- Manajemen User - Super Admin and Admin can view -->
                @if(Auth::user()->canViewUserManagement())
                <a href="{{ route('users.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('users.*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('users.*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">{{ __('sidebar.user_management') }}</span>
                </a>
                @endif

                <!-- Manajemen Role - Only Super Admin -->
                @if(Auth::user()->canManageRoles())
                <a href="{{ route('roles.index') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('roles.*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('roles.*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">{{ __('sidebar.role_management') }}</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Laporan Masalah Dropdown Group - All roles can access -->
        <div x-data="{ open: @json(request()->routeIs('reports.*')) }">
            <!-- Section Header (Clickable) -->
            <button @click="open = !open"
                    class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group">
                <i class="ph ph-clipboard-text text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">{{ __('sidebar.reports') }}</span>
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
                    <span class="truncate">{{ __('sidebar.report_list') }}</span>
                </a>

                <!-- Scan QR Code -->
                <a href="{{ route('reports.scan') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                   {{ request()->routeIs('reports.scan') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.scan') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">{{ __('sidebar.scan_qr') }}</span>
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
