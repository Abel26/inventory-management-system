# Ebara Inventory Management System - UI Components Guide

## Overview

This guide explains how to use the new UI components built for the Ebara Inventory Management System. The components follow Laravel Blade component conventions and use Tailwind CSS with Ebara's brand colors.

## Getting Started

### Using the Master Layout

To use the new admin layout in your views:

```blade
<x-app-layout>
    <x-slot name="header">
        Page Title
    </x-slot>
    
    <x-slot name="description">
        Optional page description
    </x-slot>
    
    <!-- Your page content here -->
    <div class="...">
        Content goes here...
    </div>
</x-app-layout>
```

### Using Breadcrumbs

```blade
<x-app-layout>
    <x-slot name="header">Products</x-slot>
    
    <x-slot:breadcrumb>
        {{ [
            ['title' => 'Inventory', 'url' => route('inventory.index')],
            ['title' => 'Products', 'url' => null],
        ] }}
    </x-slot>
    
    <!-- Content -->
</x-app-layout>
```

## Components Reference

### Layout Components

#### `<x-layout.sidebar />`

The sidebar component with navigation menu.

**Props:**
- `collapsed` (bool): Whether sidebar is collapsed
- `navigation` (array): Custom navigation menu structure

**Usage:**
```blade
<x-layout.sidebar :collapsed="false" />
```

#### `<x-layout.navbar />`

The top navigation bar with search, notifications, and user dropdown.

**Props:**
- `sidebarOpen` (bool): Current sidebar state

**Usage:**
```blade
<x-layout.navbar :sidebar-open="$sidebarOpen" />
```

#### `<x-layout.footer />`

Simple copyright footer.

**Usage:**
```blade
<x-layout.footer />
```

### Navigation Components

#### `<x-navigation.sidebar-link />`

Single sidebar navigation link.

**Props:**
- `title` (string): Link text
- `icon` (string): Phosphor icon class (e.g., 'ph-squares-four')
- `route` (string): Route name
- `active` (bool): Whether this is the active page
- `collapsed` (bool): Whether sidebar is collapsed

**Usage:**
```blade
<x-navigation.sidebar-link 
    title="Dashboard" 
    icon="ph-squares-four"
    route="dashboard"
    :active="request()->routeIs('dashboard')"
    :collapsed="false"
/>
```

#### `<x-navigation.sidebar-section />`

Sidebar section with collapsible children.

**Props:**
- `title` (string): Section title
- `icon` (string): Phosphor icon class
- `collapsed` (bool): Whether sidebar is collapsed
- `children` (array): Array of child items

**Usage:**
```blade
<x-navigation.sidebar-section 
    title="Inventory"
    icon="ph-warehouse"
    :collapsed="false"
    :children="[
        ['title' => 'Stock In', 'route' => 'inventory.stock-in', 'active' => false],
        ['title' => 'Stock Out', 'route' => 'inventory.stock-out', 'active' => false],
    ]"
/>
```

#### `<x-navigation.breadcrumb />`

Breadcrumb navigation.

**Props:**
- `items` (array): Array of breadcrumb items with 'title' and 'url'

**Usage:**
```blade
<x-navigation.breadcrumb :items="[
    ['title' => 'Home', 'url' => route('dashboard')],
    ['title' => 'Products', 'url' => route('products.index')],
]" />
```

### UI Components

#### `<x-ui.card />`

Card container component.

**Props:**
- `title` (string): Optional card title
- `description` (string): Optional card description
- `padding` (string): 'sm', 'default', 'lg', or 'none'

**Usage:**
```blade
<x-ui.card title="Statistics" description="Overview of your inventory" padding="lg">
    <!-- Card content -->
</x-ui.card>
```

#### `<x-ui.button />`

Button component with variants.

**Props:**
- `variant` (string): 'primary', 'secondary', 'danger', 'ghost'
- `size` (string): 'sm', 'default', 'lg'
- `type` (string): 'button', 'submit', 'reset'

