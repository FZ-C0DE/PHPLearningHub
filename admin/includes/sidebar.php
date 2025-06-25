<aside class="sidebar">
    <div class="sidebar-header">
        <h2 class="sidebar-title">Bloggua Admin</h2>
    </div>
    
    <nav class="sidebar-nav">
        <a href="dasbor.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dasbor.php' ? 'active' : ''; ?>">
            <i>📊</i> Dashboard
        </a>
        
        <a href="kelola-post.php" class="nav-item <?php echo in_array(basename($_SERVER['PHP_SELF']), ['kelola-post.php', 'buat-post.php', 'edit-post.php']) ? 'active' : ''; ?>">
            <i>📝</i> Post
        </a>
        
        <a href="kelola-kategori.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'kelola-kategori.php' ? 'active' : ''; ?>">
            <i>📂</i> Kategori
        </a>
        
        <a href="moderasi-komentar.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'moderasi-komentar.php' ? 'active' : ''; ?>">
            <i>💬</i> Komentar
        </a>
        
        <a href="kelola-slider.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'kelola-slider.php' ? 'active' : ''; ?>">
            <i>🖼️</i> Slider
        </a>
        
        <hr style="margin: 1rem 0; border: none; border-top: 1px solid var(--gray-200);">
        
        <a href="../beranda.php" class="nav-item" target="_blank">
            <i>🌐</i> Lihat Blog
        </a>
        
        <a href="keluar.php" class="nav-item">
            <i>🚪</i> Logout
        </a>
    </nav>
</aside>