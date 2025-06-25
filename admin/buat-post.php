<?php
// Halaman untuk membuat post baru
// Form lengkap dengan editor, upload gambar, dan pilihan kategori

require_once '../config/session.php';
requireLogin();

require_once '../config/database_auto.php';
require_once '../models/Post.php';
require_once '../models/Category.php';
require_once '../includes/functions.php';

$modelPost = new Post();
$modelKategori = new Category();
$kategoriList = $modelKategori->getAllCategories();

$errors = [];
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'judul' => sanitizeInput($_POST['judul']),
        'konten' => $_POST['konten'], // Jangan sanitize konten HTML
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
    
    // Proses upload thumbnail
    $namaFileThumbnail = null;
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        if (isValidImageFile($_FILES['thumbnail'])) {
            $namaFileThumbnail = uploadImage($_FILES['thumbnail'], '../uploads/');
            if (!$namaFileThumbnail) {
                $errors[] = 'Gagal mengupload gambar thumbnail.';
            }
        } else {
            $errors[] = 'File thumbnail harus berupa gambar (JPG, PNG, GIF).';
        }
    }
    
    if (empty($errors)) {
        // Siapkan data untuk database dengan struktur yang sesuai
        $dataPost = [
            'title' => $formData['judul'],
            'slug' => $modelPost->generateSlug($formData['judul']),
            'content' => $formData['konten'],
            'excerpt' => !empty($formData['ringkasan']) ? $formData['ringkasan'] : generateExcerpt($formData['konten']),
            'category_id' => $formData['kategori_id'],
            'thumbnail' => $namaFileThumbnail,
            'status' => $formData['status']
        ];
        
        if ($modelPost->createPost($dataPost)) {
            showAlert('Post berhasil dibuat.', 'success');
            redirect('/admin/kelola-post.php');
        } else {
            $errors[] = 'Gagal menyimpan post.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Post Baru - Bloggua Admin</title>
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
                        <h1 class="text-3xl font-bold text-abu-900">Buat Post Baru</h1>
                        <p class="text-abu-600 mt-1">Buat artikel blog yang menarik</p>
                    </div>
                    <a href="kelola-post.php" class="inline-flex items-center px-4 py-2 bg-gray-100 text-abu-800 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
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
                                           placeholder="Masukkan judul post yang menarik..."
                                           required
                                           value="<?php echo htmlspecialchars($formData['judul'] ?? ''); ?>">
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
                                              placeholder="Tulis konten post Anda di sini... Anda dapat menggunakan HTML untuk formatting."
                                              required><?php echo htmlspecialchars($formData['konten'] ?? ''); ?></textarea>
                                    <p class="text-sm text-abu-600 mt-2">
                                        Anda dapat menggunakan tag HTML seperti &lt;p&gt;, &lt;h2&gt;, &lt;h3&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt; untuk formatting.
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
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all" 
                                              placeholder="Ringkasan singkat untuk preview (opsional - akan di-generate otomatis jika kosong)"><?php echo htmlspecialchars($formData['ringkasan'] ?? ''); ?></textarea>
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
                                                <option value="draft" <?php echo ($formData['status'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>
                                                    💾 Simpan sebagai Draft
                                                </option>
                                                <option value="published" <?php echo ($formData['status'] ?? '') === 'published' ? 'selected' : ''; ?>>
                                                    🚀 Publikasikan Sekarang
                                                </option>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Simpan Post
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
                                                        <?php echo ($formData['kategori_id'] ?? '') == $kategori['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($kategori['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                        <p class="text-sm text-abu-600 mt-2">
                                            <a href="kelola-kategori.php" class="text-merah-utama hover:text-merah-gelap">
                                                + Kelola kategori
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Upload Thumbnail -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-abu-900 mb-4">Gambar Thumbnail</h3>
                                    
                                    <div>
                                        <input type="file" 
                                               id="thumbnail" 
                                               name="thumbnail" 
                                               accept="image/*"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-merah-utama focus:border-merah-utama file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-merah-utama file:text-white hover:file:bg-merah-gelap">
                                        <p class="text-xs text-abu-600 mt-2">
                                            Maksimal 5MB (JPG, PNG, GIF). Gambar akan otomatis di-resize untuk web.
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Tips Penulisan -->
                                <div class="bg-blue-50 rounded-xl p-6">
                                    <h3 class="text-lg font-bold text-blue-900 mb-4">💡 Tips Penulisan</h3>
                                    <ul class="text-sm text-blue-800 space-y-2">
                                        <li>• Gunakan judul yang menarik dan deskriptif</li>
                                        <li>• Buat konten yang informatif dan berkualitas</li>
                                        <li>• Gunakan sub-heading (H2, H3) untuk struktur</li>
                                        <li>• Tambahkan gambar untuk memperkaya konten</li>
                                        <li>• Preview sebelum mempublikasikan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin-modern.js"></script>
    <script>
        // Auto-resize textarea
        const textarea = document.getElementById('konten');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    </script>
</body>
</html>