<!-- Sidebar Modern Admin -->
<aside class="fixed inset-y-0 left-0 w-64 bg-white shadow-2xl z-40 border-r border-gray-200">
    <!-- Header Sidebar -->
    <div class="gradient-bg p-6">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-white font-bold text-lg">Bloggua</h2>
                <p class="text-red-100 text-sm">Admin Panel</p>
            </div>
        </div>
    </div>
    
    <!-- Menu Navigasi -->
    <nav class="mt-6 px-3">
        <div class="space-y-2">
            <!-- Dashboard -->
            <a href="dasbor.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dasbor.php' ? 'active' : ''; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            
            <!-- Kelola Post -->
            <div class="nav-group">
                <p class="nav-group-title">Konten</p>
                <a href="kelola-post.php" class="nav-item <?php echo in_array(basename($_SERVER['PHP_SELF']), ['kelola-post.php', 'buat-post.php', 'edit-post.php']) ? 'active' : ''; ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Kelola Post</span>
                </a>
                
                <a href="kelola-kategori.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'kelola-kategori.php' ? 'active' : ''; ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span>Kategori</span>
                </a>
                
                <a href="moderasi-komentar.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'moderasi-komentar.php' ? 'active' : ''; ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>Komentar</span>
                    <?php 
                    // Ambil jumlah komentar pending
                    $db = Database::getInstance()->getConnection();
                    $pendingCount = $db->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
                    if ($pendingCount > 0): 
                    ?>
                        <span class="ml-auto bg-kuning text-white text-xs px-2 py-1 rounded-full"><?php echo $pendingCount; ?></span>
                    <?php endif; ?>
                </a>
                
                <a href="statistik.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'statistik.php' ? 'active' : ''; ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Statistik</span>
                </a>
            </div>
            
            <!-- Quick Actions -->
            <div class="nav-group">
                <p class="nav-group-title">Aksi Cepat</p>
                <a href="buat-post.php" class="nav-item-accent">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Buat Post Baru</span>
                </a>
            </div>
            
            <!-- Blog & Settings -->
            <div class="nav-group">
                <p class="nav-group-title">Lainnya</p>
                <a href="../beranda.php" target="_blank" class="nav-item">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span>Lihat Blog</span>
                </a>
                
                <a href="keluar.php" class="nav-item text-red-600 hover:bg-red-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar</span>
                </a>
            </div>
        </div>
    </nav>
</aside>

<style>
.nav-item {
    @apply flex items-center px-3 py-3 text-abu-800 rounded-xl transition-all duration-200 hover:bg-gray-50 font-medium;
}

.nav-item.active {
    @apply bg-merah-muda text-merah-utama border-r-4 border-merah-utama;
}

.nav-item-accent {
    @apply flex items-center px-3 py-3 gradient-bg text-white rounded-xl transition-all duration-200 hover:shadow-lg transform hover:scale-105 font-medium;
}

.nav-group {
    @apply mt-6;
}

.nav-group-title {
    @apply text-xs font-semibold text-abu-600 uppercase tracking-wider px-3 mb-2;
}

.nav-item svg {
    @apply mr-3 flex-shrink-0;
}
</style>