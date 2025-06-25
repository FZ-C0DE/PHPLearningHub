<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../includes/functions.php';

// Cek apakah admin sudah login
requireAdminLogin();

// Statistik dasar
$db = Database::getInstance()->getConnection();

// Total posts
$sql = "SELECT COUNT(*) as total FROM posts";
$stmt = $db->query($sql);
$totalPosts = $stmt->fetchColumn();

// Total kategori
$sql = "SELECT COUNT(*) as total FROM categories";
$stmt = $db->query($sql);
$totalCategories = $stmt->fetchColumn();

// Total komentar
$sql = "SELECT COUNT(*) as total FROM comments";
$stmt = $db->query($sql);
$totalComments = $stmt->fetchColumn();

// Post terbaru
$sql = "SELECT p.*, c.name as category_name 
        FROM posts p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC 
        LIMIT 5";
$stmt = $db->query($sql);
$recentPosts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bloggua</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-red': '#dc2626',
                        'dark-red': '#b91c1c'
                    }
                }
            }
        }
    </script>
</head>
<body class="admin-body">
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Dashboard Admin</h1>
                <div class="btn-group">
                    <a href="../beranda.php" class="btn btn-secondary" target="_blank">Lihat Blog</a>
                </div>
            </div>
            
            <!-- Statistik Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📝</div>
                    <div class="stat-content">
                        <h3><?php echo $totalPosts; ?></h3>
                        <p>Total Post</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📁</div>
                    <div class="stat-content">
                        <h3><?php echo $totalCategories; ?></h3>
                        <p>Kategori</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">💬</div>
                    <div class="stat-content">
                        <h3><?php echo $totalComments; ?></h3>
                        <p>Komentar</p>
                    </div>
                </div>
            </div>
            
            <!-- Post Terbaru -->
            <div class="card">
                <div class="card-header">
                    <h2>Post Terbaru</h2>
                    <a href="kelola-post.php" class="btn btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentPosts)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentPosts as $post): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    <?php echo htmlspecialchars($post['category_name'] ?: 'Tanpa Kategori'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatDate($post['created_at']); ?></td>
                                            <td class="table-actions">
                                                <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="../artikel.php?slug=<?php echo urlencode($post['slug']); ?>" class="btn btn-sm btn-success" target="_blank">Lihat</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center">Belum ada post.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h2>Aksi Cepat</h2>
                </div>
                <div class="card-body">
                    <div class="btn-group">
                        <a href="buat-post.php" class="btn btn-primary">Buat Post Baru</a>
                        <a href="kelola-kategori.php" class="btn btn-success">Kelola Kategori</a>
                        <a href="moderasi-komentar.php" class="btn btn-warning">Moderasi Komentar</a>
                        <a href="kelola-slider.php" class="btn btn-info">Kelola Slider</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>