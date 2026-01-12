<?php

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
});

require __DIR__.'/auth.php';
