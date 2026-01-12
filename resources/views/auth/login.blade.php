<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ebara Inventory') }} - Login</title>
    
    <!-- Fonts: Inter via Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons: Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex items-center justify-center" 
      style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23009B77" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/svg%3E');">
    
    <div class="w-full max-w-md px-4 sm:px-6">
        
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <x-brand.ebara-logo class="h-16 w-16 text-ebara-500" />
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Ebara Inventory</h1>
            <p class="mt-2 text-sm text-gray-600">Sign in to your account</p>
        </div>
        
        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="ph ph-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Login Card -->
        <x-ui.card padding="lg">
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                
                <!-- Username Field -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-user text-gray-400"></i>
                        </div>
                        <input 
                            id="username" 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors {{ $errors->has('username') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                            type="text" 
                            name="username" 
                            :value="old('username')" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="Enter your username"
                        >
                    </div>
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="mb-4" x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-lock-key text-gray-400"></i>
                        </div>
                        <input 
                            id="password" 
                            class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg text-sm leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                            :type="show ? 'text' : 'password'"
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >
                        <!-- Show/Hide Password Toggle -->
                        <button 
                            type="button" 
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                        >
                            <i class="ph" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label for="remember" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input 
                                id="remember" 
                                type="checkbox" 
                                class="sr-only peer" 
                                name="remember"
                            >
                            <div class="w-5 h-5 border-2 border-gray-300 rounded peer-checked:bg-ebara-500 peer-checked:border-ebara-500 transition-colors flex items-center justify-center">
                                <i class="ph ph-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    
                    <!-- Forgot Password Link -->
                    @if (Route::has('password.request'))
                        <a 
                            class="text-sm text-ebara-600 hover:text-ebara-700 font-medium transition-colors" 
                            href="{{ route('password.request') }}"
                        >
                            Forgot password?
                        </a>
                    @endif
                </div>
                
                <!-- Login Button -->
                <x-ui.button variant="primary" size="default" type="submit" class="w-full">
                    <i class="ph ph-sign-in mr-2"></i>
                    Sign In
                </x-ui.button>
            </form>
        </x-ui.card>
        
        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} Ebara Indonesia. All rights reserved.
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Inventory Management System v{{ config('app.version', '1.0.0') }}
            </p>
        </div>
        
    </div>
    
</body>
</html>
