-- Tabel untuk sistem slider beranda
CREATE TABLE IF NOT EXISTS sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'Judul slider',
    description TEXT COMMENT 'Deskripsi slider',
    image VARCHAR(500) NOT NULL COMMENT 'URL gambar slider',
    link_url VARCHAR(500) COMMENT 'URL tujuan ketika slider diklik',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Status aktif slider',
    sort_order INT DEFAULT 0 COMMENT 'Urutan tampil slider',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert data default slider
INSERT INTO sliders (title, description, image, link_url, is_active, sort_order) VALUES
('Selamat Datang di Bloggua', 'Platform blog modern untuk berbagi cerita dan pengalaman terbaik Anda', 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', '#posts', TRUE, 1),
('Tulis Cerita Anda', 'Bagikan pengalaman, tips, dan pengetahuan dengan komunitas Bloggua', 'https://images.unsplash.com/photo-1455390582262-044cdead277a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', '#posts', TRUE, 2),
('Komunitas Blogger', 'Bergabung dengan ribuan blogger untuk saling berbagi dan belajar', 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', '#posts', TRUE, 3);