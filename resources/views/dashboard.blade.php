<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>
    
    <x-slot name="description">
        Welcome back! Here's an overview of your inventory.
    </x-slot>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.card padding="lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">1,234</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="ph ph-trend-up"></i> +12% from last month
                    </p>
                </div>
                <div class="p-3 bg-ebara-100 rounded-lg">
                    <i class="ph ph-package text-ebara-600 text-xl"></i>
                </div>
            </div>
        </x-ui.card>
        
        <x-ui.card padding="lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Stock In Today</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">45</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="ph ph-trend-up"></i> +5% from yesterday
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="ph ph-arrow-down-left text-green-600 text-xl"></i>
                </div>
            </div>
        </x-ui.card>
        
        <x-ui.card padding="lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Stock Out Today</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">28</p>
                    <p class="text-xs text-red-600 mt-2">
                        <i class="ph ph-trend-down"></i> -3% from yesterday
                    </p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="ph ph-arrow-up-right text-red-600 text-xl"></i>
                </div>
            </div>
        </x-ui.card>
        
        <x-ui.card padding="lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Low Stock Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">12</p>
                    <p class="text-xs text-yellow-600 mt-2">
                        <i class="ph ph-warning"></i> Needs attention
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="ph ph-warning-circle text-yellow-600 text-xl"></i>
                </div>
            </div>
        </x-ui.card>
    </div>
    
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Stock Movements -->
        <x-ui.card class="lg:col-span-2">
            <x-slot name="title">Recent Stock Movements</x-slot>
            <x-slot name="description">Latest inventory transactions</x-slot>
            
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Industrial Pump X200</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-ui.badge variant="success">Stock In</x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+50</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 12, 2026</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Water Pump 1500</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-ui.badge variant="danger">Stock Out</x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-25</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 12, 2026</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Centrifugal Pump</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-ui.badge variant="success">Stock In</x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">+100</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 11, 2026</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Submersible Pump</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-ui.badge variant="danger">Stock Out</x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-10</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jan 11, 2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-ui.card>
        
        <!-- Quick Actions -->
        <x-ui.card>
            <x-slot name="title">Quick Actions</x-slot>
            <x-slot name="description">Common tasks</x-slot>
            
            <div class="space-y-3">
                <a href="#" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-ebara-500 hover:bg-ebara-50 transition-colors group">
                    <div class="p-2 bg-ebara-100 rounded-lg group-hover:bg-ebara-200 transition-colors">
                        <i class="ph ph-plus text-ebara-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Add New Product</p>
                        <p class="text-xs text-gray-500">Create a new product entry</p>
                    </div>
                </a>
                
                <a href="#" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-green-500 hover:bg-green-50 transition-colors group">
                    <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                        <i class="ph ph-arrow-down-left text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Stock In</p>
                        <p class="text-xs text-gray-500">Record incoming inventory</p>
                    </div>
                </a>
                
                <a href="#" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-red-500 hover:bg-red-50 transition-colors group">
                    <div class="p-2 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                        <i class="ph ph-arrow-up-right text-red-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Stock Out</p>
                        <p class="text-xs text-gray-500">Record outgoing inventory</p>
                    </div>
                </a>
                
                <a href="#" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition-colors group">
                    <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                        <i class="ph ph-chart-bar text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">View Reports</p>
                        <p class="text-xs text-gray-500">Generate inventory reports</p>
                    </div>
                </a>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
