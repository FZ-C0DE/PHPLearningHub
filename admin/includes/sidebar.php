<aside class="sidebar">
    <div class="sidebar-header">
        <h2 class="sidebar-title">Bloggua Admin</h2>
    </div>
    
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
            <i>📊</i> Dashboard
        </a>
        
        <a href="posts.php" class="nav-item <?php echo in_array(basename($_SERVER['PHP_SELF']), ['posts.php', 'post_create.php', 'post_edit.php']) ? 'active' : ''; ?>">
            <i>📝</i> Post
        </a>
        
        <a href="categories.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'active' : ''; ?>">
            <i>📂</i> Kategori
        </a>
        
        <a href="comments.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'comments.php' ? 'active' : ''; ?>">
            <i>💬</i> Komentar
        </a>
        
        <hr style="margin: 1rem 0; border: none; border-top: 1px solid var(--gray-200);">
        
        <a href="../" class="nav-item" target="_blank">
            <i>🌐</i> Lihat Blog
        </a>
        
        <a href="logout.php" class="nav-item">
            <i>🚪</i> Logout
        </a>
    </nav>
</aside>