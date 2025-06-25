# Bloggua - Sistem Blog Modern PHP MySQL

## Ringkasan Proyek
Sistem blog modern dengan desain elegant dan profesional menggunakan nuansa merah putih. Dibangun dengan PHP 8.2 dan MySQL, menggunakan Tailwind CSS untuk tampilan yang tidak kaku dan modern.

## Fitur Utama
✅ **Desain Modern**: Tampilan elegant profesional dengan Tailwind CSS  
✅ **Panel Admin Lengkap**: Dashboard dengan statistik dan CRUD operations  
✅ **Keamanan Tinggi**: Prepared statements, password hashing, validasi input  
✅ **Bahasa Indonesia**: Semua file dan komentar menggunakan bahasa Indonesia  
✅ **Responsive Design**: Berfungsi optimal di desktop dan mobile  
✅ **Upload Gambar**: Sistem upload dengan validasi keamanan  

## Struktur Database
**Nama Database**: `db_blog`

Tabel utama:
- `posts` - Artikel blog
- `categories` - Kategori artikel  
- `comments` - Komentar pembaca
- `admin_users` - User admin

## File Utama

### Frontend
- `beranda.php` - Halaman utama blog modern
- `artikel.php` - Detail artikel dengan komentar
- `index.php` - Redirect ke beranda.php

### Admin Panel  
- `admin/masuk.php` - Login admin
- `admin/dasbor.php` - Dashboard utama
- `admin/kelola-post.php` - Manajemen posts
- `admin/buat-post.php` - Form buat post
- `admin/edit-post.php` - Form edit post
- `admin/kelola-kategori.php` - Manajemen kategori
- `admin/moderasi-komentar.php` - Moderasi komentar

## Quick Start

1. **Setup Database**
```sql
CREATE DATABASE db_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **Import Schema**
```bash
mysql -u root -p db_blog < database/schema.sql
```

3. **Konfigurasi Database**
Edit `config/database.php`:
```php
define('DB_NAME', 'db_blog');
```

4. **Akses Website**
- Blog: `http://localhost/bloggua/beranda.php`
- Admin: `http://localhost/bloggua/admin/masuk.php`
- Login: `admin` / `password`

## Teknologi
- **Backend**: PHP 8.2 dengan arsitektur MVC
- **Database**: MySQL 8.0+ dengan PDO
- **Frontend**: Tailwind CSS via CDN
- **Security**: Prepared statements, bcrypt hashing
- **Upload**: Validasi file dengan GD extension

## Deployment
Sistem ini dirancang khusus untuk deployment lokal (XAMPP/LAMP) dengan MySQL. Semua konfigurasi sudah dioptimalkan untuk lingkungan development dan production lokal.

---
© 2025 Bloggua - Blog Modern Nuansa Merah Putih