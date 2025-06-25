<?php
// Halaman untuk mengelola kategori blog
// Fitur: tambah, edit, hapus kategori dengan validasi

require_once '../config/session.php';
requireLogin();

require_once '../config/database.php';
require_once '../models/Category.php';
require_once '../includes/functions.php';

$modelKategori = new Category();

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'buat') {
        $nama = sanitizeInput($_POST['nama']);
        $deskripsi = sanitizeInput($_POST['deskripsi']);
        
        if (!empty($nama)) {
            if (!$modelKategori->categoryExists($nama)) {
                if ($modelKategori->createCategory($nama, $deskripsi)) {
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
        redirect('/admin/kelola-kategori.php');
    }
    
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'update') {
        $id = (int)$_POST['id'];
        $nama = sanitizeInput($_POST['nama']);
        $deskripsi = sanitizeInput($_POST['deskripsi']);
        
        if (!empty($nama)) {
            if (!$modelKategori->categoryExists($nama, $id)) {
                if ($modelKategori->updateCategory($id, $nama, $deskripsi)) {
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
        redirect('/admin/kelola-kategori.php');
    }
}

// Proses hapus kategori
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($modelKategori->deleteCategory($id)) {
        showAlert('Kategori berhasil dihapus.', 'success');
    } else {
        showAlert('Gagal menghapus kategori. Pastikan tidak ada post yang menggunakan kategori ini.', 'error');
    }
    redirect('/admin/kelola-kategori.php');
}

$kategoriList = $modelKategori->getAllCategories();
$kategoriEdit = null;

if (isset($_GET['edit'])) {
    $kategoriEdit = $modelKategori->getCategoryById((int)$_GET['edit']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Bloggua Admin</title>
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
                        'kuning': '#eab308',
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
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
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
                        <h1 class="text-3xl font-bold text-abu-900">Kelola Kategori</h1>
                        <p class="text-abu-600 mt-1">Organisir konten blog dengan kategori</p>
                    </div>
                </div>
            </header>
            
            <?php displayAlert(); ?>
            
            <!-- Konten -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Form Tambah/Edit Kategori -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                        <div class="px-6 py-4 gradient-bg">
                            <h2 class="text-xl font-bold text-white">
                                <?php echo $kategoriEdit ? 'Edit Kategori' : 'Tambah Kategori Baru'; ?>
                            </h2>
                        </div>
                        <div class="p-6">
                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="aksi" value="<?php echo $kategoriEdit ? 'update' : 'buat'; ?>">
                                <?php if ($kategoriEdit): ?>
                                    <input type="hidden" name="id" value="<?php echo $kategoriEdit['id']; ?>">
                                <?php endif; ?>
                                
                                <div>
                                    <label for="nama" class="block text-sm font-semibold text-abu-800 mb-2">
                                        Nama Kategori *
                                    </label>
                                    <input type="text" 
                                           id="nama" 
                                           name="nama" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all" 
                                           placeholder="Masukkan nama kategori..."
                                           required
                                           value="<?php echo $kategoriEdit ? htmlspecialchars($kategoriEdit['name']) : ''; ?>">
                                </div>
                                
                                <div>
                                    <label for="deskripsi" class="block text-sm font-semibold text-abu-800 mb-2">
                                        Deskripsi
                                    </label>
                                    <textarea id="deskripsi" 
                                              name="deskripsi" 
                                              rows="4" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all" 
                                              placeholder="Deskripsi kategori (opsional)..."><?php echo $kategoriEdit ? htmlspecialchars($kategoriEdit['description']) : ''; ?></textarea>
                                </div>
                                
                                <div class="flex space-x-3">
                                    <button type="submit" class="flex-1 gradient-bg text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <?php echo $kategoriEdit ? 'Update Kategori' : 'Tambah Kategori'; ?>
                                    </button>
                                    <?php if ($kategoriEdit): ?>
                                        <a href="kelola-kategori.php" class="flex-1 bg-gray-200 text-abu-800 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors text-center">
                                            Batal
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Daftar Kategori -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-abu-900">Daftar Kategori (<?php echo count($kategoriList); ?>)</h2>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <?php if (!empty($kategoriList)): ?>
                                <div class="divide-y divide-gray-200">
                                    <?php foreach ($kategoriList as $kategori): ?>
                                        <div class="p-6 hover:bg-gray-50 transition-colors">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-abu-900 mb-1">
                                                        <?php echo htmlspecialchars($kategori['name']); ?>
                                                    </h3>
                                                    <?php if (!empty($kategori['description'])): ?>
                                                        <p class="text-abu-600 text-sm mb-3">
                                                            <?php echo htmlspecialchars($kategori['description']); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <div class="flex items-center space-x-4">
                                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            <?php echo $kategori['post_count']; ?> post
                                                        </span>
                                                        <span class="text-xs text-abu-500">
                                                            Dibuat: <?php echo formatDate($kategori['created_at']); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="kelola-kategori.php?edit=<?php echo $kategori['id']; ?>" 
                                                       class="inline-flex items-center px-3 py-1 bg-kuning text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <?php if ($kategori['post_count'] == 0): ?>
                                                        <a href="kelola-kategori.php?aksi=hapus&id=<?php echo $kategori['id']; ?>" 
                                                           class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors"
                                                           data-action="delete" data-item-type="kategori" data-item-name="<?php echo htmlspecialchars($kategori['name']); ?>">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Hapus
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-3 py-1 bg-gray-300 text-gray-600 text-xs font-medium rounded-lg cursor-not-allowed">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                            </svg>
                                                            Digunakan
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-abu-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-abu-900 mb-2">Belum ada kategori</h3>
                                    <p class="text-abu-600">Mulai dengan membuat kategori pertama Anda</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Tips Kategori -->
                <div class="mt-8 bg-blue-50 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-4">💡 Tips Kategori</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                        <ul class="space-y-2">
                            <li>• Gunakan nama kategori yang jelas dan spesifik</li>
                            <li>• Hindari membuat terlalu banyak kategori</li>
                            <li>• Kelompokkan konten yang serupa</li>
                        </ul>
                        <ul class="space-y-2">
                            <li>• Kategori membantu SEO dan navigasi</li>
                            <li>• Kategori yang memiliki post tidak dapat dihapus</li>
                            <li>• Deskripsi kategori bersifat opsional</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin-modern.js"></script>
</body>
</html>