<?php
// Demo database configuration for Replit (SQLite)
// For local deployment, use database.php with MySQL

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            // Use SQLite for demo in Replit environment
            $this->connection = new PDO(
                "sqlite:bloggua_demo.db",
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            
            // Create tables if they don't exist
            $this->createTables();
            $this->insertSampleData();
            
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function createTables() {
        $sql = "
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content TEXT NOT NULL,
            excerpt TEXT,
            thumbnail VARCHAR(255),
            category_id INTEGER,
            status VARCHAR(20) DEFAULT 'draft',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        );
        
        CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            author_name VARCHAR(100) NOT NULL,
            author_email VARCHAR(100) NOT NULL,
            content TEXT NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
        );
        
        CREATE TABLE IF NOT EXISTS admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );";
        
        $this->connection->exec($sql);
    }
    
    private function insertSampleData() {
        // Check if data already exists
        $count = $this->connection->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        if ($count > 0) return;
        
        // Insert categories
        $stmt = $this->connection->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $categories = [
            ['Teknologi', 'Artikel tentang teknologi terbaru'],
            ['Lifestyle', 'Artikel tentang gaya hidup'],
            ['Travel', 'Artikel tentang perjalanan dan wisata'],
            ['Food', 'Artikel tentang kuliner dan makanan']
        ];
        
        foreach ($categories as $category) {
            $stmt->execute($category);
        }
        
        // Insert admin user
        $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
        $stmt->execute(['admin', $hashedPassword]);
        
        // Insert sample posts
        $stmt = $this->connection->prepare("
            INSERT INTO posts (title, slug, content, excerpt, category_id, status) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $posts = [
            [
                'Selamat Datang di Bloggua',
                'selamat-datang-di-bloggua',
                '<p>Selamat datang di <strong>Bloggua</strong>, platform blog sederhana yang dirancang dengan nuansa merah dan putih yang elegan.</p><p>Blog ini dilengkapi dengan fitur-fitur lengkap seperti:</p><ul><li>Sistem manajemen konten yang mudah digunakan</li><li>Admin panel yang komprehensif</li><li>Sistem komentar interaktif</li><li>Pencarian artikel yang cepat</li><li>Desain responsif untuk semua perangkat</li></ul><p>Kami berkomitmen untuk menyajikan konten berkualitas dan pengalaman pengguna yang optimal.</p>',
                'Selamat datang di Bloggua, platform blog dengan desain merah putih yang elegan dan fitur lengkap untuk berbagi konten berkualitas.',
                1,
                'published'
            ],
            [
                'Tips Memulai Blog untuk Pemula',
                'tips-memulai-blog-untuk-pemula',
                '<p>Memulai blog bisa terasa menakutkan bagi pemula, namun dengan tips yang tepat, siapa pun bisa menjadi blogger sukses.</p><p><strong>1. Tentukan Niche atau Topik</strong><br>Pilih topik yang Anda kuasai dan minati. Konsistensi dalam topik akan membantu membangun audiens yang loyal.</p><p><strong>2. Buat Konten Berkualitas</strong><br>Fokus pada kualitas daripada kuantitas. Satu artikel berkualitas lebih baik daripada sepuluh artikel biasa-biasa saja.</p><p><strong>3. Konsisten dalam Posting</strong><br>Buat jadwal posting yang realistis dan patuhi jadwal tersebut.</p><p><strong>4. Promosikan di Media Sosial</strong><br>Gunakan platform media sosial untuk mempromosikan konten blog Anda.</p>',
                'Panduan lengkap untuk memulai blog bagi pemula dengan tips praktis yang mudah diikuti.',
                1,
                'published'
            ]
        ];
        
        foreach ($posts as $post) {
            $stmt->execute($post);
        }
        
        // Insert sample comments
        $stmt = $this->connection->prepare("
            INSERT INTO comments (post_id, author_name, author_email, content, status) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $comments = [
            [1, 'Budi Santoso', 'budi@example.com', 'Blog yang sangat menarik! Desainnya simple tapi elegan.', 'approved'],
            [1, 'Sari Dewi', 'sari@example.com', 'Fitur-fiturnya lengkap sekali. Terima kasih sudah berbagi!', 'approved'],
            [2, 'Ahmad Rahman', 'ahmad@example.com', 'Tips yang sangat berguna untuk pemula seperti saya. Terima kasih!', 'approved']
        ];
        
        foreach ($comments as $comment) {
            $stmt->execute($comment);
        }
    }
}
?>