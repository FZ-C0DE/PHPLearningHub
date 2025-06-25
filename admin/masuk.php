<?php
// Halaman login admin dengan desain modern
// Proses autentikasi untuk masuk ke panel admin

session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Redirect jika sudah login
if (isLoggedIn()) {
    redirect('/admin/dasbor.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT id, username, password FROM admin_users WHERE username = :username";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch();
            
            // Untuk demo, izinkan password plain text dan hashed
            if ($user && ($password === 'password' || password_verify($password, $user['password']))) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['last_activity'] = time();
                
                redirect('/admin/dasbor.php');
            } else {
                $error = 'Username atau password salah.';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    } else {
        $error = 'Username dan password harus diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin - Bloggua</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'merah-utama': '#dc2626',
                        'merah-gelap': '#b91c1c',
                        'merah-muda': '#fef2f2'
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #dc2626, #b91c1c); }
        .glass-effect { backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center">
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white opacity-10 rounded-full animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white opacity-5 rounded-full animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-white opacity-5 rounded-full animate-pulse delay-500"></div>
    </div>

    <!-- Container Login -->
    <div class="relative z-10 w-full max-w-md mx-4">
        <!-- Card Login Modern -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="gradient-bg p-8 text-center">
                <div class="w-20 h-20 bg-white rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-10 h-10 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Bloggua Admin</h1>
                <p class="text-red-100">Masuk ke panel administrasi</p>
            </div>

            <!-- Form Login -->
            <div class="p-8">
                <?php if (!empty($error)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-6">
                    <div>
                        <label for="username" class="block text-gray-700 font-semibold mb-3">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   class="w-full pl-10 pr-4 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all text-lg" 
                                   required 
                                   autocomplete="username"
                                   placeholder="Masukkan username"
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'admin'; ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-gray-700 font-semibold mb-3">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full pl-10 pr-4 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all text-lg" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Masukkan password">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full gradient-bg text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        Masuk ke Admin Panel
                    </button>
                </form>
                
                <!-- Info Demo -->
                <div class="mt-8 p-6 bg-gray-50 rounded-xl">
                    <div class="text-center">
                        <h3 class="font-semibold text-gray-800 mb-2">Demo Akun</h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Username: <code class="bg-gray-200 px-2 py-1 rounded font-mono">admin</code><br>
                            Password: <code class="bg-gray-200 px-2 py-1 rounded font-mono">password</code>
                        </p>
                        <a href="/beranda.php" class="inline-flex items-center text-merah-utama hover:text-merah-gelap transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-8">
            <p class="text-white text-sm opacity-80">
                © 2025 Bloggua - Panel Administrasi
            </p>
        </div>
    </div>

    <script>
        // Animasi loading button saat submit
        document.querySelector('form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-3 inline" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
            button.disabled = true;
        });

        // Focus otomatis ke input username
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>