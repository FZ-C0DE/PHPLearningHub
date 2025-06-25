<?php
require_once 'config/database_demo.php';
require_once 'models/Post.php';
require_once 'models/Category.php';
require_once 'includes/functions.php';

// Get current page for pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$postsPerPage = 6;
$offset = ($page - 1) * $postsPerPage;

// Initialize models
$postModel = new Post();
$categoryModel = new Category();

// Get posts and categories
$posts = $postModel->getAllPosts($postsPerPage, $offset, $search);
$totalPosts = $postModel->getTotalPosts($search);
$totalPages = ceil($totalPosts / $postsPerPage);
$categories = $categoryModel->getAllCategories();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($search) ? 'Pencarian: ' . htmlspecialchars($search) . ' - ' : ''; ?>Bloggua - Blog Sederhana dengan Nuansa Merah Putih</title>
    <meta name="description" content="Bloggua adalah platform blog sederhana dengan desain merah putih yang elegan. Temukan artikel menarik tentang teknologi, lifestyle, travel, dan kuliner.">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="/" class="logo">Bloggua</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="/">Beranda</a></li>
                    <li><a href="/admin/login.php">Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <!-- Search Box -->
        <div class="search-container">
            <form method="GET" action="/" class="search-box">
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Cari artikel..." 
                    value="<?php echo htmlspecialchars($search); ?>"
                >
                <button type="submit" class="search-btn">Cari</button>
            </form>
        </div>

        <?php if (!empty($search)): ?>
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2>Hasil pencarian untuk: "<?php echo htmlspecialchars($search); ?>"</h2>
                <p>Ditemukan <?php echo $totalPosts; ?> artikel</p>
            </div>
        <?php endif; ?>

        <!-- Posts Grid -->
        <?php if (!empty($posts)): ?>
            <div class="posts-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
                        <?php if ($post['thumbnail']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($post['thumbnail']); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>" 
                                 class="post-thumbnail">
                        <?php else: ?>
                            <div class="post-thumbnail" style="background: linear-gradient(135deg, var(--primary-red), var(--dark-red)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem;">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <?php if ($post['category_name']): ?>
                                <span class="post-category"><?php echo htmlspecialchars($post['category_name']); ?></span>
                            <?php endif; ?>
                            
                            <h2 class="post-title">
                                <a href="post.php?slug=<?php echo urlencode($post['slug']); ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h2>
                            
                            <p class="post-excerpt">
                                <?php echo htmlspecialchars($post['excerpt'] ?: generateExcerpt($post['content'])); ?>
                            </p>
                            
                            <div class="post-meta">
                                <span><?php echo formatDate($post['created_at']); ?></span>
                                <a href="post.php?slug=<?php echo urlencode($post['slug']); ?>" class="read-more">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php echo getPagination($page, $totalPages, '/'); ?>
        <?php else: ?>
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px;">
                <h2>Tidak ada artikel yang ditemukan</h2>
                <?php if (!empty($search)): ?>
                    <p>Coba gunakan kata kunci yang berbeda atau <a href="/">lihat semua artikel</a>.</p>
                <?php else: ?>
                    <p>Belum ada artikel yang dipublikasikan.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer style="background: var(--gray-900); color: white; text-align: center; padding: 2rem; margin-top: 4rem;">
        <p>&copy; 2025 Bloggua. Semua hak dilindungi undang-undang.</p>
        <p>Blog sederhana dengan nuansa merah putih yang elegan</p>
    </footer>
</body>
</html>