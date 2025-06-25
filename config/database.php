<?php
class Database {
    private static $instance = null;
    private $connection;
    private $isConnected = false;
    
    // Konfigurasi database MySQL untuk XAMPP/LAMP
    private $host = 'localhost';
    private $database = 'db_blog';
    private $username = 'root';
    private $password = '';
    
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            $this->isConnected = true;
        } catch (PDOException $e) {
            $this->isConnected = false;
            error_log("Database connection failed: " . $e->getMessage());
            
            // Tampilkan pesan error yang informatif
            $errorMessage = "
            <div style='max-width: 800px; margin: 50px auto; padding: 30px; font-family: Arial, sans-serif; background: #f8f9fa; border-left: 5px solid #dc3545; border-radius: 8px;'>
                <h2 style='color: #dc3545; margin-bottom: 20px;'>⚠️ Database MySQL Belum Tersedia</h2>
                <p style='margin-bottom: 15px; line-height: 1.6;'><strong>Sistem Bloggua memerlukan database MySQL untuk berfungsi dengan baik.</strong></p>
                
                <h3 style='color: #495057; margin: 20px 0 10px 0;'>Langkah Setup Database:</h3>
                <ol style='line-height: 1.8; padding-left: 20px;'>
                    <li><strong>Install XAMPP</strong> - Download dari <a href='https://www.apachefriends.org/download.html' target='_blank' style='color: #dc3545;'>apachefriends.org</a></li>
                    <li><strong>Start MySQL</strong> - Buka XAMPP Control Panel → Start MySQL</li>
                    <li><strong>Akses phpMyAdmin</strong> - Browser: <code>http://localhost/phpmyadmin</code></li>
                    <li><strong>Buat Database</strong> - Nama: <code style='background: #e9ecef; padding: 2px 6px; border-radius: 3px;'>db_blog</code></li>
                    <li><strong>Import Schema</strong> - Upload file: <code style='background: #e9ecef; padding: 2px 6px; border-radius: 3px;'>database/schema.sql</code></li>
                </ol>
                
                <div style='background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin: 20px 0;'>
                    <strong>📂 Copy project ke:</strong> <code style='background: #e9ecef; padding: 2px 6px; border-radius: 3px;'>C:\\xampp\\htdocs\\bloggua\\</code><br>
                    <strong>🌐 Akses via:</strong> <code style='background: #e9ecef; padding: 2px 6px; border-radius: 3px;'>http://localhost/bloggua/beranda.php</code>
                </div>
                
                <p style='margin-top: 20px; color: #6c757d; font-size: 14px;'>
                    <strong>Error detail:</strong> " . $e->getMessage() . "
                </p>
                
                <div style='margin-top: 25px; padding: 15px; background: #d1ecf1; border-radius: 5px;'>
                    <strong>💡 Panduan lengkap tersedia di:</strong><br>
                    • <code>PANDUAN-PENGGUNAAN.md</code><br>
                    • <code>INSTALASI-MYSQL.md</code>
                </div>
            </div>";
            
            die($errorMessage);
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
    
    public function isConnected() {
        return $this->isConnected;
    }
}