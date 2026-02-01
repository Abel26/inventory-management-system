# Panduan Setup HTTPS untuk Fitur QR Scanner

## Masalah Utama

Fitur "Scan QR Code" pada aplikasi Inventory Management System tidak berfungsi pada koneksi HTTP karena pembatasan keamanan browser modern. Browser seperti Chrome, Safari, dan Firefox secara ketat memblokir akses kamera (`navigator.mediaDevices.getUserMedia`) pada origins yang tidak aman.

## Mengapa HTTPS Diperlukan?

1. **Keamanan Privasi**: Browser melindungi privasi pengguna dengan mencegah akses kamera pada koneksi tidak aman
2. **Persyaratan WebRTC**: API yang digunakan untuk akses kamera merupakan bagian dari WebRTC yang memerlukan secure context
3. **Kebijakan Browser**: Semua browser modern telah mengimplementasikan pembatasan ini sejak 2018

## Solusi Teknis

### Langkah 1: Setup Domain

Server saat ini menggunakan IP langsung (`147.93.81.28`) yang tidak dapat digunakan untuk SSL certificate. Anda perlu:

1. **Daftarkan Domain** (misal: `app.ebara.com` atau `inventory.ebara.com`)
2. **Pointing DNS** ke IP server (`147.93.81.28`)
3. **Tunggu Propagasi DNS** (biasanya 24-48 jam)

### Langkah 2: Install SSL Certificate

#### Opsi A: Let's Encrypt (Gratis)

```bash
# Install Certbot
sudo apt update
sudo apt install certbot python3-certbot-nginx

# Generate SSL Certificate
sudo certbot --nginx -d app.ebara.com

# Setup Auto-renewal
sudo crontab -e
# Tambahkan baris ini:
# 0 12 * * * /usr/bin/certbot renew --quiet
```

#### Opsi B: Self-Signed Certificate (Development)

```bash
# Generate private key
sudo openssl genrsa -out /etc/ssl/private/ebara-selfsigned.key 2048

# Generate certificate
sudo openssl req -new -x509 -key /etc/ssl/private/ebara-selfsigned.key -out /etc/ssl/certs/ebara-selfsigned.crt -days 365
```

### Langkah 3: Konfigurasi Nginx

Edit file konfigurasi Nginx:

```nginx
server {
    listen 80;
    server_name app.ebara.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name app.ebara.com;

    ssl_certificate /etc/letsencrypt/live/app.ebara.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/app.ebara.com/privkey.pem;

    # SSL Configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;

    # Laravel Application
    root /var/www/inventory-management-system/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
}
```

### Langkah 4: Update Laravel Configuration

Edit file `.env`:

```env
APP_URL=https://app.ebara.com
ASSET_URL=https://app.ebara.com

# Force HTTPS
FORCE_HTTPS=true
```

Edit file `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\URL;

public function boot()
{
    if (env('FORCE_HTTPS')) {
        URL::forceScheme('https');
    }
}
```

## Testing HTTPS Setup

1. **Buka Browser**: Akses `https://app.ebara.com`
2. **Check Certificate**: Pastikan ikon gembok terlihat di address bar
3. **Test QR Scanner**: Buka halaman scan dan pastikan kamera dapat diakses
4. **Mobile Testing**: Test pada berbagai perangkat mobile (Android/iOS)

## Troubleshooting

### Masalah: Certificate Error

**Symptom**: Browser menampilkan "Your connection is not private"

**Solution**:
- Pastikan certificate sudah terinstall dengan benar
- Check chain certificate completeness
- Restart Nginx: `sudo systemctl restart nginx`

### Masalah: Mixed Content

**Symptom**: Ikonya gembok terlihat tetapi ada warning mixed content

**Solution**:
- Pastikan semua assets menggunakan HTTPS
- Check hard-coded HTTP URLs di kode
- Gunakan helper `asset()` atau `url()` di Laravel

### Masalah: Camera Still Blocked

**Symptom**: HTTPS sudah aktif tetapi kamera masih tidak berfungsi

**Solution**:
- Clear browser cache dan cookies
- Test di incognito/private mode
- Check browser permissions untuk kamera
- Pastikan tidak ada CSP (Content Security Policy) yang memblokir

## Alternatif Sementara

Jika setup HTTPS tidak memungkinkan segera, pertimbangkan:

1. **Progressive Web App (PWA)**: Install sebagai app di mobile
2. **Native App**: Develop mobile app menggunakan React Native/Flutter
3. **Cloudflare**: Gunakan Cloudflare SSL (gratis) untuk proxy HTTPS

## Checklist Final

- [ ] Domain sudah pointing ke IP server
- [ ] SSL certificate sudah terinstall
- [ ] Nginx sudah dikonfigurasi untuk HTTPS
- [ ] Laravel APP_URL menggunakan HTTPS
- [ ] Force HTTPS sudah diaktifkan
- [ ] QR scanner berfungsi di mobile
- [ ] Tidak ada mixed content warnings
- [ ] Auto-renewal SSL sudah di-setup

## Kontak Support

Jika mengalami kesulitan dalam setup HTTPS, hubungi tim DevOps dengan menyertakan:

1. Screenshot error browser
2. Nginx error logs: `sudo tail -f /var/log/nginx/error.log`
3. Laravel logs: `tail -f storage/logs/laravel.log`
4. Hasil dari `openssl s_client -connect app.ebara.com:443`

---

**Catatan Penting**: Tanpa HTTPS yang dikonfigurasi dengan benar, fitur QR scanner tidak akan pernah berfungsi pada browser modern, terutama pada perangkat mobile. Ini bukan bug aplikasi, tetapi persyaratan keamanan dari browser itu sendiri.