<?php
// Utility functions for Bloggua

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function uploadImage($file, $uploadDir = 'uploads/') {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
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
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destination = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }
    
    return false;
}

function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

function formatDateTime($datetime) {
    return date('d M Y H:i', strtotime($datetime));
}

function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . '...';
}

function generateExcerpt($content, $length = 200) {
    $content = strip_tags($content);
    return truncateText($content, $length);
}

function getPagination($currentPage, $totalPages, $baseUrl) {
    $pagination = '';
    
    if ($totalPages > 1) {
        $pagination .= '<div class="pagination">';
        
        // Previous button
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $pagination .= '<a href="' . $baseUrl . '?page=' . $prevPage . '">‹ Sebelumnya</a>';
        }
        
        // Page numbers
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        if ($start > 1) {
            $pagination .= '<a href="' . $baseUrl . '?page=1">1</a>';
            if ($start > 2) {
                $pagination .= '<span>...</span>';
            }
        }
        
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                $pagination .= '<span class="current">' . $i . '</span>';
            } else {
                $pagination .= '<a href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a>';
            }
        }
        
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $pagination .= '<span>...</span>';
            }
            $pagination .= '<a href="' . $baseUrl . '?page=' . $totalPages . '">' . $totalPages . '</a>';
        }
        
        // Next button
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $pagination .= '<a href="' . $baseUrl . '?page=' . $nextPage . '">Selanjutnya ›</a>';
        }
        
        $pagination .= '</div>';
    }
    
    return $pagination;
}

function showAlert($message, $type = 'success') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

function displayAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        echo '<div class="alert alert-' . $alert['type'] . '">' . $alert['message'] . '</div>';
        unset($_SESSION['alert']);
    }
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'];
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function isValidImageFile($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    
    $fileType = mime_content_type($file['tmp_name']);
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    return in_array($fileType, $allowedTypes) && in_array($fileExtension, $allowedExtensions);
}

function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>