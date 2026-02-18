#!/bin/bash

# Script untuk setup vendor assets dari npm packages ke public/vendor/
# Ini memperbaiki masalah 500 error akibat missing vendor files

echo "=========================================="
echo "VENDOR ASSETS SETUP SCRIPT"
echo "=========================================="
echo ""

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

# Create vendor directory
print_info "Creating vendor directory..."
mkdir -p public/vendor
print_status "Vendor directory created"

# Copy jQuery
print_info "Setting up jQuery..."
if [ -f "node_modules/jquery/dist/jquery.min.js" ]; then
    cp node_modules/jquery/dist/jquery.min.js public/vendor/
    cp node_modules/jquery/dist/jquery.min.map public/vendor/
    print_status "jQuery copied to public/vendor/"
else
    print_error "jQuery not found in node_modules"
fi

# Copy DataTables
print_info "Setting up DataTables..."
if [ -f "node_modules/datatables.net/js/jquery.dataTables.min.js" ]; then
    cp node_modules/datatables.net/js/jquery.dataTables.min.js public/vendor/
    cp node_modules/datatables.net-dt/js/dataTables.dataTables.min.js public/vendor/
    cp node_modules/datatables.net-dt/css/jquery.dataTables.min.css public/vendor/
    print_status "DataTables copied to public/vendor/"
else
    print_error "DataTables not found in node_modules"
fi

# Copy SweetAlert2
print_info "Setting up SweetAlert2..."
if [ -f "node_modules/sweetalert2/dist/sweetalert2.min.js" ]; then
    cp node_modules/sweetalert2/dist/sweetalert2.min.js public/vendor/
    print_status "SweetAlert2 copied to public/vendor/"
else
    print_error "SweetAlert2 not found in node_modules"
fi

# Copy ApexCharts
print_info "Setting up ApexCharts..."
if [ -f "node_modules/apexcharts/dist/apexcharts.min.js" ]; then
    cp node_modules/apexcharts/dist/apexcharts.min.js public/vendor/
    print_status "ApexCharts copied to public/vendor/"
else
    print_error "ApexCharts not found in node_modules"
fi

# Copy html5-qrcode
print_info "Setting up html5-qrcode..."
if [ -f "node_modules/html5-qrcode/html5-qrcode.min.js" ]; then
    cp node_modules/html5-qrcode/html5-qrcode.min.js public/vendor/
    print_status "html5-qrcode copied to public/vendor/"
else
    print_error "html5-qrcode not found in node_modules"
fi

# Verify all files exist
print_info "Verifying vendor files..."
VENDOR_FILES=(
    "public/vendor/jquery.min.js"
    "public/vendor/jquery.dataTables.min.js"
    "public/vendor/dataTables.dataTables.min.js"
    "public/vendor/jquery.dataTables.min.css"
    "public/vendor/sweetalert2.min.js"
    "public/vendor/apexcharts.min.js"
    "public/vendor/html5-qrcode.min.js"
)

ALL_FILES_EXIST=true
for file in "${VENDOR_FILES[@]}"; do
    if [ -f "$file" ]; then
        print_status "✓ $file"
    else
        print_error "✗ $file is missing"
        ALL_FILES_EXIST=false
    fi
done

if [ "$ALL_FILES_EXIST" = true ]; then
    print_status "All vendor files are in place!"
else
    print_error "Some vendor files are missing. Please check npm install."
fi

# Set proper permissions
print_info "Setting proper permissions..."
chmod -R 755 public/vendor
print_status "Permissions set for public/vendor/"

# Create manifest file for tracking
print_info "Creating vendor manifest..."
cat > public/vendor/manifest.json << EOF
{
    "created": "$(date -u +%Y-%m-%dT%H:%M:%SZ)",
    "version": "1.0.0",
    "files": [
        {
            "name": "jquery",
            "version": "$(grep '"jquery"' package.json | head -1 | cut -d'"' -f4)",
            "files": ["jquery.min.js", "jquery.min.map"]
        },
        {
            "name": "datatables.net",
            "version": "$(grep '"datatables.net"' package.json | head -1 | cut -d'"' -f4)",
            "files": ["jquery.dataTables.min.js", "dataTables.dataTables.min.js", "jquery.dataTables.min.css"]
        },
        {
            "name": "sweetalert2",
            "version": "$(grep '"sweetalert2"' package.json | head -1 | cut -d'"' -f4)",
            "files": ["sweetalert2.min.js"]
        },
        {
            "name": "apexcharts",
            "version": "$(grep '"apexcharts"' package.json | head -1 | cut -d'"' -f4)",
            "files": ["apexcharts.min.js"]
        },
        {
            "name": "html5-qrcode",
            "version": "$(grep '"html5-qrcode"' package.json | head -1 | cut -d'"' -f4)",
            "files": ["html5-qrcode.min.js"]
        }
    ]
}
EOF
print_status "Vendor manifest created"

echo ""
print_status "Vendor assets setup completed!"
echo ""
print_info "Next steps:"
echo "1. Run 'npm run build' to compile your assets"
echo "2. Clear your browser cache"
echo "3. Test the application"
echo ""
print_warning "Note: Run this script after 'npm install' to update vendor files"