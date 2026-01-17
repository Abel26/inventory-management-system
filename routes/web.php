<?php

use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\AssetModelController;
use App\Http\Controllers\AssetToolController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Asset Management Routes
    Route::prefix('assets')->name('assets.')->group(function () {
        // Materials
        Route::get('materials', [AssetManagementController::class, 'index'])->name('materials.index');
        Route::get('materials/data', [AssetManagementController::class, 'getData'])->name('materials.data');
        Route::get('materials/export', [AssetManagementController::class, 'export'])->name('materials.export');
        Route::get('materials/export-pdf', [AssetManagementController::class, 'exportPdf'])->name('materials.export-pdf');
        Route::get('materials/{id}', [AssetManagementController::class, 'show'])->name('materials.show');
        Route::post('materials', [AssetManagementController::class, 'store'])->name('materials.store');
        Route::put('materials/{id}', [AssetManagementController::class, 'update'])->name('materials.update');
        Route::delete('materials/{id}', [AssetManagementController::class, 'destroy'])->name('materials.destroy');
        Route::get('materials/{id}/qr-code', [AssetManagementController::class, 'qrCode'])->name('materials.qr-code');
        
        // Tools
        Route::get('tools', [AssetToolController::class, 'index'])->name('tools.index');
        Route::get('tools/data', [AssetToolController::class, 'getData'])->name('tools.data');
        Route::get('tools/export', [AssetToolController::class, 'export'])->name('tools.export');
        Route::get('tools/export-pdf', [AssetToolController::class, 'exportPdf'])->name('tools.export-pdf');
        Route::get('tools/{id}', [AssetToolController::class, 'show'])->name('tools.show');
        Route::post('tools', [AssetToolController::class, 'store'])->name('tools.store');
        Route::put('tools/{id}', [AssetToolController::class, 'update'])->name('tools.update');
        Route::delete('tools/{id}', [AssetToolController::class, 'destroy'])->name('tools.destroy');
        Route::get('tools/{id}/qr-code', [AssetToolController::class, 'qrCode'])->name('tools.qr-code');
        
        // Models
        Route::get('models', [AssetModelController::class, 'index'])->name('models.index');
        Route::get('models/data', [AssetModelController::class, 'getData'])->name('models.data');
        Route::get('models/export', [AssetModelController::class, 'export'])->name('models.export');
        Route::get('models/export-pdf', [AssetModelController::class, 'exportPdf'])->name('models.export-pdf');
        Route::get('models/{id}', [AssetModelController::class, 'show'])->name('models.show');
        Route::post('models', [AssetModelController::class, 'store'])->name('models.store');
        Route::put('models/{id}', [AssetModelController::class, 'update'])->name('models.update');
        Route::delete('models/{id}', [AssetModelController::class, 'destroy'])->name('models.destroy');
        Route::get('models/{id}/qr-code', [AssetModelController::class, 'qrCode'])->name('models.qr-code');
    });
    
    // Role Management Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        // Custom routes must come before parameterized routes
        Route::get('/export', [RoleController::class, 'export'])->name('export');
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        // Custom routes must come before parameterized routes
        Route::get('/permissions/by-module', [RoleController::class, 'getPermissionsByModule'])->name('permissions.by-module');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // Report Management Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        // Ticketing Reports (ReportController)
        // Custom routes must come before parameterized routes
        Route::get('/scan', [ReportController::class, 'scan'])->name('scan');
        Route::get('/by-status/{status}', [ReportController::class, 'getByStatus'])->name('by-status');

        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::get('/{id}', [ReportController::class, 'show'])->name('show');
        Route::put('/{id}/status', [ReportController::class, 'updateStatus'])->name('update-status');

        // Inventory & Transaction Reports (ReportsController)
        Route::get('/inventory', [ReportsController::class, 'inventory'])->name('inventory');
        Route::get('/transactions', [ReportsController::class, 'transactions'])->name('transactions');
    });
});

require __DIR__.'/auth.php';
