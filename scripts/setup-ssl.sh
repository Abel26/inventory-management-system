#!/bin/bash

# SSL Setup Script for Inventory Management System
# This script helps setup SSL certificate for QR Scanner functionality

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration variables
DOMAIN=""
EMAIL=""
WEB_ROOT="/var/www/inventory-management-system/public"
NGINX_CONFIG="/etc/nginx/sites-available/inventory-management-ssl"

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to check if running as root
check_root() {
    if [[ $EUID -ne 0 ]]; then
        print_error "This script must be run as root (use sudo)"
        exit 1
    fi
}

# Function to get user input
get_user_input() {
    echo -e "${BLUE}=== SSL Setup Configuration ===${NC}"
    
    read -p "Enter your domain (e.g., app.ebara.com): " DOMAIN
    if [[ -z "$DOMAIN" ]]; then
        print_error "Domain is required"
        exit 1
    fi
    
    read -p "Enter your email for SSL certificate: " EMAIL
    if [[ -z "$EMAIL" ]]; then
        print_error "Email is required for SSL certificate"
        exit 1
    fi
    
    read -p "Enter web root path (default: $WEB_ROOT): " INPUT_WEB_ROOT
    if [[ -n "$INPUT_WEB_ROOT" ]]; then
        WEB_ROOT="$INPUT_WEB_ROOT"
    fi
    
    echo -e "\n${BLUE}Configuration Summary:${NC}"
    echo "Domain: $DOMAIN"
    echo "Email: $EMAIL"
    echo "Web Root: $WEB_ROOT"
    echo ""
    read -p "Continue with these settings? (y/n): " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_error "Setup cancelled"
        exit 1
    fi
}

# Function to install dependencies
install_dependencies() {
    print_status "Installing dependencies..."
    
    # Update package list
    apt update
    
    # Install Nginx if not already installed
    if ! command -v nginx &> /dev/null; then
        print_status "Installing Nginx..."
        apt install -y nginx
    fi
    
    # Install Certbot
    print_status "Installing Certbot..."
    apt install -y certbot python3-certbot-nginx
    
    print_success "Dependencies installed successfully"
}

# Function to create Nginx configuration
create_nginx_config() {
    print_status "Creating Nginx configuration..."
    
    # Create config file from template
    cat > "$NGINX_CONFIG" << EOF
# HTTP to HTTPS Redirect
server {
    listen 80;
    server_name $DOMAIN;
    return 301 https://\$server_name\$request_uri;
}

# HTTPS Main Configuration
server {
    listen 443 ssl http2;
    server_name $DOMAIN;

    # SSL Certificate Paths (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/$DOMAIN/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN/privkey.pem;

    # SSL Security Configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Laravel Application Configuration
    root $WEB_ROOT;
    index index.php index.html index.htm;

    # Logging
    access_log /var/log/nginx/inventory-access.log;
    error_log /var/log/nginx/inventory-error.log;

    # Main Location Block
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # PHP-FPM Configuration
    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
        fastcgi_read_timeout 300;
    }

    # Static Assets Optimization
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Security - Block access to sensitive files
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    location ~ /(\.env|\.git|composer\.json|composer\.lock|package\.json|webpack\.mix\.js)$ {
        deny all;
        access_log off;
        log_not_found off;
    }

    # Laravel Storage
    location /storage {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    # File Upload Limits
    client_max_body_size 50M;
    client_body_timeout 60s;
    client_header_timeout 60s;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types
        text/plain
        text/css
        text/xml
        text/javascript
        application/json
        application/javascript
        application/xml+rss
        application/atom+xml
        image/svg+xml;
}
EOF

    # Enable the site
    ln -sf "$NGINX_CONFIG" /etc/nginx/sites-enabled/
    
    # Remove default site if exists
    rm -f /etc/nginx/sites-enabled/default
    
    print_success "Nginx configuration created"
}

# Function to obtain SSL certificate
obtain_ssl() {
    print_status "Obtaining SSL certificate for $DOMAIN..."
    
    # Test Nginx configuration
    nginx -t
    
    # Restart Nginx to apply HTTP config
    systemctl restart nginx
    
    # Obtain SSL certificate
    certbot --nginx -d "$DOMAIN" --non-interactive --agree-tos --email "$EMAIL" --redirect
    
    print_success "SSL certificate obtained successfully"
}

# Function to setup auto-renewal
setup_auto_renewal() {
    print_status "Setting up SSL auto-renewal..."
    
    # Add cron job for auto-renewal
    (crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet") | crontab -
    
    print_success "Auto-renewal setup complete"
}

# Function to update Laravel configuration
update_laravel_config() {
    print_status "Updating Laravel configuration..."
    
    # Update .env file
    if [[ -f "$WEB_ROOT/../.env" ]]; then
        sed -i "s|^APP_URL=.*|APP_URL=https://$DOMAIN|g" "$WEB_ROOT/../.env"
        sed -i "s|^ASSET_URL=.*|ASSET_URL=https://$DOMAIN|g" "$WEB_ROOT/../.env"
        
        # Add FORCE_HTTPS if not exists
        if ! grep -q "FORCE_HTTPS" "$WEB_ROOT/../.env"; then
            echo "FORCE_HTTPS=true" >> "$WEB_ROOT/../.env"
        else
            sed -i "s|^FORCE_HTTPS=.*|FORCE_HTTPS=true|g" "$WEB_ROOT/../.env"
        fi
        
        print_success "Laravel .env updated"
    else
        print_warning ".env file not found at $WEB_ROOT/../.env"
        print_warning "Please manually update your Laravel configuration"
    fi
}

# Function to test SSL setup
test_ssl() {
    print_status "Testing SSL configuration..."
    
    # Test SSL certificate
    if openssl s_client -connect "$DOMAIN:443" -servername "$DOMAIN" < /dev/null > /dev/null 2>&1; then
        print_success "SSL certificate is working"
    else
        print_error "SSL certificate test failed"
        return 1
    fi
    
    # Test HTTP to HTTPS redirect
    if curl -I -L "http://$DOMAIN" 2>/dev/null | grep -q "https://$DOMAIN"; then
        print_success "HTTP to HTTPS redirect is working"
    else
        print_warning "HTTP to HTTPS redirect may not be working properly"
    fi
}

# Function to show next steps
show_next_steps() {
    echo ""
    echo -e "${GREEN}=== SSL Setup Complete! ===${NC}"
    echo ""
    echo -e "${BLUE}Next Steps:${NC}"
    echo "1. Test your application at: https://$DOMAIN"
    echo "2. Test the QR Scanner feature on mobile devices"
    echo "3. Update your DNS records if needed"
    echo "4. Monitor SSL certificate renewal logs: /var/log/letsencrypt/letsencrypt.log"
    echo ""
    echo -e "${BLUE}Useful Commands:${NC}"
    echo "- Test SSL certificate: openssl s_client -connect $DOMAIN:443"
    echo "- Check Nginx status: systemctl status nginx"
    echo "- View Nginx logs: tail -f /var/log/nginx/inventory-error.log"
    echo "- Renew SSL manually: certbot renew"
    echo ""
    echo -e "${GREEN}QR Scanner should now work on mobile devices!${NC}"
}

# Main execution
main() {
    echo -e "${BLUE}=== Inventory Management System SSL Setup ===${NC}"
    echo ""
    
    check_root
    get_user_input
    install_dependencies
    create_nginx_config
    obtain_ssl
    setup_auto_renewal
    update_laravel_config
    
    if test_ssl; then
        show_next_steps
    else
        print_error "SSL setup completed with errors. Please check the configuration."
        exit 1
    fi
}

# Run main function
main "$@"