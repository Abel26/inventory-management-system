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
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-ebara-500/20 text-ebara-400' : '' }}">
            <i class="ph ph-squares-four text-lg flex-shrink-0 mr-3"></i>
            <span class="flex-1 text-left truncate">Dashboard</span>
        </a>
        
        <!-- Products -->
        <a href="{{ route('products.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group {{ request()->routeIs('products*') ? 'bg-ebara-500/20 text-ebara-400' : '' }}">
            <i class="ph ph-package text-lg flex-shrink-0 mr-3"></i>
            <span class="flex-1 text-left truncate">Products</span>
        </a>
        
        <!-- Assets Dropdown Group -->
        <div x-data="{ open: {{ request()->routeIs('assets.materials*') || request()->routeIs('assets.tools*') || request()->routeIs('assets.models*') ? 'true' : 'false' }} }">
            
            <!-- Section Header (Clickable) -->
            <button 
                @click="open = !open"
                class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group"
            >
                <i class="ph ph-cube text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">Assets</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                
                <!-- Materials -->
                <a href="{{ route('assets.materials.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('assets.materials*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.materials*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Materials</span>
                </a>
                
                <!-- Tools -->
                <a href="{{ route('assets.tools.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('assets.tools*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.tools*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Tools</span>
                </a>
                
                <!-- Models -->
                <a href="{{ route('assets.models.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('assets.models*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('assets.models*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Models</span>
                </a>
                
            </div>
        </div>
        
        <!-- Inventory Dropdown Group -->
        <div x-data="{ open: {{ request()->routeIs('inventory.stock-in') || request()->routeIs('inventory.stock-out') || request()->routeIs('inventory.history') ? 'true' : 'false' }} }">
            
            <!-- Section Header (Clickable) -->
            <button 
                @click="open = !open"
                class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group"
            >
                <i class="ph ph-warehouse text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">Inventory</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                
                <!-- Stock In -->
                <a href="{{ route('inventory.stock-in') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('inventory.stock-in') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.stock-in') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Stock In</span>
                </a>
                
                <!-- Stock Out -->
                <a href="{{ route('inventory.stock-out') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('inventory.stock-out') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.stock-out') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Stock Out</span>
                </a>
                
                <!-- History -->
                <a href="{{ route('inventory.history') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('inventory.history') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.history') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">History</span>
                </a>
                
            </div>
        </div>
        
        <!-- Reports Dropdown Group -->
        <div x-data="{ open: {{ request()->routeIs('reports.inventory') || request()->routeIs('reports.transactions') ? 'true' : 'false' }} }">
            
            <!-- Section Header (Clickable) -->
            <button 
                @click="open = !open"
                class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group"
            >
                <i class="ph ph-chart-bar text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">Reports</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                
                <!-- Inventory Report -->
                <a href="{{ route('reports.inventory') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('reports.inventory') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.inventory') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Inventory Report</span>
                </a>
                
                <!-- Transactions -->
                <a href="{{ route('reports.transactions') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('reports.transactions') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.transactions') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Transactions</span>
                </a>
                
            </div>
        </div>

        <!-- Ticketing Reports Dropdown Group -->
        <div x-data="{ open: {{ request()->routeIs('reports.index') || request()->routeIs('reports.create') || request()->routeIs('reports.scan') || request()->routeIs('reports.show') ? 'true' : 'false' }} }">

            <!-- Section Header (Clickable) -->
            <button
                @click="open = !open"
                class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group"
            >
                <i class="ph ph-clipboard-text text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">Laporan</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">

                <!-- Scan QR Code -->
                <a href="{{ route('reports.scan') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('reports.scan') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.scan') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Scan QR Code</span>
                </a>

                <!-- Dashboard -->
                <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('reports.index') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.index') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Dashboard</span>
                </a>

                <!-- Create Report -->
                <a href="{{ route('reports.create') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('reports.create') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.create') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Buat Laporan</span>
                </a>

            </div>
        </div>

        <!-- Settings Dropdown Group -->
        <div x-data="{ open: {{ request()->routeIs('profile.edit') || request()->routeIs('settings*') ? 'true' : 'false' }} }">
            
            <!-- Section Header (Clickable) -->
            <button 
                @click="open = !open"
                class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-200 group"
            >
                <i class="ph ph-gear text-lg flex-shrink-0 mr-3"></i>
                <span class="flex-1 text-left truncate">Settings</span>
                <i class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <!-- Children Menu (Collapsible) -->
            <div x-show="open" x-collapse class="mt-1 space-y-1">
                
                <!-- Profile -->
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('profile.edit') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('profile.edit') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">Profile</span>
                </a>
                
                <!-- System Settings -->
                <a href="{{ route('settings.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                    {{ request()->routeIs('settings*') ? 'bg-ebara-500/20 text-ebara-400' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                    <span class="w-6 h-6 flex items-center justify-center mr-3">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('settings*') ? 'bg-ebara-400' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                    </span>
                    <span class="truncate">System Settings</span>
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
            <div class="flex-1 min-w-0 overflow-hidden transition-opacity duration-300" x-show="$parent.sidebarOpen">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
    
</div>
