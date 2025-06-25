# Panduan Penggunaan Bloggua

## Instalasi di XAMPP/LAMP

### 1. Persiapan Database
- Pastikan XAMPP/LAMP sudah terinstall dan MySQL berjalan
- Buka phpMyAdmin di browser: `http://localhost/phpmyadmin`
- Buat database baru dengan nama: `db_blog`
- Import file `database/schema.sql` ke database `db_blog`


### 2. Konfigurasi Database
- Edit file `config/database.php`
- Pastikan konfigurasi database sesuai:
  ```php
  private $host = 'localhost';
  private $database = 'db_blog';
  private $username = 'root';
  private $password = '';
  ```

### 3. Instalasi di XAMPP
- Copy seluruh folder project ke `htdocs/bloggua/`
- Akses blog di browser: `http://localhost/bloggua/beranda.php`
- Akses admin di browser: `http://localhost/bloggua/admin/masuk.php`

## Login Admin
- Username: `admin`
- Password: `password`

## Fitur Utama

### 1. Panel Admin
- **Dashboard (dasbor.php)**: Statistik dan overview sistem
- **Kelola Post (kelola-post.php)**: Manajemen artikel blog
- **Buat Post (buat-post.php)**: Membuat artikel baru
- **Edit Post (edit-post.php)**: Mengedit artikel existing
- **Kelola Kategori (kelola-kategori.php)**: Manajemen kategori
- **Moderasi Komentar (moderasi-komentar.php)**: Moderasi komentar
- **Kelola Slider (kelola-slider.php)**: Manajemen slider beranda

### 2. Sistem Slider
- Kelola gambar slider dari admin panel
- Support URL gambar eksternal (Unsplash, dll)
- Auto-slide dengan navigasi manual
- Responsive design untuk mobile/desktop

### 3. Blog Frontend
- **Beranda (beranda.php)**: Halaman utama dengan slider dan daftar post
- **Artikel (artikel.php)**: Halaman detail artikel dengan komentar
- **Pencarian**: Live search dengan suggestions
- **Responsive**: Mobile-friendly design

## File Penting

### Struktur Admin
```
admin/
├── masuk.php           # Login admin
├── dasbor.php          # Dashboard utama
├── kelola-post.php     # Manajemen post
├── buat-post.php       # Form buat post
├── edit-post.php       # Form edit post
├── kelola-kategori.php # Manajemen kategori
├── moderasi-komentar.php # Moderasi komentar
├── kelola-slider.php   # Manajemen slider
└── keluar.php          # Logout
```

### Database Schema
- `posts`: Artikel blog
- `categories`: Kategori artikel
- `comments`: Komentar artikel
- `admin_users`: User admin
- `sliders`: Data slider beranda

## Keamanan
- Password hashing dengan bcrypt
- Prepared statements untuk SQL
- Input sanitization
- Session management dengan timeout
- File upload validation

## Troubleshooting

### Error 404 Admin
Jika mengalami error 404 pada halaman admin:
1. Pastikan menggunakan URL lengkap: `http://localhost/bloggua/admin/dasbor.php`
2. Periksa file .htaccess tidak mengalami conflict
3. Restart Apache service

### Database Connection Error
1. Periksa service MySQL sudah berjalan (Start MySQL di XAMPP)
2. Verifikasi kredensial database di `config/database.php`
3. Pastikan database `db_blog` sudah dibuat
4. Import database/schema.sql ke phpMyAdmin


### Slider Tidak Muncul
1. Periksa tabel `sliders` sudah dibuat
2. Pastikan ada data slider aktif di database
3. Periksa URL gambar dapat diakses

## Support
- Semua komentar kode dalam bahasa Indonesia
- File admin menggunakan penamaan Indonesia
- Desain responsif modern dengan Tailwind CSS
- Kompatibel dengan PHP 8.2 dan MySQL 8.0
- KHUSUS untuk deployment XAMPP/LAMP lokal