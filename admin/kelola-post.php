<?php
// Halaman untuk mengelola semua post blog
// Fitur: tampilkan, edit, hapus, dan pencarian post

require_once '../config/session.php';
requireLogin();

require_once '../config/database.php';
require_once '../models/Post.php';
require_once '../includes/functions.php';

$modelPost = new Post();

// Proses hapus post
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($modelPost->deletePost($id)) {
        showAlert('Post berhasil dihapus.', 'success');
    } else {
        showAlert('Gagal menghapus post.', 'error');
    }
    redirect('/admin/kelola-post.php');
}

// Pengaturan pagination
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$postPerHalaman = 10;
$offset = ($halaman - 1) * $postPerHalaman;

$postList = $modelPost->getAllPostsAdmin($postPerHalaman, $offset);
$totalPost = Database::getInstance()->getConnection()->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalHalaman = ceil($totalPost / $postPerHalaman);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Post - Bloggua Admin</title>
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
                        <h1 class="text-3xl font-bold text-abu-900">Kelola Post</h1>
                        <p class="text-abu-600 mt-1">Kelola semua artikel blog Anda</p>
                    </div>
                    <a href="buat-post.php" class="inline-flex items-center px-6 py-3 gradient-bg text-white rounded-xl hover:shadow-lg transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Post Baru
                    </a>
                </div>
            </header>
            
            <?php displayAlert(); ?>
            
            <!-- Konten -->
            <div class="p-8">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Header Card -->
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-abu-900">Daftar Post (<?php echo $totalPost; ?>)</h2>
                        <!-- Kotak Pencarian -->
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" id="table-search" placeholder="Cari post..." 
                                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-merah-utama focus:border-merah-utama">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabel Post -->
                    <div class="overflow-x-auto">
                        <?php if (!empty($postList)): ?>
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase tracking-wider">Judul & Konten</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase tracking-wider">Kategori</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($postList as $post): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-start space-x-4">
                                                    <?php if ($post['thumbnail']): ?>
                                                        <img src="../uploads/<?php echo htmlspecialchars($post['thumbnail']); ?>" 
                                                             alt="Thumbnail" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                                    <?php else: ?>
                                                        <div class="w-16 h-16 bg-merah-muda rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-6 h-6 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-semibold text-abu-900 truncate">
                                                            <?php echo htmlspecialchars($post['title']); ?>
                                                        </p>
                                                        <p class="text-xs text-abu-600 mt-1 line-clamp-2">
                                                            <?php echo htmlspecialchars(truncateText(strip_tags($post['content']), 100)); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php if ($post['category_name']): ?>
                                                    <span class="inline-flex px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                        <?php echo htmlspecialchars($post['category_name']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-abu-600 text-sm">Tanpa kategori</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                                             <?php echo $post['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                    <?php echo $post['status'] === 'published' ? 'Terpublikasi' : 'Draft'; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-abu-600">
                                                <div>
                                                    <?php echo formatDate($post['created_at']); ?>
                                                    <?php if ($post['updated_at'] !== $post['created_at']): ?>
                                                        <br><span class="text-xs text-abu-500">Diupdate: <?php echo formatDate($post['updated_at']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <a href="edit-post.php?id=<?php echo $post['id']; ?>" 
                                                       class="inline-flex items-center px-3 py-1 bg-kuning text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <a href="../artikel.php?slug=<?php echo urlencode($post['slug']); ?>" target="_blank"
                                                       class="inline-flex items-center px-3 py-1 bg-hijau text-white text-xs font-medium rounded-lg hover:bg-green-600 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                        </svg>
                                                        Lihat
                                                    </a>
                                                    <a href="kelola-post.php?aksi=hapus&id=<?php echo $post['id']; ?>" 
                                                       class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors"
                                                       data-action="delete" data-item-type="post" data-item-name="<?php echo htmlspecialchars($post['title']); ?>">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Hapus
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <?php if ($totalHalaman > 1): ?>
                                <div class="px-6 py-4 border-t border-gray-200">
                                    <nav class="flex justify-center">
                                        <div class="flex space-x-2">
                                            <?php if ($halaman > 1): ?>
                                                <a href="kelola-post.php?halaman=<?php echo $halaman - 1; ?>" 
                                                   class="px-4 py-2 bg-gray-100 text-abu-800 rounded-lg hover:bg-gray-200 transition-colors">
                                                    ‹ Sebelumnya
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = max(1, $halaman - 2); $i <= min($totalHalaman, $halaman + 2); $i++): ?>
                                                <?php if ($i == $halaman): ?>
                                                    <span class="px-4 py-2 gradient-bg text-white rounded-lg font-semibold">
                                                        <?php echo $i; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <a href="kelola-post.php?halaman=<?php echo $i; ?>" 
                                                       class="px-4 py-2 bg-gray-100 text-abu-800 rounded-lg hover:bg-gray-200 transition-colors">
                                                        <?php echo $i; ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            
                                            <?php if ($halaman < $totalHalaman): ?>
                                                <a href="kelola-post.php?halaman=<?php echo $halaman + 1; ?>" 
                                                   class="px-4 py-2 bg-gray-100 text-abu-800 rounded-lg hover:bg-gray-200 transition-colors">
                                                    Selanjutnya ›
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-20">
                                <svg class="w-24 h-24 text-abu-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-xl font-bold text-abu-900 mb-4">Belum ada post yang dibuat</h3>
                                <p class="text-abu-600 mb-6">Mulai membuat konten blog pertama Anda</p>
                                <a href="buat-post.php" class="inline-flex items-center px-6 py-3 gradient-bg text-white rounded-xl hover:shadow-lg transition-all transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Buat Post Pertama
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin-modern.js"></script>
</body>
</html>