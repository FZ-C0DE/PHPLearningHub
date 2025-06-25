# Panduan Instalasi Bloggua - Blog Modern PHP MySQL

## Deskripsi Sistem
Bloggua adalah sistem blog modern dengan desain merah putih yang elegant dan profesional. Sistem ini dibangun khusus untuk deployment lokal dengan PHP dan MySQL, menggunakan Tailwind CSS untuk tampilan yang modern dan responsif.

## Fitur Utama
- ✅ Desain modern, elegant, dan profesional dengan Tailwind CSS
- ✅ Tema merah putih yang eye-catching
- ✅ Panel admin lengkap dengan dashboard interaktif
- ✅ CRUD lengkap untuk posts, kategori, dan komentar
- ✅ Sistem moderasi komentar
- ✅ Upload gambar dengan validasi keamanan
- ✅ Pencarian artikel real-time
- ✅ Pagination yang smooth
- ✅ Dashboard statistik dengan grafik interaktif
- ✅ Analytics views artikel dan tracking pengunjung
- ✅ Mobile responsive hamburger menu
- ✅ Live search dengan auto-suggestions
- ✅ Responsive design untuk semua perangkat
- ✅ Keamanan tinggi dengan prepared statements
- ✅ Session management dengan auto-logout
- ✅ Semua kode dan file menggunakan bahasa Indonesia

## Persyaratan Sistem
- PHP 8.2 atau lebih tinggi
- MySQL 8.0 atau MariaDB 10.4+
- XAMPP/LAMP/WAMP atau web server lainnya
- Ekstensi PHP: PDO MySQL, GD/Imagick untuk upload gambar

## Langkah Instalasi

### 1. Download dan Extract
```bash
# Extract semua file project ke direktori web server
# Windows (XAMPP): C:\xampp\htdocs\bloggua
# Linux (LAMP): /var/www/html/bloggua
# Mac (MAMP): /Applications/MAMP/htdocs/bloggua
```

### 2. Setup Database MySQL
```sql
-- Buat database baru
CREATE DATABASE db_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import schema database
-- Gunakan file database/schema.sql
```

### 3. Konfigurasi Database
Edit file `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Username MySQL Anda
define('DB_PASS', '');              // Password MySQL Anda  
define('DB_NAME', 'db_blog');
```

### 4. Set Permissions
```bash
# Pastikan folder uploads dapat ditulis
chmod 755 uploads/
# Atau di Windows, pastikan folder memiliki write permission
```

### 5. Import Database Schema
- Buka phpMyAdmin atau MySQL command line
- Import file `database/schema.sql`
- Atau jalankan: `mysql -u root -p db_blog < database/schema.sql`

### 6. Akses Website
- **Nyalakan XAMPP/LAMP**: Start Apache dan MySQL
- **Blog Utama**: `http://localhost/bloggua/beranda.php`
- **Admin Panel**: `http://localhost/bloggua/admin/masuk.php`
- **Login Admin**: 
  - Username: `admin`
  - Password: `password`

## Struktur File Utama

```
bloggua/
├── beranda.php              # Halaman utama blog (modern design)
├── artikel.php              # Halaman detail artikel  
├── index.php                # Redirect ke beranda.php
├── halaman-tidak-ditemukan.php # 404 page
├── admin/                   # Panel admin
│   ├── masuk.php           # Login admin
│   ├── dasbor.php          # Dashboard admin  
│   ├── kelola-post.php     # Manajemen posts
│   ├── buat-post.php       # Form buat post baru
│   ├── edit-post.php       # Form edit post
│   ├── kelola-kategori.php # Manajemen kategori
│   ├── moderasi-komentar.php # Moderasi komentar
│   ├── statistik.php      # Dashboard analytics interaktif
│   └── includes/           # Komponen admin
├── config/                 # Konfigurasi sistem
│   ├── database.php        # Koneksi database MySQL
│   └── session.php         # Manajemen session
├── models/                 # Model database
│   ├── Post.php           # Model post/artikel
│   ├── Category.php       # Model kategori  
│   ├── Comment.php        # Model komentar
│   └── Analytics.php      # Model analytics dan statistik
├── includes/              # Fungsi helper
│   └── functions.php      # Utility functions
├── assets/               # Asset files
│   └── js/
│       └── admin-modern.js # JavaScript admin modern
├── uploads/              # Directory upload gambar
├── database/            # Schema dan setup database
│   └── schema.sql       # SQL schema lengkap
├── api/                 # API endpoints
│   └── search.php       # Live search API
└── PANDUAN-INSTALASI.md # File ini
```

