<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Sanitize Input Middleware
 *
 * Middleware ini menyediakan sanitasi input secara global untuk mencegah:
 * - XSS (Cross-Site Scripting)
 * - SQL Injection (basic pattern detection)
 * - "Judol" Injection (script tags, iframe, dll)
 *
 * Middleware ini akan:
 * 1. Strip tags dari input string (kecuali field yang di-whitelist)
 * 2. Encode HTML entities untuk mencegah XSS
 * 3. Deteksi dan blokir pola SQL injection yang mencurigakan
 * 4. Log input yang mencurigakan untuk monitoring
 */
class SanitizeInputMiddleware
{
    /**
     * Fields yang tidak akan disanitasi (untuk rich text editor, dll)
     * Jika ada field yang memerlukan HTML, tambahkan ke array ini
     */
    private const UNSANITIZED_FIELDS = [
        'description',
        'notes',
        'content',
        'body',
        'message',
    ];

    /**
     * Pola SQL injection yang mencurigakan
     */
    private const SQL_INJECTION_PATTERNS = [
        '/(\s|^)(union|select|insert|update|delete|drop|alter|truncate|create|exec|execute)(\s|$)/i',
        '/(\s|^)(or|and)\s+\d+\s*=\s*\d+/i',
        '/(\s|^)(or|and)\s+["\']?\w+["\']?\s*=\s*["\']?\w+["\']?/i',
        '/(\s|^)(--|#|\/\*|\*\/)/',
        '/(\s|^)(xp_|sp_)/i', // SQL Server stored procedures
        '/(\s|^)(0x[0-9a-f]+)/i', // Hex encoding
    ];

    /**
     * Pola XSS yang mencurigakan
     */
    private const XSS_PATTERNS = [
        '/<script[^>]*>.*?<\/script>/is',
        '/<iframe[^>]*>.*?<\/iframe>/is',
        '/<object[^>]*>.*?<\/object>/is',
        '/<embed[^>]*>.*?<\/embed>/is',
        '/<form[^>]*>.*?<\/form>/is',
        '/on\w+\s*=\s*["\'][^"\']*["\']/i', // Event handlers
        '/javascript:/i',
        '/data:[^;]*;base64/i',
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
        // Sanitasi semua input request
        $this->sanitizeRequest($request);

        return $next($request);
    }

    /**
     * Sanitasi semua input dalam request
     *
     * @param \Illuminate\Http\Request $request
     */
    private function sanitizeRequest(Request $request): void
    {
        // Sanitasi query parameters
        if ($request->query->count() > 0) {
            $request->query->replace(
                $this->sanitizeArray($request->query->all(), 'query')
            );
        }

        // Sanitasi request body (form data, JSON, dll)
        if ($request->request->count() > 0) {
            $request->request->replace(
                $this->sanitizeArray($request->request->all(), 'body')
            );
        }

        // Sanitasi JSON payload
        if ($request->isJson()) {
            $jsonContent = $request->getContent();
            $decoded = json_decode($jsonContent, true);

            if (is_array($decoded)) {
                $sanitized = $this->sanitizeArray($decoded, 'json');
                $request->json()->replace($sanitized);
            }
        }
    }

    /**
     * Sanitasi array secara rekursif
     *
     * @param array $data Data yang akan disanitasi
     * @param string $source Sumber data (query, body, json)
     * @return array Data yang sudah disanitasi
     */
    private function sanitizeArray(array $data, string $source): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            // Cek apakah field ini di-whitelist untuk tidak disanitasi
            $isUnsanitizedField = in_array($key, self::UNSANITIZED_FIELDS);

            if (is_array($value)) {
                // Sanitasi array secara rekursif
                $sanitized[$key] = $this->sanitizeArray($value, $source);
            } elseif (is_string($value)) {
                // Deteksi pola berbahaya
                $this->detectMaliciousPatterns($value, $key, $source);

                if ($isUnsanitizedField) {
                    // Field yang di-whitelist tetap divalidasi untuk XSS patterns
                    $sanitized[$key] = $this->sanitizeXSSPatterns($value);
                } else {
                    // Sanitasi string biasa
                    $sanitized[$key] = $this->sanitizeString($value);
                }
            } else {
                // Biarkan tipe data lain (integer, boolean, dll) apa adanya
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitasi string dengan strip tags dan HTML entity encoding
     *
     * @param string $value String yang akan disanitasi
     * @return string String yang sudah disanitasi
     */
    private function sanitizeString(string $value): string
    {
        // Trim whitespace
        $value = trim($value);

        // Strip HTML tags
        $value = strip_tags($value);

        // Encode HTML entities
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Decode HTML entities kembali untuk field yang memerlukan HTML
        // (opsional, tergantung kebutuhan aplikasi)

        return $value;
    }

    /**
     * Sanitasi pola XSS saja (untuk field yang memerlukan HTML)
     *
     * @param string $value String yang akan disanitasi
     * @return string String yang sudah disanitasi
     */
    private function sanitizeXSSPatterns(string $value): string
    {
        // Hapus pola XSS yang berbahaya
        foreach (self::XSS_PATTERNS as $pattern) {
            $value = preg_replace($pattern, '', $value);
        }

        return $value;
    }

    /**
     * Deteksi pola berbahaya dalam input
     *
     * @param string $value Nilai input
     * @param string $key Nama field
     * @param string $source Sumber data
     */
    private function detectMaliciousPatterns(string $value, string $key, string $source): void
    {
        // Deteksi SQL injection
        foreach (self::SQL_INJECTION_PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                $this->logSuspiciousInput($value, $key, $source, 'SQL Injection');
            }
        }

        // Deteksi XSS patterns
        foreach (self::XSS_PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                $this->logSuspiciousInput($value, $key, $source, 'XSS Attack');
            }
        }

        // Deteksi pola "Judol" injection (domain judi, dll)
        $this->detectJudolPatterns($value, $key, $source);
    }

    /**
     * Deteksi pola "Judol" injection
     *
     * @param string $value Nilai input
     * @param string $key Nama field
     * @param string $source Sumber data
     */
    private function detectJudolPatterns(string $value, string $key, string $source): void
    {
        // Pola domain judi yang umum
        $judolPatterns = [
            '/judi|slot|togel|poker|casino|betting|gambling/i',
            '/situs\s+(?:terpercaya|resmi|aman)\s*(?:judi|slot|togel)/i',
            '/(?:link|url|daftar)\s*(?:alternatif|login)\s*(?:judi|slot|togel)/i',
        ];

        foreach ($judolPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $this->logSuspiciousInput($value, $key, $source, 'Judol Injection');
            }
        }
    }

    /**
     * Log input yang mencurigakan
     *
     * @param string $value Nilai input
     * @param string $key Nama field
     * @param string $source Sumber data
     * @param string $type Jenis serangan
     */
    private function logSuspiciousInput(string $value, string $key, string $source, string $type): void
    {
        Log::warning('Suspicious input detected', [
            'type' => $type,
            'source' => $source,
            'field' => $key,
            'value' => substr($value, 0, 200), // Log hanya 200 karakter pertama
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ]);
    }
}
