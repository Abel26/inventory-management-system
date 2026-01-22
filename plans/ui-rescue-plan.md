# UI Rescue Plan - Responsive Layout Architecture

## Problem Diagnosis

### 1. Guest Layout (Login/Register)
- **Status**: Already has `@vite` directive and proper structure
- **Issue**: CSS not loading due to CSP blocking Vite dev server (localhost:5173)
- **Root Cause**: SecurityHeadersMiddleware CSP was too strict

### 2. App Layout (Main Dashboard)
- **Status**: Has sidebar but poor mobile UX
- **Issues**:
  - Sidebar is built inline in app.blade.php (not using sidebar component)
  - Mobile sidebar toggle lacks proper Z-layer architecture
  - No backdrop overlay for mobile
  - Content doesn't adjust properly for desktop sidebar
  - Hamburger menu behavior is clunky

### 3. Navigation Component
- **Status**: navigation.blade.php exists but is a traditional top navbar (not used)
- **Issue**: Doesn't match the sidebar-based design pattern

## Architectural Blueprint

### Z-Layer Architecture for Mobile-First Navigation

```
┌─────────────────────────────────────────────────────────────┐
│ Layer Z-50: Mobile Header (Visible < lg)                    │
│ ┌──────────┬────────────────────────────┬──────────────────┐ │
│ │ Hamburger│         Logo               │   Profile Dropdown│ │
│ └──────────┴────────────────────────────┴──────────────────┘ │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│ Layer Z-40: Backdrop Overlay (Mobile Only)                   │
│            (Appears when sidebar is open)                    │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│ Layer Z-30: Sidebar (Off-Canvas on Mobile, Fixed on Desktop) │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ Logo Section                                            │ │
│ ├─────────────────────────────────────────────────────────┤ │
│ │ Navigation Menu                                         │ │
│ ├─────────────────────────────────────────────────────────┤ │
│ │ User Info (Bottom)                                      │ │
│ └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│ Layer Z-0: Main Content                                     │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │                                                         │ │
│ │         Page Content (Tables, Forms, etc.)             │ │
│ │                                                         │ │
│ └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Implementation Plan

### Phase 1: Guest Layout Fix
**File**: `resources/views/layouts/guest.blade.php`

**Analysis**: The file is already correct with:
- ✅ `<meta name="viewport" content="width=device-width, initial-scale=1">`
- ✅ `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- ✅ Centered wrapper: `min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100`

**Action**: No changes needed - CSS loading issue is CSP-related (already fixed in SecurityHeadersMiddleware)

---

### Phase 2: Mobile Navbar Component
**File**: `resources/views/components/layout/mobile-navbar.blade.php` (NEW)

**Purpose**: Fixed header for mobile with hamburger toggle and profile dropdown

**Structure**:
```blade
<header class="fixed top-0 left-0 right-0 h-16 bg-white shadow-sm z-50 flex items-center justify-between px-4 lg:hidden">
    <!-- Hamburger Button (Left) -->
    <button @click="$dispatch('toggle-sidebar')" class="p-2 rounded-lg hover:bg-gray-100">
        <i class="ph ph-list text-2xl text-slate-600"></i>
    </button>

    <!-- Logo (Center) -->
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
        <x-brand.ebara-logo class="h-8 w-8 text-ebara-500" />
        <span class="text-lg font-bold text-slate-800">EBARA</span>
    </a>

    <!-- Profile Dropdown (Right) -->
    <x-user.user-dropdown />
</header>
```

---

### Phase 3: Sidebar Component Update
**File**: `resources/views/components/layout/sidebar.blade.php`

**Changes Required**:
1. Remove dependency on `$parent.sidebarOpen` - use Alpine.js event dispatching
2. Ensure proper mobile/desktop styling with responsive classes
3. Add collapse button for desktop sidebar toggle

**Key Classes**:
- Mobile: `fixed inset-y-0 left-0 z-30 w-72 transform transition-transform duration-300`
- Desktop: `lg:translate-x-0 lg:static lg:inset-auto lg:w-64`

---

### Phase 4: App Layout Rewrite
**File**: `resources/views/layouts/app.blade.php`

**New Structure**:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ebara Inventory') }} - {{ $title ?? 'Dashboard' }}</title>

    <!-- Fonts: Inter via Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons: Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS for Tailwind -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.tailwindcss.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-700 bg-slate-50 h-full overflow-hidden"
      x-data="{ sidebarOpen: false }"
      @toggle-sidebar.window="sidebarOpen = !sidebarOpen">

    <!-- ==================== LAYER Z-50: MOBILE HEADER ==================== -->
    <x-layout.mobile-navbar />

    <!-- ==================== LAYER Z-40: BACKDROP OVERLAY (Mobile Only) ==================== -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <div class="flex h-screen lg:pt-0 pt-16">
        <!-- ==================== LAYER Z-30: SIDEBAR ==================== -->
        <aside class="fixed inset-y-0 left-0 z-30 w-72 bg-slate-900 text-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:w-64 lg:shadow-none"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <x-layout.sidebar />
        </aside>

        <!-- ==================== LAYER Z-0: MAIN CONTENT ==================== -->
        <main class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">
            <!-- Desktop Header (Visible >= lg) -->
            <header class="hidden lg:flex h-16 bg-white shadow-sm items-center justify-between px-6">
                <!-- Sidebar Toggle Button -->
                <button @click="$dispatch('toggle-sidebar')"
                        class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="ph ph-list text-2xl text-slate-600"></i>
                </button>

                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    <x-user.notification-bell :count="$pendingReportCount ?? 0" :notifications="$latestNotifications ?? null" />
                    <x-user.user-dropdown />
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-4 lg:p-6">
                {{ $slot ?? $content ?? '' }}
            </div>

            <!-- Footer -->
            <x-layout.footer />
        </main>
    </div>

    <!-- Scripts Stack -->
    @stack('scripts')
</body>
</html>
```

---

## Responsive Component Standards

### Tables
All tables MUST be wrapped in:
```blade
<div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <!-- Table content -->
    </table>
</div>
```

### Forms
Use responsive grid:
```blade
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Form fields -->
</div>
```

---

## Testing Checklist

- [ ] Guest layout (Login/Register) loads with proper styling
- [ ] Mobile hamburger menu opens sidebar
- [ ] Mobile sidebar has backdrop overlay
- [ ] Clicking backdrop closes sidebar
- [ ] Desktop sidebar is always visible
- [ ] Desktop sidebar toggle button works
- [ ] Tables scroll horizontally on mobile
- [ ] Forms use 1 column on mobile, 2 columns on tablet+
- [ ] No content is hidden behind sidebar on desktop
- [ ] Transitions are smooth (300ms)
- [ ] Alpine.js state management works correctly

---

## File Changes Summary

| File | Action | Notes |
|------|--------|-------|
| `guest.blade.php` | No change needed | Already correct |
| `mobile-navbar.blade.php` | CREATE | New component for mobile header |
| `sidebar.blade.php` | UPDATE | Remove $parent dependency, add responsive classes |
| `app.blade.php` | REWRITE | Implement Z-layer architecture |
| `bootstrap/app.php` | RE-ENABLE | Uncomment SecurityHeadersMiddleware after testing |

---

## Next Steps

1. Create `mobile-navbar.blade.php` component
2. Update `sidebar.blade.php` for better Alpine.js integration
3. Rewrite `app.blade.php` with Z-layer architecture
4. Test all responsive breakpoints
5. Re-enable SecurityHeadersMiddleware
6. Verify CSP allows all required resources
