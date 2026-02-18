#!/bin/bash

# Production Troubleshooting Script for Inventory Management System
# This script helps diagnose and fix common production issues

echo "=========================================="
echo "PRODUCTION TROUBLESHOOTING SCRIPT"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
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

# Check if running in production environment
check_environment() {
    print_info "Checking environment configuration..."
    
    if [ ! -f ".env" ]; then
        print_error ".env file not found!"
        print_info "Please copy .env.production.example to .env and configure it"
        exit 1
    fi
    
    # Check APP_ENV
    if grep -q "APP_ENV=production" .env; then
        print_status "APP_ENV is set to production"
    else
        print_warning "APP_ENV is not set to production"
    fi
    
    # Check APP_DEBUG
    if grep -q "APP_DEBUG=false" .env; then
        print_status "APP_DEBUG is set to false"
    else
        print_error "APP_DEBUG is not set to false - this is a security risk!"
    fi
}

# Check vendor dependencies
check_dependencies() {
    print_info "Checking vendor dependencies..."
    
    if [ ! -d "vendor" ]; then
        print_error "vendor directory not found!"
        print_info "Running composer install..."
        composer install --optimize-autoloader --no-dev
    else
        print_status "vendor directory exists"
    fi
    
    if [ ! -d "node_modules" ]; then
        print_error "node_modules not found!"
        print_info "Running npm install..."
        npm ci --production
    else
        print_status "node_modules exists"
    fi
}

# Check compiled assets
check_assets() {
    print_info "Checking compiled assets..."
    
    if [ ! -d "public/build" ]; then
        print_error "Compiled assets not found!"
        print_info "Running npm run build..."
        npm run build
    else
        print_status "Compiled assets exist"
    fi
    
    # Check vendor assets
    if [ ! -d "public/vendor" ]; then
        print_error "Vendor assets directory not found!"
        print_info "Creating vendor assets directory..."
        mkdir -p public/vendor
        
        # Copy jQuery
        print_info "Copying jQuery..."
        cp node_modules/jquery/dist/jquery.min.js public/vendor/
        cp node_modules/jquery/dist/jquery.min.map public/vendor/
        
        # Copy DataTables
        print_info "Copying DataTables..."
        cp node_modules/datatables.net/js/jquery.dataTables.min.js public/vendor/
        cp node_modules/datatables.net-dt/js/dataTables.dataTables.min.js public/vendor/
        cp node_modules/datatables.net-dt/css/jquery.dataTables.min.css public/vendor/
        
        # Copy SweetAlert2
        print_info "Copying SweetAlert2..."
        cp node_modules/sweetalert2/dist/sweetalert2.min.js public/vendor/
        
        # Copy ApexCharts
        print_info "Copying ApexCharts..."
        cp node_modules/apexcharts/dist/apexcharts.min.js public/vendor/
        
        # Copy html5-qrcode
        print_info "Copying html5-qrcode..."
        cp node_modules/html5-qrcode/html5-qrcode.min.js public/vendor/
        
        print_status "Vendor assets copied successfully"
    else
        print_status "Vendor assets directory exists"
    fi
}

# Check database configuration
check_database() {
    print_info "Checking database configuration..."
    
    # Check if database is configured
    if ! grep -q "DB_DATABASE=" .env; then
        print_error "Database not configured in .env"
        return
    fi
    
    # Test database connection
    php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection: OK';" 2>/dev/null
    if [ $? -eq 0 ]; then
        print_status "Database connection is working"
    else
        print_error "Database connection failed"
    fi
    
    # Check if migrations are up to date
    php artisan migrate:status 2>/dev/null | grep -q "Pending"
    if [ $? -eq 0 ]; then
        print_warning "There are pending migrations"
        print_info "Running migrations..."
        php artisan migrate --force
    else
        print_status "All migrations are up to date"
    fi
}

# Check cache and config
check_cache() {
    print_info "Checking cache and configuration..."
    
    # Clear and cache config
    print_info "Clearing and caching configuration..."
    php artisan config:clear
    php artisan config:cache
    
    # Clear and cache routes
    print_info "Clearing and caching routes..."
    php artisan route:clear
    php artisan route:cache
    
    # Clear and cache views
    print_info "Clearing and caching views..."
    php artisan view:clear
    php artisan view:cache
    
    print_status "Cache and configuration optimized"
}

# Check file permissions
check_permissions() {
    print_info "Checking file permissions..."
    
    # Storage directory
    if [ -d "storage" ]; then
        chmod -R 755 storage
        print_status "Storage permissions set to 755"
    fi
    
    # Bootstrap cache
    if [ -d "bootstrap/cache" ]; then
        chmod -R 755 bootstrap/cache
        print_status "Bootstrap cache permissions set to 755"
    fi
    
    # Public directory
    if [ -d "public" ]; then
        chmod -R 755 public
        print_status "Public directory permissions set to 755"
    fi
}

