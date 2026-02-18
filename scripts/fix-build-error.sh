#!/bin/bash

echo "=========================================="
echo "FIXING BUILD ERRORS"
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[⚠]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

print_info() {
    echo -e "${BLUE}[ℹ]${NC} $1"
}

# Fix terser issue
print_info "Installing terser for production builds..."
npm install --save-dev terser

# Fix vite config to handle terser properly
print_info "Updating vite.config.js for production compatibility..."

cat > vite.config.js << 'EOF'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: process.env.NODE_ENV === 'production',
                drop_debugger: process.env.NODE_ENV === 'production'
            }
        }
    }
});
EOF

print_status "vite.config.js updated for production"

# Clean build directory
print_info "Cleaning build directory..."
rm -rf public/build

# Rebuild assets
print_info "Rebuilding assets..."
npm run build

if [ $? -eq 0 ]; then
    print_status "Assets built successfully!"
else
    print_error "Build still failed. Trying alternative approach..."
    
    # Alternative build without minification
    print_info "Trying build without minification..."
    
    cat > vite.config.js << 'EOF'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        minify: false // Disable minification temporarily
    }
});
EOF
    
    npm run build
    
    if [ $? -eq 0 ]; then
        print_status "Assets built successfully without minification!"
        print_warning "Consider installing terser properly for production optimization"
    else
        print_error "Build failed completely. Check npm configuration."
        exit 1
    fi
fi

print_status "Build fix completed!"