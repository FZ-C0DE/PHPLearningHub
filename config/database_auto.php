<?php
// Auto-detect database configuration
// Menggunakan SQLite untuk demo di Replit, MySQL untuk deployment lokal

// Deteksi environment Replit
$isReplit = (
    isset($_SERVER['REPL_ID']) || 
    isset($_SERVER['REPLIT_DB_URL']) ||
    isset($_SERVER['REPL_SLUG']) ||
    getenv('REPL_ID') !== false ||
    strpos(__DIR__, '/home/runner') !== false ||
    file_exists('/nix/store')
);

// Cek juga jika MySQL tersedia
$mysqlAvailable = false;
try {
    if (class_exists('PDO')) {
        $drivers = PDO::getAvailableDrivers();
        $mysqlAvailable = in_array('mysql', $drivers);
        
        // Test koneksi MySQL
        if ($mysqlAvailable && !$isReplit) {
            $testConnection = new PDO('mysql:host=localhost', 'root', '', [
                PDO::ATTR_TIMEOUT => 1,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
    }
} catch (Exception $e) {
    $mysqlAvailable = false;
}

if ($isReplit || !$mysqlAvailable) {
    // Gunakan SQLite untuk demo di Replit atau jika MySQL tidak tersedia
    require_once __DIR__ . '/database_demo.php';
} else {
    // Gunakan MySQL untuk deployment lokal
    require_once __DIR__ . '/database.php';
}
?>