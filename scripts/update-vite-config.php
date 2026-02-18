<?php

/**
 * Script untuk update Vite configuration agar include vendor assets
 * Ini memastikan semua vendor JavaScript dan CSS files tersedia di production
 */

echo "==========================================\n";
echo "VITE CONFIGURATION UPDATE\n";
echo "==========================================\n\n";

// Colors for output
function print_status($message) {
    echo "\033[0;32m[✓]\033[0m $message\n";
}

function print_warning($message) {
    echo "\033[1;33m[⚠]\033[0m $message\n";
}

function print_error($message) {
    echo "\033[0;31m[✗]\033[0m $message\n";
}

function print_info($message) {
    echo "\033[0;34m[ℹ]\033[0m $message\n";
}

// Check if vite.config.js exists
if (!file_exists('vite.config.js')) {
    print_error("vite.config.js not found!");
    exit(1);
}

// Backup original config
if (!file_exists('vite.config.js.backup')) {
    if (!copy('vite.config.js', 'vite.config.js.backup')) {
        print_error("Failed to backup vite.config.js");
        exit(1);
    }
    print_status("Backup created: vite.config.js.backup");
}

// Read package.json to get dependencies
$packageJson = json_decode(file_get_contents('package.json'), true);
if (!$packageJson) {
    print_error("Failed to parse package.json");
    exit(1);
}

// Create new vite config with vendor assets
$newConfig = <<<VITE_CONFIG
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/landing.css', 
                'resources/js/app.js',
                // Vendor assets - ensure they're available
                'public/vendor/jquery.min.js',
                'public/vendor/jquery.dataTables.min.js',
                'public/vendor/dataTables.dataTables.min.js',
                'public/vendor/jquery.dataTables.min.css',
                'public/vendor/sweetalert2.min.js',
                'public/vendor/apexcharts.min.js',
                'public/vendor/html5-qrcode.min.js'
            ],
            refresh: true,
        }),
    ],
    // Ensure vendor assets are treated as external and not processed
    optimizeDeps: {
        exclude: [
            'jquery',
            'datatables.net',
            'datatables.net-dt',
            'sweetalert2',
            'apexcharts',
            'html5-qrcode'
        ]
    },
    // Configure build output
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['jquery'],
                    datatable: ['datatables.net', 'datatables.net-dt'],
                    ui: ['sweetalert2', 'apexcharts'],
                    scanner: ['html5-qrcode']
                }
            }
        },
        // Generate source maps for debugging (remove in production if needed)
        sourcemap: process.env.NODE_ENV !== 'production',
        // Minify for production
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: process.env.NODE_ENV === 'production',
                drop_debugger: process.env.NODE_ENV === 'production'
            }
        }
    },
    // Development server configuration
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost'
        }
    }
});
VITE_CONFIG;

// Write new config
if (file_put_contents('vite.config.js', $newConfig)) {
    print_status("vite.config.js updated successfully");
} else {
    print_error("Failed to update vite.config.js");
    exit(1);
}

// Create post-build script to copy vendor assets
$postBuildScript = <<<POST_BUILD
#!/bin/bash

# Post-build script to ensure vendor assets are available
# This runs after npm run build

echo "Post-build: Copying vendor assets..."

# Create vendor directory if it doesn't exist
mkdir -p public/build/assets/vendor

# Copy vendor files to build directory
if [ -d "public/vendor" ]; then
    cp -r public/vendor/* public/build/assets/vendor/
    echo "Vendor assets copied to build directory"
else
    echo "Warning: public/vendor directory not found"
    echo "Run 'php scripts/setup-vendor-assets.sh' first"
fi

# Create manifest entry for vendor assets
if [ -f "public/build/manifest.json" ]; then
    # Backup original manifest
    cp public/build/manifest.json public/build/manifest.json.backup
    
    # Add vendor assets to manifest
    echo "Adding vendor assets to manifest..."
    python3 << EOF
import json
import os

# Load existing manifest
with open('public/build/manifest.json', 'r') as f:
    manifest = json.load(f)

# Add vendor assets
vendor_files = []
if os.path.exists('public/vendor'):
    for root, dirs, files in os.walk('public/vendor'):
        for file in files:
            if file.endswith(('.js', '.css')):
                rel_path = os.path.relpath(os.path.join(root, file), 'public/vendor')
                vendor_files.append({
                    'file': f'assets/vendor/{file}',
                    'src': f'resources/vendor/{rel_path}',
                    'isEntry': True
                })

# Add to manifest
for asset in vendor_files:
    manifest[f'vendor/{asset["file"].split("/")[-1]}'] = asset

# Save updated manifest
with open('public/build/manifest.json', 'w') as f:
    json.dump(manifest, f, indent=2)

print("Manifest updated with vendor assets")
EOF
fi

echo "Post-build completed"
POST_BUILD;

if (file_put_contents('scripts/post-build.sh', $postBuildScript)) {
    // Make it executable
    chmod('scripts/post-build.sh', 0755);
    print_status("Post-build script created: scripts/post-build.sh");
} else {
    print_error("Failed to create post-build script");
}

// Update package.json scripts
$packageJson['scripts']['build:prod'] = 'npm run build && npm run post-build';
$packageJson['scripts']['post-build'] = './scripts/post-build.sh';
$packageJson['scripts']['setup:vendor'] = './scripts/setup-vendor-assets.sh';

if (file_put_contents('package.json', json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
    print_status("package.json updated with new scripts");
} else {
    print_error("Failed to update package.json");
}

echo "\n";
print_status("Vite configuration update completed!");
echo "\n";
print_info("New scripts available:");
echo "- npm run setup:vendor  - Setup vendor assets from npm packages";
echo "- npm run build:prod    - Build with vendor assets";
echo "- npm run post-build    - Post-build vendor asset copy";
echo "\n";
print_info("Next steps:");
echo "1. Run 'npm run setup:vendor' to copy vendor assets";
echo "2. Run 'npm run build:prod' to build for production";
echo "3. Clear browser cache and test the application";
echo "\n";
print_warning("Note: The original vite.config.js is backed up as vite.config.js.backup");