**Usage:**
```blade
<x-ui.button variant="primary" size="default" type="submit">
    Save Changes
</x-ui.button>

<x-ui.button variant="secondary" size="sm">
    Cancel
</x-ui.button>

<x-ui.button variant="danger">
    Delete
</x-ui.button>
```

#### `<x-ui.badge />`

Status badge component.

**Props:**
- `variant` (string): 'default', 'success', 'warning', 'danger', 'info'

**Usage:**
```blade
<x-ui.badge variant="success">Active</x-ui.badge>
<x-ui.badge variant="warning">Pending</x-ui.badge>
<x-ui.badge variant="danger">Inactive</x-ui.badge>
```

#### `<x-ui.alert />`

Alert/notification component.

**Props:**
- `type` (string): 'success', 'error', 'warning', 'info'
- `message` (string): Alert message
- `dismissible` (bool): Whether alert can be dismissed

**Usage:**
```blade
<x-ui.alert type="success" message="Product created successfully!" :dismissible="true" />
<x-ui.alert type="error" :dismissible="true">
    An error occurred. Please try again.
</x-ui.alert>
```

### User Components

#### `<x-user.user-avatar />`

User avatar component with initials.

**Props:**
- `user` (User): User model
- `size` (string): 'sm', 'md', 'lg'

**Usage:**
```blade
<x-user.user-avatar :user="$user" size="md" />
```

#### `<x-user.user-dropdown />`

User profile dropdown with menu options.

**Usage:**
```blade
<x-user.user-dropdown />
```

#### `<x-user.notification-bell />`

Notification bell with badge count.

**Props:**
- `count` (int): Number of unread notifications

**Usage:**
```blade
<x-user.notification-bell :count="3" />
```

### Brand Components

#### `<x-brand.ebara-logo />`

Ebara company logo SVG.

**Props:**
- `class` (string): Additional CSS classes

**Usage:**
```blade
<x-brand.ebara-logo class="h-8 w-8 text-ebara-500" />
```

## Color Palette

### Ebara Theme Colors

| Color | Hex | Usage |
|-------|-----|-------|
| `ebara-500` | `#009B77` | Primary brand color |
| `ebara-600` | `#008065` | Hover state |
| `slate-900` | `#111827` | Sidebar background |
| `bg-primary` | `#F3F4F6` | Main content background |
| `bg-secondary` | `#FFFFFF` | Card/surface background |

### Utility Classes

```blade
<!-- Text colors -->
text-text-primary    <!-- #111827 - Primary text -->
text-text-secondary  <!-- #6B7280 - Secondary text -->
text-text-light     <!-- #9CA3AF - Light text -->

<!-- Background colors -->
bg-bg-primary       <!-- #F3F4F6 - Main background -->
bg-bg-secondary     <!-- #FFFFFF - Surface background -->
bg-slate-900       <!-- #111827 - Dark background -->

<!-- Ebara brand colors -->
text-ebara-500      <!-- Primary brand text -->
bg-ebara-500        <!-- Primary brand background -->
bg-ebara-100        <!-- Light brand background -->
```

## Icons

The components use **Phosphor Icons**. Include the script in your layout:

```html
<script src="https://unpkg.com/@phosphor-icons/web"></script>
```

**Common Icons:**

```blade
<!-- Navigation -->
<i class="ph ph-house"></i>
<i class="ph ph-squares-four"></i>
<i class="ph ph-package"></i>
<i class="ph ph-warehouse"></i>
<i class="ph ph-chart-bar"></i>
<i class="ph ph-gear"></i>

<!-- Actions -->
<i class="ph ph-plus"></i>
<i class="ph ph-pencil"></i>
<i class="ph ph-trash"></i>
<i class="ph ph-eye"></i>
<i class="ph ph-magnifying-glass"></i>

<!-- User -->
<i class="ph ph-user"></i>
<i class="ph ph-bell"></i>
<i class="ph ph-sign-out"></i>

<!-- Status -->
<i class="ph ph-check-circle"></i>
<i class="ph ph-x-circle"></i>
<i class="ph ph-warning"></i>
<i class="ph ph-info"></i>
```

