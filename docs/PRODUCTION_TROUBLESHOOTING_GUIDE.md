# Production Troubleshooting Guide

## 🚨 Masalah Utama: 500 Internal Server Error

Berdasarkan analisis kode, berikut adalah 5-7 kemungkinan sumber masalah 500 error di production:

### 1. **Missing Vendor Assets (Most Likely)**
**Problem:** Library JavaScript (jQuery, DataTables, SweetAlert2, dll) tidak ditemukan di `public/vendor/`
**Cause:** File vendor tidak di-copy dari node_modules ke public/vendor
**Symptoms:** 
- 500 error saat loading halaman yang menggunakan DataTables
- JavaScript errors di browser console
- Tampilan tidak lengkap atau tidak responsif

### 2. **Incorrect Environment Configuration**
**Problem:** Configuration environment tidak tepat untuk production
**Cause:** 
- `APP_DEBUG` masih `true` (security risk)
- `APP_ENV` bukan `production`
- Database connection salah
**Symptoms:**
- Error 500 di semua halaman
- Stack trace exposure (security issue)

### 3. **Database Connection Issues**
**Problem:** Koneksi database gagal atau migrasi belum dijalankan
**Cause:**
- Database credentials salah
- Migration pending
- Database server down
**Symptoms:**
- 500 error saat akses data
- Database connection timeout

### 4. **Asset Compilation Issues**
**Problem:** Assets tidak di-compile dengan benar untuk production
**Cause:**
- `npm run build` tidak dijalankan
- Vite configuration salah
- Cache tidak di-clear
**Symptoms:**
- CSS/JS 404 errors
- Tampilan broken

### 5. **Permission Issues**
**Problem:** File/folder permissions tidak tepat
**Cause:**
- Storage folder tidak writable
- Public folder permissions salah
**Symptoms:**
- Upload errors
- Log write failures
- Cache issues

### 6. **Memory/Resource Limits**
**Problem:** Server resources tidak cukup
**Cause:**
- PHP memory limit terlalu rendah
- Execution timeout
- OOM killer
**Symptoms:**
- Random 500 errors
- Slow responses

### 7. **Cache/Session Issues**
**Problem:** Cache atau session configuration bermasalah
**Cause:**
- Cache driver tidak tersedia
- Session configuration salah
- Redis connection issues
**Symptoms:**
- Login issues
- Intermittent errors

---

## 🔧 Solutions & Fixes

### Quick Fix Script
Jalankan script berikut untuk memperbaiki masalah yang paling umum:

```bash
# 1. Setup vendor assets (fixes missing JS libraries)
chmod +x scripts/setup-vendor-assets.sh
./scripts/setup-vendor-assets.sh

# 2. Update Vite configuration
php scripts/update-vite-config.php

# 3. Install dependencies
npm install
npm run build:prod

# 4. Clear and cache everything
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# 5. Run production troubleshooter
chmod +x scripts/production-troubleshoot.sh
./scripts/production-troubleshoot.sh
```

### Manual Fixes

#### 1. Fix Missing Vendor Assets
```bash
# Create vendor directory
mkdir -p public/vendor

# Copy jQuery
cp node_modules/jquery/dist/jquery.min.js public/vendor/
cp node_modules/jquery/dist/jquery.min.map public/vendor/

# Copy DataTables
cp node_modules/datatables.net/js/jquery.dataTables.min.js public/vendor/
cp node_modules/datatables.net-dt/js/dataTables.dataTables.min.js public/vendor/
cp node_modules/datatables.net-dt/css/jquery.dataTables.min.css public/vendor/

# Copy SweetAlert2
cp node_modules/sweetalert2/dist/sweetalert2.min.js public/vendor/

# Copy ApexCharts
cp node_modules/apexcharts/dist/apexcharts.min.js public/vendor/

# Copy html5-qrcode
cp node_modules/html5-qrcode/html5-qrcode.min.js public/vendor/

# Set permissions
chmod -R 755 public/vendor
```

#### 2. Fix Environment Configuration
```bash
# Copy production config
cp .env.production.example .env

# Generate application key
php artisan key:generate

# Edit .env file
nano .env
```

Pastikan setting berikut:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
LOG_CHANNEL=daily
LOG_LEVEL=warning
```

#### 3. Fix Database Issues
```bash
# Check database connection
php artisan tinker
> DB::connection()->getPdo();

# Run migrations
php artisan migrate --force

# Check migration status
php artisan migrate:status
```

#### 4. Fix Asset Compilation
```bash
# Install production dependencies
npm ci --production

# Build assets
npm run build

# Check build output
ls -la public/build/
```

#### 5. Fix Permissions
```bash
# Storage permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Public permissions
chmod -R 755 public

