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

    <!-- Vendor Libraries (synchronous, from npm packages in public/vendor/) -->
    <!-- Must load BEFORE Vite bundle because Vite modules are deferred and inline scripts depend on these globals -->
    <script src="{{ asset('vendor/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('vendor/jquery.dataTables.min.css') }}">
    <script src="{{ asset('vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/html5-qrcode.min.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-700 bg-slate-50 h-full overflow-hidden"
      x-data="{ sidebarOpen: true }"
      @toggle-sidebar.window="sidebarOpen = !sidebarOpen">

    <!-- ==================== LAYER Z-50: MOBILE HEADER ==================== -->
    <!-- Fixed header for mobile (< lg) with hamburger toggle and profile dropdown -->
    <x-layout.mobile-navbar />

    <!-- ==================== LAYER Z-40: BACKDROP OVERLAY (Mobile Only) ==================== -->
    <!-- Dark overlay that appears only on mobile when sidebar is open -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <!-- ==================== MAIN CONTAINER ==================== -->
    <div class="flex h-screen lg:pt-0 pt-16">
        <!-- ==================== LAYER Z-50: SIDEBAR ==================== -->
        <!-- Off-canvas on mobile, fixed on desktop -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:w-64 lg:shadow-none"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <x-layout.sidebar />
        </aside>

        <!-- ==================== LAYER Z-0: MAIN CONTENT ==================== -->
        <main class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">
            <!-- Desktop Header (Visible >= lg) -->
            <header class="hidden lg:flex h-16 bg-white shadow-sm items-center justify-between px-6">
                <!-- Sidebar Toggle Button -->
                <button @click="$dispatch('toggle-sidebar')"
                        class="p-2 rounded-lg hover:bg-gray-100 transition"
                        aria-label="Toggle sidebar">
                    <i class="ph ph-list text-2xl text-slate-600"></i>
                </button>

                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            <i class="ph ph-translate mr-2"></i>
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                            <svg class="ml-1 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            <div class="py-1">
                                <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} block px-4 py-2 text-sm">
                                    🇮🇩 Bahasa Indonesia
                                </a>
                                <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} block px-4 py-2 text-sm">
                                    🇬🇧 English
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notifications -->
                    <x-user.notification-bell :count="$pendingReportCount ?? 0" :notifications="$latestNotifications ?? null" />

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition"
                                aria-label="User menu">
                            <div class="w-8 h-8 rounded-full bg-ebara-600 flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ \Illuminate\Support\Facades\Auth::user()?->name ? substr(\Illuminate\Support\Facades\Auth::user()->name, 0, 1) : 'U' }}</span>
                            </div>
                            <i class="ph ph-caret-down text-slate-600"></i>
                        </button>

                        <!-- Dropdown Menu -->
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
                               {{ __('menu.profile') }}
                           </a>
                           <hr class="my-1 border-gray-200">
                           <form method="POST" action="{{ route('logout') }}">
                               @csrf
                               <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                   {{ __('menu.logout') }}
                               </button>
                           </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-4 lg:p-6">
                {{ $slot ?? $content ?? '' }}
            </div>

            <!-- Footer -->
            <x-layout.footer />
        </main>
    </div>

    <!-- Scripts Stack for Child Views -->
    @stack('scripts')
    
    <!-- Global SweetAlert aria-hidden fix for production -->
    <x-global-sweetalert-fix />
</body>
</html>
