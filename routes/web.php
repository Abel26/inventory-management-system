<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\AssetModelController;
use App\Http\Controllers\AssetToolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\MoldModificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ErrorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// =============================================================================
// LANGUAGE SWITCHING ROUTE (Public Access)
// =============================================================================
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// =============================================================================
// PUBLIC ROUTES (Accessible without authentication)
// =============================================================================

// Landing Page - Root route
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Public API Routes
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/public-search', [LandingController::class, 'search'])->name('public.search');
});


// =============================================================================
// GUEST ROUTES (Accessible only to unauthenticated users)
// =============================================================================

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

// =============================================================================
// AUTHENTICATED ROUTES (Require authentication and email verification)
// =============================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    // -------------------------------------------------------------------------
    // DASHBOARD ROUTES
    // -------------------------------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Dashboard Export Routes
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');
    
    // Dashboard API Endpoints
    Route::prefix('api/dashboard')->group(function () {
        Route::get('/data', [DashboardController::class, 'getData'])->name('dashboard.data');
        Route::post('/search', [DashboardController::class, 'search'])->name('dashboard.search');
        Route::post('/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');
        Route::get('/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export-pdf');
        Route::get('/export/excel', [DashboardController::class, 'exportExcel'])->name('api.dashboard.export-excel');
        Route::get('/reports/by-date', [DashboardController::class, 'getReportsByDate'])->name('dashboard.reports-by-date');
        Route::get('/assets/by-type', [DashboardController::class, 'getAssetsByType'])->name('dashboard.assets-by-type');
    });

    // Global Search API Endpoints
    Route::prefix('api')->group(function () {
        Route::post('/global-search', [GlobalSearchController::class, 'search'])->name('api.global-search');
        Route::post('/asset-detail', [GlobalSearchController::class, 'getAssetDetail'])->name('api.asset-detail');
        Route::get('/search-suggestions', [GlobalSearchController::class, 'getSuggestions'])->name('api.search-suggestions');
    });
    
    // -------------------------------------------------------------------------
    // PROFILE & SETTINGS ROUTES
    // -------------------------------------------------------------------------
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    
    // -------------------------------------------------------------------------
    // REPORTS ROUTES
    // -------------------------------------------------------------------------
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::get('/scan', [ReportController::class, 'scan'])->name('scan');
        Route::get('/{id}', [ReportController::class, 'show'])->name('show');
        Route::put('/{id}/status', [ReportController::class, 'updateStatus'])->name('update-status');
        Route::get('/by-status/{status}', [ReportController::class, 'getByStatus'])->name('by-status');

        // Inventory & Transaction Reports (ReportsController)
        Route::get('/inventory', [ReportsController::class, 'inventory'])->name('inventory');
        Route::get('/transactions', [ReportsController::class, 'transactions'])->name('transactions');
    });
    
    // -------------------------------------------------------------------------
    // ASSET MANAGEMENT ROUTES
    // -------------------------------------------------------------------------
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
        Route::get('models/{id}/history', [AssetModelController::class, 'history'])->name('models.history');
    });
    
    // -------------------------------------------------------------------------
    // MASTER DATA ROUTES
    // -------------------------------------------------------------------------
    Route::prefix('master-data')->name('master-data.')->group(function () {
        // Gedungs
        Route::prefix('gedungs')->name('gedungs.')->group(function () {
            Route::get('/', [GedungController::class, 'index'])->name('index');
            Route::get('/create', [GedungController::class, 'create'])->name('create');
            Route::post('/', [GedungController::class, 'store'])->name('store');
            Route::get('/data', [GedungController::class, 'getData'])->name('data');
            Route::get('/{id}', [GedungController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [GedungController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GedungController::class, 'update'])->name('update');
            Route::delete('/{id}', [GedungController::class, 'destroy'])->name('destroy');
        });
        
        // Satuans
        Route::prefix('satuans')->name('satuans.')->group(function () {
            Route::get('/', [SatuanController::class, 'index'])->name('index');
            Route::get('/create', [SatuanController::class, 'create'])->name('create');
            Route::post('/', [SatuanController::class, 'store'])->name('store');
            Route::get('/data', [SatuanController::class, 'getData'])->name('data');
            Route::get('/{id}', [SatuanController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SatuanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SatuanController::class, 'update'])->name('update');
            Route::delete('/{id}', [SatuanController::class, 'destroy'])->name('destroy');
        });
    });
    
    // -------------------------------------------------------------------------
    // USER MANAGEMENT ROUTES
    // -------------------------------------------------------------------------
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/data', [UserController::class, 'getData'])->name('data');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/export-pdf', [UserController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // -------------------------------------------------------------------------
    // ROLE MANAGEMENT ROUTES
    // -------------------------------------------------------------------------
    Route::prefix('roles')->name('roles.')->group(function () {
        // Custom routes must come before parameterized routes
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/data', [RoleController::class, 'getData'])->name('data');
        Route::get('/debug-endpoint', function() {
            try {
                echo "<h1>🔍 ROLES API ENDPOINT DEBUG</h1>";
                echo "<hr>";
                
                echo "<h2>1. Database Check:</h2>";
                $rolesCount = \Spatie\Permission\Models\Role::count();
                $permissionsCount = \Spatie\Permission\Models\Permission::count();
                echo "Roles in DB: <strong>{$rolesCount}</strong><br>";
                echo "Permissions in DB: <strong>{$permissionsCount}</strong><br><br>";
                
                if ($rolesCount === 0) {
                    echo "<div style='color:red'>❌ NO ROLES FOUND! Run: <code>php artisan db:seed --class=FreshRolePermissionSeeder</code></div><br>";
                }
                
                echo "<h2>2. Sample Roles:</h2>";
                $roles = \Spatie\Permission\Models\Role::withCount('permissions')->limit(3)->get();
                foreach ($roles as $role) {
                    echo "• ID: {$role->id}, Name: {$role->name}, Permissions: {$role->permissions_count}<br>";
                }
                
                echo "<br><h2>3. API Test (Direct Call):</h2>";
                $request = new \Illuminate\Http\Request([
                    'draw' => 1,
                    'start' => 0,
                    'length' => 10,
                    'search' => ['value' => '']
                ]);
                
                $controller = app(\App\Http\Controllers\RoleController::class);
                $response = $controller->getData($request);
                $responseData = json_decode($response->getContent(), true);
                
                echo "Status: <strong>{$response->getStatusCode()}</strong><br>";
                echo "Records Total: <strong>" . ($responseData['recordsTotal'] ?? 'N/A') . "</strong><br>";
                echo "Records Filtered: <strong>" . ($responseData['recordsFiltered'] ?? 'N/A') . "</strong><br>";
                echo "Data Count: <strong>" . count($responseData['data'] ?? []) . "</strong><br>";
                
                if (isset($responseData['error'])) {
                    echo "<div style='color:red'>Error: {$responseData['error']}</div>";
                }
                
                echo "<br><h2>4. Full API Response:</h2>";
                echo "<pre style='background:#f1f1f1;padding:10px;'>" . json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
                
                echo "<br><h2>5. Test AJAX Call (JavaScript):</h2>";
                echo "<button onclick='testAjax()'>Test AJAX Call</button>";
                echo "<div id='ajaxResult'></div>";
                
                echo "<script>
                function testAjax() {
                    fetch('/roles/data?draw=1&start=0&length=10&search[value]=')
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('ajaxResult').innerHTML = 
                                '<h3>AJAX Result:</h3>' +
                                '<pre style=\"background:#e8f5e8;padding:10px;\">' + 
                                JSON.stringify(data, null, 2) + 
                                '</pre>';
                        })
                        .catch(error => {
                            document.getElementById('ajaxResult').innerHTML = 
                                '<h3 style=\"color:red\">AJAX Error:</h3>' +
                                '<pre style=\"background:#f5e8e8;padding:10px;\">' + 
                                error.toString() + 
                                '</pre>';
                        });
                }
                </script>";
                
            } catch (Exception $e) {
                echo "<div style='color:red'>Exception: " . $e->getMessage() . "</div>";
                echo "<pre>" . $e->getTraceAsString() . "</pre>";
            }
        })->name('debug-endpoint');
        Route::get('/export', [RoleController::class, 'export'])->name('export');
        Route::get('/export-pdf', [RoleController::class, 'exportPdf'])->name('export-pdf');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/permissions/by-module', [RoleController::class, 'getPermissionsByModule'])->name('permissions.by-module');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
    });
    
    // -------------------------------------------------------------------------
    // MOLD MODIFICATIONS ROUTES
    // -------------------------------------------------------------------------
    Route::prefix('mold-modifications')->name('mold-modifications.')->group(function () {
        Route::get('/', [MoldModificationController::class, 'index'])->name('index');
        Route::post('/', [MoldModificationController::class, 'store'])->name('store');
        Route::put('/{id}', [MoldModificationController::class, 'update'])->name('update');
        Route::delete('/{id}', [MoldModificationController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [MoldModificationController::class, 'updateStatus'])->name('update.status');
    });
    
    // -------------------------------------------------------------------------
    // INVENTORY ROUTES
    // -------------------------------------------------------------------------
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/stock-in', function() { return view('inventory.stock-in'); })->name('stock-in');
        Route::get('/stock-out', function() { return view('inventory.stock-out'); })->name('stock-out');
        Route::get('/history', function() { return view('inventory.history'); })->name('history');
    });
});

// =============================================================================
// ADDITIONAL PUBLIC ROUTES
// =============================================================================

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// =============================================================================
// ERROR PAGES ROUTES (Public Access)
// =============================================================================
Route::prefix('error')->name('error.')->group(function () {
    Route::get('/404', [ErrorController::class, 'notFound'])->name('404');
    Route::get('/500', [ErrorController::class, 'serverError'])->name('500');
    Route::get('/403', [ErrorController::class, 'forbidden'])->name('403');
    Route::get('/419', [ErrorController::class, 'pageExpired'])->name('419');
    Route::get('/429', [ErrorController::class, 'tooManyRequests'])->name('429');
    Route::get('/{code}', [ErrorController::class, 'customError'])->name('custom');
});

require __DIR__.'/auth.php';
