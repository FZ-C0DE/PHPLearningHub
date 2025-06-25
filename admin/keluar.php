<?php
// Script untuk logout admin
// Menghapus session dan redirect ke halaman login

session_start();
require_once '../includes/functions.php';

// Hapus semua session dan redirect ke login
session_destroy();
header('Location: masuk.php');
exit();
?>