@props(['count' => 0])

<button class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-ebara-500">
    <i class="ph ph-bell text-xl"></i>
    
    @if($count > 0)
        <span class="absolute top-1 right-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] font-medium text-white items-center justify-center">
                {{ $count > 9 ? '9+' : $count }}
            </span>
        </span>
    @endif
</button>