# Owner (adjust as needed)
chown -R www-data:www-data storage bootstrap/cache public
```

---

## 📊 DataTables Specific Issues

### Common DataTables Problems
1. **jQuery not loaded** - Pastikan jQuery di-load sebelum DataTables
2. **DataTables CSS missing** - Include CSS file
3. **Server-side processing errors** - Check route dan controller
4. **AJAX errors** - Check CSRF token dan headers

### DataTables Debugging
```javascript
// Add to browser console
$.fn.dataTable.ext.errMode = 'throw';

// Check if DataTables is loaded
console.log(typeof $.fn.DataTable);

// Check table initialization
$('#materialsTable').DataTable().ajax.reload();
```

### DataTables Server-Side Requirements
Pastikan route berikut ada:
```php
// routes/web.php
Route::get('/assets/materials/data', [AssetMaterialController::class, 'data'])
    ->name('assets.materials.data');
```

Controller method harus return JSON:
```php
public function data(Request $request)
{
    $materials = AssetMaterial::query();
    
    return DataTables::of($materials)
        ->addColumn('actions', function($material) {
            // Action buttons HTML
        })
        ->make(true);
}
```

---

## 🔍 Debugging Steps

### 1. Check Error Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx/Apache logs
tail -f /var/log/nginx/error.log
tail -f /var/log/apache2/error.log

# PHP logs
tail -f /var/log/php_errors.log
```

### 2. Check Health Endpoint
```bash
# Test health endpoint
curl -I http://yourdomain.com/health

# Check response
curl http://yourdomain.com/health | jq .
```

### 3. Check Network Requests
- Open browser developer tools
- Check Network tab
- Look for 404/500 errors
- Check JavaScript console for errors

### 4. Verify Assets
```bash
# Check if vendor files exist
ls -la public/vendor/

# Check if build files exist
ls -la public/build/

# Test asset URLs
curl -I http://yourdomain.com/vendor/jquery.min.js
curl -I http://yourdomain.com/build/assets/app.js
```

---

## 🚀 Production Deployment Checklist

### Pre-Deployment
- [ ] Copy `.env.production.example` to `.env`
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Configure database credentials
- [ ] Set up Redis for cache/queue
- [ ] Configure proper logging

### Dependencies
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `npm ci --production`
- [ ] Run `npm run build`
- [ ] Copy vendor assets to `public/vendor/`

### Database
- [ ] Run `php artisan migrate --force`
- [ ] Check `php artisan migrate:status`
- [ ] Seed production data if needed

### Cache & Optimization
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Clear all caches first

### Permissions
- [ ] `chmod -R 755 storage`
- [ ] `chmod -R 755 bootstrap/cache`
- [ ] `chmod -R 755 public`
- [ ] Set correct ownership

### Security
- [ ] Verify HTTPS is working
- [ ] Check SSL certificate
- [ ] Verify firewall rules
- [ ] Set up monitoring

### Post-Deployment
- [ ] Test all pages
- [ ] Test DataTables functionality
- [ ] Test CRUD operations
- [ ] Check error logs
- [ ] Set up monitoring alerts

---

## 📱 Monitoring & Maintenance

### Health Monitoring
```bash
# Add to crontab for every 5 minutes
*/5 * * * * /path/to/project/scripts/monitor-production.sh

# Daily health check
0 2 * * * /path/to/project/scripts/production-troubleshoot.sh
```

### Log Rotation
```bash
# Add to logrotate config
/path/to/project/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

### Performance Monitoring
- Monitor response times
- Check memory usage
- Track error rates
- Monitor database performance

---

## 🆘 Emergency Procedures

### If 500 Error Occurs
1. **Check logs immediately**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

2. **Quick health check**
   ```bash
   ./scripts/production-troubleshoot.sh
   ```

3. **Clear caches**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Check vendor assets**
   ```bash
   ./scripts/setup-vendor-assets.sh
   ```

5. **Restart services**
   ```bash
   sudo systemctl restart nginx
   sudo systemctl restart php-fpm
   ```

### Rollback Procedures
1. **Restore from backup**
2. **Revert to previous commit**
3. **Restore database backup**
4. **Clear all caches**

---

## 📞 Support

If issues persist:
1. Check this guide first
2. Review error logs
3. Run diagnostic scripts
4. Contact support with:
   - Error logs
   - Steps taken
   - Server environment details
   - Timeline of issues

---

## 🔄 Regular Maintenance

### Weekly Tasks
- Check error logs
- Update dependencies
- Backup database
- Check disk space

### Monthly Tasks
- Security updates
- Performance review
- Log cleanup
- SSL certificate check

### Quarterly Tasks
- Major dependency updates
- Security audit
- Performance optimization
- Disaster recovery test