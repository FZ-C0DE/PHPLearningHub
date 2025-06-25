# Bloggua - Blog System PHP MySQL

## Instalasi XAMPP Local

### 1. Download dan Install XAMPP
- Download XAMPP dari: https://www.apachefriends.org/download.html
- Install dengan komponen: Apache, MySQL, PHP, phpMyAdmin

### 2. Setup Database
1. Start XAMPP Control Panel
2. Start Apache dan MySQL
3. Buka browser: `http://localhost/phpmyadmin`
4. Create database: `db_blog`
5. Import file: `database/schema.sql`

### 3. Copy Project
- Copy folder project ke: `C:\xampp\htdocs\bloggua\`
- Akses: `http://localhost/bloggua/beranda.php`

### 4. Login Admin
- URL: `http://localhost/bloggua/admin/masuk.php`
- Username: `admin`
- Password: `password`

## Struktur File
```
admin/
├── masuk.php          # Login admin
├── dasbor.php         # Dashboard
├── kelola-post.php    # Manajemen post
├── kelola-kategori.php # Manajemen kategori
├── kelola-slider.php  # Manajemen slider
└── moderasi-komentar.php # Moderasi komentar
```

## Fitur
- Blog dengan sistem post dan kategori
- Panel admin dengan dashboard lengkap
- Sistem slider untuk beranda
- Manajemen komentar
- Upload gambar
- Pencarian artikel
- Desain responsive

## Database
- Engine: MySQL (InnoDB)
- Charset: utf8mb4_unicode_ci
- Tables: posts, categories, comments, admin_users, sliders

## Security
- Password hashing dengan bcrypt
- Prepared statements
- Input sanitization
- Session management

Sistem ini dirancang khusus untuk XAMPP/LAMP lokal dengan MySQL.