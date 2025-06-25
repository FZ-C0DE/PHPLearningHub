<?php
require_once 'config/database.php';
require_once 'models/Post.php';
require_once 'models/Comment.php';
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: /');
    exit;
}

$postModel = new Post();
$commentModel = new Comment();

$post = $postModel->getPostBySlug($slug);

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

$comments = $commentModel->getCommentsByPostId($post['id']);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $author = sanitizeInput($_POST['author_name']);
    $email = sanitizeInput($_POST['author_email']);
    $content = sanitizeInput($_POST['content']);
    
    if (!empty($author) && !empty($email) && !empty($content)) {
        if (validateEmail($email)) {
            if ($commentModel->createComment($post['id'], $author, $email, $content)) {
                showAlert('Komentar berhasil dikirim dan sedang menunggu moderasi.', 'success');
                redirect('post.php?slug=' . $slug);
            } else {
                $error = 'Gagal mengirim komentar. Silakan coba lagi.';
            }
        } else {
            $error = 'Email tidak valid.';
        }
    } else {
        $error = 'Semua field harus diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Bloggua</title>
    <meta name="description" content="<?php echo htmlspecialchars($post['excerpt'] ?: generateExcerpt($post['content'])); ?>">
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
        <article class="single-post">
            <header class="post-header">
                <?php if ($post['category_name']): ?>
                    <span class="post-category"><?php echo htmlspecialchars($post['category_name']); ?></span>
                <?php endif; ?>
                
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                
                <div class="post-meta" style="justify-content: center; margin-bottom: 2rem;">
                    <span>Dipublikasikan pada <?php echo formatDate($post['created_at'], 'd F Y'); ?></span>
                </div>
            </header>

            <?php if ($post['thumbnail']): ?>
                <img src="uploads/<?php echo htmlspecialchars($post['thumbnail']); ?>" 
                     alt="<?php echo htmlspecialchars($post['title']); ?>" 
                     class="post-image">
            <?php endif; ?>

            <div class="post-body">
                <?php echo $post['content']; ?>
            </div>
        </article>

        <!-- Comments Section -->
        <section class="comments-section">
            <h3 class="comments-title">
                Komentar (<?php echo count($comments); ?>)
            </h3>

            <!-- Comment Form -->
            <div class="comment-form">
                <h4 style="margin-bottom: 1rem;">Tinggalkan Komentar</h4>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="author_name">Nama *</label>
                        <input type="text" 
                               id="author_name" 
                               name="author_name" 
                               class="form-control" 
                               required
                               value="<?php echo isset($_POST['author_name']) ? htmlspecialchars($_POST['author_name']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="author_email">Email * (tidak akan dipublikasikan)</label>
                        <input type="email" 
                               id="author_email" 
                               name="author_email" 
                               class="form-control" 
                               required
                               value="<?php echo isset($_POST['author_email']) ? htmlspecialchars($_POST['author_email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Komentar *</label>
                        <textarea id="content" 
                                  name="content" 
                                  class="form-control" 
                                  rows="5" 
                                  required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" name="submit_comment" class="btn btn-primary">
                        Kirim Komentar
                    </button>
                </form>
            </div>

            <!-- Comments List -->
            <?php if (!empty($comments)): ?>
                <div class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-author">
                                <?php echo htmlspecialchars($comment['author_name']); ?>
                            </div>
                            <div class="comment-date">
                                <?php echo formatDateTime($comment['created_at']); ?>
                            </div>
                            <div class="comment-content">
                                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: var(--gray-600); margin-top: 2rem;">
                    Belum ada komentar. Jadilah yang pertama berkomentar!
                </p>
            <?php endif; ?>
        </section>

        <!-- Back to Home -->
        <div style="text-align: center; margin-top: 3rem;">
            <a href="/" class="btn btn-primary">← Kembali ke Beranda</a>
        </div>
    </main>

    <!-- Footer -->
    <footer style="background: var(--gray-900); color: white; text-align: center; padding: 2rem; margin-top: 4rem;">
        <p>&copy; 2025 Bloggua. Semua hak dilindungi undang-undang.</p>
        <p>Blog sederhana dengan nuansa merah putih yang elegan</p>
    </footer>
</body>
</html>