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
                    <!-- Notifications -->
                    <x-user.notification-bell :count="$pendingReportCount ?? 0" :notifications="$latestNotifications ?? null" />

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition"
                                aria-label="User menu">
                            <div class="w-8 h-8 rounded-full bg-ebara-600 flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ auth()->user()->name ? substr(auth()->user()->name, 0, 1) : 'U' }}</span>
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
</body>
</html>
