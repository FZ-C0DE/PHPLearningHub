<?php
require_once '../config/session.php';
requireLogin();

require_once '../config/database.php';
require_once '../models/Category.php';
require_once '../includes/functions.php';

$categoryModel = new Category();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $name = sanitizeInput($_POST['name']);
        $description = sanitizeInput($_POST['description']);
        
        if (!empty($name)) {
            if (!$categoryModel->categoryExists($name)) {
                if ($categoryModel->createCategory($name, $description)) {
                    showAlert('Kategori berhasil dibuat.', 'success');
                } else {
                    showAlert('Gagal membuat kategori.', 'error');
                }
            } else {
                showAlert('Kategori dengan nama ini sudah ada.', 'error');
            }
        } else {
            showAlert('Nama kategori harus diisi.', 'error');
        }
        redirect('kelola-kategori.php');
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = (int)$_POST['id'];
        $name = sanitizeInput($_POST['name']);
        $description = sanitizeInput($_POST['description']);
        
        if (!empty($name)) {
            if (!$categoryModel->categoryExists($name, $id)) {
                if ($categoryModel->updateCategory($id, $name, $description)) {
                    showAlert('Kategori berhasil diupdate.', 'success');
                } else {
                    showAlert('Gagal mengupdate kategori.', 'error');
                }
            } else {
                showAlert('Kategori dengan nama ini sudah ada.', 'error');
            }
        } else {
            showAlert('Nama kategori harus diisi.', 'error');
        }
        redirect('kelola-kategori.php');
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($categoryModel->deleteCategory($id)) {
        showAlert('Kategori berhasil dihapus.', 'success');
    } else {
        showAlert('Gagal menghapus kategori. Pastikan tidak ada post yang menggunakan kategori ini.', 'error');
    }
    redirect('kelola-kategori.php');
}

$categories = $categoryModel->getAllCategories();
$editCategory = null;

if (isset($_GET['edit'])) {
    $editCategory = $categoryModel->getCategoryById((int)$_GET['edit']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Bloggua Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Kelola Kategori</h1>
            </div>
            
            <?php displayAlert(); ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Add/Edit Category Form -->
                <div class="content-card">
                    <div class="card-header">
                        <h2 class="card-title"><?php echo $editCategory ? 'Edit Kategori' : 'Tambah Kategori Baru'; ?></h2>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="<?php echo $editCategory ? 'update' : 'create'; ?>">
                            <?php if ($editCategory): ?>
                                <input type="hidden" name="id" value="<?php echo $editCategory['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Kategori *</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-control" 
                                       required
                                       value="<?php echo $editCategory ? htmlspecialchars($editCategory['name']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea id="description" 
                                          name="description" 
                                          class="form-control" 
                                          rows="3"><?php echo $editCategory ? htmlspecialchars($editCategory['description']) : ''; ?></textarea>
                            </div>
                            
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $editCategory ? 'Update Kategori' : 'Tambah Kategori'; ?>
                                </button>
                                <?php if ($editCategory): ?>
                                    <a href="categories.php" class="btn btn-secondary">Batal</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Categories List -->
                <div class="content-card">
                    <div class="card-header">
                        <h2 class="card-title">Daftar Kategori</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($categories)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Post</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($category['name']); ?></strong>
                                                    <?php if (!empty($category['description'])): ?>
                                                        <br>
                                                        <small style="color: var(--gray-600);">
                                                            <?php echo htmlspecialchars($category['description']); ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-success">
                                                        <?php echo $category['post_count']; ?> post
                                                    </span>
                                                </td>
                                                <td class="table-actions">
                                                    <div class="btn-group">
                                                        <a href="categories.php?edit=<?php echo $category['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <?php if ($category['post_count'] == 0): ?>
                                                            <a href="categories.php?action=delete&id=<?php echo $category['id']; ?>" 
                                                               class="btn btn-sm btn-danger"
                                                               onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p style="text-align: center; color: var(--gray-600); padding: 2rem;">
                                Belum ada kategori yang dibuat.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>