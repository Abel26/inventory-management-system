<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi CORS untuk membatasi akses cross-origin ke API.
    | Ini penting untuk mencegah serangan CSRF dan data theft.
    |
    | Security Notes:
    * - paths: Batasi hanya endpoint yang perlu diakses dari luar
    * - allowed_origins: Hanya izinkan domain yang terpercaya
    * - allowed_methods: Hanya izinkan method yang diperlukan
    * - allowed_headers: Hanya izinkan header yang diperlukan
    * - supports_credentials: Hanya aktifkan jika benar-benar diperlukan
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // ⚠️ SECURITY: Update ini untuk production!

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // ⚠️ SECURITY: Hanya aktifkan jika diperlukan

];
