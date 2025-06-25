<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../includes/functions.php';

// Cek apakah admin sudah login
requireAdminLogin();

$db = Database::getInstance()->getConnection();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $title = sanitizeInput($_POST['title']);
                $description = sanitizeInput($_POST['description']);
                $image = sanitizeInput($_POST['image']);
                $link_url = sanitizeInput($_POST['link_url']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                $sort_order = (int)$_POST['sort_order'];
                
                if (!empty($title) && !empty($image)) {
                    $sql = "INSERT INTO sliders (title, description, image, link_url, is_active, sort_order) 
                            VALUES (:title, :description, :image, :link_url, :is_active, :sort_order)";
                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':title', $title);
                    $stmt->bindValue(':description', $description);
                    $stmt->bindValue(':image', $image);
                    $stmt->bindValue(':link_url', $link_url);
                    $stmt->bindValue(':is_active', $is_active, PDO::PARAM_INT);
                    $stmt->bindValue(':sort_order', $sort_order, PDO::PARAM_INT);
                    
                    if ($stmt->execute()) {
                        showAlert('Slider berhasil ditambahkan.', 'success');
                    } else {
                        showAlert('Gagal menambahkan slider.', 'error');
                    }
                } else {
                    showAlert('Judul dan gambar harus diisi.', 'error');
                }
                redirect('kelola-slider.php');
                break;
                
            case 'update':
                $id = (int)$_POST['id'];
                $title = sanitizeInput($_POST['title']);
                $description = sanitizeInput($_POST['description']);
                $image = sanitizeInput($_POST['image']);
                $link_url = sanitizeInput($_POST['link_url']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                $sort_order = (int)$_POST['sort_order'];
                
                if (!empty($title) && !empty($image)) {
                    $sql = "UPDATE sliders SET title = :title, description = :description, 
                            image = :image, link_url = :link_url, is_active = :is_active, 
                            sort_order = :sort_order WHERE id = :id";
                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title', $title);
                    $stmt->bindValue(':description', $description);
                    $stmt->bindValue(':image', $image);
                    $stmt->bindValue(':link_url', $link_url);
                    $stmt->bindValue(':is_active', $is_active, PDO::PARAM_INT);
                    $stmt->bindValue(':sort_order', $sort_order, PDO::PARAM_INT);
                    
                    if ($stmt->execute()) {
                        showAlert('Slider berhasil diupdate.', 'success');
                    } else {
                        showAlert('Gagal mengupdate slider.', 'error');
                    }
                } else {
                    showAlert('Judul dan gambar harus diisi.', 'error');
                }
                redirect('kelola-slider.php');
                break;
        }
    }
}

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "DELETE FROM sliders WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        showAlert('Slider berhasil dihapus.', 'success');
    } else {
        showAlert('Gagal menghapus slider.', 'error');
    }
    redirect('kelola-slider.php');
}

// Get all sliders
$sql = "SELECT * FROM sliders ORDER BY sort_order ASC, created_at DESC";
$stmt = $db->query($sql);
$sliders = $stmt->fetchAll();

// Get slider for edit
$editSlider = null;
if (isset($_GET['edit']) && $_GET['edit']) {
    $editId = (int)$_GET['edit'];
    $sql = "SELECT * FROM sliders WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $editId, PDO::PARAM_INT);
    $stmt->execute();
    $editSlider = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Slider - Bloggua Admin</title>
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
                <h1 class="page-title">Kelola Slider</h1>
                <div class="btn-group">
                    <a href="dasbor.php" class="btn btn-secondary">← Kembali</a>
                </div>
            </div>
            
            <!-- Form Tambah/Edit Slider -->
            <div class="card">
                <div class="card-header">
                    <h2><?php echo $editSlider ? 'Edit Slider' : 'Tambah Slider Baru'; ?></h2>
                </div>
                <div class="card-body">
                    <form method="POST" class="form">
                        <input type="hidden" name="action" value="<?php echo $editSlider ? 'update' : 'create'; ?>">
                        <?php if ($editSlider): ?>
                            <input type="hidden" name="id" value="<?php echo $editSlider['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="title">Judul Slider *</label>
                            <input type="text" id="title" name="title" 
                                   value="<?php echo $editSlider ? htmlspecialchars($editSlider['title']) : ''; ?>" 
                                   required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" name="description" rows="3" class="form-control"><?php echo $editSlider ? htmlspecialchars($editSlider['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">URL Gambar *</label>
                            <input type="url" id="image" name="image" 
                                   value="<?php echo $editSlider ? htmlspecialchars($editSlider['image']) : ''; ?>" 
                                   required class="form-control"
                                   placeholder="https://example.com/image.jpg">
                            <small class="form-help">Gunakan URL gambar dari sumber eksternal (Unsplash, dll)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="link_url">URL Link (Opsional)</label>
                            <input type="url" id="link_url" name="link_url" 
                                   value="<?php echo $editSlider ? htmlspecialchars($editSlider['link_url']) : ''; ?>" 
                                   class="form-control"
                                   placeholder="https://example.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order">Urutan</label>
                            <input type="number" id="sort_order" name="sort_order" 
                                   value="<?php echo $editSlider ? $editSlider['sort_order'] : 0; ?>" 
                                   class="form-control" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_active" 
                                       <?php echo (!$editSlider || $editSlider['is_active']) ? 'checked' : ''; ?>>
                                Aktif
                            </label>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $editSlider ? 'Update Slider' : 'Tambah Slider'; ?>
                            </button>
                            <?php if ($editSlider): ?>
                                <a href="kelola-slider.php" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Daftar Slider -->
            <div class="card">
                <div class="card-header">
                    <h2>Daftar Slider</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($sliders)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Preview</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Urutan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sliders as $slider): ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo htmlspecialchars($slider['image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($slider['title']); ?>"
                                                     style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($slider['title']); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars(truncateText($slider['description'], 60)); ?>
                                            </td>
                                            <td><?php echo $slider['sort_order']; ?></td>
                                            <td>
                                                <span class="badge <?php echo $slider['is_active'] ? 'badge-success' : 'badge-secondary'; ?>">
                                                    <?php echo $slider['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                                </span>
                                            </td>
                                            <td class="table-actions">
                                                <div class="btn-group">
                                                    <a href="kelola-slider.php?edit=<?php echo $slider['id']; ?>" 
                                                       class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="kelola-slider.php?action=delete&id=<?php echo $slider['id']; ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus slider ini?')">Hapus</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center">Belum ada slider.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>