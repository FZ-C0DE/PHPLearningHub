<?php
// Halaman detail artikel dengan sistem komentar
// Menampilkan konten lengkap artikel dan form komentar

require_once 'config/database_demo.php';
require_once 'models/Post.php';
require_once 'models/Comment.php';
require_once 'models/Analytics.php';
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? sanitizeInput($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: /beranda.php');
    exit;
}

$modelPost = new Post();
$modelKomentar = new Comment();
$analytics = new Analytics();

$artikel = $modelPost->getPostBySlug($slug);

if ($artikel) {
    // Record view untuk analytics
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $analytics->recordPostView($artikel['id'], $ipAddress, $userAgent);
}

if (!$artikel) {
    header('HTTP/1.0 404 Not Found');
    include 'halaman-tidak-ditemukan.php';
    exit;
}

$komentarList = $modelKomentar->getCommentsByPostId($artikel['id']);

// Proses pengiriman komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_komentar'])) {
    $penulis = sanitizeInput($_POST['nama_penulis']);
    $email = sanitizeInput($_POST['email_penulis']);
    $isiKomentar = sanitizeInput($_POST['isi_komentar']);
    
    if (!empty($penulis) && !empty($email) && !empty($isiKomentar)) {
        if (validateEmail($email)) {
            if ($modelKomentar->createComment($artikel['id'], $penulis, $email, $isiKomentar)) {
                showAlert('Komentar berhasil dikirim dan sedang menunggu moderasi.', 'success');
                redirect('artikel.php?slug=' . $slug);
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
    <title><?php echo htmlspecialchars($artikel['title']); ?> - Bloggua</title>
    <meta name="description" content="<?php echo htmlspecialchars($artikel['excerpt'] ?: generateExcerpt($artikel['content'])); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'merah-utama': '#dc2626',
                        'merah-gelap': '#b91c1c',
                        'merah-muda': '#fef2f2',
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
        .artikel-content img { @apply rounded-lg shadow-lg my-6; }
        .artikel-content h1, .artikel-content h2, .artikel-content h3 { @apply font-bold text-abu-900 mt-8 mb-4; }
        .artikel-content h1 { @apply text-3xl; }
        .artikel-content h2 { @apply text-2xl; }
        .artikel-content h3 { @apply text-xl; }
        .artikel-content p { @apply mb-4 leading-relaxed; }
        .artikel-content ul, .artikel-content ol { @apply ml-6 mb-4; }
        .artikel-content li { @apply mb-2; }
        .artikel-content blockquote { @apply border-l-4 border-merah-utama pl-4 italic bg-merah-muda p-4 rounded-r-lg my-6; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg shadow-2xl">
        <div class="container mx-auto px-6 py-4">
            <nav class="flex items-center justify-between">
                <a href="/beranda.php" class="text-3xl font-bold text-white hover:text-red-100 transition-colors">
                    Bloggua
                </a>
                <div class="flex items-center space-x-6">
                    <a href="/beranda.php" class="text-white hover:text-red-100 transition-colors font-medium">Beranda</a>
                    <a href="/admin/masuk.php" class="bg-white text-merah-utama px-6 py-2 rounded-full font-semibold hover:bg-red-50 transition-all">
                        Admin
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="container mx-auto px-6 py-12">
        <article class="max-w-4xl mx-auto">
            <!-- Header Artikel -->
            <header class="text-center mb-12">
                <?php if ($artikel['category_name']): ?>
                    <span class="inline-block bg-merah-utama text-white px-6 py-2 rounded-full text-sm font-semibold mb-6">
                        <?php echo htmlspecialchars($artikel['category_name']); ?>
                    </span>
                <?php endif; ?>
                
                <h1 class="text-4xl md:text-5xl font-bold text-abu-900 mb-6 leading-tight">
                    <?php echo htmlspecialchars($artikel['title']); ?>
                </h1>
                
                <div class="flex items-center justify-center text-abu-600 space-x-4">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <?php echo formatDate($artikel['created_at'], 'd F Y'); ?>
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0H7"></path>
                        </svg>
                        <?php echo count($komentarList); ?> Komentar
                    </span>
                </div>
            </header>

            <!-- Gambar Artikel -->
            <?php if ($artikel['thumbnail']): ?>
                <div class="mb-12">
                    <img src="uploads/<?php echo htmlspecialchars($artikel['thumbnail']); ?>" 
                         alt="<?php echo htmlspecialchars($artikel['title']); ?>" 
                         class="w-full max-h-96 object-cover rounded-2xl shadow-2xl">
                </div>
            <?php endif; ?>

            <!-- Konten Artikel -->
            <div class="prose prose-lg max-w-none artikel-content bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-12">
                <?php echo $artikel['content']; ?>
            </div>

            <!-- Tombol Kembali -->
            <div class="text-center mb-12">
                <a href="/beranda.php" class="inline-flex items-center bg-merah-utama text-white px-8 py-3 rounded-full hover:bg-merah-gelap transition-all transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </article>

        <!-- Bagian Komentar -->
        <section class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                <h3 class="text-3xl font-bold text-abu-900 mb-8 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Komentar (<?php echo count($komentarList); ?>)
                </h3>

                <!-- Form Komentar -->
                <div class="bg-merah-muda rounded-xl p-8 mb-8">
                    <h4 class="text-xl font-bold text-abu-900 mb-6">Tinggalkan Komentar</h4>
                    
                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_penulis" class="block text-abu-800 font-semibold mb-2">Nama *</label>
                                <input type="text" 
                                       id="nama_penulis" 
                                       name="nama_penulis" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all" 
                                       required
                                       value="<?php echo isset($_POST['nama_penulis']) ? htmlspecialchars($_POST['nama_penulis']) : ''; ?>">
                            </div>
                            
                            <div>
                                <label for="email_penulis" class="block text-abu-800 font-semibold mb-2">Email * (tidak dipublikasikan)</label>
                                <input type="email" 
                                       id="email_penulis" 
                                       name="email_penulis" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all" 
                                       required
                                       value="<?php echo isset($_POST['email_penulis']) ? htmlspecialchars($_POST['email_penulis']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div>
                            <label for="isi_komentar" class="block text-abu-800 font-semibold mb-2">Komentar *</label>
                            <textarea id="isi_komentar" 
                                      name="isi_komentar" 
                                      rows="5" 
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all" 
                                      required><?php echo isset($_POST['isi_komentar']) ? htmlspecialchars($_POST['isi_komentar']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" name="kirim_komentar" class="bg-merah-utama text-white px-8 py-3 rounded-lg hover:bg-merah-gelap transition-all transform hover:scale-105 font-semibold">
                            Kirim Komentar
                        </button>
                    </form>
                </div>

                <!-- Daftar Komentar -->
                <?php if (!empty($komentarList)): ?>
                    <div class="space-y-6">
                        <?php foreach ($komentarList as $komentar): ?>
                            <div class="border-l-4 border-merah-utama bg-gray-50 rounded-r-xl p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h5 class="font-bold text-abu-900 text-lg">
                                        <?php echo htmlspecialchars($komentar['author_name']); ?>
                                    </h5>
                                    <span class="text-abu-600 text-sm">
                                        <?php echo formatDateTime($komentar['created_at']); ?>
                                    </span>
                                </div>
                                <div class="text-abu-800 leading-relaxed">
                                    <?php echo nl2br(htmlspecialchars($komentar['content'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-abu-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-abu-600 text-lg">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-abu-900 text-white py-12 mt-16">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 Bloggua. Semua hak dilindungi undang-undang.</p>
            <p class="text-gray-400 mt-2">Blog modern dengan nuansa merah putih yang elegan</p>
        </div>
    </footer>
</body>
</html>