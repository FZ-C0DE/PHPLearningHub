<?php
// Halaman untuk moderasi komentar blog
// Fitur: approve, reject, delete komentar dengan bulk actions

session_start();
if (!isAdminLoggedIn()) {
    redirect('masuk.php');
}

require_once '../config/database.php';
require_once '../models/Comment.php';
require_once '../includes/functions.php';

$modelKomentar = new Comment();

// Proses aksi komentar
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $aksi = $_GET['aksi'];
    
    switch ($aksi) {
        case 'setuju':
            if ($modelKomentar->updateCommentStatus($id, 'approved')) {
                showAlert('Komentar berhasil disetujui.', 'success');
            } else {
                showAlert('Gagal menyetujui komentar.', 'error');
            }
            break;
            
        case 'tolak':
            if ($modelKomentar->updateCommentStatus($id, 'rejected')) {
                showAlert('Komentar berhasil ditolak.', 'success');
            } else {
                showAlert('Gagal menolak komentar.', 'error');
            }
            break;
            
        case 'hapus':
            if ($modelKomentar->deleteComment($id)) {
                showAlert('Komentar berhasil dihapus.', 'success');
            } else {
                showAlert('Gagal menghapus komentar.', 'error');
            }
            break;
    }
    
    redirect('/admin/moderasi-komentar.php');
}

// Pengaturan pagination
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$komentarPerHalaman = 15;
$offset = ($halaman - 1) * $komentarPerHalaman;

