# Panduan Instalasi MySQL untuk Bloggua

## Persiapan XAMPP/LAMP

### 1. Download dan Install XAMPP
- Download XAMPP dari: https://www.apachefriends.org/download.html
- Install dengan memilih komponen: Apache, MySQL, PHP, phpMyAdmin
- Jalankan XAMPP Control Panel

### 2. Start Services
- Klik "Start" pada Apache
- Klik "Start" pada MySQL
- Pastikan kedua service berjalan (hijau)

### 3. Akses phpMyAdmin
- Buka browser: `http://localhost/phpmyadmin`
- Login dengan username: `root`, password: kosong

## Setup Database Bloggua

### 1. Buat Database
```sql
CREATE DATABASE db_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Import Schema
- Pilih database `db_blog`
- Klik tab "Import"
- Pilih file `database/schema.sql`
- Klik "Go" untuk import

### 3. Verifikasi Tabel
Pastikan tabel berikut sudah terbuat:
- `admin_users`
- `categories` 
- `posts`
- `comments`
- `sliders`

## Konfigurasi Bloggua

### 1. Edit config/database.php
```php
private $host = 'localhost';
private $database = 'db_blog';
private $username = 'root';
private $password = ''; // kosong untuk XAMPP default
```

### 2. Copy Project ke htdocs
- Copy folder project ke: `C:\xampp\htdocs\bloggua\`
- Atau sesuai lokasi instalasi XAMPP

### 3. Akses Website
- Blog: `http://localhost/bloggua/beranda.php`
- Admin: `http://localhost/bloggua/admin/masuk.php`

## Login Admin Default
- Username: `admin`
- Password: `password`

## Troubleshooting

### MySQL tidak start
1. Periksa port 3306 tidak digunakan aplikasi lain
2. Restart XAMPP sebagai Administrator
3. Periksa log MySQL di XAMPP Control Panel

### Database connection failed
1. Pastikan MySQL service berjalan
2. Periksa nama database sudah benar: `db_blog`
3. Verifikasi username/password di config/database.php

### Tabel tidak ditemukan
1. Import ulang file database/schema.sql
2. Periksa database `db_blog` sudah dipilih
3. Refresh phpMyAdmin

## Catatan Penting
- **HANYA gunakan MySQL** - tidak ada PostgreSQL atau SQLite
- Database name harus `db_blog`
- Charset: `utf8mb4_unicode_ci`
- Engine: `InnoDB` untuk semua tabel
- Sistem dirancang khusus untuk XAMPP/LAMP lokal