<x-app-layout>
    <x-slot name="header">{{ __('modules.reports.inventory_title') }}</x-slot>
    <x-slot name="description">{{ __('modules.reports.inventory_subtitle') }}</x-slot>
    
    <x-ui.card>
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4">
                <i class="ph ph-chart-bar text-ebara-500 text-3xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">{{ __('modules.reports.inventory_title') }}</h2>
            <p class="text-gray-500 mt-2">{{ __('modules.reports.coming_soon') }}</p>
            <p class="text-sm text-gray-400 mt-4">{{ __('modules.reports.coming_soon_desc') }}</p>
            <ul class="text-left text-gray-600 mt-4 space-y-2 max-w-md mx-auto">
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>{{ __('modules.reports.feature_stock_levels') }}</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>{{ __('modules.reports.feature_stock_movements') }}</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>{{ __('modules.reports.feature_valuation') }}</span>
                </li>
                <li class="flex items-center">
                    <i class="ph ph-check text-ebara-500 mr-2"></i>
                    <span>{{ __('modules.reports.feature_export') }}</span>
                </li>
            </ul>
        </div>
    </x-ui.card>
</x-app-layout>