$komentarList = $modelKomentar->getAllComments($komentarPerHalaman, $offset);
$totalKomentar = $modelKomentar->getTotalComments();
$totalHalaman = ceil($totalKomentar / $komentarPerHalaman);
$komentarPending = $modelKomentar->getPendingCommentsCount();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderasi Komentar - Bloggua Admin</title>
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
                        <h1 class="text-3xl font-bold text-abu-900">Moderasi Komentar</h1>
                        <p class="text-abu-600 mt-1">Kelola dan moderasi komentar dari pembaca</p>
                    </div>
                    <?php if ($komentarPending > 0): ?>
                        <div class="flex items-center bg-kuning text-white px-6 py-3 rounded-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold"><?php echo $komentarPending; ?> Menunggu Moderasi</span>
                        </div>
                    <?php endif; ?>
                </div>
            </header>
            
            <?php displayAlert(); ?>
            
            <!-- Konten -->
            <div class="p-8">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Header Card -->
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-abu-900">Daftar Komentar (<?php echo $totalKomentar; ?>)</h2>
                        <!-- Filter dan Pencarian -->
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" id="table-search" placeholder="Cari komentar..." 
                                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-merah-utama focus:border-merah-utama">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Daftar Komentar -->
                    <div class="overflow-x-auto">
                        <?php if (!empty($komentarList)): ?>
                            <div class="divide-y divide-gray-200">
                                <?php foreach ($komentarList as $komentar): ?>
                                    <div class="p-6 <?php echo $komentar['status'] === 'pending' ? 'bg-yellow-50' : ''; ?> hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start space-x-4">
                                            <!-- Avatar -->
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-merah-muda rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            <!-- Konten Komentar -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between mb-2">
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-abu-900">
                                                            <?php echo htmlspecialchars($komentar['author_name']); ?>
                                                        </h3>
                                                        <div class="flex items-center space-x-4 text-sm text-abu-600">
                                                            <span><?php echo htmlspecialchars($komentar['author_email']); ?></span>
                                                            <span>•</span>
                                                            <span><?php echo formatDateTime($komentar['created_at']); ?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Status Badge -->
                                                    <?php
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    $statusIcon = '';
                                                    switch ($komentar['status']) {
                                                        case 'approved':
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusText = 'Disetujui';
                                                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                                            break;
                                                        case 'rejected':
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            $statusText = 'Ditolak';
                                                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                            $statusText = 'Pending';
                                                            $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                                    }
                                                    ?>
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full <?php echo $statusClass; ?>">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <?php echo $statusIcon; ?>
                                                        </svg>
                                                        <?php echo $statusText; ?>
                                                    </span>
                                                </div>
                                                
                                                <!-- Post yang dikomentari -->
                                                <div class="mb-3">
                                                    <p class="text-sm text-abu-600">
                                                        Komentar pada: 
                                                        <a href="../artikel.php?slug=<?php echo urlencode($komentar['post_slug'] ?? '#'); ?>" 
                                                           target="_blank" 
                                                           class="text-merah-utama hover:text-merah-gelap font-medium">
                                                            <?php echo htmlspecialchars(truncateText($komentar['post_title'], 50)); ?>
                                                        </a>
                                                    </p>
                                                </div>
                                                
                                                <!-- Isi Komentar -->
                                                <div class="bg-gray-100 rounded-lg p-4 mb-4">
                                                    <p class="text-abu-800 leading-relaxed">
                                                        <?php echo nl2br(htmlspecialchars($komentar['content'])); ?>
                                                    </p>
                                                </div>
                                                
                                                <!-- Tombol Aksi -->
                                                <div class="flex items-center space-x-2">
                                                    <?php if ($komentar['status'] !== 'approved'): ?>
                                                        <a href="moderasi-komentar.php?aksi=setuju&id=<?php echo $komentar['id']; ?>" 
                                                           class="inline-flex items-center px-3 py-1 bg-hijau text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Setujui
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($komentar['status'] !== 'rejected'): ?>
                                                        <a href="moderasi-komentar.php?aksi=tolak&id=<?php echo $komentar['id']; ?>" 
                                                           class="inline-flex items-center px-3 py-1 bg-kuning text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Tolak
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <a href="moderasi-komentar.php?aksi=hapus&id=<?php echo $komentar['id']; ?>" 
                                                       class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors"
                                                       data-action="delete" data-item-type="komentar" data-item-name="dari <?php echo htmlspecialchars($komentar['author_name']); ?>">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Hapus
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if ($totalHalaman > 1): ?>
                                <div class="px-6 py-4 border-t border-gray-200">
                                    <nav class="flex justify-center">
                                        <div class="flex space-x-2">
                                            <?php if ($halaman > 1): ?>
                                                <a href="moderasi-komentar.php?halaman=<?php echo $halaman - 1; ?>" 
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
                                                    <a href="moderasi-komentar.php?halaman=<?php echo $i; ?>" 
                                                       class="px-4 py-2 bg-gray-100 text-abu-800 rounded-lg hover:bg-gray-200 transition-colors">
                                                        <?php echo $i; ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            
                                            <?php if ($halaman < $totalHalaman): ?>
                                                <a href="moderasi-komentar.php?halaman=<?php echo $halaman + 1; ?>" 
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <h3 class="text-xl font-bold text-abu-900 mb-4">Belum ada komentar</h3>
                                <p class="text-abu-600">Komentar dari pembaca akan muncul di sini untuk dimoderasi</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Panduan Moderasi -->
                <div class="mt-8 bg-blue-50 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-4">📋 Panduan Moderasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                        <div>
                            <h4 class="font-semibold mb-2">Setujui komentar jika:</h4>
                            <ul class="space-y-1">
                                <li>• Relevan dengan konten artikel</li>
                                <li>• Menggunakan bahasa yang sopan</li>
                                <li>• Memberikan nilai tambah diskusi</li>
                                <li>• Tidak mengandung spam atau iklan</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-2">Tolak komentar jika:</h4>
                            <ul class="space-y-1">
                                <li>• Mengandung kata-kata kasar atau ofensif</li>
                                <li>• Spam atau promosi tidak relevan</li>
                                <li>• Menyebarkan informasi palsu</li>
                                <li>• Melanggar aturan komunitas</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin-modern.js"></script>
</body>
</html>