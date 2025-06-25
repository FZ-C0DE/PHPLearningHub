<?php
require_once '../config/session.php';
requireLogin();

require_once '../config/database.php';
require_once '../models/Post.php';
require_once '../includes/functions.php';

$postModel = new Post();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($postModel->deletePost($id)) {
        showAlert('Post berhasil dihapus.', 'success');
    } else {
        showAlert('Gagal menghapus post.', 'error');
    }
    redirect('posts.php');
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$postsPerPage = 10;
$offset = ($page - 1) * $postsPerPage;

$posts = $postModel->getAllPostsAdmin($postsPerPage, $offset);
$totalPosts = Database::getInstance()->getConnection()->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalPages = ceil($totalPosts / $postsPerPage);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Post - Bloggua Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Kelola Post</h1>
                <a href="post_create.php" class="btn btn-primary">Buat Post Baru</a>
            </div>
            
            <?php displayAlert(); ?>
            
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">Daftar Post</h2>
                    <div>
                        <input type="text" id="table-search" placeholder="Cari post..." class="form-control" style="width: 250px;">
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($posts)): ?>
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
                                    <?php foreach ($posts as $post): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                                <br>
                                                <small style="color: var(--gray-600);">
                                                    <?php echo truncateText(strip_tags($post['content']), 80); ?>
                                                </small>
                                            </td>
                                            <td><?php echo htmlspecialchars($post['category_name'] ?: 'Tidak ada'); ?></td>
                                            <td>
                                                <span class="badge <?php echo $post['status'] === 'published' ? 'badge-success' : 'badge-warning'; ?>">
                                                    <?php echo $post['status'] === 'published' ? 'Terpublikasi' : 'Draft'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo formatDate($post['created_at']); ?>
                                                <?php if ($post['updated_at'] !== $post['created_at']): ?>
                                                    <br><small style="color: var(--gray-600);">Diupdate: <?php echo formatDate($post['updated_at']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td class="table-actions">
                                                <div class="btn-group">
                                                    <a href="post_edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="../artikel.php?slug=<?php echo urlencode($post['slug']); ?>" class="btn btn-sm btn-success" target="_blank">Lihat</a>
                                                    <a href="posts.php?action=delete&id=<?php echo $post['id']; ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus post ini?')">Hapus</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div class="pagination">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <?php if ($i == $page): ?>
                                        <span class="current"><?php echo $i; ?></span>
                                    <?php else: ?>
                                        <a href="posts.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 3rem;">
                            <p>Belum ada post yang dibuat.</p>
                            <a href="post_create.php" class="btn btn-primary">Buat Post Pertama</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>