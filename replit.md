# Project Documentation

## Overview

Bloggua adalah sistem blog PHP lengkap dengan database MySQL dan desain modern menggunakan Tailwind CSS. Sistem ini memiliki nuansa merah putih yang elegan dan profesional, dengan panel admin yang komprehensif untuk operasi CRUD pada post, kategori, dan komentar. Dibangun dengan praktik keamanan terbaik termasuk prepared statements, password hashing, dan validasi input. Semua komentar kode dan penamaan file menggunakan bahasa Indonesia sesuai permintaan pengguna.

## System Architecture

### Current Architecture
- **Frontend**: Interface blog responsif dengan tema merah/putih menggunakan Tailwind CSS
- **Backend**: PHP 8.2 dengan arsitektur bergaya MVC dan komentar bahasa Indonesia
- **Database**: MySQL dengan skema terstruktur untuk posts, categories, comments, dan admin users
- **Web Server**: PHP built-in development server pada port 5000
- **Admin Panel**: Dashboard lengkap dengan autentikasi dan manajemen konten

### Technology Stack
- PHP 8.2 (bahasa backend utama)
- MySQL (database eksklusif dengan koneksi PDO)
- Tailwind CSS via CDN (styling modern)
- HTML5/CSS3/JavaScript (frontend)
- Security: PDO prepared statements, password hashing, session management


## Key Components

### Web Server Configuration
- **Server**: PHP built-in server (`php -S 0.0.0.0:5000`)
- **Port**: Internal/external port 5000
- **Workflow**: Automated PHP server startup

### File Structure
- `beranda.php`: Halaman utama blog dengan daftar post dan pencarian (desain modern)
- `artikel.php`: Halaman detail artikel dengan sistem komentar
- `index.php`: Redirect ke beranda.php untuk kompatibilitas
- `admin/`: Panel admin lengkap dengan penamaan file bahasa Indonesia
  - `masuk.php`: Halaman login admin
  - `dasbor.php`: Dashboard admin
  - `kelola-post.php`: Manajemen semua post
  - `buat-post.php`: Form membuat post baru
  - `edit-post.php`: Form edit post
  - `kelola-kategori.php`: Manajemen kategori
  - `moderasi-komentar.php`: Moderasi komentar
  - `statistik.php`: Dashboard analytics dengan grafik interaktif
- `models/`: Model database (Post, Category, Comment, Analytics) dengan komentar Indonesia
- `config/`: Konfigurasi database dan session
- `assets/`: File CSS/JS dengan desain modern menggunakan Tailwind CSS
- `uploads/`: Direktori upload gambar dengan pembatasan keamanan

## Data Flow

Complete blog system with database integration:
1. HTTP requests arrive at port 5000
2. PHP server processes requests with routing based on file structure
3. Database queries via PDO with prepared statements
4. Admin authentication via sessions with 30-minute timeout
5. File uploads processed with security validation
6. Content rendered with XSS protection and input sanitization

## External Dependencies

### Runtime Dependencies
- PHP 8.2 with PDO MySQL extension
- MySQL database server
- GD or Imagick extension for image processing

### Infrastructure Dependencies
- PHP built-in development server
- MySQL database with utf8mb4 charset
- File system permissions for uploads directory

## Deployment Strategy

### Current Deployment
- **Platform**: Designed for local XAMPP/LAMP environments
- **Server**: PHP built-in development server for testing
- **Database**: MySQL with complete schema and sample data
- **Security**: Production-ready security measures implemented

### Scalability Considerations
- Uses PHP development server (suitable for local development)
- Complete database integration with optimized queries
- Session-based authentication with timeout management
- Comprehensive error handling and input validation

## Changelog

