<?php
require_once '../config/session.php';
requireLogin();

require_once '../config/database_demo.php';
require_once '../models/Comment.php';
require_once '../includes/functions.php';

$commentModel = new Comment();

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    switch ($action) {
        case 'approve':
            if ($commentModel->updateCommentStatus($id, 'approved')) {
                showAlert('Komentar berhasil disetujui.', 'success');
            } else {
                showAlert('Gagal menyetujui komentar.', 'error');
            }
            break;
            
        case 'reject':
            if ($commentModel->updateCommentStatus($id, 'rejected')) {
                showAlert('Komentar berhasil ditolak.', 'success');
            } else {
                showAlert('Gagal menolak komentar.', 'error');
            }
            break;
            
        case 'delete':
            if ($commentModel->deleteComment($id)) {
                showAlert('Komentar berhasil dihapus.', 'success');
            } else {
                showAlert('Gagal menghapus komentar.', 'error');
            }
            break;
    }
    
    redirect('/admin/comments.php');
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$commentsPerPage = 15;
$offset = ($page - 1) * $commentsPerPage;

$comments = $commentModel->getAllComments($commentsPerPage, $offset);
$totalComments = $commentModel->getTotalComments();
$totalPages = ceil($totalComments / $commentsPerPage);
$pendingCount = $commentModel->getPendingCommentsCount();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderasi Komentar - Bloggua Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Moderasi Komentar</h1>
                <?php if ($pendingCount > 0): ?>
                    <span class="badge badge-warning" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <?php echo $pendingCount; ?> Menunggu Moderasi
                    </span>
                <?php endif; ?>
            </div>
            
            <?php displayAlert(); ?>
            
            <div class="content-card">
                <div class="card-header">
                    <h2 class="card-title">Daftar Komentar</h2>
                    <div>
                        <input type="text" id="table-search" placeholder="Cari komentar..." class="form-control" style="width: 250px;">
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($comments)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Penulis</th>
                                        <th>Komentar</th>
                                        <th>Post</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($comments as $comment): ?>
                                        <tr style="<?php echo $comment['status'] === 'pending' ? 'background: #fefce8;' : ''; ?>">
                                            <td>
                                                <strong><?php echo htmlspecialchars($comment['author_name']); ?></strong>
                                                <br>
                                                <small style="color: var(--gray-600);">
                                                    <?php echo htmlspecialchars($comment['author_email']); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div style="max-width: 300px;">
                                                    <?php echo nl2br(htmlspecialchars(truncateText($comment['content'], 120))); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="../post.php?slug=<?php echo urlencode($comment['post_slug'] ?? '#'); ?>" 
                                                   target="_blank" 
                                                   style="color: var(--primary-red);">
                                                    <?php echo htmlspecialchars(truncateText($comment['post_title'], 30)); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
                                                switch ($comment['status']) {
                                                    case 'approved':
                                                        $statusClass = 'badge-success';
                                                        $statusText = 'Disetujui';
                                                        break;
                                                    case 'rejected':
                                                        $statusClass = 'badge-danger';
                                                        $statusText = 'Ditolak';
                                                        break;
                                                    default:
                                                        $statusClass = 'badge-warning';
                                                        $statusText = 'Pending';
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>">
                                                    <?php echo $statusText; ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatDateTime($comment['created_at']); ?></td>
                                            <td class="table-actions">
                                                <div class="btn-group">
                                                    <?php if ($comment['status'] !== 'approved'): ?>
                                                        <a href="comments.php?action=approve&id=<?php echo $comment['id']; ?>" 
                                                           class="btn btn-sm btn-success">Setujui</a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($comment['status'] !== 'rejected'): ?>
                                                        <a href="comments.php?action=reject&id=<?php echo $comment['id']; ?>" 
                                                           class="btn btn-sm btn-warning">Tolak</a>
                                                    <?php endif; ?>
                                                    
                                                    <a href="comments.php?action=delete&id=<?php echo $comment['id']; ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</a>
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
                                        <a href="comments.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 3rem;">
                            <p>Belum ada komentar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>

<style>
.badge-danger {
    background: var(--danger);
    color: white;
}
</style>