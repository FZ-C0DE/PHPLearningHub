<?php
// Fungsi-fungsi helper untuk admin panel

// Fungsi untuk redirect
function redirect($url) {
    if (!headers_sent()) {
        header("Location: " . $url);
        exit();
    } else {
        echo "<script>window.location.href = '" . $url . "';</script>";
        exit();
    }
}

// Cek apakah admin sudah login
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Sanitasi input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Fungsi untuk menampilkan alert
function showAlert($message, $type = 'info') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

// Fungsi untuk mendapatkan alert
function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}

// Generate slug dari string
function generateSlug($string) {
    $slug = strtolower($string);
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Upload file gambar
function uploadImage($file, $directory = '../uploads/') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    $filename = time() . '_' . basename($file['name']);
    $filepath = $directory . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'uploads/' . $filename;
    }
    
    return false;
}

// Format tanggal Indonesia
function formatTanggalIndonesia($date) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $hari = date('d', $timestamp);
    $bulanNum = date('n', $timestamp);
    $tahun = date('Y', $timestamp);
    
    return $hari . ' ' . $bulan[$bulanNum] . ' ' . $tahun;
}

// Truncate text
function truncateText($text, $limit = 100) {
    if (strlen($text) > $limit) {
        return substr($text, 0, $limit) . '...';
    }
    return $text;
}
?>