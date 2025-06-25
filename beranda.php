<?php
// Halaman beranda utama blog Bloggua
// Menampilkan daftar artikel dengan fitur pencarian dan pagination

require_once 'config/database_auto.php';
require_once 'models/Post.php';
require_once 'models/Category.php';
require_once 'includes/functions.php';

// Ambil halaman saat ini untuk pagination
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$pencarian = isset($_GET['pencarian']) ? sanitizeInput($_GET['pencarian']) : '';
$artikelPerHalaman = 6;
$offset = ($halaman - 1) * $artikelPerHalaman;

// Inisialisasi model
$modelPost = new Post();
$modelKategori = new Category();

// Ambil data posts dan kategori
$artikelList = $modelPost->getAllPosts($artikelPerHalaman, $offset, $pencarian);
$totalArtikel = $modelPost->getTotalPosts($pencarian);
$totalHalaman = ceil($totalArtikel / $artikelPerHalaman);
$kategoriList = $modelKategori->getAllCategories();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($pencarian) ? 'Pencarian: ' . htmlspecialchars($pencarian) . ' - ' : ''; ?>Bloggua - Blog Modern Nuansa Merah Putih</title>
    <meta name="description" content="Bloggua adalah platform blog modern dengan desain merah putih yang elegan. Temukan artikel menarik tentang teknologi, lifestyle, travel, dan kuliner.">
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
                        'abu-200': '#e5e5e5',
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
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header Modern -->
    <header class="gradient-bg shadow-2xl sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <nav class="flex items-center justify-between">
                <a href="/beranda.php" class="text-3xl font-bold text-white hover:text-red-100 transition-colors">
                    Bloggua
                </a>
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/beranda.php" class="text-white hover:text-red-100 transition-colors font-medium">Beranda</a>
                    <a href="/admin/masuk.php" class="bg-white text-merah-utama px-6 py-2 rounded-full font-semibold hover:bg-red-50 transition-all transform hover:scale-105">
                        Admin
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-white hover:text-red-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </nav>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden bg-merah-gelap mt-4 rounded-lg overflow-hidden">
                <div class="px-4 py-3 space-y-2">
                    <a href="/beranda.php" class="block text-white hover:bg-red-700 px-4 py-2 rounded transition-colors">Beranda</a>
                    <a href="/admin/masuk.php" class="block text-white hover:bg-red-700 px-4 py-2 rounded transition-colors">Admin Panel</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="gradient-bg py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Selamat Datang di <span class="text-red-200">Bloggua</span>
            </h1>
            <p class="text-xl text-red-100 mb-10 max-w-2xl mx-auto">
                Platform blog modern dengan nuansa merah putih yang elegan. Temukan inspirasi dan berbagi cerita terbaik Anda.
            </p>
            
            <!-- Kotak Pencarian Modern & Responsif -->
            <div class="max-w-2xl mx-auto px-4">
                <form method="GET" action="/beranda.php" class="relative">
                    <input 
                        type="text" 
                        name="pencarian" 
                        id="search-input"
                        class="w-full px-6 md:px-8 py-3 md:py-4 rounded-full text-base md:text-lg border-0 shadow-2xl focus:ring-4 focus:ring-red-200 focus:outline-none"
                        placeholder="Cari artikel menarik..." 
                        value="<?php echo htmlspecialchars($pencarian); ?>"
                        autocomplete="off"
                    >
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-merah-utama text-white px-4 md:px-8 py-2 rounded-full hover:bg-merah-gelap transition-all">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="hidden md:inline ml-2">Cari</span>
                    </button>
                </form>
                
                <!-- Search Suggestions (Live Search) -->
                <div id="search-suggestions" class="hidden absolute top-full left-0 right-0 bg-white rounded-xl shadow-2xl mt-2 border border-gray-200 z-50">
                    <div class="p-4">
                        <div class="text-sm text-gray-600 mb-2">Hasil pencarian:</div>
                        <div id="suggestion-results" class="space-y-2">
                            <!-- Live search results akan dimuat di sini -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Konten Utama -->
    <main class="container mx-auto px-6 py-16">
        <?php if (!empty($pencarian)): ?>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-abu-900 mb-4">Hasil pencarian untuk: "<?php echo htmlspecialchars($pencarian); ?>"</h2>
                <p class="text-abu-600 text-lg">Ditemukan <?php echo $totalArtikel; ?> artikel</p>
            </div>
        <?php endif; ?>

        <!-- Grid Artikel Modern -->
        <?php if (!empty($artikelList)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <?php foreach ($artikelList as $artikel): ?>
                    <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                        <?php if ($artikel['thumbnail']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($artikel['thumbnail']); ?>" 
                                 alt="<?php echo htmlspecialchars($artikel['title']); ?>" 
                                 class="w-full h-64 object-cover">
                        <?php else: ?>
                            <div class="h-64 gradient-bg flex items-center justify-center">
                                <h3 class="text-white text-xl font-bold text-center px-6">
                                    <?php echo htmlspecialchars($artikel['title']); ?>
                                </h3>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <?php if ($artikel['category_name']): ?>
                                <span class="inline-block bg-merah-muda text-merah-utama px-4 py-1 rounded-full text-sm font-semibold mb-4">
                                    <?php echo htmlspecialchars($artikel['category_name']); ?>
                                </span>
                            <?php endif; ?>
                            
                            <h2 class="text-xl font-bold text-abu-900 mb-3 line-clamp-2">
                                <a href="artikel.php?slug=<?php echo urlencode($artikel['slug']); ?>" class="hover:text-merah-utama transition-colors">
                                    <?php echo htmlspecialchars($artikel['title']); ?>
                                </a>
                            </h2>
                            
                            <p class="text-abu-600 mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($artikel['excerpt'] ?: generateExcerpt($artikel['content'])); ?>
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-abu-600"><?php echo formatDate($artikel['created_at']); ?></span>
                                <a href="artikel.php?slug=<?php echo urlencode($artikel['slug']); ?>" 
                                   class="bg-merah-utama text-white px-4 py-2 rounded-lg hover:bg-merah-gelap transition-all transform hover:scale-105">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination Modern -->
            <?php if ($totalHalaman > 1): ?>
                <div class="flex justify-center">
                    <nav class="flex space-x-2">
                        <?php if ($halaman > 1): ?>
                            <a href="beranda.php?halaman=<?php echo $halaman - 1; ?><?php echo $pencarian ? '&pencarian=' . urlencode($pencarian) : ''; ?>" 
                               class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                ‹ Sebelumnya
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $halaman - 2); $i <= min($totalHalaman, $halaman + 2); $i++): ?>
                            <?php if ($i == $halaman): ?>
                                <span class="px-4 py-2 bg-merah-utama text-white rounded-lg font-semibold">
                                    <?php echo $i; ?>
                                </span>
                            <?php else: ?>
                                <a href="beranda.php?halaman=<?php echo $i; ?><?php echo $pencarian ? '&pencarian=' . urlencode($pencarian) : ''; ?>" 
                                   class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <?php echo $i; ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($halaman < $totalHalaman): ?>
                            <a href="beranda.php?halaman=<?php echo $halaman + 1; ?><?php echo $pencarian ? '&pencarian=' . urlencode($pencarian) : ''; ?>" 
                               class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Selanjutnya ›
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="bg-white rounded-2xl shadow-lg p-12 max-w-2xl mx-auto">
                    <svg class="w-24 h-24 text-abu-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.467-.881-6.072-2.33"></path>
                    </svg>
                    <h2 class="text-3xl font-bold text-abu-900 mb-4">Tidak ada artikel yang ditemukan</h2>
                    <?php if (!empty($pencarian)): ?>
                        <p class="text-abu-600 mb-6">Coba gunakan kata kunci yang berbeda atau lihat semua artikel.</p>
                        <a href="beranda.php" class="bg-merah-utama text-white px-8 py-3 rounded-full hover:bg-merah-gelap transition-all transform hover:scale-105">
                            Lihat Semua Artikel
                        </a>
                    <?php else: ?>
                        <p class="text-abu-600">Belum ada artikel yang dipublikasikan.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer Modern -->
    <footer class="bg-abu-900 text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <div class="mb-8">
                <h3 class="text-2xl font-bold mb-4">Bloggua</h3>
                <p class="text-gray-400 max-w-2xl mx-auto">
                    Platform blog modern dengan nuansa merah putih yang elegan. Tempat berbagi inspirasi dan cerita terbaik.
                </p>
            </div>
            <div class="border-t border-gray-700 pt-8">
                <p class="text-gray-400">
                    &copy; 2025 Bloggua. Semua hak dilindungi undang-undang.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Script untuk fitur interaktif
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    
                    // Animasi icon hamburger
                    const icon = this.querySelector('svg');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    } else {
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                    }
                });
                
                // Close mobile menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                        mobileMenu.classList.add('hidden');
                        const icon = mobileMenuButton.querySelector('svg');
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    }
                });
            }
            
            // Live Search Functionality
            const searchInput = document.getElementById('search-input');
            const searchSuggestions = document.getElementById('search-suggestions');
            const suggestionResults = document.getElementById('suggestion-results');
            let searchTimeout;
            
            if (searchInput && searchSuggestions) {
                // Position suggestions relative to search container
                const searchContainer = searchInput.closest('.max-w-2xl');
                if (searchContainer) {
                    searchContainer.style.position = 'relative';
                }
                
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    
                    clearTimeout(searchTimeout);
                    
                    if (query.length < 2) {
                        searchSuggestions.classList.add('hidden');
                        return;
                    }
                    
                    // Debounce search
                    searchTimeout = setTimeout(() => {
                        performLiveSearch(query);
                    }, 300);
                });
                
                searchInput.addEventListener('focus', function() {
                    if (this.value.trim().length >= 2) {
                        searchSuggestions.classList.remove('hidden');
                    }
                });
                
                // Close suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                        searchSuggestions.classList.add('hidden');
                    }
                });
            }
            
            // Live search function
            function performLiveSearch(query) {
                fetch(`/api/search.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displaySearchSuggestions(data);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }
            
            function displaySearchSuggestions(results) {
                if (!results || results.length === 0) {
                    suggestionResults.innerHTML = '<div class="text-gray-500 text-sm py-2">Tidak ada hasil ditemukan</div>';
                    searchSuggestions.classList.remove('hidden');
                    return;
                }
                
                const html = results.slice(0, 5).map(post => `
                    <a href="artikel.php?slug=${encodeURIComponent(post.slug)}" 
                       class="block p-3 hover:bg-gray-50 rounded-lg transition-colors border-l-4 border-merah-utama">
                        <div class="font-medium text-gray-900 text-sm">${escapeHtml(post.title)}</div>
                        <div class="text-gray-600 text-xs mt-1">${escapeHtml(post.excerpt || '').substring(0, 100)}...</div>
                        ${post.category_name ? `<div class="inline-block bg-merah-muda text-merah-utama px-2 py-1 rounded text-xs mt-2">${escapeHtml(post.category_name)}</div>` : ''}
                    </a>
                `).join('');
                
                suggestionResults.innerHTML = html;
                searchSuggestions.classList.remove('hidden');
            }
            
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Smooth scroll untuk anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Animasi fade in untuk card articles
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe semua artikel cards
            document.querySelectorAll('article').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>