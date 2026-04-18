<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ebara Inventory') }} - Register</title>
    
    <!-- Fonts: Inter via Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex items-center justify-center p-6" 
      style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23009B77" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/svg%3E');">
    
    <div class="w-full max-w-md">
        
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Ebara Inventory Logo" class="h-16 w-auto">
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
            <p class="mt-2 text-sm text-gray-600">Join Ebara Inventory Management System</p>
        </div>
        
        <!-- Registration Card -->
        <x-ui.card padding="lg">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="space-y-4 mb-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph ph-identification-card text-gray-400"></i>
                            </div>
                            <input id="name" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors {{ $errors->has('name') ? 'border-red-500' : '' }}" type="text" name="name" :value="old('name')" required autofocus placeholder="Full Name">
                        </div>
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">ID Pegawai (6 characters)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph ph-user text-gray-400"></i>
                            </div>
                            <input id="username" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors {{ $errors->has('username') ? 'border-red-500' : '' }}" type="text" name="username" :value="old('username')" maxlength="6" minlength="6" required placeholder="Example: 123456">
                        </div>
                        <p class="mt-1 text-[10px] text-gray-400 font-bold italic">Note: Password default akan sama dengan ID Pegawai Anda.</p>
                        @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <x-ui.button variant="primary" size="default" type="submit" class="w-full py-3">
                        <i class="ph ph-user-plus mr-2"></i>
                        Register Account
                    </x-ui.button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm text-ebara-600 hover:text-ebara-700 font-medium transition-colors">
                            Already have an account? Sign In
                        </a>
                    </div>
                </div>
            </form>
        </x-ui.card>
        
        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} Ebara Indonesia. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
