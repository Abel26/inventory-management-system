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

        // Add Content-Security-Policy header (basic)
        // CSP memblokir script dari domain yang tidak di-whitelist
        // Ini sangat penting untuk mencegah "Judol" scripts
        $this->addContentSecurityPolicy($response);

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
     */
    private function addContentSecurityPolicy($response): void
    {
        // Basic CSP policy
        // default-src 'self': Hanya resource dari domain sendiri
        // script-src 'self' 'unsafe-inline' 'unsafe-eval': Script hanya dari domain sendiri
        // style-src 'self' 'unsafe-inline': Style hanya dari domain sendiri
        // img-src 'self' data: https: 'unsafe-inline': Gambar dari domain sendiri dan data URI
        // font-src 'self': Font hanya dari domain sendiri
        // connect-src 'self': Request hanya ke domain sendiri
        // frame-ancestors 'none': Tidak boleh di-embed di iframe
        // base-uri 'self': Base URL hanya dari domain sendiri
        // form-action 'self': Form hanya submit ke domain sendiri
        // frame-src 'none': Tidak boleh ada iframe
        $cspPolicy = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'", // unsafe-inline untuk Alpine.js/Vue.js
            "style-src 'self' 'unsafe-inline'", // unsafe-inline untuk Tailwind CSS
            "img-src 'self' data: https: 'unsafe-inline'",
            "font-src 'self'",
            "connect-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-src 'none'",
            "object-src 'none'",
            "report-uri " . config('app.url') . '/csp-report', // CSP violation report endpoint
        ]);

        $response->headers->set('Content-Security-Policy', $cspPolicy);
    }
}
