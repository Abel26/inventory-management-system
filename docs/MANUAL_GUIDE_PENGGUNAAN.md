# Manual Guide Penggunaan - Ebara Inventory Management System

## Daftar Isi

1. [Pengenalan Sistem](#1-pengenalan-sistem)
2. [Landing Page](#2-landing-page)
3. [Proses Login](#3-proses-login)
4. [Dashboard Utama](#4-dashboard-utama)
5. [Modul Asset Management](#5-modul-asset-management)
6. [Modul Master Data](#6-modul-master-data)
7. [Modul Laporan](#7-modul-laporan)
8. [Modul Manajemen Pengguna](#8-modul-manajemen-pengguna)
9. [Modul Inventaris](#9-modul-inventaris)
10. [Fitur Tambahan](#10-fitur-tambahan)

---

## 1. Pengenalan Sistem

Ebara Inventory Management System adalah aplikasi berbasis web yang dirancang untuk mengelola inventaris perusahaan secara efisien. Sistem ini dilengkapi dengan fitur QR Code tracking, multi-bahasa (Indonesia/English), dan dashboard interaktif untuk monitoring real-time.

### Fitur Utama:
- ✅ Manajemen Asset (Material, Tools, Models)
- ✅ QR Code Generation & Scanning
- ✅ Multi-bahasa (Indonesia/English)
- ✅ Dashboard Real-time
- ✅ Laporan & Reporting
- ✅ Role-based Access Control
- ✅ Mobile Responsive Design

---

## 2. Landing Page

### 2.1 Akses Landing Page
- **URL**: `http://localhost/` atau domain aplikasi Anda
- **Akses**: Publik (tidak memerlukan login)

### 2.2 Komponen Landing Page

#### Hero Section
- **Logo Ebara**: Menampilkan branding perusahaan
- **Judul Utama**: "Sistem Manajemen Inventaris Modern"
- **Deskripsi**: Penjelasan singkat tentang sistem
- **CTA Button**: Tombol "Masuk ke Dashboard" untuk redirect ke halaman login

#### Quick Asset Check
- **Search Bar**: Pencarian cepat asset tanpa login
- **Fitur Search**:
  - Minimal 2 karakter untuk trigger pencarian
  - Real-time search results
  - Menampilkan kode, nama, dan lokasi asset
  - Support untuk material, tools, dan models

#### Language Switcher
- **Posisi**: Header kanan
- **Opsi**: Indonesia 🇮🇩 / English 🇬🇧
- **Fungsi**: Mengubah bahasa seluruh aplikasi secara instan

### 2.3 Cara Penggunaan Landing Page

1. **Cari Asset Tanpa Login**:
   - Ketik minimal 2 karakter di search bar
   - Tunggu hasil muncul otomatis
   - Klik hasil untuk melihat detail asset

2. **Akses Login**:
   - Klik tombol "Masuk ke Dashboard"
   - Akan diarahkan ke halaman login

---

## 3. Proses Login

### 3.1 Akses Halaman Login
- **URL**: `/login`
- **Method**: POST

### 3.2 Komponen Login Form

#### Form Fields
- **Email**: Email terdaftar user
- **Password**: Password user
- **Remember Me**: Checkbox untuk ingat login (30 hari)

#### Security Features
- **CSRF Protection**: Token keamanan Laravel
- **Rate Limiting**: Maksimal 5 attempts per menit
- **Session Security**: Http-only cookies dengan SameSite strict

#### Validation Messages
- **Email Required**: Email harus diisi
- **Password Required**: Password harus diisi
- **Invalid Credentials**: Email atau password salah
- **Account Locked**: Akun terkunci karena terlalu banyak percobaan

### 3.3 Cara Login

1. **Buka Halaman Login**:
   - Klik "Masuk ke Dashboard" dari landing page
   - Atau langsung akses `/login`

2. **Input Kredensial**:
   - Masukkan email yang terdaftar
   - Masukkan password yang benar

3. **Opsional Remember Me**:
   - Centang "Ingat saya" untuk auto-login
   - Berlaku 30 hari

4. **Submit Login**:
   - Klik tombol "Sign In"
   - Tunggu proses autentikasi

5. **Redirect**:
   - Sukses: Diarahkan ke dashboard
   - Gagal: Tampilkan error message

### 3.4 Troubleshooting Login

#### Problem: Login Gagal
- **Solution**: Periksa email dan password, case-sensitive

#### Problem: Akun Terkunci
- **Solution**: Tunggu 1 menit atau hubungi admin

#### Problem: Lupa Password
- **Solution**: Gunakan fitur "Forgot Password" (jika tersedia)

---

## 4. Dashboard Utama

### 4.1 Akses Dashboard
- **URL**: `/dashboard`
- **Requirement**: Login terlebih dahulu

### 4.2 Komponen Dashboard

#### Welcome Banner
- **Dynamic Greeting**: "Selamat Pagi/Siang/Sore/Malam, [Nama User]"
- **Critical Reports Summary**: Jumlah laporan kritis
- **Action Buttons**:
  - "Laporkan Masalah": Redirect ke form laporan
  - "Download Report": Export dashboard ke Excel

#### Statistics Cards
- **Total Assets**: Jumlah total semua asset
- **Low Stock Items**: Asset dengan stok menipis
- **Critical Reports**: Laporan dengan status kritis
- **Active Users**: User yang sedang online

#### Charts Section
- **Asset Distribution**: Pie chart per jenis asset
- **Stock Trends**: Line chart trend stok 30 hari
- **Reports by Status**: Bar chart status laporan

#### Recent Activities
- **Latest Reports**: 5 laporan terbaru
- **Recent Asset Updates**: Asset yang baru diupdate
- **System Notifications**: Notifikasi sistem

### 4.3 Fitur Dashboard

#### Global Search (Omni-Search)
- **Position**: Top navigation bar
- **Function**: Pencarian semua data dalam satu tempat
- **Search Scope**:
  - Asset Materials
  - Asset Tools
  - Asset Models
  - Reports
  - Users

#### Quick Actions
- **Add New Asset**: Quick add material/tool/model
- **Create Report**: Quick create issue report
- **QR Scanner**: Quick scan QR code

#### Filters & Date Range
- **Date Filter**: Filter data berdasarkan tanggal
- **Status Filter**: Filter berdasarkan status
- **Location Filter**: Filter berdasarkan lokasi/gedung

### 4.4 Cara Penggunaan Dashboard

#### Melihat Statistik
1. **Buka Dashboard**: Login dan diarahkan otomatis
2. **View Cards**: Lihat statistik utama di bagian atas
3. **Analyze Charts**: Perhatikan grafik untuk trend analysis

#### Menggunakan Global Search
1. **Klik Search Icon**: Di top navigation
2. **Ketik Query**: Minimal 2 karakter
3. **Pilih Category**: Pilih jenis pencarian
4. **View Results**: Klik hasil untuk detail

#### Export Data
1. **Download Excel**: Klik "Download Report"
2. **Choose Date Range**: Pilih periode data
3. **Select Data Type**: Pilih jenis data yang diexport
4. **Download**: File Excel otomatis di-download

---

## 5. Modul Asset Management

### 5.1 Struktur Menu Asset

#### Menu Utama: Assets
- **Submenu Materials**: Manajemen material inventaris
- **Submenu Tools**: Manajemen peralatan
- **Submenu Models**: Manajemen model/mold

### 5.2 Asset Materials

#### Akses
- **URL**: `/assets/materials`
- **Menu**: Sidebar → Assets → Materials

#### Features
- **Data Table**: Daftar material dengan pagination
- **Search & Filter**: Pencarian dan filter berdasarkan kriteria
- **CRUD Operations**: Create, Read, Update, Delete
- **QR Code**: Generate QR code untuk setiap material
- **Export**: Export data ke Excel/PDF

#### Form Fields
- **Material Code**: Kode unik auto-generated
- **Name**: Nama material
- **Type**: Tipe material (dropdown)
- **Quantity**: Jumlah stok
- **Unit**: Satuan (pcs, kg, liter, dll)
- **Min Threshold**: Batas minimum stok
- **Supplier**: Nama supplier
- **Entry Date**: Tanggal masuk
- **Expiry Date**: Tanggal kadaluarsa (opsional)
- **Location**: Lokasi penyimpanan
- **Description**: Deskripsi material
- **Unit Price**: Harga per satuan
- **Gedung**: Lokasi gedung (dropdown)
- **Image**: Upload foto material

#### Status Indicators
- **In Stock**: Hijau ✅ (stok aman)
- **Low Stock**: Kuning ⚠️ (stok menipis)
- **Out of Stock**: Merah ❌ (stok habis)

#### Cara Penggunaan

**Tambah Material Baru**:
1. Klik tombol "Tambah Material"
2. Isi semua form field yang required
3. Upload foto (opsional)
4. Klik "Simpan"
5. QR Code otomatis dibuat

**Edit Material**:
1. Klik icon edit di data table
2. Ubah data yang diperlukan
3. Klik "Update"

**Generate QR Code**:
1. Klik icon QR code di data table
2. QR Code muncul dalam modal
3. Download atau print QR Code

**Export Data**:
1. Klik tombol "Export"
2. Pilih format (Excel/PDF)
3. File otomatis di-download

### 5.3 Asset Tools

#### Akses
- **URL**: `/assets/tools`
- **Menu**: Sidebar → Assets → Tools

#### Features
- **Tool Management**: Manajemen peralatan inventaris
- **Purchase Information**: Tracking pembelian tools
- **Maintenance Schedule**: Jadwal maintenance tools
- **QR Code Tracking**: QR code untuk setiap tool

#### Form Fields (Tambahan dari Materials)
- **Purchase Date**: Tanggal pembelian
- **Purchase Price**: Harga pembelian
- **Warranty**: Masa garansi
- **Maintenance Schedule**: Jadwal maintenance
- **Condition**: Kondisi tool (Baik/Rusak/Maintenance)

#### Cara Penggunaan
Sama dengan Asset Materials dengan tambahan fitur tracking pembelian dan maintenance.

### 5.4 Asset Models

#### Akses
- **URL**: `/assets/models`
- **Menu**: Sidebar → Assets → Models

#### Features
- **Model Management**: Manajemen model/mold
- **Material Association**: Hubungkan model dengan materials
- **Modification History**: Track perubahan model
- **QR Code Generation**: QR code untuk model

#### Form Fields
- **Model Code**: Kode unik model
- **Name**: Nama model
- **Description**: Deskripsi model
- **Materials**: Material yang digunakan (multi-select)
- **Created Date**: Tanggal pembuatan
- **Status**: Status model (Active/Inactive)
- **Image**: Foto model

#### Modification History
- **Track Changes**: Setiap perubahan tercatat
- **Version Control**: Manajemen versi model
- **Change Log**: Log perubahan detail

---

## 6. Modul Master Data

### 6.1 Struktur Menu Master Data

#### Menu Utama: Master Data
- **Submenu Gedung**: Manajemen lokasi gedung
- **Submenu Satuan**: Manajemen satuan unit

### 6.2 Manajemen Gedung

#### Akses
- **URL**: `/master-data/gedungs`
- **Menu**: Sidebar → Master Data → Gedung

#### Features
- **Location Management**: Manajemen lokasi gedung/floor
- **Asset Assignment**: Assign asset ke gedung
- **Hierarchical Structure**: Struktur hierarki lokasi

#### Form Fields
- **Nama Gedung**: Nama lengkap gedung
- **Kode**: Kode unik gedung
- **Alamat**: Alamat lengkap
- **Kapasitas**: Kapasitas maksimal
- **Description**: Deskripsi gedung
- **Status**: Status gedung (Active/Inactive)

#### Cara Penggunaan
1. **Tambah Gedung**: Klik "Tambah Gedung" → Isi form → Simpan
2. **Edit Gedung**: Klik icon edit → Ubah data → Update
3. **Hapus Gedung**: Klik icon delete → Konfirmasi → Hapus

### 6.3 Manajemen Satuan

#### Akses
- **URL**: `/master-data/satuans`
- **Menu**: Sidebar → Master Data → Satuan

#### Features
- **Unit Management**: Manajemen satuan unit
- **Standardization**: Standardisasi satuan
- **Conversion**: Konversi antar satuan (jika diperlukan)

#### Form Fields
- **Nama Satuan**: Nama satuan (pcs, kg, liter, dll)
- **Kode**: Kode singkat satuan
- **Description**: Deskripsi satuan
- **Type**: Tipe satuan (Weight, Volume, Quantity, dll)

#### Default Satuan
- **Pieces**: pcs
- **Kilogram**: kg
- **Liter**: liter
- **Meter**: m
- **Box**: box

---

## 7. Modul Laporan

### 7.1 Struktur Menu Laporan

#### Menu Utama: Reports
- **Submenu Report List**: Daftar semua laporan
- **Submenu Scan QR**: Scanner QR code untuk laporan

### 7.2 Report List

#### Akses
- **URL**: `/reports`
- **Menu**: Sidebar → Reports → Report List

#### Features
- **Report Management**: Manajemen semua laporan
- **Status Tracking**: Tracking status laporan
- **Priority Management**: Manajemen prioritas laporan
- **Assignment**: Assign laporan ke user

#### Report Types
- **Issue Report**: Laporan masalah/issue
- **Damage Report**: Laporan kerusakan
- **Loss Report**: Laporan kehilangan
- **Maintenance Report**: Laporan maintenance

#### Form Fields
- **Report Type**: Tipe laporan
- **Title**: Judul laporan
- **Description**: Deskripsi detail
- **Asset**: Asset terkait (auto-fill dari QR scan)
- **Priority**: Prioritas (Low/Medium/High/Critical)
- **Location**: Lokasi kejadian
- **Reported By**: Pelapor (auto-fill user login)
- **Date**: Tanggal laporan
- **Attachments**: File attachment (opsional)

#### Status Workflow
1. **Open**: Laporan baru dibuat
2. **In Progress**: Sedang diproses
3. **Resolved**: Telah diselesaikan
4. **Closed**: Laporan ditutup
5. **Rejected**: Laporan ditolak

#### Cara Penggunaan

**Buat Laporan Baru**:
1. Klik "Buat Laporan"
2. Pilih tipe laporan
3. Isi semua field required
4. Upload attachment jika ada
5. Klik "Submit"

**Track Status**:
1. Lihat daftar laporan
2. Klik laporan untuk detail
3. Lihat status dan progress
4. Add comment jika diperlukan

### 7.3 QR Scanner

#### Akses
- **URL**: `/reports/scan`
- **Menu**: Sidebar → Reports → Scan QR

#### Features
- **QR Code Scanner**: Scan QR code menggunakan camera
- **Auto Report**: Auto-generate report dari QR scan
- **Camera Access**: Akses camera device
- **Manual Input**: Input manual jika QR tidak terbaca

#### Cara Penggunaan
1. **Buka Scanner**: Klik menu Scan QR
2. **Allow Camera**: Berikan izin akses camera
3. **Scan QR**: Arahkan camera ke QR code
4. **Auto Detect**: System otomatis detect asset
5. **Create Report**: Form laporan terisi otomatis
6. **Submit**: Kirim laporan

---

## 8. Modul Manajemen Pengguna

### 8.1 Struktur Menu Pengaturan Akses

#### Menu Utama: Pengaturan Akses
- **Submenu User Management**: Manajemen pengguna
- **Submenu Role Management**: Manajemen role dan permission

### 8.2 User Management

#### Akses
- **URL**: `/users`
- **Menu**: Sidebar → Pengaturan Akses → User Management

#### Features
- **User CRUD**: Create, Read, Update, Delete users
- **Status Management**: Active/Inactive user
- **Role Assignment**: Assign role ke user
- **Profile Management**: Management profil user

#### Form Fields
- **Name**: Nama lengkap user
- **Email**: Email user (unique)
- **Password**: Password (auto-generated untuk new user)
- **Role**: Role user (dropdown)
- **Department**: Department user
- **Phone**: Nomor telepon
- **Status**: Status user (Active/Inactive)

#### User Status
- **Active**: User dapat login
- **Inactive**: User tidak dapat login
- **Suspended**: User ditangguhkan sementara

#### Cara Penggunaan
1. **Tambah User**: Klik "Tambah User" → Isi form → Simpan
2. **Edit User**: Klik icon edit → Ubah data → Update
3. **Reset Password**: Klik "Reset Password" → Generate new password
4. **Toggle Status**: Klik toggle untuk active/inactive

### 8.3 Role Management

#### Akses
- **URL**: `/roles`
- **Menu**: Sidebar → Pengaturan Akses → Role Management

#### Features
- **Role CRUD**: Create, Read, Update, Delete roles
- **Permission Management**: Management permission per role
- **Module-based**: Permission berdasarkan module

#### Default Roles
- **Super Admin**: Full access semua fitur
- **Admin**: Access kecuali user management
- **Manager**: Access ke asset dan reports
- **Staff**: Access terbatas sesuai department
- **Viewer**: Read-only access

#### Permission Matrix

| Module | Super Admin | Admin | Manager | Staff | Viewer |
|--------|-------------|--------|---------|--------|--------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Assets (CRUD) | ✅ | ✅ | ✅ | ⚠️ | ❌ |
| Master Data | ✅ | ✅ | ❌ | ❌ | ❌ |
| Reports | ✅ | ✅ | ✅ | ✅ | ✅ |
| User Management | ✅ | ❌ | ❌ | ❌ | ❌ |
| Role Management | ✅ | ❌ | ❌ | ❌ | ❌ |

**Legend**: ✅ Full Access, ⚠️ Limited Access, ❌ No Access

#### Cara Penggunaan
1. **Create Role**: Klik "Tambah Role" → Nama role → Pilih permissions → Simpan
2. **Edit Role**: Klik icon edit → Ubah permissions → Update
3. **Assign Role**: Di user management, pilih role untuk user

---

## 9. Modul Inventaris

### 9.1 Struktur Menu Inventaris

#### Menu Tersembunyi (Dalam Development)
- **Stock In**: Penerimaan barang
- **Stock Out**: Pengeluaran barang
- **History**: Riwayat transaksi

### 9.2 Stock Management

#### Features (Planned)
- **Stock In**: Record penerimaan barang
- **Stock Out**: Record pengeluaran barang
- **Adjustment**: Penyesuaian stok
- **Transfer**: Transfer antar lokasi

#### Transaction Types
- **Purchase**: Pembelian baru
- **Return**: Retur barang
- **Usage**: Pemakaian barang
- **Damage**: Barang rusak
- **Loss**: Barang hilang

---

## 10. Fitur Tambahan

### 10.1 Global Search

#### Access
- **Position**: Top navigation bar
- **Shortcut**: Ctrl+K (keyboard shortcut)

#### Search Scope
- **Assets**: Materials, Tools, Models
- **Reports**: All reports
- **Users**: User management
- **Master Data**: Gedungs, Satuans

#### Advanced Search
- **Filters**: Filter berdasarkan kategori
- **Date Range**: Filter berdasarkan tanggal
- **Status**: Filter berdasarkan status
- **Location**: Filter berdasarkan lokasi

### 10.2 QR Code System

#### Generation
- **Auto Generate**: QR code otomatis untuk setiap asset
- **Custom Design**: QR code dengan branding
- **Batch Generate**: Generate multiple QR codes
- **Export Options**: Export PNG, SVG, PDF

#### Scanning
- **Mobile Scanner**: Scanner menggunakan camera mobile
- **Desktop Scanner**: Scanner menggunakan webcam
- **Bulk Scanner**: Scan multiple QR codes
- **Manual Input**: Input manual jika scan gagal

#### QR Code Information
- **Asset Details**: Nama, kode, deskripsi
- **Location**: Lokasi asset
- **Status**: Status current asset
- **History**: Riwayat perubahan

### 10.3 Multi-Language Support

#### Available Languages
- **Indonesia**: Bahasa utama sistem
- **English**: Bahasa Inggris untuk international

#### Language Switching
- **Method**: Dropdown di header
- **Persistence**: Language preference disimpan di session
- **Scope**: Seluruh aplikasi termasuk error messages

#### Translation Coverage
- **UI Elements**: Menu, button, form labels
- **Messages**: Success, error, info messages
- **Reports**: Laporan dalam bahasa yang dipilih
- **Email**: Template email multi-bahasa

### 10.4 Export & Reporting

#### Export Formats
- **Excel**: .xlsx format dengan formatting
- **PDF**: .pdf format dengan header/footer
- **CSV**: .csv format untuk data processing
- **Print**: Print-friendly layout

#### Report Types
- **Asset Reports**: Daftar semua asset
- **Stock Reports**: Laporan stok
- **Transaction Reports**: Laporan transaksi
- **Summary Reports**: Ringkasan statistik

#### Custom Reports
- **Date Range**: Pilih periode laporan
- **Filters**: Filter berdasarkan kriteria
- **Columns**: Pilih kolom yang ditampilkan
- **Sorting**: Sort berdasarkan kolom

### 10.5 Notifications

#### System Notifications
- **Real-time**: Real-time updates
- **Email**: Email notifications
- **In-app**: In-app notification center
- **Push**: Push notifications (mobile)

#### Notification Types
- **Low Stock**: Notifikasi stok menipis
- **Report Updates**: Update status laporan
- **System Alerts**: Alert sistem
- **User Actions**: Aktivitas user penting

---

## Troubleshooting Common Issues

### Login Issues
- **Problem**: Cannot login
- **Solution**: Check credentials, clear browser cache, contact admin

### QR Code Issues
- **Problem**: QR code not scanning
- **Solution**: Check camera permission, ensure good lighting, try manual input

### Performance Issues
- **Problem**: Slow loading
- **Solution**: Check internet connection, clear browser cache, contact IT

### Data Issues
- **Problem**: Data not saving
- **Solution**: Check required fields, check internet connection, try again

---

## Best Practices

### Data Entry
- **Consistent Naming**: Use consistent naming conventions
- **Complete Information**: Fill all required fields
- **Regular Updates**: Keep data up-to-date
- **Backup**: Regular backup important data

### Security
- **Strong Passwords**: Use complex passwords
- **Regular Changes**: Change passwords regularly
- **Access Control**: Limit access to sensitive data
- **Logout**: Always logout when done

### QR Code Management
- **Clear QR Codes**: Ensure QR codes are clear and scannable
- **Regular Testing**: Test QR codes regularly
- **Backup QR Codes**: Keep backup of QR codes
- **Proper Placement**: Place QR codes in accessible locations

---

## Contact & Support

### Technical Support
- **Email**: support@ebara.com
- **Phone**: +62-21-1234-5678
- **Hours**: Monday-Friday, 08:00-17:00

### User Training
- **Onsite Training**: Available upon request
- **Online Training**: Monthly webinars
- **Documentation**: Always available in system
- **Video Tutorials**: Available in help center

---

**Document Version**: 1.0  
**Last Updated**: 17 Februari 2026  
**Next Review**: 17 Mei 2026