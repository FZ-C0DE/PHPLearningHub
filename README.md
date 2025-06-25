# Bloggua - Blog Sederhana dengan Nuansa Merah Putih

## Deskripsi
Bloggua adalah sistem blog sederhana yang dibangun dengan PHP dan MySQL, menampilkan desain elegant dengan nuansa warna dominan putih dan merah. Blog ini dilengkapi dengan admin panel yang lengkap untuk mengelola konten.

## Fitur Utama

### Frontend (Blog)
- ✅ Tampilan responsif dengan desain merah putih yang elegan
- ✅ Halaman beranda dengan grid post yang menarik
- ✅ Halaman detail post dengan sistem komentar
- ✅ Pencarian artikel real-time
- ✅ Pagination untuk navigasi mudah
- ✅ Thumbnail gambar untuk setiap post
- ✅ Kategori post yang terorganisir

### Admin Panel
- ✅ Dashboard dengan statistik lengkap
- ✅ Autentikasi admin (username: admin, password: password)
- ✅ CRUD lengkap untuk posts (Create, Read, Update, Delete)
- ✅ Manajemen kategori dengan validation
- ✅ Moderasi komentar (approve/reject/delete)
- ✅ Upload gambar thumbnail dengan validation
- ✅ Auto-save draft functionality
- ✅ Session timeout otomatis (30 menit)

### Keamanan
- ✅ Prepared statements untuk mencegah SQL injection
- ✅ Password hashing dengan bcrypt
- ✅ Input validation dan sanitization
- ✅ File upload security
- ✅ Session management yang aman

## Teknologi
- **Backend**: PHP 8.2+
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Security**: PDO prepared statements, password hashing

## Struktur Folder
```
bloggua/
├── assets/
│   ├── css/          # Stylesheet files
│   └── js/           # JavaScript files
├── admin/            # Admin panel
│   ├── includes/     # Admin shared components
│   └── *.php         # Admin pages
├── config/           # Configuration files
├── database/         # Database schema and setup
├── includes/         # Shared functions
├── models/           # Data models (MVC pattern)
├── uploads/          # Image uploads directory
├── index.php         # Homepage
├── post.php          # Single post page
└── 404.php           # Error page
```

## Instalasi untuk Lokal

### Persyaratan
- PHP 8.2+ dengan ekstensi PDO MySQL
- MySQL 8.0+ atau MariaDB 10.4+
- Web server (Apache/Nginx) atau PHP built-in server

### Langkah Instalasi

1. **Download dan Extract**
   ```bash
   # Download project files ke direktori web server
   # Misalnya: /var/www/html/bloggua atau C:\xampp\htdocs\bloggua
   ```

2. **Setup Database**
   ```bash
   # Buat database MySQL
   mysql -u root -p
   CREATE DATABASE bloggua CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Konfigurasi Database**
   Edit file `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');          // Username MySQL Anda
   define('DB_PASS', '');              // Password MySQL Anda
   define('DB_NAME', 'bloggua');
   ```

4. **Import Database Schema**
   ```bash
   # Melalui phpMyAdmin: import file database/schema.sql
   # Atau via command line:
   mysql -u root -p bloggua < database/schema.sql
   ```

5. **Set Permissions**
   ```bash
   # Pastikan folder uploads dapat ditulis
   chmod 755 uploads/
   ```

6. **Akses Website**
   - Blog: `http://localhost/bloggua/`
   - Admin: `http://localhost/bloggua/admin/login.php`
   - Login: username = `admin`, password = `password`

## Fitur Detail

### Sistem Post
- Judul dan konten dengan HTML support
- Auto-generate slug dari judul
- Excerpt otomatis dari konten
- Upload thumbnail dengan resize
- Status draft/published
- Timestamp created/updated

### Sistem Komentar
- Form komentar dengan validasi email
- Moderasi admin (pending/approved/rejected)
- Anti-spam dengan status pending default
- Display hanya komentar yang disetujui

### Sistem Kategori
- CRUD kategori dengan validation
- Hitungan post per kategori
- Prevent delete kategori yang memiliki post

### Admin Dashboard
- Statistik real-time (posts, categories, comments)
- Quick actions untuk tugas umum
- Recent posts overview
- Pending comments counter

## Kustomisasi

### Mengubah Warna
Edit file `assets/css/style.css` dan `assets/css/admin.css`, ubah CSS variables:
```css
:root {
    --primary-red: #dc2626;    /* Warna merah utama */
    --dark-red: #b91c1c;       /* Warna merah gelap */
    --light-red: #fef2f2;      /* Warna merah muda */
}
```

### Menambah Admin User
```sql
INSERT INTO admin_users (username, password) 
VALUES ('username_baru', '$2y$10$hashed_password');
```

### Upload Limit
Edit `includes/functions.php`, ubah variabel `$maxSize` di function `uploadImage()`.

## Troubleshooting

### Error Database Connection
- Pastikan MySQL service running
- Cek kredensial database di `config/database.php`
- Pastikan database `bloggua` sudah dibuat

### Upload Gambar Gagal
- Cek permission folder `uploads/` (755)
- Pastikan PHP extension `gd` atau `imagick` terinstall
- Cek PHP `upload_max_filesize` dan `post_max_size`

### Session Issues
- Pastikan folder session PHP dapat ditulis
- Cek `session.save_path` di php.ini

## Security Notes
- Ganti password admin default setelah instalasi
- Set permission folder yang sesuai (tidak 777)
- Pastikan folder `uploads/` tidak dapat mengeksekusi PHP
- Update PHP dan MySQL secara berkala

## Demo Data
Database schema sudah include sample data:
- 4 kategori (Teknologi, Lifestyle, Travel, Food)
- 2 sample posts dengan komentar
- 1 admin user (admin/password)

## Support
Untuk bantuan teknis atau customization, silakan hubungi developer atau baca dokumentasi PHP/MySQL official.

---
© 2025 Bloggua - Blog Sederhana dengan Nuansa Merah Putih