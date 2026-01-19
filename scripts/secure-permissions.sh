#!/bin/bash

# ============================================
# Secure Folder Permissions Script
# ============================================
# Script ini menyetel permission folder dan file
# untuk keamanan production environment.
# ============================================

echo "🔒 Setting secure folder permissions..."

# Set permissions untuk direktori (755)
echo "📁 Setting directory permissions to 755..."
find /path/to/project -type d -exec chmod 755 {} \;

# Set permissions untuk file (644)
echo "📄 Setting file permissions to 644..."
find /path/to/project -type f -exec chmod 644 {} \;

# Set permissions untuk storage dan cache (775)
echo "📦 Setting storage and cache permissions to 775..."
chmod -R 775 storage bootstrap/cache

# Set permissions untuk public/index.php (644 - not writable by web server)
echo "🔒 Setting public/index.php permissions to 644..."
chmod 644 public/index.php

# Set permissions untuk .env (600 - only readable by owner)
echo "🔐 Setting .env permissions to 600..."
chmod 600 .env

# Disable directory listing di .htaccess
echo "🚫 Disabling directory listing..."
if [ -f "public/.htaccess" ]; then
    if ! grep -q "Options -Indexes" "public/.htaccess"; then
        echo "Options -Indexes" >> public/.htaccess
        echo "✅ Added Options -Indexes to public/.htaccess"
    fi
fi

# Remove sensitive files from public
echo "🗑️  Removing sensitive files from public directory..."
rm -f public/.env
rm -f public/.git
rm -f public/composer.json
rm -f public/composer.lock
rm -f public/package.json
rm -f public/package-lock.json

# Clear caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Secure permissions set successfully!"
echo ""
echo "⚠️  IMPORTANT SECURITY NOTES:"
echo "1. Update /path/to/project with actual project path"
echo "2. Ensure web server user has appropriate permissions"
echo "3. Verify .env file is not accessible from web"
echo "4. Ensure HTTPS is enabled on the server"
echo "5. Run 'php artisan key:generate' for new APP_KEY"
echo "6. Set APP_DEBUG=false in .env"
echo "7. Review and update .env.production.example values"