# Check logging
check_logging() {
    print_info "Checking logging configuration..."
    
    # Create logs directory if it doesn't exist
    if [ ! -d "storage/logs" ]; then
        mkdir -p storage/logs
        print_status "Created logs directory"
    fi
    
    # Set proper permissions for logs
    chmod -R 755 storage/logs
    print_status "Log permissions set to 755"
    
    # Check if log channel is configured for production
    if grep -q "LOG_CHANNEL=daily" .env; then
        print_status "Log channel is set to daily"
    else
        print_warning "Consider setting LOG_CHANNEL=daily for production"
    fi
}

# Create production health check endpoint
create_health_check() {
    print_info "Creating production health check..."
    
    cat > routes/health.php << 'EOF'
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

Route::get('/health', function () {
    $status = 'healthy';
    $checks = [];
    
    // Database check
    try {
        DB::connection()->getPdo();
        $checks['database'] = 'ok';
    } catch (\Exception $e) {
        $checks['database'] = 'error: ' . $e->getMessage();
        $status = 'unhealthy';
    }
    
    // Cache check
    try {
        Cache::put('health_check', 'ok', 60);
        $cacheResult = Cache::get('health_check');
        $checks['cache'] = $cacheResult === 'ok' ? 'ok' : 'error';
        if ($cacheResult !== 'ok') $status = 'unhealthy';
    } catch (\Exception $e) {
        $checks['cache'] = 'error: ' . $e->getMessage();
        $status = 'unhealthy';
    }
    
    // Storage check
    try {
        $testFile = 'health_' . time() . '.txt';
        \Storage::disk('public')->put($testFile, 'test');
        \Storage::disk('public')->delete($testFile);
        $checks['storage'] = 'ok';
    } catch (\Exception $e) {
        $checks['storage'] = 'error: ' . $e->getMessage();
        $status = 'unhealthy';
    }
    
    return response()->json([
        'status' => $status,
        'timestamp' => now()->toISOString(),
        'checks' => $checks
    ], $status === 'healthy' ? 200 : 503);
});
EOF
    
    # Add health route to web.php
    if ! grep -q "require_once" routes/web.php; then
        echo "" >> routes/web.php
        echo "// Health check endpoint" >> routes/web.php
        echo "require_once __DIR__.'/health.php';" >> routes/web.php
        print_status "Health check endpoint added"
    fi
}

# Create production monitoring script
create_monitoring() {
    print_info "Creating production monitoring script..."
    
    cat > scripts/monitor-production.sh << 'EOF'
#!/bin/bash

# Production Monitoring Script
# Run this script regularly to check production health

LOG_FILE="storage/logs/monitor.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

echo "[$DATE] Starting production health check..." >> $LOG_FILE

# Check if application is responding
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health 2>/dev/null)
if [ "$HTTP_STATUS" = "200" ]; then
    echo "[$DATE] ✓ Application is healthy (HTTP $HTTP_STATUS)" >> $LOG_FILE
else
    echo "[$DATE] ✗ Application is unhealthy (HTTP $HTTP_STATUS)" >> $LOG_FILE
    # Send alert here (email, Slack, etc.)
fi

# Check disk space
DISK_USAGE=$(df / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "[$DATE] ⚠ Disk usage is high: ${DISK_USAGE}%" >> $LOG_FILE
fi

# Check memory usage
MEM_USAGE=$(free | awk 'NR==2{printf "%.0f", $3*100/$2}')
if [ $MEM_USAGE -gt 80 ]; then
    echo "[$DATE] ⚠ Memory usage is high: ${MEM_USAGE}%" >> $LOG_FILE
fi

# Check recent errors in Laravel log
ERROR_COUNT=$(tail -100 storage/logs/laravel.log | grep -c "ERROR" 2>/dev/null)
if [ $ERROR_COUNT -gt 5 ]; then
    echo "[$DATE] ⚠ High error count in logs: $ERROR_COUNT errors in last 100 lines" >> $LOG_FILE
fi

echo "[$DATE] Health check completed" >> $LOG_FILE
echo "" >> $LOG_FILE
EOF
    
    chmod +x scripts/monitor-production.sh
    print_status "Production monitoring script created"
}

# Main execution
main() {
    echo "Starting production troubleshooting..."
    echo ""
    
    check_environment
    echo ""
    
    check_dependencies
    echo ""
    
    check_assets
    echo ""
    
    check_database
    echo ""
    
    check_cache
    echo ""
    
    check_permissions
    echo ""
    
    check_logging
    echo ""
    
    create_health_check
    echo ""
    
    create_monitoring
    echo ""
    
    print_status "Production troubleshooting completed!"
    echo ""
    print_info "Next steps:"
    echo "1. Review the output above for any errors"
    echo "2. Test the application in browser"
    echo "3. Check the health endpoint: /health"
    echo "4. Set up monitoring: ./scripts/monitor-production.sh"
    echo "5. Consider setting up a cron job for monitoring"
    echo ""
    print_info "Common fixes applied:"
    echo "✓ Dependencies installed and optimized"
    echo "✓ Assets compiled and vendor files copied"
    echo "✓ Database migrations checked"
    echo "✓ Cache and configuration optimized"
    echo "✓ File permissions corrected"
    echo "✓ Health check endpoint created"
    echo "✓ Monitoring script created"
}

# Run main function
main