<?php

use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\AssetModelController;
use App\Http\Controllers\AssetToolController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \Illuminate\Support\Facades\Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Executive Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard API Endpoints
    Route::prefix('api/dashboard')->group(function () {
        Route::get('/data', [DashboardController::class, 'getData'])->name('dashboard.data');
        Route::post('/search', [DashboardController::class, 'search'])->name('dashboard.search');
        Route::post('/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');
        Route::get('/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export-pdf');
        Route::get('/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');
        Route::get('/reports/by-date', [DashboardController::class, 'getReportsByDate'])->name('dashboard.reports-by-date');
        Route::get('/assets/by-type', [DashboardController::class, 'getAssetsByType'])->name('dashboard.assets-by-type');
    });

    // Global Search API Endpoints
    Route::prefix('api')->group(function () {
        Route::post('/global-search', [GlobalSearchController::class, 'search'])->name('api.global-search');
        Route::post('/asset-detail', [GlobalSearchController::class, 'getDetail'])->name('api.asset-detail');
        Route::get('/search-suggestions', [GlobalSearchController::class, 'getSuggestions'])->name('api.search-suggestions');
    });
});

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
    
    // Master Data Routes
    Route::prefix('master-data')->name('master-data.')->middleware(['auth', 'verified'])->group(function () {
        // Gedungs
        Route::get('gedungs', [GedungController::class, 'index'])->name('gedungs.index');
        Route::get('gedungs/create', [GedungController::class, 'create'])->name('gedungs.create');
        Route::get('gedungs/data', [GedungController::class, 'getData'])->name('gedungs.data');
        Route::get('gedungs/{id}', [GedungController::class, 'show'])->name('gedungs.show');
        Route::get('gedungs/{id}/edit', [GedungController::class, 'edit'])->name('gedungs.edit');
        Route::post('gedungs', [GedungController::class, 'store'])->name('gedungs.store');
        Route::put('gedungs/{id}', [GedungController::class, 'update'])->name('gedungs.update');
        Route::delete('gedungs/{id}', [GedungController::class, 'destroy'])->name('gedungs.destroy');
        
        // Satuans
        Route::get('satuans', [SatuanController::class, 'index'])->name('satuans.index');
        Route::get('satuans/create', [SatuanController::class, 'create'])->name('satuans.create');
        Route::get('satuans/data', [SatuanController::class, 'getData'])->name('satuans.data');
        Route::get('satuans/{id}', [SatuanController::class, 'show'])->name('satuans.show');
        Route::get('satuans/{id}/edit', [SatuanController::class, 'edit'])->name('satuans.edit');
        Route::post('satuans', [SatuanController::class, 'store'])->name('satuans.store');
        Route::put('satuans/{id}', [SatuanController::class, 'update'])->name('satuans.update');
        Route::delete('satuans/{id}', [SatuanController::class, 'destroy'])->name('satuans.destroy');
    });
    
    // Role Management Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        // Custom routes must come before parameterized routes
        Route::get('/export', [RoleController::class, 'export'])->name('export');
        Route::get('/export-pdf', [RoleController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        // Custom routes must come before parameterized routes
        Route::get('/permissions/by-module', [RoleController::class, 'getPermissionsByModule'])->name('permissions.by-module');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/export-pdf', [UserController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'getData'])->name('data');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
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

    // Inventory Routes (Placeholder - Controller to be implemented)
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/stock-in', function() { return view('inventory.stock-in'); })->name('stock-in');
        Route::get('/stock-out', function() { return view('inventory.stock-out'); })->name('stock-out');
        Route::get('/history', function() { return view('inventory.history'); })->name('history');
    });

    // Settings Routes (Placeholder - Controller to be implemented)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function() { return view('settings.index'); })->name('index');
    });
});

require __DIR__.'/auth.php';
