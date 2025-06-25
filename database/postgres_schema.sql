-- Bloggua PostgreSQL Database Schema
-- Optimized for both PostgreSQL (Replit) and MySQL (Local XAMPP)

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table  
CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Posts table
CREATE TABLE IF NOT EXISTS posts (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    thumbnail VARCHAR(255),
    status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft', 'published', 'archived')),
    views INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Comments table
CREATE TABLE IF NOT EXISTS comments (
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES posts(id) ON DELETE CASCADE,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(255),
    content TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sliders table untuk homepage
CREATE TABLE IF NOT EXISTS sliders (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(500) NOT NULL,
    link_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: password)
INSERT INTO admin_users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON CONFLICT (username) DO NOTHING;

-- Sample categories
INSERT INTO categories (name, description) VALUES 
('Teknologi', 'Artikel tentang teknologi terkini'),
('Lifestyle', 'Tips dan trik kehidupan sehari-hari'),
('Travel', 'Panduan dan cerita perjalanan')
ON CONFLICT (name) DO NOTHING;

-- Sample posts
INSERT INTO posts (title, slug, content, excerpt, category_id, thumbnail, status) VALUES 
('Selamat Datang di Bloggua', 'selamat-datang-di-bloggua', '<p>Selamat datang di <strong>Bloggua</strong> - platform blog modern dengan desain yang elegan dan profesional.</p><p>Di sini Anda dapat:</p><ul><li>Membaca artikel menarik tentang teknologi, lifestyle, dan travel</li><li>Berbagi pengalaman dan pengetahuan</li><li>Bergabung dengan komunitas blogger Indonesia</li></ul><p>Sistem blog ini dibangun dengan teknologi modern dan keamanan terbaik untuk memberikan pengalaman blogging yang optimal.</p>', 'Platform blog modern untuk berbagi cerita dan pengalaman terbaik Anda', 1, '', 'published'),
('Tips Produktivitas Kerja dari Rumah', 'tips-produktivitas-kerja-dari-rumah', '<p>Bekerja dari rumah telah menjadi norma baru di era digital. Berikut adalah tips untuk meningkatkan produktivitas:</p><h3>1. Buat Jadwal Tetap</h3><p>Tentukan jam kerja yang konsisten setiap hari dan patuhi jadwal tersebut.</p><h3>2. Siapkan Ruang Kerja Khusus</h3><p>Pisahkan area kerja dari area istirahat untuk meningkatkan fokus.</p><h3>3. Minimalisir Gangguan</h3><p>Matikan notifikasi yang tidak perlu selama jam kerja.</p><h3>4. Ambil Istirahat Teratur</h3><p>Gunakan teknik Pomodoro: 25 menit kerja, 5 menit istirahat.</p>', 'Tips dan strategi untuk meningkatkan produktivitas saat bekerja dari rumah', 2, '', 'published'),
('Destinasi Wisata Terbaik di Indonesia', 'destinasi-wisata-terbaik-di-indonesia', '<p>Indonesia memiliki keindahan alam yang luar biasa. Berikut destinasi wisata yang wajib dikunjungi:</p><h3>1. Bali - Pulau Dewata</h3><p>Terkenal dengan pantai indah, budaya yang kaya, dan pemandangan sawah terasering yang memukau.</p><h3>2. Yogyakarta - Kota Budaya</h3><p>Pusat kebudayaan Jawa dengan candi bersejarah seperti Borobudur dan Prambanan.</p><h3>3. Raja Ampat - Surga Bawah Laut</h3><p>Destinasi diving terbaik dunia dengan kekayaan biota laut yang tak tertandingi.</p><h3>4. Lombok - Pulau Seribu Masjid</h3><p>Pantai Pink, Gunung Rinjani, dan Gili Trawangan menawarkan pengalaman wisata yang berbeda.</p>', 'Jelajahi keindahan destinasi wisata terbaik yang ada di Indonesia', 3, '', 'published')
ON CONFLICT (slug) DO NOTHING;

-- Default sliders
INSERT INTO sliders (title, description, image, link_url, is_active, sort_order) VALUES
('Selamat Datang di Bloggua', 'Platform blog modern untuk berbagi cerita dan pengalaman terbaik Anda', 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', '#posts', TRUE, 1),
('Tulis Cerita Anda', 'Bagikan pengalaman, tips, dan pengetahuan dengan komunitas Bloggua', 'https://images.unsplash.com/photo-1455390582262-044cdead277a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', '#posts', TRUE, 2),
('Komunitas Blogger', 'Bergabung dengan ribuan blogger untuk saling berbagi dan belajar', 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', '#posts', TRUE, 3)
ON CONFLICT DO NOTHING;

-- Sample comments
INSERT INTO comments (post_id, author_name, author_email, content, status) VALUES
(1, 'Ahmad Pratama', 'ahmad@email.com', 'Artikel yang sangat menarik! Saya suka dengan desain blognya yang modern dan clean.', 'approved'),
(1, 'Sari Indah', 'sari@email.com', 'Terima kasih atas platform yang luar biasa ini. Semoga bisa terus berkembang!', 'approved'),
(2, 'Budi Santoso', 'budi@email.com', 'Tips yang sangat bermanfaat untuk WFH. Saya sudah mencoba dan hasilnya memuaskan!', 'approved'),
(3, 'Maya Putri', 'maya@email.com', 'Indonesia memang indah! Saya sudah pernah ke Bali dan memang luar biasa.', 'approved')
ON CONFLICT DO NOTHING;