## Fitur Admin Panel

### Dashboard Modern
- Statistik real-time (total posts, kategori, komentar)
- Quick actions untuk tugas umum
- Recent posts overview
- Design modern dengan Tailwind CSS

### Manajemen Posts
- **Buat Post**: Form lengkap dengan editor HTML
- **Edit Post**: Update konten dengan preview
- **Kelola Post**: Daftar semua post dengan filter
- **Upload Gambar**: Thumbnail otomatis dengan validasi

### Manajemen Kategori  
- **CRUD Kategori**: Tambah, edit, hapus kategori
- **Validasi**: Cek kategori yang digunakan sebelum hapus
- **Organisasi**: Kelompokkan post berdasarkan kategori

### Moderasi Komentar
- **Approve/Reject**: Moderasi komentar dari pembaca
- **Bulk Actions**: Kelola banyak komentar sekaligus  
- **Filter Status**: Lihat berdasarkan status moderasi

## Keamanan

### Fitur Keamanan Terintegrasi
- **Prepared Statements**: Mencegah SQL injection
- **Password Hashing**: bcrypt untuk password admin
- **Input Validation**: Sanitasi semua input user
- **File Upload Security**: Validasi tipe dan ukuran file
- **Session Security**: Auto-logout setelah 30 menit inaktif
- **CSRF Protection**: Token untuk form submission

### Best Practices
- Ganti password admin default setelah instalasi
- Set permission folder yang tepat (tidak 777)
- Update PHP dan MySQL secara berkala
- Backup database secara rutin

## Kustomisasi

### Mengubah Tema Warna
Edit variabel CSS di setiap file:
```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'merah-utama': '#dc2626',    // Warna merah utama
                'merah-gelap': '#b91c1c',    // Warna merah gelap  
                'merah-muda': '#fef2f2',     // Warna merah muda
            }
        }
    }
}
```

### Menambah Admin User
```sql
INSERT INTO admin_users (username, password) 
VALUES ('username_baru', '$2y$10$hashed_password');
```

### Konfigurasi Upload
Edit `includes/functions.php`, ubah `$maxSize` di function `uploadImage()`:
```php
$maxSize = 5 * 1024 * 1024; // 5MB default
```

## Data Demo

Database sudah include data sample:
- 4 kategori (Teknologi, Lifestyle, Travel, Food)
- 2 artikel sample dengan komentar
- 1 admin user (admin/password)

## Troubleshooting

### Error Koneksi Database
- Pastikan MySQL service berjalan
- Cek kredensial di `config/database.php`
- Pastikan database `db_blog` sudah dibuat

### Upload Gambar Gagal  
- Cek permission folder `uploads/` (755)
- Pastikan ekstensi PHP `gd` terinstall
- Cek setting PHP `upload_max_filesize` dan `post_max_size`

### Session Issues
- Pastikan folder session PHP dapat ditulis
- Cek `session.save_path` di php.ini

### Error 404 pada Admin
- Pastikan semua file admin ada di folder `admin/`
- Cek case-sensitive nama file di Linux

## Support dan Pengembangan

### Demo Login
- URL: `http://localhost/bloggua/admin/masuk.php`
- Username: `admin`
- Password: `password`

### Fitur Tambahan Yang Bisa Dikembangkan
- Multi-user dengan role management
- SEO optimization dengan meta tags
- Social media integration
- Newsletter subscription
- Advanced text editor (WYSIWYG)
- Caching system untuk performa
- Backup/restore otomatis

---

© 2025 Bloggua - Sistem Blog Modern dengan Nuansa Merah Putih