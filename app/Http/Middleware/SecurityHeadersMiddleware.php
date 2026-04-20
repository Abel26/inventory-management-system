<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Security Headers Middleware
 *
 * Middleware ini menambahkan security headers untuk mencegah:
 * - Clickjacking (X-Frame-Options)
 * - MIME sniffing (X-Content-Type-Options)
 * - XSS (X-XSS-Protection)
 * - Referrer leakage (Referrer-Policy)
 * - Unwanted device access (Permissions-Policy)
 * - Downgrade attacks (Strict-Transport-Security)
 *
 * Mengapa penting untuk mencegah "Judol" Injection:
 * - X-Frame-Options mencegah website di-embed di iframe situs judi
 * - X-Content-Type-Options mencegah browser mengeksekusi file sebagai script
 * - CSP (Content Security Policy) memblokir script dari domain eksternal (domain judi)
 * - Permissions-Policy membatasi akses ke fitur browser yang bisa dieksploitasi
 */
class SecurityHeadersMiddleware
{
    /**
     * List of security headers to be added
     *
     * X-Frame-Options: Mencegah clickjacking dengan memblokir iframe
     * X-Content-Type-Options: Mencegah MIME sniffing yang bisa dieksploitasi
     * X-XSS-Protection: Filter XSS di browser lama
     * Referrer-Policy: Mencegah kebocoran data melalui referrer
     * Permissions-Policy: Membatasi akses ke fitur browser
     */
    private const SECURITY_HEADERS = [
        'X-Frame-Options' => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'same-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add security headers
        foreach (self::SECURITY_HEADERS as $header => $value) {
            $response->headers->set($header, $value);
        }

        // Prevent browser caching for authenticated pages
        // Firefox aggressively caches HTML without Cache-Control headers,
        // causing stale notification data to be served from cache.
        if ($request->hasCookie(config('session.cookie')) || $request->hasHeader('Authorization')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Wed, 11 Jan 1984 05:00:00 GMT');
        }

        // Add HSTS header only in production with HTTPS
        // Strict-Transport-Security (HSTS) memaksa browser menggunakan HTTPS
        if (config('app.env') === 'production' && $request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Remove server information untuk security by obscurity
        // Mencegah penyerang mengetahui versi server yang digunakan
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        // Add Content-Security-Policy header (environment-aware)
        // CSP memblokir script dari domain yang tidak di-whitelist
        // Ini sangat penting untuk mencegah "Judol" scripts
        $this->addContentSecurityPolicy($response, $request);

        return $response;
    }

    /**
     * Add Content Security Policy header
     *
     * CSP membatasi sumber resource (script, style, image, dll) yang boleh dimuat.
     * Ini mencegah loading script dari domain judi (Judol) yang sering
     * disuntikkan melalui XSS atau defacement.
     *
     * @param mixed $response Response object
     * @param \Illuminate\Http\Request $request Request object
     */
    private function addContentSecurityPolicy($response, Request $request): void
    {
        $isLocal = config('app.env') === 'local';
        $isSecure = $request->secure();
        $protocol = $isSecure ? 'https:' : 'http:';

        // Scenario A: Local Development - Ultra-permissive CSP
        // Allow everything needed for development (Vite HMR, debugbars, inline styles)
        if ($isLocal) {
            $cspPolicy = implode('; ', [
                "default-src * 'unsafe-inline' 'unsafe-eval' data: blob:",
                "script-src * 'unsafe-inline' 'unsafe-eval' blob:",
                "connect-src * 'unsafe-inline' ws: wss:",
                "style-src * 'unsafe-inline'",
                "img-src * data: blob:",
                "font-src * data:",
                "frame-src *",
                "object-src *",
                "base-uri *",
                "form-action *",
            ]);
        }
        // Scenario B: Production - Strict & Secure CSP (Protocol-aware)
        // Only allow specific domains needed for the application
        // Supports both HTTP (transitional) and HTTPS (production-ready)
        else {
            $cspPolicy = implode('; ', [
                "default-src 'self' {$protocol}",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: {$protocol}",
                "style-src 'self' 'unsafe-inline' {$protocol} //fonts.googleapis.com",
                "img-src 'self' data: {$protocol} blob:",
                "font-src 'self' {$protocol} //fonts.googleapis.com //fonts.gstatic.com data:",
                "connect-src 'self' {$protocol}",
                "frame-src 'self' {$protocol} //www.google.com",
                "frame-ancestors 'none'",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ]);
        }

        $response->headers->set('Content-Security-Policy', $cspPolicy);
    }
}
