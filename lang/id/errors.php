<?php

return [
    'meta_title' => 'Error {code} - Ebara Inventory Management System',
    'meta_description' => 'Halaman error {code} - Sistem Manajemen Inventaris Ebara',
    
    '404' => [
        'title' => 'Halaman Tidak Ditemukan',
        'subtitle' => 'Oops! Halaman yang Anda cari tidak ada atau telah dipindahkan.',
        'description' => 'Sepertinya Anda tersesat di dunia inventaris kami. Mari kami bantu Anda menemukan jalan kembali.',
        'actions' => [
            'home' => 'Kembali ke Beranda',
            'dashboard' => 'Ke Dashboard',
            'search' => 'Cari Aset',
            'contact' => 'Hubungi Kami'
        ],
        'suggestions' => [
            'title' => 'Mungkin Anda Mencari:',
            'dashboard' => 'Dashboard Inventaris',
            'assets' => 'Daftar Aset',
            'reports' => 'Laporan',
            'login' => 'Masuk ke Sistem'
        ]
    ],
    
    '500' => [
        'title' => 'Kesalahan Server Internal',
        'subtitle' => 'Oops! Terjadi kesalahan tak terduga di server kami.',
        'description' => 'Tim kami telah diberitahu tentang masalah ini dan sedang bekerja untuk memperbaikinya. Mohon coba lagi dalam beberapa saat.',
        'actions' => [
            'refresh' => 'Muat Ulang Halaman',
            'home' => 'Kembali ke Beranda',
            'dashboard' => 'Ke Dashboard',
            'report' => 'Laporkan Masalah'
        ],
        'reassurance' => 'Jangan khawatir, data Anda aman dan tidak terpengaruh oleh kesalahan ini.'
    ],
    
    '403' => [
        'title' => 'Akses Ditolak',
        'subtitle' => 'Oops! Anda tidak memiliki izin untuk mengakses halaman ini.',
        'description' => 'Sepertinya Anda mencoba mengakses area yang memerlukan izin khusus. Silakan masuk dengan akun yang tepat atau hubungi administrator.',
        'actions' => [
            'login' => 'Masuk ke Sistem',
            'home' => 'Kembali ke Beranda',
            'dashboard' => 'Ke Dashboard',
            'contact' => 'Hubungi Administrator'
        ],
        'help' => 'Jika Anda yakin seharusnya memiliki akses, silakan hubungi tim IT kami.'
    ],
    
    '419' => [
        'title' => 'Halaman Kadaluarsa',
        'subtitle' => 'Oops! Sesi Anda telah kadaluarsa.',
        'description' => 'Untuk keamanan Anda, sesi aktif hanya berlaku untuk waktu terbatas. Silakan muat ulang halaman dan coba lagi.',
        'actions' => [
            'refresh' => 'Muat Ulang Halaman',
            'login' => 'Masuk Kembali',
            'home' => 'Kembali ke Beranda'
        ],
        'security_note' => 'Ini adalah fitur keamanan untuk melindungi akun Anda.'
    ],
    
    '429' => [
        'title' => 'Terlalu Banyak Permintaan',
        'subtitle' => 'Oops! Anda telah melebihi batas permintaan yang diizinkan.',
        'description' => 'Untuk menjaga performa sistem, kami membatasi jumlah permintaan dalam waktu tertentu. Silakan tunggu beberapa saat sebelum mencoba lagi.',
        'actions' => [
            'wait' => 'Tunggu {seconds} Detik',
            'refresh' => 'Coba Lagi',
            'home' => 'Kembali ke Beranda'
        ],
        'rate_limit_info' => 'Batas permintaan akan direset secara otomatis.'
    ],
    
    'common' => [
        'error_code' => 'Kode Error: {code}',
        'timestamp' => 'Waktu: {time}',
        'help_text' => 'Butuh bantuan? Hubungi tim support kami.',
        'back_button' => 'Kembali',
        'continue_button' => 'Lanjutkan'
    ]
];