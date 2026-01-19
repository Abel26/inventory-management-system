<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use finfo;

/**
 * Secure File Upload Service
 *
 * Menyediakan upload file yang aman dengan validasi ketat untuk mencegah:
 * - Remote Code Execution (RCE) melalui PHP shell upload
 * - "Judol" Injection melalui file berisi script berbahaya
 * - File spoofing (MIME type palsu)
 * - Double extension bypass
 */
class SecureFileUploadService
{
    // Whitelist ekstensi file yang diizinkan
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];

    // Blacklist ekstensi file berbahaya
    private const BLOCKED_EXTENSIONS = [
        'php', 'php5', 'php7', 'phtml', 'html', 'htm', 'svg',
        'js', 'exe', 'sh', 'pl', 'cgi', 'py', 'rb', 'jsp', 'asp',
        'aspx', 'cfm', 'shtml', 'xhtml', 'xml'
    ];

    // MIME types yang diizinkan
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf'
    ];

    // Maximum file size (5MB)
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    /**
     * Upload file dengan validasi keamanan yang ketat
     *
     * @param UploadedFile $file File yang akan diupload
     * @param string $directory Direktori tujuan di storage
     * @return string Path file yang tersimpan
     * @throws \InvalidArgumentException Jika validasi gagal
     */
    public function upload(UploadedFile $file, string $directory = 'uploads'): string
    {
        // 1. Validasi ukuran file
        $this->validateFileSize($file);

        // 2. Validasi ekstensi file
        $extension = $this->validateExtension($file);

        // 3. Validasi MIME type (server-side, bukan dari browser)
        $this->validateMimeType($file, $extension);

        // 4. Validasi double extension (misal: image.php.jpg)
        $this->validateDoubleExtension($file);

        // 5. Validasi konten file untuk script tersembunyi
        $this->validateFileContent($file);

        // 6. Validasi dimensi untuk file gambar
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $this->validateImageDimensions($file);
        }

        // 7. Generate nama file yang aman (hash + timestamp)
        $fileName = $this->generateSecureFileName($extension);

        // 8. Simpan file ke storage (public disk)
        $path = $file->storeAs($directory, $fileName, 'public');

        return $path;
    }

    /**
     * Upload multiple files dengan validasi keamanan
     *
     * @param array $files Array dari UploadedFile
     * @param string $directory Direktori tujuan di storage
     * @return array Array dari path file yang tersimpan
     */
    public function uploadMultiple(array $files, string $directory = 'uploads'): array
    {
        $paths = [];

        foreach ($files as $file) {
            $paths[] = $this->upload($file, $directory);
        }

        return $paths;
    }

    /**
     * Validasi ukuran file
     *
     * Mencegah upload file terlalu besar yang bisa menyebabkan DoS
     */
    private function validateFileSize(UploadedFile $file): void
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \InvalidArgumentException(
                'Ukuran file tidak boleh lebih dari ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB'
            );
        }
    }

    /**
     * Validasi ekstensi file
     *
     * Mencegah upload file dengan ekstensi berbahaya seperti .php, .sh, dll
     * Ini adalah pertahanan utama melawan PHP shell upload
     */
    private function validateExtension(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // Cek ekstensi yang diblokir
        if (in_array($extension, self::BLOCKED_EXTENSIONS)) {
            throw new \InvalidArgumentException(
                'Ekstensi file tidak diizinkan. File berbahaya terdeteksi.'
            );
        }

        // Cek ekstensi yang diizinkan
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \InvalidArgumentException(
                'Hanya file ' . implode(', ', self::ALLOWED_EXTENSIONS) . ' yang diizinkan'
            );
        }

        return $extension;
    }

    /**
     * Validasi MIME type menggunakan server-side detection
     *
     * Menggunakan finfo_file() untuk mendeteksi MIME type sebenarnya dari file,
     * bukan mengandalkan MIME type dari browser yang bisa dipalsukan.
     *
     * Ini mencegah file spoofing di mana attacker mengubah ekstensi .php menjadi .jpg
     */
    private function validateMimeType(UploadedFile $file, string $extension): void
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file->getRealPath());

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            throw new \InvalidArgumentException(
                'Tipe file tidak valid. File yang diupload bukan file yang diizinkan.'
            );
        }

        // Cross-check MIME type dengan ekstensi untuk mencegah spoofing
        $expectedMimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf'
        ];

        if (isset($expectedMimeTypes[$extension]) && $expectedMimeTypes[$extension] !== $mimeType) {
            throw new \InvalidArgumentException(
                'MIME type tidak sesuai dengan ekstensi file. File yang diupload mencurigakan.'
            );
        }
    }

    /**
     * Validasi double extension (misal: image.php.jpg)
     *
     * Mencegah bypass validasi dengan menggunakan double extension.
     * Beberapa server konfigurasi bisa mengeksekusi file .php meskipun
     * ekstensinya .php.jpg
     */
    private function validateDoubleExtension(UploadedFile $file): void
    {
        $fileName = $file->getClientOriginalName();
        $parts = explode('.', $fileName);

        if (count($parts) > 2) {
            throw new \InvalidArgumentException(
                'Nama file tidak valid. File dengan multiple extension tidak diizinkan.'
            );
        }
    }

    /**
     * Validasi konten file untuk mendeteksi script tersembunyi
     *
     * Mencegah "Judol" Injection dengan memindai konten file untuk:
     * - PHP tags
     * - Script tags
     * - Iframe tags
     * - JavaScript protocols
     *
     * Ini penting karena file bisa saja memiliki ekstensi .jpg tapi
     * isinya berisi script berbahaya yang bisa dieksploitasi.
     */
    private function validateFileContent(UploadedFile $file): void
    {
        $content = file_get_contents($file->getRealPath());

        // Cek untuk PHP tags
        if (preg_match('/<\?php|<\?|<\?=/i', $content)) {
            throw new \InvalidArgumentException(
                'File mengandung script PHP. File berbahaya terdeteksi.'
            );
        }

        // Cek untuk script tags
        if (preg_match('/<script[^>]*>.*?<\/script>/is', $content)) {
            throw new \InvalidArgumentException(
                'File mengandung script tags. File berbahaya terdeteksi.'
            );
        }

        // Cek untuk iframe tags (sering digunakan untuk injection)
        if (preg_match('/<iframe[^>]*>.*?<\/iframe>/is', $content)) {
            throw new \InvalidArgumentException(
                'File mengandung iframe tags. File berbahaya terdeteksi.'
            );
        }

        // Cek untuk javascript: protocol (XSS vector)
        if (preg_match('/javascript:/i', $content)) {
            throw new \InvalidArgumentException(
                'File mengandung javascript protocol. File berbahaya terdeteksi.'
            );
        }

        // Cek untuk data URI dengan script
        if (preg_match('/data:[^;]*;base64.*<script/is', $content)) {
            throw new \InvalidArgumentException(
                'File mengandung data URI script. File berbahaya terdeteksi.'
            );
        }

        // Cek untuk eval() dan dangerous functions (untuk SVG dan PDF)
        if (preg_match('/\beval\s*\(/i', $content)) {
            throw new \InvalidArgumentException(
                'File mengandung fungsi eval() yang berbahaya. File berbahaya terdeteksi.'
            );
        }
    }

    /**
     * Validasi dimensi gambar
     *
     * Mencegah upload gambar dengan dimensi ekstrem yang bisa menyebabkan:
     * - Memory exhaustion (DoS)
     * - ImageTragick vulnerability exploit
     */
    private function validateImageDimensions(UploadedFile $file): void
    {
        $imageInfo = getimagesize($file->getRealPath());

        if ($imageInfo === false) {
            throw new \InvalidArgumentException(
                'File bukan gambar yang valid. File yang diupload mencurigakan.'
            );
        }

        // Validasi dimensi maksimal (5000x5000)
        $maxDimension = 5000;
        if ($imageInfo[0] > $maxDimension || $imageInfo[1] > $maxDimension) {
            throw new \InvalidArgumentException(
                "Dimensi gambar terlalu besar. Maksimum {$maxDimension}x{$maxDimension} piksel."
            );
        }

        // Validasi dimensi minimal (10x10)
        $minDimension = 10;
        if ($imageInfo[0] < $minDimension || $imageInfo[1] < $minDimension) {
            throw new \InvalidArgumentException(
                "Dimensi gambar terlalu kecil. Minimum {$minDimension}x{$minDimension} piksel."
            );
        }
    }

    /**
     * Generate nama file yang aman menggunakan hash
     *
     * Menggunakan SHA-256 hash dari timestamp + random string untuk:
     * - Mencegah filename collision
     * - Mencegah prediksi nama file (security by obscurity)
     * - Mencegah eksekusi script melalui nama file yang mengandung karakter khusus
     */
    private function generateSecureFileName(string $extension): string
    {
        $timestamp = time();
        $random = Str::random(16);
        $hash = hash('sha256', $timestamp . $random);

        return substr($hash, 0, 32) . '.' . $extension;
    }

    /**
     * Hapus file dari storage
     *
     * @param string $path Path file yang akan dihapus
     * @return bool True jika berhasil, false jika file tidak ditemukan
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Dapatkan URL publik untuk file
     *
     * @param string $path Path file di storage
     * @return string URL publik file
     */
    public function getUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Cek apakah file ada
     *
     * @param string $path Path file di storage
     * @return bool True jika file ada, false jika tidak
     */
    public function exists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }
}
