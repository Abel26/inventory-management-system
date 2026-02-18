<?php

return [
    // ===========================
    // COMMON / SHARED STRINGS
    // ===========================
    'common' => [
        // Buttons
        'add_data' => 'Tambah Data',
        'export' => 'Ekspor',
        'export_excel' => 'Ekspor ke Excel',
        'export_pdf' => 'Ekspor ke PDF',
        'save' => 'Simpan',
        'cancel' => 'Batal',
        'delete' => 'Hapus',
        'edit' => 'Edit',
        'close' => 'Tutup',
        'back' => 'Kembali',
        'submit' => 'Kirim',
        'saving' => 'Menyimpan...',
        'close_modal' => 'Tutup modal',
        'print_qr' => 'Cetak QR Code',
        'scan_qr_detail' => 'Scan QR code ini untuk melihat detail',
        'select_material' => 'Pilih Material',

        // Labels
        'code' => 'Kode',
        'name' => 'Nama',
        'type' => 'Tipe',
        'category' => 'Kategori',
        'brand' => 'Merk',
        'quantity' => 'Jumlah',
        'unit' => 'Satuan',
        'location' => 'Lokasi',
        'condition' => 'Kondisi',
        'description' => 'Deskripsi',
        'supplier' => 'Supplier',
        'status' => 'Status',
        'actions' => 'Aksi',
        'year' => 'Tahun',
        'stock' => 'Stok',
        'photo' => 'Foto',
        'notes' => 'Catatan',
        'email' => 'Email',
        'password' => 'Password',
        'role' => 'Role',
        'priority' => 'Prioritas',
        'created_at' => 'Dibuat Pada',
        'updated_at' => 'Diubah Pada',
        'no' => 'No',

        // Placeholders
        'optional' => 'Opsional',
        'example' => 'Contoh',
        'select' => 'Pilih',
        'search' => 'Cari...',

        // Select Options - Conditions
        'select_condition' => 'Pilih Kondisi',
        'condition_good' => 'Baik',
        'condition_repair' => 'Perbaikan',
        'condition_damaged' => 'Rusak',
        'condition_disposed' => 'Dibuang',

        // Other
        'other' => 'Lainnya',
        'no_data' => 'Tidak ada data',
        'required' => 'Wajib',
    ],

    // ===========================
    // DATATABLES LANGUAGE
    // ===========================
    'datatable' => [
        'search' => 'Cari:',
        'length_menu' => 'Tampilkan _MENU_ data',
        'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
        'info_empty' => 'Tidak ada data yang tersedia',
        'info_filtered' => '(difilter dari _MAX_ total data)',
        'zero_records' => 'Tidak ada data yang cocok',
        'empty_table' => 'Tidak ada data tersedia di tabel',
        'first' => 'Pertama',
        'last' => 'Terakhir',
        'next' => 'Selanjutnya',
        'previous' => 'Sebelumnya',
        'sort_ascending' => ': aktifkan untuk mengurutkan kolom secara ascending',
        'sort_descending' => ': aktifkan untuk mengurutkan kolom secara descending',
    ],

    // ===========================
    // SWEETALERT MESSAGES
    // ===========================
    'swal' => [
        'confirm_title' => 'Apakah Anda yakin?',
        'delete_warning' => 'Data yang dihapus tidak dapat dikembalikan',
        'update_warning' => 'Data akan diperbarui. Pastikan data sudah benar.',
        'create_warning' => 'Data akan ditambahkan. Pastikan data sudah benar.',
        'yes_save' => 'Ya, Simpan',
        'yes_delete' => 'Ya, hapus',
        'cancel' => 'Batal',
        'success' => 'Berhasil',
        'error_title' => 'Terjadi Kesalahan',
        'error' => 'Error',
        'data_saved' => 'Data berhasil disimpan',
        'data_updated' => 'Data berhasil diupdate',
        'data_deleted' => 'Data berhasil dihapus',
        'fetch_error' => 'Gagal mengambil data',
        'delete_error' => 'Gagal menghapus data',
        'generic_error' => 'Terjadi kesalahan. Silakan coba lagi.',
        'validation_error' => 'Validation error. Silakan periksa kembali input Anda.',
        'permission_error' => 'Anda tidak memiliki izin untuk melakukan aksi ini.',
        'not_found' => 'Data tidak ditemukan.',
        'server_error' => 'Terjadi kesalahan server. Silakan coba lagi.',
        'qr_error' => 'Gagal generate QR Code',
        'qr_load_error' => 'Gagal memuat QR Code',
        'qr_code_for' => 'QR Code untuk:',
        // Report-specific
        'submit_report' => 'Kirim Laporan?',
        'submit_report_text' => 'Pastikan data yang Anda masukkan sudah benar.',
        'yes_submit' => 'Ya, Kirim!',
        'ok' => 'OK',
    ],

    // ===========================
    // ASSET MATERIALS
    // ===========================
    'asset_materials' => [
        'title' => 'Material Aset',
        'subtitle' => 'Kelola inventaris material dan persediaan',
        'search_placeholder' => 'Cari material...',
        'empty_state' => 'Belum ada data Material Aset ditemukan.',

        // Stats
        'total_materials' => 'Total Material',
        'low_stock' => 'Stok Menipis',
        'out_of_stock' => 'Stok Habis',
        'total_value' => 'Total Nilai',

        // Table Headers
        'asset_name' => 'Nama Aset',

        // Modal
        'add_title' => 'Tambah Material',
        'add_subtitle' => 'Isi informasi material baru',
        'edit_title' => 'Edit Material',
        'edit_subtitle' => 'Edit informasi material',

        // Form Labels
        'material_code' => 'Kode Material',
        'material_name' => 'Nama Material',
        'min_stock' => 'Min. Stok',
        'unit_price' => 'Harga Satuan',
        'entry_date' => 'Tanggal Masuk',
        'expiry_date' => 'Tanggal Kadaluarsa',
        'code_placeholder' => 'Opsional, akan digenerate otomatis',

        // Select Options - Types
        'select_type' => 'Pilih Tipe',
        'type_raw_material' => 'Bahan Baku',
        'type_component' => 'Komponen',
        'type_accessory' => 'Aksesoris',
        'type_other' => 'Lainnya',

        // Error Messages
        'fetch_error' => 'Gagal mengambil data material',
    ],

    // ===========================
    // ASSET TOOLS
    // ===========================
    'asset_tools' => [
        'title' => 'Alat Aset',
        'subtitle' => 'Kelola inventaris alat dan peralatan',
        'search_placeholder' => 'Cari alat...',
        'empty_state' => 'Belum ada data Alat Aset ditemukan.',

        // Stats
        'total_tools' => 'Total Alat',
        'good_condition' => 'Kondisi Baik',
        'repair' => 'Perbaikan',
        'damaged' => 'Rusak',

        // Modal
        'add_title' => 'Tambah Alat',
        'add_subtitle' => 'Isi informasi alat baru',
        'edit_title' => 'Edit Alat',
        'edit_subtitle' => 'Edit informasi alat',

        // Form Labels
        'tool_code' => 'Kode Alat',
        'tool_name' => 'Nama Alat',
        'purchase_date' => 'Tanggal Pembelian',
        'purchase_price' => 'Harga Beli',

        // Placeholders
        'code_placeholder' => 'Contoh: TL-001',
        'brand_placeholder' => 'Contoh: Mitsubishi, Bosch',
        'type_placeholder' => 'Contoh: MX-200',
        'price_placeholder' => 'Contoh: 1500000',
        'quantity_placeholder' => 'Contoh: 1',

        // Select Options - Categories
        'select_category' => 'Pilih Kategori',
        'category_hand_tools' => 'Alat Tangan',
        'category_power_tools' => 'Alat Listrik',
        'category_measuring' => 'Alat Ukur',
        'category_other' => 'Lainnya',

        // Error Messages
        'user_info' => 'Info User',
        'username' => 'Username',
        'phone' => 'No. Telepon',
        'status' => 'Status',
        'is_active' => 'User Aktif',
        'active_help' => 'Aktifkan untuk memberikan akses login',
        'name_placeholder' => 'Masukkan nama lengkap',
        'username_placeholder' => 'Masukkan username',
        'phone_placeholder' => 'Masukkan nomor telepon',
        'password_placeholder' => 'Masukkan password baru',
        'password_conf_placeholder' => 'Konfirmasi password baru',
        'empty_state_desc' => 'Mulai dengan menambahkan user pertama',
        'fetch_error' => 'Gagal mengambil data user',
        'update_confirm' => 'Data alat akan diperbarui. Pastikan data sudah benar.',
        'create_confirm' => 'Data alat akan ditambahkan. Pastikan data sudah benar.',
        'delete_permission_error' => 'Anda tidak memiliki izin untuk menghapus data ini',
    ],

    // ===========================
    // ASSET MODELS
    // ===========================
    'asset_models' => [
        'title' => 'Model Aset',
        'subtitle' => 'Kelola inventaris model dan cetakan',
        'search_placeholder' => 'Cari model...',
        'empty_state' => 'Belum ada data Model Aset ditemukan.',

        // Stats
        'total_models' => 'Total Model',
        'good_condition' => 'Kondisi Baik',
        'repair' => 'Perbaikan',
        'damaged' => 'Rusak',
        'active' => 'Aktif',
        'inactive' => 'Non-Aktif',
        'total_assets' => 'Total Aset',

        // Modal
        'add_title' => 'Tambah Model',
        'add_subtitle' => 'Isi informasi model baru',
        'edit_title' => 'Edit Model',
        'edit_subtitle' => 'Edit informasi model',

        // Form Labels
        'model_code' => 'Kode Model',
        'model_name' => 'Nama Model',
        'purchase_date' => 'Tanggal Pembelian',
        'purchase_price' => 'Harga Beli',
        'manufacture_date' => 'Tanggal Pembuatan',
        'material' => 'Material',
        'type_placeholder' => 'Contoh: Injection, CNC',
        'save_error' => 'Terjadi kesalahan saat menyimpan data',

        // Table Headers
        'model_number' => 'Nomor Model',

        // Select Options - Categories
        'select_category' => 'Pilih Kategori',
        'category_mold' => 'Cetakan',
        'category_die' => 'Die',
        'category_jig' => 'Jig',
        'category_fixture' => 'Fixture',
        'category_other' => 'Lainnya',

        // Error Messages
        'fetch_error' => 'Gagal mengambil data model',
    ],

    // ===========================
    // GEDUNGS (BUILDINGS)
    // ===========================
    'gedungs' => [
        'title' => 'Data Gedung',
        'subtitle' => 'Kelola data gedung dan lokasi',
        'search_placeholder' => 'Cari gedung...',
        'empty_state' => 'Belum ada data Gedung ditemukan.',

        // Stats
        'total_buildings' => 'Total Gedung',
        'total_floors' => 'Total Lantai',
        'total_rooms' => 'Total Ruangan',
        'active_locations' => 'Lokasi Aktif',

        // Table Headers
        'building_name' => 'Nama Gedung',
        'address' => 'Alamat',
        'floor_count' => 'Lantai',
        'room_count' => 'Ruangan',
        'total_models' => 'Total Model',
        'total_materials' => 'Total Material',
        'total_tools' => 'Total Tool',
        'total_assets' => 'Total Aset',
        'total_assets_in_building' => 'Total Aset di Gedung',

        // Modal
        'add_title' => 'Tambah Gedung',
        'add_subtitle' => 'Isi informasi gedung baru',
        'edit_title' => 'Edit Gedung',
        'edit_subtitle' => 'Edit informasi gedung',

        // Form Labels
        'building_code' => 'Kode Gedung',
        'code_placeholder' => 'Contoh: GD-001',
        'name_placeholder' => 'Contoh: Gedung Produksi A',
        'save_building' => 'Simpan Gedung',
        'update_subtitle' => 'Perbarui informasi gedung',

        // Info Box
        'info_title' => 'Informasi Gedung',
        'info_description' => 'Gedung digunakan sebagai referensi lokasi aset. Pastikan data diisi dengan benar.',

        // Error Messages
        'fetch_error' => 'Gagal mengambil data gedung',
    ],

    // ===========================
    // SATUANS (UNITS)
    // ===========================
    'satuans' => [
        'title' => 'Data Satuan',
        'subtitle' => 'Kelola data satuan pengukuran',
        'search_placeholder' => 'Cari satuan...',
        'empty_state' => 'Belum ada data Satuan ditemukan.',

        // Stats
        'total_units' => 'Total Satuan',

        // Table Headers
        'unit_name' => 'Nama Satuan',
        'abbreviation' => 'Singkatan',

        // Modal
        'add_title' => 'Tambah Satuan',
        'add_subtitle' => 'Isi informasi satuan baru',
        'edit_title' => 'Edit Satuan',
        'edit_subtitle' => 'Edit informasi satuan',

        // Form Labels
        'unit_code' => 'Kode Satuan',
        'unit_symbol' => 'Simbol',
        'name_placeholder' => 'Contoh: Kilogram, Liter',
        'code_placeholder' => 'Contoh: kg, ltr',
        'update_subtitle' => 'Edit informasi satuan',

        // Error Messages
        'fetch_error' => 'Gagal mengambil data satuan',
    ],

    // ===========================
    // USERS
    // ===========================
    'users' => [
        'title' => 'Manajemen Pengguna',
        'subtitle' => 'Kelola akun pengguna dan akses',
        'search_placeholder' => 'Cari pengguna...',
        'empty_state' => 'Belum ada data Pengguna ditemukan.',

        // Stats
        'total_users' => 'Total Pengguna',
        'active_users' => 'Pengguna Aktif',
        'admin_users' => 'Pengguna Admin',
        'regular_users' => 'Pengguna Biasa',

        // Table Headers
        'user_name' => 'Nama',
        'email' => 'Email',
        'role' => 'Role',
        'status' => 'Status',

        // Modal
        'add_title' => 'Tambah Pengguna',
        'add_subtitle' => 'Isi informasi pengguna baru',
        'edit_title' => 'Edit Pengguna',
        'edit_subtitle' => 'Edit informasi pengguna',

        // Form Labels
        'full_name' => 'Nama Lengkap',
        'email_address' => 'Alamat Email',
        'password' => 'Password',
        'confirm_password' => 'Konfirmasi Password',
        'select_role' => 'Pilih Role',
        'password_info' => 'Kosongkan jika tidak ingin mengubah password',

        // Status
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',

        // Error Messages
        'fetch_error' => 'Gagal mengambil data pengguna',
    ],

    // ===========================
    // ROLES
    // ===========================
    'roles' => [
        'title' => 'Manajemen Role',
        'subtitle' => 'Kelola role dan izin akses',
        'search_placeholder' => 'Cari role...',
        'empty_state' => 'Belum ada data Role ditemukan.',

        // Stats
        'total_roles' => 'Total Role',

        // Table Headers
        'role_name' => 'Nama Role',
        'permissions' => 'Izin',
        'user_count' => 'Pengguna',
        'guard_name' => 'Guard',

        // Modal
        'add_title' => 'Tambah Role',
        'add_subtitle' => 'Isi informasi role baru',
        'edit_title' => 'Edit Role',
        'edit_subtitle' => 'Edit informasi role',

        // Error Messages
        'permissions_count' => 'Jumlah Permission',
        'fetch_error' => 'Gagal mengambil data role',
    ],

    // ===========================
    // REPORTS (LAPORAN MASALAH)
    // ===========================
    'reports' => [
        'title' => 'Laporan Masalah',
        'subtitle' => 'Kelola dan lacak laporan masalah aset',
        'search_placeholder' => 'Cari laporan...',
        'empty_state' => 'Belum ada data laporan ditemukan.',

        // Stats
        'total_reports' => 'Total Laporan',
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',

        // Table Headers
        'report_code' => 'Kode Laporan',
        'asset_name' => 'Nama Aset',
        'issue_type' => 'Jenis Masalah',
        'priority' => 'Prioritas',
        'reporter' => 'Pelapor',
        'date' => 'Tanggal',

        // Filter Buttons
        'all' => 'Semua',
        'status_pending' => 'Pending',
        'status_in_progress' => 'In Progress',
        'status_resolved' => 'Resolved',
        'status_rejected' => 'Rejected',

        // Create Page
        'create_title' => 'Buat Laporan',
        'create_subtitle' => 'Laporkan masalah pada aset',
        'no_asset_selected' => 'Tidak ada aset yang dipilih',
        'scan_qr_first' => 'Silakan scan QR code aset terlebih dahulu untuk membuat laporan.',
        'scan_qr_code' => 'Scan QR Code',

        // Create Form - Issue Types
        'issue_damage' => 'Kerusakan',
        'issue_maintenance' => 'Perawatan',
        'issue_lost' => 'Hilang',
        'issue_stock_discrepancy' => 'Selisih Stok',

        // Create Form - Priority
        'priority_low' => 'Rendah',
        'priority_medium' => 'Sedang',
        'priority_high' => 'Tinggi',
        'priority_critical' => 'Kritis',

        // Create Form - Fields
        'description_placeholder' => 'Jelaskan masalah yang terjadi...',
        'photo_evidence' => 'Foto Bukti (Opsional)',
        'photo_max_size' => 'Maksimal 5MB. Format: JPG, PNG, GIF.',
        'upload' => 'Upload',
        'submit_report' => 'Kirim Laporan',

        // Show Page
        'detail_title' => 'Detail Laporan',
        'created_at' => 'Dibuat pada',
        'asset_details' => 'Detail Aset',
        'view_asset' => 'Lihat Aset',
        'asset_not_found' => 'Aset tidak ditemukan atau telah dihapus.',
        'issue_description' => 'Deskripsi Masalah',
        'reported_by' => 'Dilaporkan Oleh',
        'update_status' => 'Update Status',
        'admin_note' => 'Catatan Admin',
        'admin_note_placeholder' => 'Tambahkan catatan tentang penyelesaian...',
        'status_history' => 'Riwayat Status',
        'report_created' => 'Laporan Dibuat',
        'status_changed_to' => 'Status diubah ke',
        'resolved_by' => 'Diselesaikan oleh',
        'note_label' => 'Catatan:',
        'back' => 'Kembali',

        // Scan
        'scan_title' => 'Scan QR Code Aset',
        'scan_description' => 'Gunakan kamera perangkat Anda untuk memindai QR code aset atau masukkan kode secara manual',
        'camera_mode' => 'Mode Kamera Live (HTTPS) - Kamera real-time tersedia',
        'camera_mode_desc' => 'Kamera Live Siap',
        'camera_mode_instruction' => 'Klik "Mulai Scan" untuk memulai',
        'start_scan_live' => 'Mulai Scan Live',
        'standard_mode' => 'Mode Kamera Standar (HTTP) - Scan via foto/gambar',
        'standard_mode_desc' => 'Scan via Foto',
        'standard_mode_instruction' => 'Klik "Mulai Scan" untuk ambil foto',
        'take_photo' => 'Ambil Foto QR',
        'scanner_title' => 'QR Scanner',
        'preparing_scanner' => 'Menyiapkan scanner...',
        'wait_moment' => 'Mohon tunggu sebentar',
        'start_scan' => 'Mulai Scan',
        'stop_scan' => 'Stop Scan',
        'manual_input_title' => 'Input Manual',
        'manual_input_label' => 'Kode QR / Kode Aset',
        'manual_input_placeholder' => 'Contoh: A001 atau MOD-001',
        'search_asset' => 'Cari Aset',
        'supported_assets' => 'Aset yang Didukung',
        'tips_scan' => 'Tips Scan',
        'tip_light' => 'Pastikan pencahayaan cukup terang',
        'tip_steady' => 'Tahan perangkat dengan stabil',
        'tip_distance' => 'Jarak ideal: 10-20 cm dari QR code',
        'tip_manual' => 'Gunakan input manual jika scan gagal',
        'processing_image' => 'Memproses gambar...',
        'reading_qr' => 'Membaca QR code dari foto',
        'qr_success' => 'QR Code berhasil terbaca: ',
        'camera_error' => 'Gagal membuka kamera. Silakan coba lagi.',
        'camera_permission_denied' => 'Izin kamera ditolak. Silakan berikan izin akses kamera di browser Anda.',
        'camera_not_found' => 'Kamera tidak ditemukan. Pastikan perangkat Anda memiliki kamera.',
        'camera_in_use' => 'Kamera sedang digunakan oleh aplikasi lain.',
        'camera_constraint' => 'Kamera tidak memenuhi persyaratan yang dibutuhkan.',
        'scan_image_error' => 'Tidak dapat membaca QR code dari gambar.',
        'no_qr_found' => 'QR code tidak ditemukan dalam gambar. Pastikan QR code terlihat jelas.',
        'decode_error' => 'Gagal memproses gambar. Silakan coba dengan gambar lain.',

        // Inventory Report
        'inventory_title' => 'Laporan Inventaris',
        'inventory_subtitle' => 'Lihat analitik dan statistik inventaris',
        'coming_soon' => 'Fitur ini akan segera hadir.',
        'coming_soon_desc' => 'Anda akan dapat:',
        'feature_stock_levels' => 'Lihat level stok berdasarkan kategori',
        'feature_stock_movements' => 'Lacak pergerakan stok dari waktu ke waktu',
        'feature_valuation' => 'Buat laporan valuasi inventaris',
        'feature_export' => 'Ekspor ke Excel/PDF',

        // Transactions Report
        'transactions_title' => 'Transaksi',
        'transactions_subtitle' => 'Lihat semua transaksi inventaris',
        'transactions_report_title' => 'Laporan Transaksi',
        'feature_all_movements' => 'Lihat semua pergerakan stok',
        'feature_filter_date' => 'Filter berdasarkan rentang tanggal',
        'feature_filter_product' => 'Filter berdasarkan produk',
        'feature_filter_type' => 'Filter berdasarkan jenis transaksi',
    ],
];
