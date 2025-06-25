<?php
// Session configuration
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
ini_set('session.cookie_lifetime', 1800);
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    logout();
}

// Auto logout for inactive sessions
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
    session_unset();
    session_destroy();
    header('Location: /admin/login.php');
    exit;
}
$_SESSION['last_activity'] = time();

// Security functions
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: /admin/login.php');
    exit;
}
?>