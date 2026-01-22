<!--
    Mobile Navbar Component
    Fixed header for mobile (< lg) with hamburger toggle and profile dropdown
    Z-Index: 50 (Top layer)
-->
<header class="fixed top-0 left-0 right-0 h-16 bg-white shadow-sm z-50 flex items-center justify-between px-4 lg:hidden">
    <!-- Hamburger Button (Left) -->
    <button @click="$dispatch('toggle-sidebar')"
            class="p-2 rounded-lg hover:bg-gray-100 transition"
            aria-label="Toggle sidebar">
        <i class="ph ph-list text-2xl text-slate-600"></i>
    </button>

    <!-- Logo (Center) -->
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
        <x-brand.ebara-logo class="h-8 w-8 text-ebara-500" />
        <span class="text-lg font-bold text-slate-800">EBARA</span>
    </a>

    <!-- Right Actions -->
    <div class="flex items-center space-x-3">
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
