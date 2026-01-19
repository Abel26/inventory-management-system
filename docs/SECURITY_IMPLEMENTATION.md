# Security Implementation Guide

## Overview

Dokumen ini menjelaskan implementasi keamanan yang telah dilakukan pada Laravel Inventory Management System untuk mencegah OWASP Top 10 vulnerabilities dan ancaman khusus seperti "Judol" Injection, RCE, dan Data Breach.

---

## Table of Contents

1. [Implemented Security Measures](#implemented-security-measures)
2. [Deployment Checklist](#deployment-checklist)
3. [How Each Measure Prevents "Judol" Attacks](#how-each-measure-prevents-judol-attacks)
4. [Monitoring & Maintenance](#monitoring--maintenance)
5. [Testing](#testing)

---

## Implemented Security Measures

### 1. File Upload Security (CRITICAL)

**Files Created/Modified:**
- `app/Services/SecureFileUploadService.php` - NEW
- `app/Http/Requests/StoreAssetMaterialRequest.php` - MODIFIED
- `app/Http/Requests/StoreAssetToolRequest.php` - MODIFIED
- `app/Http/Requests/StoreAssetModelRequest.php` - MODIFIED
- `app/Http/Requests/UpdateAssetMaterialRequest.php` - MODIFIED
- `app/Http/Requests/UpdateAssetToolRequest.php` - MODIFIED
- `app/Http/Requests/UpdateAssetModelRequest.php` - MODIFIED

**Features:**
- ✅ Server-side MIME type validation (using `finfo_file()`)
- ✅ Strict whitelist of allowed extensions (jpg, jpeg, png, pdf, webp)
- ✅ Blacklist of dangerous extensions (php, php5, html, svg, js, exe, etc.)
- ✅ Double extension detection
- ✅ File content scanning for hidden scripts
- ✅ Image dimension validation
- ✅ Force renaming with SHA-256 hash
- ✅ Maximum file size limit (5MB)

**How to Use:**
```php
use App\Services\SecureFileUploadService;

class AssetController extends Controller
{
    public function store(StoreAssetRequest $request, SecureFileUploadService $fileService)
    {
        if ($request->hasFile('image')) {
            $path = $fileService->upload($request->file('image'), 'assets');
            // Save path to database
        }
    }
}
```

---

### 2. Input Sanitization (HIGH)

**Files Created/Modified:**
- `app/Http/Middleware/SanitizeInputMiddleware.php` - NEW
- `bootstrap/app.php` - MODIFIED

**Features:**
- ✅ Global input sanitization for all requests
- ✅ Strip HTML tags from non-rich-text fields
- ✅ HTML entity encoding
- ✅ SQL injection pattern detection
- ✅ XSS pattern detection
- ✅ "Judol" injection pattern detection
- ✅ Automatic logging of suspicious inputs
- ✅ Whitelist for fields that require HTML (description, notes, etc.)

**How it Works:**
- Middleware automatically sanitizes all GET, POST, and JSON inputs
- Suspicious patterns are logged to Laravel logs
- Whitelisted fields (description, notes) are only checked for dangerous patterns

---

### 3. Authentication & Session Hardening (HIGH)

**Files Created/Modified:**
- `routes/auth.php` - MODIFIED
- `config/session.php` - MODIFIED
- `app/Providers/AppServiceProvider.php` - MODIFIED

**Features:**
- ✅ Rate limiting on login (5 attempts/minute)
- ✅ Rate limiting on register (5 attempts/minute)
- ✅ Rate limiting on password reset (3 attempts/minute)
- ✅ Session encryption enabled
- ✅ HTTP-only cookies
- ✅ Same-site strict cookies
- ✅ Strong password policy (min 12 chars, mixed case, numbers, symbols)
- ✅ Compromised password checking

**Password Policy:**
```php
Password::defaults(function () {
    return Password::min(12)
        ->mixedCase()
        ->numbers()
        ->symbols()
        ->uncompromised();
});
```

---

### 4. Server Headers & CSP (MEDIUM)

**Files Created/Modified:**
- `app/Http/Middleware/SecurityHeadersMiddleware.php` - NEW
- `bootstrap/app.php` - MODIFIED

**Security Headers:**
- ✅ `X-Frame-Options: DENY` - Prevents clickjacking
- ✅ `X-Content-Type-Options: nosniff` - Prevents MIME sniffing
- ✅ `X-XSS-Protection: 1; mode=block` - XSS filter
- ✅ `Referrer-Policy: same-origin` - Prevents referrer leakage
- ✅ `Permissions-Policy` - Restricts browser features
- ✅ `Strict-Transport-Security` - Forces HTTPS (production only)
- ✅ `Content-Security-Policy` - Blocks unauthorized scripts
- ✅ Removes Server and X-Powered-By headers

**CSP Directives:**
```http
Content-Security-Policy: default-src 'self'; 
    script-src 'self' 'unsafe-inline' 'unsafe-eval';
    style-src 'self' 'unsafe-inline';
    img-src 'self' data: https: 'unsafe-inline';
    font-src 'self';
    connect-src 'self';
    frame-ancestors 'none';
    base-uri 'self';
    form-action 'self';
    frame-src 'none';
    object-src 'none';
```

---

### 5. Application Configuration (HIGH)

**Files Created/Modified:**
- `.env.production.example` - NEW
- `config/security.php` - NEW
- `config/cors.php` - NEW

**Production Environment Variables:**
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate new key>
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
SESSION_ENCRYPT=true
LOG_LEVEL=warning
```

---

### 6. Additional Security Measures

**Files Created/Modified:**
- `scripts/secure-permissions.sh` - NEW

**Features:**
- ✅ Folder permissions script (755 for dirs, 644 for files)
- ✅ Directory listing disabled
- ✅ Sensitive files removed from public
- ✅ Cache clearing and optimization

---

## Deployment Checklist

### Before Deployment

- [ ] Review and update `.env.production.example` values
- [ ] Generate new `APP_KEY` with `php artisan key:generate`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `SESSION_SECURE_COOKIE=true` in `.env`
- [ ] Set `SESSION_SAME_SITE=strict` in `.env`
- [ ] Set `SESSION_ENCRYPT=true` in `.env`
- [ ] Set `LOG_LEVEL=warning` in `.env`
- [ ] Configure HTTPS on server
- [ ] Update `ALLOWED_ORIGINS` in `.env`
- [ ] Set strong database password
- [ ] Set strong Redis password
- [ ] Configure real mail service

### During Deployment

- [ ] Run `composer install --no-dev`
- [ ] Run `npm run build`
- [ ] Run `php artisan migrate --force`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `php artisan storage:link`
- [ ] Execute `scripts/secure-permissions.sh`
- [ ] Verify `.env` is not accessible from web
- [ ] Verify `public/index.php` is not writable

### After Deployment

- [ ] Test file upload with various file types
- [ ] Test login with incorrect credentials (verify rate limiting)
- [ ] Check security headers in browser DevTools
- [ ] Verify CSP is working
- [ ] Test XSS attempts
- [ ] Review Laravel logs for suspicious activities
- [ ] Configure error monitoring (Sentry, Bugsnag, etc.)
- [ ] Configure uptime monitoring
- [ ] Set up regular backups
- [ ] Test backup restoration

---

## How Each Measure Prevents "Judol" Attacks

### File Upload Security

| Threat | Prevention |
|--------|-------------|
| PHP Shell Upload | Whitelist extensions block `.php`, `.phtml`, etc. |
| MIME Type Spoofing | Server-side `finfo_file()` detects real file type |
| Double Extension Bypass | Explicit double extension check |
| Script in Image Files | Content scanning detects hidden scripts |
| Predictable File Names | SHA-256 hash prevents prediction |
| File Execution in Public | Files stored in `storage/app/public`, not `public/` |

**Why it prevents "Judol" scripts:**
- Attackers often upload PHP shells disguised as images to gain server control
- By validating the actual MIME type and scanning content, we prevent this
- Renaming files with hashes prevents attackers from accessing uploaded files via predictable URLs

---

### Input Sanitization

| Threat | Prevention |
|--------|-------------|
| XSS via Input Fields | Strip tags and encode HTML entities |
| SQL Injection | Pattern detection and logging |
| "Judol" Keywords | Pattern detection for gambling-related terms |
| Script Injection | Strip `<script>`, `<iframe>`, etc. |

**Why it prevents "Judol" scripts:**
- "Judol" attackers inject gambling ads via XSS or database
- By sanitizing all input, we prevent script tags from being stored
- Logging suspicious inputs helps detect attack attempts

---

### Authentication & Session Hardening

| Threat | Prevention |
|--------|-------------|
| Brute Force | Rate limiting limits attempts |
| Credential Stuffing | Rate limiting and strong passwords |
| Session Hijacking | Http-only and Same-site cookies |
| Session Fixation | Encrypted sessions with unique IDs |

**Why it prevents "Judol" scripts:**
- Brute force attacks are often used to gain admin access
- Once admin access is gained, attackers can inject "Judol" scripts
- Strong password policy and rate limiting prevent this

---

### Server Headers & CSP

| Threat | Prevention |
|--------|-------------|
| Clickjacking | `X-Frame-Options: DENY` |
| MIME Sniffing | `X-Content-Type-Options: nosniff` |
| XSS | `X-XSS-Protection` and CSP |
| External Scripts | CSP `script-src 'self'` |
| Downgrade Attacks | `Strict-Transport-Security` |

**Why it prevents "Judol" scripts:**
- "Judol" scripts often load from external gambling domains
- CSP blocks all scripts except from your own domain
- `X-Frame-Options` prevents your site from being embedded in gambling iframes

---

## Monitoring & Maintenance

### Log Files to Monitor

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Failed login attempts
grep "Failed login" storage/logs/laravel.log

# Suspicious input
grep "Suspicious input" storage/logs/laravel.log

# File uploads
grep "File upload" storage/logs/laravel.log
```

### Regular Tasks

**Weekly:**
- Review security logs
- Check for failed login attempts
- Monitor file uploads

**Monthly:**
- Update dependencies (`composer update`)
- Review security advisories
- Audit user permissions

**Quarterly:**
- Security audit
- Penetration testing
- Review and rotate `APP_KEY`

**Annually:**
- Full security assessment
- Update security policies
- Security training for team

---

## Testing

### Security Testing Checklist

- [ ] Upload file with `.php` extension → Should be rejected
- [ ] Upload file with double extension → Should be rejected
- [ ] Upload file with fake MIME type → Should be rejected
- [ ] Upload file containing PHP tags → Should be rejected
- [ ] Upload SVG file with scripts → Should be rejected
- [ ] Input with `<script>` tag → Should be sanitized
- [ ] SQL injection attempt → Should fail
- [ ] Brute force login → Should be rate limited
- [ ] XSS via URL parameter → Should be prevented
- [ ] Session hijacking attempt → Should be prevented
- [ ] CSP violation → Should be reported
- [ ] Check security headers in DevTools
- [ ] Test with security scanner (e.g., OWASP ZAP)

### Testing Tools

```bash
# Check security headers
curl -I https://yourdomain.com

# Test CSP
https://csp-evaluator.withgoogle.com/

# Test SSL
https://www.ssllabs.com/ssltest/

# Security scan
# OWASP ZAP
# Burp Suite
# Nikto
```

---

## References

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security Documentation](https://laravel.com/docs/security)
- [CSP Evaluator](https://csp-evaluator.withgoogle.com/)
- [Content Security Policy Level 3](https://www.w3.org/TR/CSP3/)

---

## Support

For questions or issues related to security implementation:
1. Review the [Security Hardening Plan](../plans/security-hardening-plan.md)
2. Check Laravel logs for errors
3. Review the Security Configuration in `config/security.php`

---

**Last Updated:** 2026-01-19
**Version:** 1.0.0