## Responsive Design

### Breakpoints

- **Mobile**: < 640px (hidden sidebar, hamburger menu)
- **Tablet**: 640px - 1024px (full navbar)
- **Desktop**: > 1024px (fixed sidebar, collapsible)

### Sidebar Behavior

The sidebar automatically adapts to screen size:

```blade
<!-- Mobile: Off-canvas with overlay -->
<!-- Desktop: Fixed left, collapsible -->
```

## Example: Complete Page

```blade
<x-app-layout>
    <x-slot name="header">Products</x-slot>
    <x-slot name="description">Manage your inventory products</x-slot>
    
    <!-- Breadcrumb -->
    <x-navigation.breadcrumb :items="[
        ['title' => 'Inventory', 'url' => route('inventory.index')],
        ['title' => 'Products', 'url' => null],
    ]" />
    
    <!-- Action Bar -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-2">
            <x-ui.button variant="primary">
                <i class="ph ph-plus mr-2"></i> Add Product
            </x-ui.button>
            <x-ui.button variant="secondary">
                <i class="ph ph-download mr-2"></i> Export
            </x-ui.button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-ui.card padding="lg">
            <div class="text-center">
                <p class="text-sm text-gray-500">Total Products</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">1,234</p>
            </div>
        </x-ui.card>
        <!-- More cards... -->
    </div>
    
    <!-- Data Table Card -->
    <x-ui.card>
        <x-slot name="title">Product List</x-slot>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Industrial Pump X200</td>
                        <td class="px-6 py-4 text-sm text-gray-500">PUMP-001</td>
                        <td class="px-6 py-4 text-sm text-gray-500">150</td>
                        <td class="px-6 py-4">
                            <x-ui.badge variant="success">In Stock</x-ui.badge>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <x-ui.button variant="ghost" size="sm">Edit</x-ui.button>
                            <x-ui.button variant="danger" size="sm">Delete</x-ui.button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-ui.card>
</x-app-layout>
```

## Flash Messages

Flash messages are automatically displayed using the alert component:

```php
// In your controller
return redirect()->route('products.index')
    ->with('success', 'Product created successfully!')
    ->with('error', 'Some error occurred')
    ->with('warning', 'Low stock warning')
    ->with('info', 'Information message');
```

## Customizing the Sidebar Navigation

To customize the sidebar menu, you can pass a custom navigation array:

```blade
<x-layout.sidebar 
    :collapsed="false"
    :navigation="[
        [
            'title' => 'Custom Menu',
            'icon' => 'ph-star',
            'route' => 'custom.route',
            'active' => request()->routeIs('custom*'),
        ],
        [
            'title' => 'Grouped Items',
            'icon' => 'ph-folder',
            'children' => [
                ['title' => 'Item 1', 'route' => 'item1', 'active' => false],
                ['title' => 'Item 2', 'route' => 'item2', 'active' => false],
            ],
        ],
    ]"
/>
```

## Tips & Best Practices

1. **Always use the component variants** for consistent styling
2. **Use semantic HTML** within card components
3. **Leverage Alpine.js** for interactive elements
4. **Follow the naming convention** for icon classes (ph-icon-name)
5. **Use the grid system** for responsive layouts
6. **Keep accessibility in mind** - use ARIA labels where needed

## Troubleshooting

### Icons not showing
Make sure Phosphor Icons script is included in your layout:
```html
<script src="https://unpkg.com/@phosphor-icons/web"></script>
```

### Colors not applying
Run the build command to compile Tailwind CSS:
```bash
npm run build
```

### Sidebar not collapsing
Ensure Alpine.js is loaded and the data attributes are correct.

## Support

For questions or issues, refer to the main documentation or contact the development team.