```
Changelog:
- June 25, 2025: Sistem blog Bloggua lengkap dengan desain modern
  - Blog PHP dengan tema merah putih menggunakan Tailwind CSS
  - Database MySQL dengan posts, categories, comments, sliders
  - Panel admin dengan operasi CRUD dan penamaan Indonesia
  - Fitur keamanan: prepared statements, password hashing
  - Sistem upload gambar dengan validasi
  - Desain responsif untuk mobile/desktop
  - Fungsi pencarian dan pagination
  - Sistem moderasi komentar
  - Dashboard statistik dengan grafik interaktif (Chart.js)
  - Analytics tracking views artikel dan pengunjung
  - Widget mini analytics di dashboard utama
  - API endpoint untuk real-time data analytics
  - Mobile responsive navigation dengan hamburger menu
  - Live search dengan suggestions dan debouncing
  - Sistem slider beranda yang terhubung dengan admin panel
  - Hero slider dengan animasi dan navigasi otomatis
  - Kelola slider melalui admin panel dengan upload gambar
  - Routing diperbaiki untuk kompatibilitas XAMPP/Apache lokal
  - File admin menggunakan penamaan Indonesia (dasbor.php, kelola-post.php, dll)
  - Semua komentar kode dan nama file dalam bahasa Indonesia
  - Tampilan modern, elegant, dan profesional tanpa referensi demo
```

## User Preferences

```
Preferred communication style: Simple, everyday language.
Project requirements: 
- PHP dan MySQL saja untuk komputer lokal (XAMPP/LAMP)

- Desain tidak kaku dan kuno, harus modern elegant profesional
- Gunakan Tailwind CSS via CDN
- Semua komentar kode dalam bahasa Indonesia
- Penamaan file menggunakan bahasa Indonesia
- Tampilan website modern dan profesional
- Deployment lokal XAMPP/LAMP saja
```

## Development Notes

### Completed Features
1. ✅ Sistem blog PHP lengkap dengan arsitektur MVC dan komentar Indonesia
2. ✅ Database MySQL dengan skema penuh dan relasi
3. ✅ Autentikasi admin dan manajemen session
4. ✅ Operasi CRUD untuk posts, kategori, komentar
5. ✅ Sistem upload gambar dengan validasi keamanan
6. ✅ Desain responsif tema merah/putih modern dengan Tailwind CSS
7. ✅ Fungsi pencarian dan pagination
8. ✅ Sistem moderasi komentar
9. ✅ Langkah-langkah keamanan (prepared statements, password hashing)
10. ✅ Tampilan modern, elegant, dan profesional
11. ✅ Penamaan file dan komentar dalam bahasa Indonesia
12. ✅ Interface admin yang user-friendly dengan animasi modern
13. ✅ Dashboard statistik dengan grafik interaktif menggunakan Chart.js
14. ✅ Sistem tracking views artikel dengan analytics lengkap
15. ✅ Grafik real-time untuk views, post populer, kategori, dan trend bulanan
16. ✅ Mobile responsive hamburger menu yang berfungsi dengan baik
17. ✅ Live search dengan suggestions dan API endpoint terpisah
18. ✅ Pencarian responsif dengan debouncing dan highlight results

### Architecture Decisions
- **PHP Server**: PHP built-in server untuk development, dirancang untuk deployment LAMP lokal
- **MySQL Database**: Database relasional terstruktur dengan foreign keys yang tepat
- **MVC Pattern**: Model untuk akses data, pemisahan concerns
- **Security First**: Semua input disanitasi, prepared statements digunakan di seluruh sistem
- **Local Deployment**: Dioptimalkan khusus untuk lingkungan XAMPP/LAMP lokal
- **Modern Design**: Menggunakan Tailwind CSS untuk tampilan yang tidak kaku dan profesional  
- **Indonesian Naming**: Semua file dan komentar menggunakan bahasa Indonesia sesuai permintaan
- **Database Name**: Menggunakan nama database "db_blog" untuk deployment MySQL lokal
- **Pure MySQL**: Hanya menggunakan MySQL untuk database
- **Local Only**: Sistem dirancang khusus untuk XAMPP/LAMP lokal