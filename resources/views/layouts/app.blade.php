<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ebara Inventory') }} - {{ $page_title ?? 'Dashboard' }}</title>
    
    <!-- Fonts: Inter via Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons: Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-text-primary bg-bg-primary h-full overflow-hidden">
    
    <!-- Main Container: Full viewport height, flex layout -->
    <div class="flex h-screen w-full bg-bg-primary" x-data="{ 
        sidebarOpen: {{ session('sidebar_open', true) ? 'true' : 'false' }},
        mobileSidebarOpen: false 
    }">
        
        <!-- ==================== SIDEBAR ==================== -->
        <aside 
            x-transition:enter="transition-transform duration-300 ease-in-out"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform duration-300 ease-in-out"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white shadow-sidebar
                   lg:static lg:inset-auto lg:translate-x-0
                   lg:transition-all lg:duration-300 lg:ease-in-out
                   :class="sidebarOpen ? 'lg:w-72' : 'lg:w-20'"
                   :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <x-layout.sidebar />
        </aside>
        
        <!-- Mobile Sidebar Overlay -->
        <div 
            x-show="mobileSidebarOpen"
            x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
            style="display: none;"
        ></div>
        
        <!-- ==================== MAIN CONTENT WRAPPER ==================== -->
        <div class="flex flex-1 flex-col h-screen overflow-hidden">
            
            <!-- ==================== NAVBAR ==================== -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
                <x-layout.navbar />
            </header>
            
            <!-- ==================== PAGE CONTENT ==================== -->
            <main class="flex-1 overflow-y-auto bg-bg-primary p-4 sm:p-6 lg:p-8">
                
                <!-- Breadcrumb Navigation (Optional) -->
                @if(isset($breadcrumb))
                    <x-navigation.breadcrumb :items="$breadcrumb" />
                @endif
                
                <!-- Page Heading -->
                @isset($header)
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-text-primary">{{ $header }}</h1>
                        @isset($description)
                            <p class="mt-1 text-sm text-text-secondary">{{ $description }}</p>
                        @endisset
                    </div>
                @endisset
                
                <!-- Page Content Slot -->
                <div class="animate-fade-in">
                    {{ $slot }}
                </div>
                
            </main>
            
            <!-- ==================== FOOTER ==================== -->
            <footer class="bg-white border-t border-gray-200 py-4">
                <x-layout.footer />
            </footer>
            
        </div>
        
    </div>
    
    <!-- Flash Messages Container -->
    <div id="flash-messages" class="fixed top-20 right-4 z-50 space-y-2">
        @foreach(['success', 'error', 'warning', 'info'] as $type)
            @if(session()->has($type))
                <x-ui.alert :type="$type" :message="session()->get($type)" :dismissible="true" />
            @endif
        @endforeach
    </div>
    
</body>
</html>
