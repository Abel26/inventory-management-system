<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h2 class="font-bold text-2xl text-gray-800">Account Settings</h2>
                <p class="text-gray-500">Manage your profile information and security.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column: Profile Overview Card -->
                <div class="lg:col-span-4">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-8 text-center">
                        <div class="h-24 w-24 bg-ebara-100 text-ebara-700 rounded-full mx-auto flex items-center justify-center text-3xl font-bold mb-4">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-500 text-sm mb-4">{{ Auth::user()->email }}</p>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active User
                        </div>
                    </div>
                </div>

                <!-- Right Column: Action Forms -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Profile Information Card -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- Update Password Card -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        @include('profile.partials.update-password-form')
                    </div>

                    <!-- Danger Zone: Delete Account Card -->
                    <div class="bg-red-50/50 p-8 rounded-2xl border border-red-100">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
