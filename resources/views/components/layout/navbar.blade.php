<div class="flex items-center justify-between h-16 px-4 sm:px-6">
    
    <!-- Left Section: Hamburger + Breadcrumb -->
    <div class="flex items-center space-x-4">
        
        <!-- Sidebar Toggle Button (Desktop) -->
        <button 
            @click="$dispatch('toggle-sidebar')"
            class="hidden lg:flex p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
        >
            <i class="ph ph-list text-xl"></i>
        </button>
        
        <!-- Mobile Menu Button -->
        <button 
            @click="$dispatch('toggle-mobile-sidebar')"
            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
        >
            <i class="ph ph-list text-xl"></i>
        </button>
        
        <!-- Mobile Logo -->
        <div class="lg:hidden flex items-center space-x-2">
            <x-brand.ebara-logo class="h-8 w-8 text-ebara-500" />
            <span class="text-lg font-semibold text-gray-900">Ebara IMS</span>
        </div>
        
        <!-- Breadcrumb (Desktop) -->
        <nav class="hidden lg:flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-ebara-500 transition-colors">
                        <i class="ph ph-house"></i>
                    </a>
                </li>
                @if(request()->route()->getName() !== 'dashboard')
                    <li class="text-gray-300">
                        <i class="ph ph-caret-right"></i>
                    </li>
                    <li class="text-gray-700 font-medium">
                        {{ Str::title(str_replace('.', ' ', request()->route()->getName())) }}
                    </li>
                @endif
            </ol>
        </nav>
    </div>
    
    <!-- Center Section: Search (Desktop) -->
    <div class="hidden md:flex flex-1 max-w-lg mx-8">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="ph ph-magnifying-glass text-gray-400"></i>
            </div>
            <input 
                type="text" 
                placeholder="Search products, inventory..." 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 sm:text-sm transition-colors"
            >
        </div>
    </div>
    
    <!-- Right Section: Notifications + User Dropdown -->
    <div class="flex items-center space-x-3">
        
        <!-- Notifications -->
        <x-user.notification-bell :count="3" />
        
        <!-- User Profile Dropdown -->
        <x-user.user-dropdown />
        
    </div>
    
</div>
