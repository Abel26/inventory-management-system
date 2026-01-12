<x-dropdown align="right" width="56">
    <x-slot name="trigger">
        <button class="flex items-center space-x-3 p-1 rounded-full hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-ebara-500">
            <x-user.user-avatar :user="Auth::user()" class="h-9 w-9" />
            <div class="hidden md:block text-left">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->role ?? 'User' }}</p>
            </div>
            <i class="ph ph-caret-down text-gray-400 hidden md:block"></i>
        </button>
    </x-slot>
    
    <x-slot name="content">
        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->email }}</p>
        </div>
        
        <div class="py-1">
            <x-dropdown-link :href="route('profile.edit')">
                <i class="ph ph-user mr-2"></i> Profile
            </x-dropdown-link>
            <x-dropdown-link :href="route('settings.index')">
                <i class="ph ph-gear mr-2"></i> Settings
            </x-dropdown-link>
        </div>
        
        <div class="border-t border-gray-100 py-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link 
                    :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="text-red-600 hover:bg-red-50"
                >
                    <i class="ph ph-sign-out mr-2"></i> Log Out
                </x-dropdown-link>
            </form>
        </div>
    </x-slot>
</x-dropdown>
