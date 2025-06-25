<?php
// Database setup script for Bloggua
// This script will create the database and tables with sample data

require_once '../config/database.php';

try {
    // Create database connection (without specifying database name initially)
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE " . DB_NAME);
    
    echo "Database created successfully.<br>";
    
    // Read and execute SQL schema
    $sql = file_get_contents('schema.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !strpos($statement, '--') === 0) {
            $pdo->exec($statement);
        }
    }
    
    // Insert hashed password for admin user
    $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hashedPassword]);
    
    echo "Database schema created successfully.<br>";
    echo "Default admin user created: username = 'admin', password = 'password'<br>";
    echo "<a href='../admin/login.php'>Go to Admin Login</a><br>";
    echo "<a href='../'>Go to Blog</a>";
    
} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>