<?php
require_once '../config/session.php';
requireLogin();

require_once '../config/database_demo.php';
require_once '../models/Post.php';
require_once '../models/Category.php';
require_once '../models/Comment.php';

$postModel = new Post();
$categoryModel = new Category();
$commentModel = new Comment();

// Get statistics
$db = Database::getInstance()->getConnection();

$totalPosts = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$publishedPosts = $db->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
$totalCategories = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalComments = $commentModel->getTotalComments();
$pendingComments = $commentModel->getPendingCommentsCount();

// Recent posts
$recentPosts = $postModel->getAllPostsAdmin(5, 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bloggua Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Dashboard</h1>
                <a href="../config/session.php?logout=1" class="logout-btn">Logout</a>
            </div>
            
            <!-- Statistics Cards -->
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalPosts; ?></div>
                    <div class="stat-label">Total Post</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $publishedPosts; ?></div>
                    <div class="stat-label">Post Terpublikasi</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalCategories; ?></div>
                    <div class="stat-label">Kategori</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalComments; ?></div>
                    <div class="stat-label">Komentar</div>
                </div>
                
                <?php if ($pendingComments > 0): ?>
                <div class="stat-card" style="border-top-color: var(--warning);">
                    <div class="stat-number" style="color: var(--warning);"><?php echo $pendingComments; ?></div>
                    <div class="stat-label">Komentar Pending</div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Recent Posts -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">Post Terbaru</h2>
                    <a href="posts.php" class="btn btn-primary btn-sm">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentPosts)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentPosts as $post): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                                <br>
                                                <small style="color: var(--gray-600);">
                                                    <?php echo truncateText(strip_tags($post['content']), 60); ?>
                                                </small>
                                            </td>
                                            <td><?php echo htmlspecialchars($post['category_name'] ?: 'Tidak ada'); ?></td>
                                            <td>
                                                <span class="badge <?php echo $post['status'] === 'published' ? 'badge-success' : 'badge-warning'; ?>">
                                                    <?php echo ucfirst($post['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatDate($post['created_at']); ?></td>
                                            <td class="table-actions">
                                                <a href="post_edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="../post.php?slug=<?php echo urlencode($post['slug']); ?>" class="btn btn-sm btn-success" target="_blank">Lihat</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--gray-600); padding: 2rem;">
                            Belum ada post yang dibuat.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">Aksi Cepat</h2>
                </div>
                <div class="card-body">
                    <div class="btn-group">
                        <a href="post_create.php" class="btn btn-primary">Buat Post Baru</a>
                        <a href="categories.php" class="btn btn-success">Kelola Kategori</a>
                        <a href="comments.php" class="btn btn-warning">Moderasi Komentar</a>
                        <a href="../" class="btn btn-secondary" target="_blank">Lihat Blog</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>

<style>
.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-success {
    background: var(--success);
    color: white;
}

.badge-warning {
    background: var(--warning);
    color: white;
}

.btn-secondary {
    background: var(--gray-600);
    color: white;
}

.btn-secondary:hover {
    background: var(--gray-800);
}
</style>