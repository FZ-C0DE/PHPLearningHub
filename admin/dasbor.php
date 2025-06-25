<?php
// Halaman dashboard admin dengan statistik dan overview
// Menampilkan ringkasan data dan aksi cepat

require_once '../config/session.php';
requireLogin();

require_once '../config/database_auto.php';
require_once '../models/Post.php';
require_once '../models/Category.php';
require_once '../models/Comment.php';

$modelPost = new Post();
$modelKategori = new Category();
$modelKomentar = new Comment();

// Ambil statistik
$db = Database::getInstance()->getConnection();

$totalPost = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$postTerpublikasi = $db->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
$totalKategori = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalKomentar = $modelKomentar->getTotalComments();
$komentarPending = $modelKomentar->getPendingCommentsCount();

// Post terbaru
$postTerbaru = $modelPost->getAllPostsAdmin(5, 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bloggua Admin</title>
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
                        'abu-100': '#f5f5f5',
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
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
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
                        <h1 class="text-3xl font-bold text-abu-900">Dashboard</h1>
                        <p class="text-abu-600 mt-1">Selamat datang kembali, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="../beranda.php" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-100 text-abu-800 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Lihat Blog
                        </a>
                        <a href="../config/session.php?logout=1" class="inline-flex items-center px-4 py-2 gradient-bg text-white rounded-lg hover:shadow-lg transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Keluar
                        </a>
                    </div>
                </div>
            </header>
            
            <!-- Konten Dashboard -->
            <div class="p-8">
                <!-- Kartu Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Post -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift border-t-4 border-merah-utama">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-abu-600 text-sm font-medium">Total Post</p>
                                <p class="text-3xl font-bold text-abu-900 mt-1"><?php echo $totalPost; ?></p>
                            </div>
                            <div class="bg-merah-muda p-3 rounded-xl">
                                <svg class="w-8 h-8 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-hijau font-medium"><?php echo $postTerpublikasi; ?> terpublikasi</span>
                        </div>
                    </div>
                    
                    <!-- Post Terpublikasi -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift border-t-4 border-hijau">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-abu-600 text-sm font-medium">Terpublikasi</p>
                                <p class="text-3xl font-bold text-abu-900 mt-1"><?php echo $postTerpublikasi; ?></p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-hijau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-abu-600">dari <?php echo $totalPost; ?> total post</span>
                        </div>
                    </div>
                    
                    <!-- Total Kategori -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift border-t-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-abu-600 text-sm font-medium">Kategori</p>
                                <p class="text-3xl font-bold text-abu-900 mt-1"><?php echo $totalKategori; ?></p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-blue-600 font-medium">Aktif</span>
                        </div>
                    </div>
                    
                    <!-- Total Komentar -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift border-t-4 border-<?php echo $komentarPending > 0 ? 'kuning' : 'purple-500'; ?>">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-abu-600 text-sm font-medium">Komentar</p>
                                <p class="text-3xl font-bold text-abu-900 mt-1"><?php echo $totalKomentar; ?></p>
                            </div>
                            <div class="bg-<?php echo $komentarPending > 0 ? 'yellow' : 'purple'; ?>-50 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-<?php echo $komentarPending > 0 ? 'kuning' : 'purple-500'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <?php if ($komentarPending > 0): ?>
                                <span class="text-kuning font-medium"><?php echo $komentarPending; ?> menunggu moderasi</span>
                            <?php else: ?>
                                <span class="text-purple-600 font-medium">Semua dimoderasi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Grid Konten -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Post Terbaru -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h2 class="text-xl font-bold text-abu-900">Post Terbaru</h2>
                                <a href="kelola-post.php" class="text-merah-utama hover:text-merah-gelap font-medium text-sm">
                                    Lihat Semua →
                                </a>
                            </div>
                            <div class="p-6">
                                <?php if (!empty($postTerbaru)): ?>
                                    <div class="space-y-4">
                                        <?php foreach ($postTerbaru as $post): ?>
                                            <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-xl transition-colors">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-merah-muda rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-abu-900 truncate">
                                                        <?php echo htmlspecialchars($post['title']); ?>
                                                    </p>
                                                    <p class="text-xs text-abu-600 mt-1">
                                                        <?php echo htmlspecialchars($post['category_name'] ?: 'Tanpa kategori'); ?> • 
                                                        <?php echo formatDate($post['created_at']); ?>
                                                    </p>
                                                    <div class="flex items-center space-x-2 mt-2">
                                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                                                     <?php echo $post['status'] === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                            <?php echo $post['status'] === 'published' ? 'Terpublikasi' : 'Draft'; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 flex space-x-1">
                                                    <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="text-abu-600 hover:text-merah-utama">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="../artikel.php?slug=<?php echo urlencode($post['slug']); ?>" target="_blank" class="text-abu-600 hover:text-hijau">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-12">
                                        <svg class="w-16 h-16 text-abu-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-abu-600">Belum ada post yang dibuat</p>
                                        <a href="buat-post.php" class="mt-4 inline-flex items-center px-4 py-2 gradient-bg text-white rounded-lg hover:shadow-lg transition-all">
                                            Buat Post Pertama
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Aksi Cepat -->
                    <div class="space-y-6">
                        <!-- Quick Actions -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-abu-900 mb-4">Aksi Cepat</h3>
                            <div class="space-y-3">
                                <a href="buat-post.php" class="flex items-center p-3 gradient-bg text-white rounded-xl hover:shadow-lg transition-all transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Buat Post Baru
                                </a>
                                <a href="kelola-kategori.php" class="flex items-center p-3 bg-hijau text-white rounded-xl hover:shadow-lg transition-all transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Kelola Kategori
                                </a>
                                <a href="moderasi-komentar.php" class="flex items-center p-3 bg-kuning text-white rounded-xl hover:shadow-lg transition-all transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Moderasi Komentar
                                    <?php if ($komentarPending > 0): ?>
                                        <span class="ml-auto bg-white text-kuning px-2 py-1 rounded-full text-xs font-bold">
                                            <?php echo $komentarPending; ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Info Sistem -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-abu-900 mb-4">Info Sistem</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-abu-600">Versi PHP:</span>
                                    <span class="font-medium"><?php echo phpversion(); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-abu-600">Database:</span>
                                    <span class="font-medium">MySQL</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-abu-600">Login Terakhir:</span>
                                    <span class="font-medium"><?php echo date('d M Y H:i'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin-modern.js"></script>
</body>
</html>