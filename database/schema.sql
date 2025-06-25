-- Bloggua Database Schema
-- Create database first: CREATE DATABASE db_blog;

USE db_blog;

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Posts table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    thumbnail VARCHAR(255),
    category_id INT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Comments table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Admin users table (simple authentication)
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: password)
INSERT INTO admin_users (username, password) VALUES 
('admin', '$2y$10$YourHashedPasswordHere');

-- Insert sample categories
INSERT INTO categories (name, description) VALUES 
('Teknologi', 'Artikel tentang teknologi terbaru'),
('Lifestyle', 'Artikel tentang gaya hidup'),
('Travel', 'Artikel tentang perjalanan dan wisata'),
('Food', 'Artikel tentang kuliner dan makanan');

-- Insert sample posts
INSERT INTO posts (title, slug, content, excerpt, category_id, status, thumbnail) VALUES 
(
    'Selamat Datang di Bloggua', 
    'selamat-datang-di-bloggua', 
    '<p>Selamat datang di <strong>Bloggua</strong>, platform blog sederhana yang dirancang dengan nuansa merah dan putih yang elegan.</p><p>Blog ini dilengkapi dengan fitur-fitur lengkap seperti:</p><ul><li>Sistem manajemen konten yang mudah digunakan</li><li>Admin panel yang komprehensif</li><li>Sistem komentar interaktif</li><li>Pencarian artikel yang cepat</li><li>Desain responsif untuk semua perangkat</li></ul><p>Kami berkomitmen untuk menyajikan konten berkualitas dan pengalaman pengguna yang optimal.</p>', 
    'Selamat datang di Bloggua, platform blog dengan desain merah putih yang elegan dan fitur lengkap untuk berbagi konten berkualitas.',
    1, 
    'published',
    NULL
),
(
    'Tips Memulai Blog untuk Pemula', 
    'tips-memulai-blog-untuk-pemula', 
    '<p>Memulai blog bisa terasa menakutkan bagi pemula, namun dengan tips yang tepat, siapa pun bisa menjadi blogger sukses.</p><p><strong>1. Tentukan Niche atau Topik</strong><br>Pilih topik yang Anda kuasai dan minati. Konsistensi dalam topik akan membantu membangun audiens yang loyal.</p><p><strong>2. Buat Konten Berkualitas</strong><br>Fokus pada kualitas daripada kuantitas. Satu artikel berkualitas lebih baik daripada sepuluh artikel biasa-biasa saja.</p><p><strong>3. Konsisten dalam Posting</strong><br>Buat jadwal posting yang realistis dan patuhi jadwal tersebut.</p><p><strong>4. Promosikan di Media Sosial</strong><br>Gunakan platform media sosial untuk mempromosikan konten blog Anda.</p>', 
    'Panduan lengkap untuk memulai blog bagi pemula dengan tips praktis yang mudah diikuti.',
    1, 
    'published',
    NULL
);

-- Insert sample comments
INSERT INTO comments (post_id, author_name, author_email, content, status) VALUES 
(1, 'Budi Santoso', 'budi@example.com', 'Blog yang sangat menarik! Desainnya simple tapi elegan.', 'approved'),
(1, 'Sari Dewi', 'sari@example.com', 'Fitur-fiturnya lengkap sekali. Terima kasih sudah berbagi!', 'approved'),
(2, 'Ahmad Rahman', 'ahmad@example.com', 'Tips yang sangat berguna untuk pemula seperti saya. Terima kasih!', 'approved');

-- Create indexes for better performance
CREATE INDEX idx_posts_status ON posts(status);
CREATE INDEX idx_posts_category ON posts(category_id);
CREATE INDEX idx_posts_slug ON posts(slug);
CREATE INDEX idx_comments_post ON comments(post_id);
CREATE INDEX idx_comments_status ON comments(status);