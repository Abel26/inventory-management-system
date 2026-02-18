<?php
/**
 * Quick Database Check for Roles & Permissions
 * Run: php database_check.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 CHECKING DATABASE FOR ROLES & PERMISSIONS\n";
echo str_repeat("=", 50) . "\n";

try {
    // Check database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection: OK\n";
    
    // Check if tables exist
    $tables = [
        'roles' => DB::select("SHOW TABLES LIKE 'roles'"),
        'permissions' => DB::select("SHOW TABLES LIKE 'permissions'"),
        'role_has_permissions' => DB::select("SHOW TABLES LIKE 'role_has_permissions'"),
    ];
    
    foreach ($tables as $table => $exists) {
        if (count($exists) > 0) {
            echo "✅ Table '{$table}' exists\n";
        } else {
            echo "❌ Table '{$table}' NOT found! Run migrations: php artisan migrate\n";
        }
    }
    
    // Check data counts
    echo "\n📊 DATA COUNTS:\n";
    echo str_repeat("-", 30) . "\n";
    
    $roleCounts = DB::table('roles')->count();
    echo "Roles: {$roleCounts}\n";
    
    $permissionCounts = DB::table('permissions')->count();
    echo "Permissions: {$permissionCounts}\n";
    
    $rolePermissionCounts = DB::table('role_has_permissions')->count();
    echo "Role-Permission Relations: {$rolePermissionCounts}\n";
    
    // Show sample data
    if ($roleCounts > 0) {
        echo "\n🔍 SAMPLE ROLES:\n";
        echo str_repeat("-", 30) . "\n";
        $roles = DB::table('roles')->limit(5)->get(['id', 'name', 'guard_name']);
        foreach ($roles as $role) {
            echo "ID: {$role->id}, Name: {$role->name}, Guard: {$role->guard_name}\n";
        }
    } else {
        echo "\n⚠️  NO ROLES FOUND IN DATABASE!\n";
        echo "🔧 SOLUTION: Run seeders:\n";
        echo "   php artisan db:seed --class=FreshRolePermissionSeeder\n";
        echo "   OR\n";
        echo "   php artisan db:seed\n";
    }
    
    if ($permissionCounts > 0) {
        echo "\n🔍 SAMPLE PERMISSIONS:\n";
        echo str_repeat("-", 30) . "\n";
        $permissions = DB::table('permissions')->limit(5)->get(['id', 'name', 'guard_name']);
        foreach ($permissions as $permission) {
            echo "ID: {$permission->id}, Name: {$permission->name}, Guard: {$permission->guard_name}\n";
        }
    }
    
    // Test Role model
    echo "\n🧪 TESTING ROLE MODEL:\n";
    echo str_repeat("-", 30) . "\n";
    
    $rolesWithCount = \Spatie\Permission\Models\Role::withCount('permissions')->get();
    if ($rolesWithCount->count() > 0) {
        echo "✅ Role::withCount('permissions') works\n";
        foreach ($rolesWithCount->take(3) as $role) {
            echo "Role: {$role->name} has {$role->permissions_count} permissions\n";
        }
    } else {
        echo "⚠️  No roles loaded with Role model\n";
    }
    
    // Test API endpoint
    echo "\n🌐 TESTING API ENDPOINT (Simulation):\n";
    echo str_repeat("-", 30) . "\n";
    
    $request = new \Illuminate\Http\Request([
        'draw' => 1,
        'start' => 0,
        'length' => 10,
        'search' => ['value' => '']
    ]);
    
    $controller = new \App\Http\Controllers\RoleController(
        new \App\Services\RoleService(
            new \App\Repositories\RoleRepository()
        )
    );
    
    $response = $controller->getData($request);
    $responseData = json_decode($response->getContent(), true);
    
    echo "API Response Status: {$response->getStatusCode()}\n";
    echo "Records Total: " . ($responseData['recordsTotal'] ?? 'N/A') . "\n";
    echo "Records Filtered: " . ($responseData['recordsFiltered'] ?? 'N/A') . "\n";
    echo "Data Count: " . (count($responseData['data'] ?? [])) . "\n";
    
    if (isset($responseData['error'])) {
        echo "❌ API Error: " . $responseData['error'] . "\n";
    } else {
        echo "✅ API Working\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🏁 DIAGNOSIS COMPLETE\n";