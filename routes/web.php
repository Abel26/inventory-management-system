<?php

use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\AssetModelController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    // Inventory Routes
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('stock-in', [InventoryController::class, 'stockIn'])->name('stock-in');
        Route::get('stock-out', [InventoryController::class, 'stockOut'])->name('stock-out');
        Route::get('history', [InventoryController::class, 'history'])->name('history');
    });
    
    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('inventory', [ReportsController::class, 'inventory'])->name('inventory');
        Route::get('transactions', [ReportsController::class, 'transactions'])->name('transactions');
    });
    
    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    
    // Asset Management Routes
    Route::prefix('assets')->name('assets.')->group(function () {
        // Materials
        Route::get('materials', [AssetManagementController::class, 'index'])->name('materials.index');
        Route::get('materials/data', [AssetManagementController::class, 'getData'])->name('materials.data');
        Route::get('materials/{id}', [AssetManagementController::class, 'show'])->name('materials.show');
        Route::post('materials', [AssetManagementController::class, 'store'])->name('materials.store');
        Route::put('materials/{id}', [AssetManagementController::class, 'update'])->name('materials.update');
        Route::delete('materials/{id}', [AssetManagementController::class, 'destroy'])->name('materials.destroy');
        Route::get('materials/{id}/qr-code', [AssetManagementController::class, 'qrCode'])->name('materials.qr-code');
        Route::get('materials/export', [AssetManagementController::class, 'export'])->name('materials.export');
        
        // Tools
        Route::get('tools', [AssetManagementController::class, 'tools'])->name('tools.index');
        
        // Models
        Route::get('models', [AssetModelController::class, 'index'])->name('models.index');
        Route::get('models/data', [AssetModelController::class, 'getData'])->name('models.data');
        Route::get('models/{id}', [AssetModelController::class, 'show'])->name('models.show');
        Route::post('models', [AssetModelController::class, 'store'])->name('models.store');
        Route::put('models/{id}', [AssetModelController::class, 'update'])->name('models.update');
        Route::delete('models/{id}', [AssetModelController::class, 'destroy'])->name('models.destroy');
        Route::get('models/{id}/qr-code', [AssetModelController::class, 'qrCode'])->name('models.qr-code');
        Route::get('models/export', [AssetModelController::class, 'export'])->name('models.export');
    });
});

require __DIR__.'/auth.php';
