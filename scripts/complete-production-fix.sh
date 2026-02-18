#!/bin/bash

echo "=========================================="
echo "COMPLETE PRODUCTION FIX SCRIPT"
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

# Step 1: Fix build errors
print_info "Step 1: Fixing build errors..."
chmod +x scripts/fix-build-error.sh
./scripts/fix-build-error.sh
echo ""

# Step 2: Fix migration conflicts
print_info "Step 2: Fixing migration conflicts..."
chmod +x scripts/fix-migration-conflict.sh
./scripts/fix-migration-conflict.sh
echo ""

# Step 3: Ensure vendor assets are in place
print_info "Step 3: Ensuring vendor assets are in place..."
if [ ! -d "public/vendor" ] || [ ! -f "public/vendor/jquery.min.js" ]; then
    ./scripts/setup-vendor-assets.sh
else
    print_status "Vendor assets already in place"
fi
echo ""

# Step 4: Clear and rebuild all caches
print_info "Step 4: Clearing and rebuilding caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
print_status "All caches cleared and rebuilt"
echo ""

# Step 5: Verify critical files exist
print_info "Step 5: Verifying critical files..."

CRITICAL_FILES=(
    "public/vendor/jquery.min.js"
    "public/vendor/jquery.dataTables.min.js"
    "public/vendor/sweetalert2.min.js"
    "public/vendor/apexcharts.min.js"
    "public/vendor/html5-qrcode.min.js"
    "public/build/assets/app.js"
    "public/build/assets/app.css"
)

ALL_FILES_EXIST=true
for file in "${CRITICAL_FILES[@]}"; do
    if [ -f "$file" ]; then
        print_status "✓ $file"
    else
        print_error "✗ $file is missing!"
        ALL_FILES_EXIST=false
    fi
done

if [ "$ALL_FILES_EXIST" = false ]; then
    print_error "Some critical files are missing!"
    exit 1
fi
echo ""

# Step 6: Test application health
print_info "Step 6: Testing application health..."

# Test if Laravel is responding
if php artisan tinker --execute="echo 'Laravel OK';" > /dev/null 2>&1; then
    print_status "✓ Laravel framework is responsive"
else
    print_error "✗ Laravel framework has issues"
fi

# Test database connection
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';" > /dev/null 2>&1; then
    print_status "✓ Database connection is working"
else
    print_error "✗ Database connection failed"
fi

# Test cache
if php artisan tinker --execute="Cache::put('test', 'ok', 60); echo Cache::get('test');" > /dev/null 2>&1; then
    print_status "✓ Cache system is working"
else
    print_error "✗ Cache system has issues"
fi
echo ""

# Step 7: Set proper permissions
print_info "Step 7: Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public
chmod -R 755 public/vendor
print_status "Permissions set correctly"
echo ""

# Step 8: Restart services if needed
print_info "Step 8: Checking if services need restart..."
if command -v systemctl > /dev/null; then
    print_info "Restarting PHP-FPM..."
    systemctl restart php-fpm 2>/dev/null || print_warning "Could not restart PHP-FPM"
    
    print_info "Restarting Nginx..."
    systemctl restart nginx 2>/dev/null || print_warning "Could not restart Nginx"
    
    print_status "Services restarted"
else
    print_warning "systemctl not available, skipping service restart"
fi
echo ""

# Step 9: Final verification
print_info "Step 9: Final verification..."

# Check if the site is accessible
if command -v curl > /dev/null; then
    HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost 2>/dev/null)
    if [ "$HTTP_STATUS" = "200" ]; then
        print_status "✓ Site is accessible (HTTP 200)"
    elif [ "$HTTP_STATUS" = "500" ]; then
        print_error "✗ Site still returning 500 error"
    elif [ -n "$HTTP_STATUS" ]; then
        print_warning "⚠ Site returning HTTP $HTTP_STATUS"
    else
        print_warning "⚠ Could not test site accessibility"
    fi
else
    print_warning "⚠ curl not available, skipping HTTP test"
fi

# Check error log for recent errors
if [ -f "storage/logs/laravel.log" ]; then
    RECENT_ERRORS=$(tail -20 storage/logs/laravel.log | grep -c "ERROR" 2>/dev/null)
    if [ "$RECENT_ERRORS" -gt 0 ]; then
        print_warning "⚠ Found $RECENT_ERRORS recent errors in log"
    else
        print_status "✓ No recent errors in log"
    fi
fi
echo ""

print_status "Complete production fix finished!"
echo ""
print_info "Next steps:"
echo "1. Test the application in browser"
echo "2. Check all DataTables functionality"
echo "3. Test CRUD operations"
echo "4. Monitor error logs: tail -f storage/logs/laravel.log"
echo "5. Set up monitoring: ./scripts/monitor-production.sh"
echo ""
print_info "If issues persist:"
echo "- Check error logs: tail -50 storage/logs/laravel.log"
echo "- Check web server logs: tail -50 /var/log/nginx/error.log"
echo "- Run health check: curl http://yourdomain.com/health"
echo ""
print_status "Production fix completed successfully!"