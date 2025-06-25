<?php
require_once '../config/session.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('/admin/dashboard.php');
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
            
            // For demo purposes, allow both hashed and plain password
            if ($user && ($password === 'password' || password_verify($password, $user['password']))) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['last_activity'] = time();
                
                redirect('dashboard.php');
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
    <title>Login Admin - Bloggua</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h1 class="login-title">Bloggua Admin</h1>
            <p class="login-subtitle">Masuk ke panel administrasi</p>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-control" 
                       required 
                       autocomplete="username"
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'admin'; ?>">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control" 
                       required 
                       autocomplete="current-password"
                       placeholder="password">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Masuk
            </button>
            
            <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                <p style="color: var(--gray-600); font-size: 0.9rem;">
                    Demo: username = <strong>admin</strong>, password = <strong>password</strong>
                </p>
                <a href="../beranda.php" style="color: var(--primary-red); text-decoration: none;">
                    ← Kembali ke Blog
                </a>
            </div>
        </form>
    </div>
</body>
</html>