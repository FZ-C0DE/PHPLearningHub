<?php
class Database {
    private static $instance = null;
    private $connection;

    
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
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            
            // Tampilkan pesan error yang informatif
            die("Database connection failed: " . $e->getMessage() . "<br><br>Pastikan XAMPP MySQL sudah running dan database 'db_blog' sudah dibuat.");
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
    

}