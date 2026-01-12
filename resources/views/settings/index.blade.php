<x-app-layout>
    <x-slot name="header">Settings</x-slot>
    <x-slot name="description">Configure system preferences</x-slot>
    
    <x-ui.card>
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4">
                <i class="ph ph-gear text-ebara-500 text-3xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">System Settings</h2>
            <p class="text-gray-500 mt-2">This feature is coming soon.</p>
            <p class="text-sm text-gray-400 mt-4">You will be able to configure:</p>
            <ul class="text-left text-gray-600 mt-4 space-y-2 max-w-md mx-auto">
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>General settings (app name, timezone, etc.)</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Notification preferences</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Security settings</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Backup and restore options</span>
                </li>
            </ul>
        </div>
    </x-ui.card>
</x-app-layout>
