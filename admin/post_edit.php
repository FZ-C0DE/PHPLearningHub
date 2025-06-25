<?php
require_once '../config/session.php';
requireLogin();

require_once '../config/database_demo.php';
require_once '../models/Post.php';
require_once '../models/Category.php';
require_once '../includes/functions.php';

$postModel = new Post();
$categoryModel = new Category();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    redirect('/admin/posts.php');
}

// Get post data
$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM posts WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch();

if (!$post) {
    showAlert('Post tidak ditemukan.', 'error');
    redirect('/admin/posts.php');
}

$categories = $categoryModel->getAllCategories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'title' => sanitizeInput($_POST['title']),
        'content' => $_POST['content'],
        'excerpt' => sanitizeInput($_POST['excerpt']),
        'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
        'status' => in_array($_POST['status'], ['draft', 'published']) ? $_POST['status'] : 'draft'
    ];
    
    // Validation
    if (empty($formData['title'])) {
        $errors[] = 'Judul harus diisi.';
    }
    
    if (empty($formData['content'])) {
        $errors[] = 'Konten harus diisi.';
    }
    
    // Handle thumbnail upload
    $thumbnailName = $post['thumbnail']; // Keep existing thumbnail
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        if (isValidImageFile($_FILES['thumbnail'])) {
            $newThumbnail = uploadImage($_FILES['thumbnail'], '../uploads/');
            if ($newThumbnail) {
                // Delete old thumbnail if exists
                if ($post['thumbnail'] && file_exists('../uploads/' . $post['thumbnail'])) {
                    unlink('../uploads/' . $post['thumbnail']);
                }
                $thumbnailName = $newThumbnail;
            } else {
                $errors[] = 'Gagal mengupload gambar thumbnail.';
            }
        } else {
            $errors[] = 'File thumbnail harus berupa gambar (JPG, PNG, GIF).';
        }
    }
    
    if (empty($errors)) {
        // Generate new slug if title changed
        if ($formData['title'] !== $post['title']) {
            $formData['slug'] = $postModel->generateSlug($formData['title']);
        } else {
            $formData['slug'] = $post['slug'];
        }
        
        $formData['thumbnail'] = $thumbnailName;
        
        // Generate excerpt if not provided
        if (empty($formData['excerpt'])) {
            $formData['excerpt'] = generateExcerpt($formData['content']);
        }
        
        if ($postModel->updatePost($id, $formData)) {
            showAlert('Post berhasil diupdate.', 'success');
            redirect('/admin/posts.php');
        } else {
            $errors[] = 'Gagal mengupdate post.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Bloggua Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1 class="page-title">Edit Post</h1>
                <div class="btn-group">
                    <a href="../post.php?slug=<?php echo urlencode($post['slug']); ?>" class="btn btn-success" target="_blank">Lihat Post</a>
                    <a href="posts.php" class="btn btn-secondary">← Kembali</a>
                </div>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 1rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="content-card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="post-form">
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                            <!-- Main Content -->
                            <div>
                                <div class="form-group">
                                    <label for="title" class="form-label">Judul Post *</label>
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           class="form-control" 
                                           required
                                           value="<?php echo htmlspecialchars($post['title']); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="content" class="form-label">Konten *</label>
                                    <textarea id="content" 
                                              name="content" 
                                              class="form-control" 
                                              rows="15" 
                                              required><?php echo htmlspecialchars($post['content']); ?></textarea>
                                    <div class="form-text">Anda dapat menggunakan HTML untuk formatting.</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="excerpt" class="form-label">Ringkasan</label>
                                    <textarea id="excerpt" 
                                              name="excerpt" 
                                              class="form-control" 
                                              rows="3"
                                              maxlength="300"><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
                                    <div class="form-text">Kosongkan untuk auto-generate dari konten.</div>
                                </div>
                            </div>
                            
                            <!-- Sidebar -->
                            <div>
                                <div class="form-group">
                                    <label for="status" class="form-label">Status *</label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                        <option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>Publish</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Kategori</label>
                                    <select id="category_id" name="category_id" class="form-control">
                                        <option value="">Pilih Kategori</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" 
                                                    <?php echo $post['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="thumbnail" class="form-label">Thumbnail</label>
                                    <?php if ($post['thumbnail']): ?>
                                        <div style="margin-bottom: 1rem;">
                                            <img src="../uploads/<?php echo htmlspecialchars($post['thumbnail']); ?>" 
                                                 alt="Current thumbnail" 
                                                 class="image-preview">
                                            <div class="form-text">Thumbnail saat ini</div>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" 
                                           id="thumbnail" 
                                           name="thumbnail" 
                                           class="form-control-file" 
                                           accept="image/*">
                                    <div class="form-text">Max 5MB (JPG, PNG, GIF) - Kosongkan jika tidak ingin mengubah</div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                                        Update Post
                                    </button>
                                </div>
                                
                                <div class="form-group">
                                    <small style="color: var(--gray-600);">
                                        <strong>Dibuat:</strong> <?php echo formatDateTime($post['created_at']); ?><br>
                                        <strong>Diupdate:</strong> <?php echo formatDateTime($post['updated_at']); ?><br>
                                        <strong>Slug:</strong> <?php echo htmlspecialchars($post['slug']); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>