<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi keamanan tambahan untuk aplikasi.
    | File ini menyimpan pengaturan keamanan yang tidak tersedia di file config lain.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    |
    | Pengaturan kebijakan password untuk aplikasi.
    | Nilai ini digunakan bersama dengan Password::defaults() di AppServiceProvider.
    |
    */
    'password' => [
        'min_length' => 12,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'check_compromised' => true, // Cek password di database kebocoran
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Pengaturan keamanan untuk file upload.
    | Nilai ini digunakan oleh SecureFileUploadService.
    |
    */
    'file_upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB in bytes
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'webp'],
        'blocked_extensions' => [
            'php', 'php5', 'php7', 'phtml', 'html', 'htm', 'svg',
            'js', 'exe', 'sh', 'pl', 'cgi', 'py', 'rb', 'jsp', 'asp'
        ],
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'application/pdf'
        ],
        'max_image_dimension' => 5000, // pixels
        'min_image_dimension' => 10, // pixels
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Pengaturan rate limiting untuk berbagai endpoint.
    | Format: 'max_attempts,decay_minutes'
    |
    */
    'rate_limiting' => [
        'login' => '5,1', // 5 attempts per minute
        'register' => '5,1', // 5 attempts per minute
        'password_reset' => '3,1', // 3 attempts per minute
        'api' => '60,1', // 60 requests per minute
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | Pengaturan Content Security Policy (CSP).
    | CSP membatasi sumber resource yang boleh dimuat oleh browser.
    |
    */
    'csp' => [
        'enabled' => env('CSP_ENABLED', true),
        'report_only' => env('CSP_REPORT_ONLY', false),
        'report_uri' => env('APP_URL') . '/csp-report',
        'directives' => [
            'default-src' => "'self'",
            'script-src' => "'self' 'unsafe-inline' 'unsafe-eval'",
            'style-src' => "'self' 'unsafe-inline'",
            'img-src' => "'self' data: https: 'unsafe-inline'",
            'font-src' => "'self'",
            'connect-src' => "'self'",
            'frame-ancestors' => "'none'",
            'base-uri' => "'self'",
            'form-action' => "'self'",
            'frame-src' => "'none'",
            'object-src' => "'none'",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Pengaturan CORS untuk production.
    | Batasi origins yang diizinkan untuk mencegah serangan CSRF.
    |
    */
    'cors' => [
        'allowed_origins' => env('ALLOWED_ORIGINS', ['*']),
        'supports_credentials' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Pengaturan security headers.
    | Headers ini ditambahkan oleh SecurityHeadersMiddleware.
    |
    */
    'headers' => [
        'x_frame_options' => 'DENY',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'same-origin',
        'permissions_policy' => 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()',
        'hsts' => [
            'enabled' => env('APP_ENV') === 'production',
            'max_age' => 31536000, // 1 year in seconds
            'include_subdomains' => true,
            'preload' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Pengaturan keamanan session.
    | Nilai ini harus konsisten dengan config/session.php.
    |
    */
    'session' => [
        'lifetime' => 120, // minutes
        'expire_on_close' => false,
        'encrypt' => true,
        'http_only' => true,
        'secure' => env('APP_ENV') === 'production',
        'same_site' => 'strict',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging & Monitoring
    |--------------------------------------------------------------------------
    |
    | Pengaturan logging untuk keamanan.
    | Log aktivitas mencurigakan untuk monitoring.
    |
    */
    'logging' => [
        'log_failed_attempts' => true,
        'log_file_uploads' => true,
        'log_suspicious_input' => true,
        'log_sql_injection_attempts' => true,
        'log_xss_attempts' => true,
        'log_judol_injection_attempts' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode
    |--------------------------------------------------------------------------
    |
    | Pengaturan maintenance mode.
    | Pastikan maintenance mode tidak mengekspos informasi sensitif.
    |
    */
    'maintenance' => [
        'secret' => env('MAINTENANCE_SECRET'), // Secret untuk bypass maintenance
        'retry_after' => 60, // seconds
    ],

];
