<x-app-layout>
    <x-slot name="header">Stock Out</x-slot>
    <x-slot name="description">Record outgoing inventory</x-slot>
    
    <x-ui.card>
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4">
                <i class="ph ph-arrow-up-right text-ebara-500 text-3xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Stock Out</h2>
            <p class="text-gray-500 mt-2">This feature is coming soon.</p>
            <p class="text-sm text-gray-400 mt-4">You will be able to:</p>
            <ul class="text-left text-gray-600 mt-4 space-y-2 max-w-md mx-auto">
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Record stock disbursements</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Scan QR codes for quick exit</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Track outgoing inventory by batch</span>
                </li>
            </ul>
        </div>
    </x-ui.card>
</x-app-layout>
