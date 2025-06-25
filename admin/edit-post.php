<?php
// Halaman untuk mengedit post yang sudah ada
// Form edit dengan data yang sudah terisi sebelumnya

require_once '../config/session.php';
requireLogin();

require_once '../config/database_auto.php';
require_once '../models/Post.php';
require_once '../models/Category.php';
require_once '../includes/functions.php';

$modelPost = new Post();
$modelKategori = new Category();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    redirect('/admin/kelola-post.php');
}

// Ambil data post yang akan diedit
$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM posts WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch();

if (!$post) {
    showAlert('Post tidak ditemukan.', 'error');
    redirect('/admin/kelola-post.php');
}

$kategoriList = $modelKategori->getAllCategories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'judul' => sanitizeInput($_POST['judul']),
        'konten' => $_POST['konten'],
        'ringkasan' => sanitizeInput($_POST['ringkasan']),
        'kategori_id' => !empty($_POST['kategori_id']) ? (int)$_POST['kategori_id'] : null,
        'status' => in_array($_POST['status'], ['draft', 'published']) ? $_POST['status'] : 'draft'
    ];
    
    // Validasi input
    if (empty($formData['judul'])) {
        $errors[] = 'Judul harus diisi.';
    }
    
    if (empty($formData['konten'])) {
        $errors[] = 'Konten harus diisi.';
    }
    
    // Proses upload thumbnail baru
    $namaFileThumbnail = $post['thumbnail']; // Gunakan thumbnail lama sebagai default
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        if (isValidImageFile($_FILES['thumbnail'])) {
            $thumbnailBaru = uploadImage($_FILES['thumbnail'], '../uploads/');
            if ($thumbnailBaru) {
                // Hapus thumbnail lama jika ada
                if ($post['thumbnail'] && file_exists('../uploads/' . $post['thumbnail'])) {
                    unlink('../uploads/' . $post['thumbnail']);
                }
                $namaFileThumbnail = $thumbnailBaru;
            } else {
                $errors[] = 'Gagal mengupload gambar thumbnail.';
            }
        } else {
            $errors[] = 'File thumbnail harus berupa gambar (JPG, PNG, GIF).';
        }
    }
    
    if (empty($errors)) {
        // Siapkan data untuk update dengan struktur yang sesuai
        $dataPost = [
            'title' => $formData['judul'],
            'slug' => ($formData['judul'] !== $post['title']) ? $modelPost->generateSlug($formData['judul']) : $post['slug'],
            'content' => $formData['konten'],
            'excerpt' => !empty($formData['ringkasan']) ? $formData['ringkasan'] : generateExcerpt($formData['konten']),
            'category_id' => $formData['kategori_id'],
            'thumbnail' => $namaFileThumbnail,
            'status' => $formData['status']
        ];
        
        if ($modelPost->updatePost($id, $dataPost)) {
            showAlert('Post berhasil diupdate.', 'success');
            redirect('/admin/kelola-post.php');
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'merah-utama': '#dc2626',
                        'merah-gelap': '#b91c1c',
                        'merah-muda': '#fef2f2',
                        'hijau': '#16a34a',
                        'abu-600': '#525252',
                        'abu-800': '#262626',
                        'abu-900': '#171717'
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #dc2626, #b91c1c); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include 'includes/sidebar-modern.php'; ?>
        
        <!-- Konten Utama -->
        <main class="flex-1 ml-64 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-abu-900">Edit Post</h1>
                        <p class="text-abu-600 mt-1">Edit artikel: <?php echo htmlspecialchars($post['title']); ?></p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="../artikel.php?slug=<?php echo urlencode($post['slug']); ?>" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-hijau text-white rounded-lg hover:bg-green-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Lihat Post
                        </a>
                        <a href="kelola-post.php" class="inline-flex items-center px-4 py-2 bg-gray-100 text-abu-800 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </header>
            
            <!-- Konten Form -->
            <div class="p-8">
                <?php if (!empty($errors)): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-red-800 font-semibold">Terjadi kesalahan:</h3>
                        </div>
                        <ul class="text-red-700 list-disc list-inside space-y-1">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <form method="POST" enctype="multipart/form-data" id="post-form" class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Kolom Utama -->
                            <div class="lg:col-span-2 space-y-6">
                                <!-- Judul Post -->
                                <div>
                                    <label for="judul" class="block text-sm font-semibold text-abu-800 mb-3">
                                        Judul Post *
                                    </label>
                                    <input type="text" 
                                           id="judul" 
                                           name="judul" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all text-lg font-medium" 
                                           required
                                           value="<?php echo htmlspecialchars($post['title']); ?>">
                                </div>
                                
                                <!-- Konten Post -->
                                <div>
                                    <label for="konten" class="block text-sm font-semibold text-abu-800 mb-3">
                                        Konten Post *
                                    </label>
                                    <textarea id="konten" 
                                              name="konten" 
                                              rows="20" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all" 
                                              required><?php echo htmlspecialchars($post['content']); ?></textarea>
                                    <p class="text-sm text-abu-600 mt-2">
                                        Anda dapat menggunakan tag HTML untuk formatting.
                                    </p>
                                </div>
                                
                                <!-- Ringkasan -->
                                <div>
                                    <label for="ringkasan" class="block text-sm font-semibold text-abu-800 mb-3">
                                        Ringkasan Post
                                    </label>
                                    <textarea id="ringkasan" 
                                              name="ringkasan" 
                                              rows="4" 
                                              maxlength="300"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all"><?php echo htmlspecialchars($post['excerpt']); ?></textarea>
                                    <p class="text-sm text-abu-600 mt-2">Kosongkan untuk auto-generate dari konten.</p>
                                </div>
                            </div>
                            
                            <!-- Sidebar -->
                            <div class="space-y-6">
                                <!-- Status Publikasi -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-abu-900 mb-4">Publikasi</h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="status" class="block text-sm font-semibold text-abu-800 mb-2">Status *</label>
                                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-merah-utama focus:border-merah-utama" required>
                                                <option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>
                                                    💾 Draft
                                                </option>
                                                <option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>
                                                    🚀 Terpublikasi
                                                </option>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Update Post
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Kategori -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-abu-900 mb-4">Kategori</h3>
                                    
                                    <div>
                                        <select id="kategori_id" name="kategori_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-merah-utama focus:border-merah-utama">
                                            <option value="">Pilih Kategori</option>
                                            <?php foreach ($kategoriList as $kategori): ?>
                                                <option value="<?php echo $kategori['id']; ?>" 
                                                        <?php echo $post['category_id'] == $kategori['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($kategori['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Upload Thumbnail -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-abu-900 mb-4">Gambar Thumbnail</h3>
                                    
                                    <?php if ($post['thumbnail']): ?>
                                        <div class="mb-4">
                                            <img src="../uploads/<?php echo htmlspecialchars($post['thumbnail']); ?>" 
                                                 alt="Thumbnail saat ini" 
                                                 class="max-w-full h-32 object-cover rounded-lg border border-gray-200">
                                            <p class="text-sm text-abu-600 mt-2">Thumbnail saat ini</p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <input type="file" 
                                               id="thumbnail" 
                                               name="thumbnail" 
                                               accept="image/*"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-merah-utama focus:border-merah-utama file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-merah-utama file:text-white hover:file:bg-merah-gelap">
                                        <p class="text-xs text-abu-600 mt-2">
                                            Maksimal 5MB (JPG, PNG, GIF). Kosongkan jika tidak ingin mengubah.
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Info Post -->
                                <div class="bg-blue-50 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-blue-900 mb-4">📊 Info Post</h3>
                                    <div class="text-sm text-blue-800 space-y-2">
                                        <div>
                                            <strong>Dibuat:</strong><br>
                                            <?php echo formatDateTime($post['created_at']); ?>
                                        </div>
                                        <div>
                                            <strong>Diupdate:</strong><br>
                                            <?php echo formatDateTime($post['updated_at']); ?>
                                        </div>
                                        <div>
                                            <strong>Slug:</strong><br>
                                            <code class="bg-blue-100 px-2 py-1 rounded text-xs"><?php echo htmlspecialchars($post['slug']); ?></code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin-modern.js"></script>
</body>
</html>