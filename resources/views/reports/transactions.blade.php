<x-app-layout>
    <x-slot name="header">Transactions</x-slot>
    <x-slot name="description">View all inventory transactions</x-slot>
    
    <x-ui.card>
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4">
                <i class="ph ph-arrows-left-right text-ebara-500 text-3xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Transactions Report</h2>
            <p class="text-gray-500 mt-2">This feature is coming soon.</p>
            <p class="text-sm text-gray-400 mt-4">You will be able to:</p>
            <ul class="text-left text-gray-600 mt-4 space-y-2 max-w-md mx-auto">
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>View all stock movements</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Filter by date range</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Filter by product</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Filter by transaction type</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>Export to Excel/PDF</span>
                </li>
            </ul>
        </div>
    </x-ui.card>
</x-app-layout>
