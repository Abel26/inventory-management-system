# Authentication & Authorization Setup Guide

## Overview

This guide explains the authentication and authorization setup for Ebara Inventory Management System using Spatie Laravel Permission package.

## Installed Package

- **Package**: `spatie/laravel-permission` v6.24.0
- **Purpose**: Role-based access control (RBAC) for the system

## Database Changes

### Users Table Updates

The following columns were added to the `users` table:

| Column | Type | Description |
|---------|-------|-------------|
| `username` | string (unique) | Username for login |
| `deleted_at` | timestamp (nullable) | Soft deletes support |

### New Tables Created

The following tables were created by Spatie Laravel Permission:

| Table | Description |
|--------|-------------|
| `permissions` | List of all permissions |
| `roles` | List of all roles |
| `model_has_permissions` | Pivot table for model-permission relationships |
| `model_has_roles` | Pivot table for model-role relationships |
| `role_has_permissions` | Pivot table for role-permission relationships |

## Model Updates

### User Model (`app/Models/User.php`)

```php
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'username',  // Added
        'email',
        'password',
    ];
}
```

**Traits Added:**
- `HasRoles` - From Spatie for role/permission management
- `SoftDeletes` - For enterprise data integrity

## Seeding

### RoleAndUserSeeder

The seeder creates:

1. **Super Admin Role**
   - Name: `superadmin`
   - Guard: `web`

2. **Super Admin User**
   - Name: `Super Admin`
   - Username: `superadmin`
   - Email: `admin@ebara.co.id`
   - Password: `Sup3r4dm1n`
   - Email Verified: Yes

3. **Role Assignment**
   - Super Admin user is assigned the `superadmin` role

## Super Admin Credentials

| Field | Value |
|-------|--------|
| **Username** | `superadmin` |
| **Password** | `Sup3r4dm1n` |
| **Email** | `admin@ebara.co.id` |

> **⚠️ Important**: Change the default password after first login in production!

## Usage Examples

### Checking User Roles

```php
// Check if user has a specific role
if ($user->hasRole('superadmin')) {
    // User is super admin
}

// Check if user has any of the given roles
if ($user->hasAnyRole(['superadmin', 'admin', 'manager'])) {
    // User has one of these roles
}

// Check if user has all given roles
if ($user->hasAllRoles(['admin', 'manager'])) {
    // User has both roles
}
```

### Assigning Roles to Users

```php
// Assign a single role
$user->assignRole('admin');

// Assign multiple roles
$user->assignRole(['admin', 'manager']);

// Sync roles (remove existing, assign new)
$user->syncRoles(['superadmin']);
```

### Removing Roles from Users

```php
// Remove a specific role
$user->removeRole('admin');

// Remove all roles
$user->syncRoles([]);
```

### Creating Roles Programmatically

```php
use Spatie\Permission\Models\Role;

$role = Role::create(['name' => 'manager']);
```

### Creating Permissions Programmatically

```php
use Spatie\Permission\Models\Permission;

$permission = Permission::create(['name' => 'edit products']);
```

### Assigning Permissions to Roles

```php
// Assign permission to role
$role->givePermissionTo('edit products');

// Assign multiple permissions
$role->givePermissionTo(['edit products', 'delete products']);

// Sync permissions
$role->syncPermissions(['edit products']);
```

### Checking Permissions

```php
// Check if user has permission
if ($user->can('edit products')) {
    // User can edit products
}

// Check via role
if ($user->hasRole('superadmin') || $user->can('edit products')) {
    // User can edit products
}
```

### Middleware

You can use the built-in middleware from Spatie:

```php
// In routes/web.php
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    // Only superadmin can access these routes
});

Route::middleware(['auth', 'permission:edit products'])->group(function () {
    // Only users with 'edit products' permission can access
});

// Multiple roles
Route::middleware(['auth', 'role:admin|manager'])->group(function () {
    // Admin or manager can access
});
```

### Blade Directives

```blade
@role('superadmin')
    <!-- Only visible to superadmins -->
@endrole

@hasrole('admin|manager')
    <!-- Visible to admins or managers -->
@endhasrole

@permission('edit products')
    <!-- Only visible to users with this permission -->
@endpermission

@can('delete products', $product)
    <!-- Only visible if user can delete this specific product -->
@endcan
```

## Configuration

The package configuration is located at `config/permission.php`.

### Key Settings

```php
return [
    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],
    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_roles' => 'model_has_roles',
        'model_has_permissions' => 'model_has_permissions',
        'role_has_permissions' => 'role_has_permissions',
    ],
    'column_names' => [
        'model_morph_key' => 'model_id',
        'pivot_role' => 'role_id',
        'pivot_permission' => 'permission_id',
        'permission_name' => 'name',
        'role_name' => 'name',
    ],
];
```

## Caching

The package caches permissions and roles for performance. Clear cache when making changes:

```bash
php artisan cache:forget spatie.permission.cache
```

Or programmatically:

```php
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
```

## Recommended Roles Structure

For the Ebara Inventory System, consider these roles:

| Role | Description | Permissions |
|-------|-------------|--------------|
| `superadmin` | Full system access | All permissions |
| `admin` | Manage inventory & users | Create, read, update, delete products, stock movements |
| `manager` | Manage inventory | Create, read, update products, stock movements |
| `staff` | View & update inventory | Read, update products, stock in/out |
| `viewer` | Read-only access | Read products, reports |

## Next Steps

1. **Create Additional Roles**: Use `RoleAndUserSeeder` or create new seeders for other roles
2. **Define Permissions**: Create permissions for each action (create, read, update, delete)
3. **Assign Permissions**: Map permissions to roles
4. **Apply Middleware**: Protect routes with role/permission middleware
5. **Update Login**: Modify login controller to support username-based login (optional)

## Troubleshooting

### Permission Cache Issues

If roles/permissions are not working:

```bash
php artisan cache:clear
php artisan config:clear
php artisan cache:forget spatie.permission.cache
```

### Migration Issues

If you encounter migration conflicts:

```bash
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

### User Not Getting Role

Ensure the user model uses the `HasRoles` trait and permissions are cached correctly.

## Security Notes

1. **Change Default Password**: Always change the Super Admin password after first login
2. **Use Strong Passwords**: Enforce password complexity requirements
3. **Limit Super Admin Access**: Only assign superadmin role to trusted personnel
4. **Audit Role Changes**: Log all role/permission assignments
5. **Use Permissions Over Roles**: Prefer granular permissions for better security

## References

- [Spatie Laravel Permission Documentation](https://spatie.be/docs/laravel-permission/v6/introduction)
- [Laravel Authentication Documentation](https://laravel.com/docs/11.x/authentication)
