<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ebara Inventory') }} - {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Fonts: Inter via Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons: Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables CSS for Tailwind -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.tailwindcss.css">
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-primary bg-bg-primary h-full">
    <div class="flex h-screen" x-data="{ sidebarOpen: {{ session('sidebar_open', true) ? 'true' : 'false' }}, mobileSidebarOpen: false }">
        
        <!-- ==================== SIDEBAR ==================== -->
        <aside 
            x-transition:enter="transition-transform duration-300 ease-in-out"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform duration-300 ease-in-out"
            x-transition:leave-start="-translate-x-0"
            x-transition:leave-end="-translate-x-0"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white shadow-sidebar lg:static lg:inset-auto lg:transition-all lg:duration-300 lg:ease-in-out"
            :class="sidebarOpen ? 'lg:w-72' : 'lg:w-20'"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-6 py-2 text-white hover:bg-slate-800 transition">
                    <svg class="w-8 h-8 text-ebara-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span x-show="sidebarOpen" class="text-xl font-bold">EBARA</span>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6 px-3">
                <ul class="space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <i class="ph ph-squares-four text-xl"></i>
                            <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Assets Dropdown Group -->
                    <li x-data="{ open: {{ request()->routeIs('assets.materials*') || request()->routeIs('assets.tools*') || request()->routeIs('assets.models*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="w-full flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('assets.*') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <i class="ph ph-cube text-xl"></i>
                            <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Assets</span>
                            <i x-show="sidebarOpen" class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <!-- Children Menu (Collapsible) -->
                        <ul x-show="open" x-collapse x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="mt-1 space-y-1 pl-4">
                            
                            <!-- Materials -->
                            <li>
                                <a href="{{ route('assets.materials.index') }}"
                                   class="flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('assets.materials*') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                                    <i class="ph ph-package text-lg"></i>
                                    <span x-show="sidebarOpen" class="ml-3">Materials</span>
                                </a>
                            </li>
                            
                            <!-- Tools -->
                            <li>
                                <a href="{{ route('assets.tools.index') }}"
                                   class="flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('assets.tools*') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                                    <i class="ph ph-wrench text-lg"></i>
                                    <span x-show="sidebarOpen" class="ml-3">Tools</span>
                                </a>
                            </li>
                            
                            <!-- Models -->
                            <li>
                                <a href="{{ route('assets.models.index') }}"
                                   class="flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('assets.models*') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                                    <i class="ph ph-cube text-lg"></i>
                                    <span x-show="sidebarOpen" class="ml-3">Models</span>
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                    
                    <!-- Role Management -->
                    @can('view roles')
                    <li>
                        <a href="{{ route('roles.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('roles.*') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <i class="ph ph-shield text-xl"></i>
                            <span x-show="sidebarOpen" class="ml-3">Manajemen Role</span>
                        </a>
                    </li>
                    @endcan

                    <!-- Reports Dropdown Group -->
                    <li x-data="{ open: {{ request()->routeIs('reports.index') || request()->routeIs('reports.scan') || request()->routeIs('reports.show') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="w-full flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                            <i class="ph ph-clipboard-text text-xl"></i>
                            <span x-show="sidebarOpen" class="ml-3 flex-1 text-left">Laporan Masalah</span>
                            <i x-show="sidebarOpen" class="ph ph-caret-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Children Menu (Collapsible) -->
                        <ul x-show="open" x-collapse x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="mt-1 space-y-1 pl-4">

                            <!-- Daftar Laporan -->
                            <li>
                                <a href="{{ route('reports.index') }}"
                                       class="flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('reports.index') || request()->routeIs('reports.show') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                                    <i class="ph ph-list-dashes text-lg"></i>
                                    <span x-show="sidebarOpen" class="ml-3">Daftar Laporan</span>
                                </a>
                            </li>

                            <!-- Scan QR Code -->
                            <li>
                                <a href="{{ route('reports.scan') }}"
                                       class="flex items-center px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('reports.scan') ? 'bg-ebara-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                                    <i class="ph ph-qr-code text-lg"></i>
                                    <span x-show="sidebarOpen" class="ml-3">Scan QR Code</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </nav>
            
            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-ebara-600 flex items-center justify-center">
                        <span class="text-white font-bold">{{ auth()->user()->name ? substr(auth()->user()->name, 0, 1) : 'U' }}</span>
                    </div>
                    <div x-show="sidebarOpen" class="ml-3">
                        <p class="text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-400">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileSidebarOpen" 
             x-transition:enter="transition-opacity duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden"
             @click="mobileSidebarOpen = false">
        </div>
        
        <!-- ==================== MAIN CONTENT ==================== -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0">
            
            <!-- ==================== NAVBAR ==================== -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 lg:px-6">
                <!-- Mobile Menu Toggle -->
                <button @click="mobileSidebarOpen = true" 
                        class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="ph ph-list text-2xl text-slate-600"></i>
                </button>
                
                <!-- Desktop Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="hidden lg:block p-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="ph ph-list text-2xl text-slate-600"></i>
                </button>
                
                <!-- Search -->
                <div class="flex-1 max-w-xl mx-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Cari..." 
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-ebara-500 focus:border-transparent">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                
                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <a href="{{ route('reports.index') }}" class="relative p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="ph ph-bell text-xl text-slate-600"></i>
                        @if(isset($pendingReportCount) && $pendingReportCount > 0)
                            <span class="absolute top-1 right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] font-medium text-white items-center justify-center">
                                    {{ $pendingReportCount > 9 ? '9+' : $pendingReportCount }}
                                </span>
                            </span>
                        @endif
                    </a>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-ebara-600 flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ auth()->user()->name ? substr(auth()->user()->name, 0, 1) : 'U' }}</span>
                            </div>
                            <i class="ph ph-caret-down text-slate-600"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-gray-100">
                                Profil
                            </a>
                            <hr class="my-1 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- ==================== CONTENT AREA ==================== -->
            <main class="flex-1 overflow-y-auto bg-bg-primary p-4 lg:p-6">
                {{ $slot ?? $content ?? '' }}
            </main>
            
            <!-- ==================== FOOTER ==================== -->
            <footer class="bg-white border-t border-gray-200 py-3 px-4 lg:px-6 flex-shrink-0">
                <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-slate-500">
                    <p>&copy; {{ date('Y') }} Ebara Indonesia. All rights reserved.</p>
                    <p class="mt-2 sm:mt-0">Inventory Management System v1.0</p>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Scripts Stack for Child Views -->
    @stack('scripts')
</body>
</html>

