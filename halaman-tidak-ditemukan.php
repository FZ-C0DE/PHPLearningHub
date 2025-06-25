<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - Bloggua</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'merah-utama': '#dc2626',
                        'merah-gelap': '#b91c1c',
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

    <!-- Konten 404 -->
    <main class="container mx-auto px-6 py-20">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Ilustrasi 404 -->
            <div class="mb-12">
                <div class="relative">
                    <h1 class="text-9xl font-bold text-merah-utama opacity-20">404</h1>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-32 h-32 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.467-.881-6.072-2.33"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Pesan Error -->
            <div class="bg-white rounded-2xl shadow-lg p-12">
                <h2 class="text-4xl font-bold text-abu-900 mb-6">Oops! Halaman Tidak Ditemukan</h2>
                <p class="text-xl text-abu-600 mb-8 leading-relaxed">
                    Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan, 
                    dihapus, atau URL yang Anda masukkan salah.
                </p>
                
                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <a href="/beranda.php" class="inline-flex items-center px-8 py-3 gradient-bg text-white rounded-xl hover:shadow-lg transition-all transform hover:scale-105 font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                    <button onclick="history.back()" class="inline-flex items-center px-8 py-3 bg-gray-200 text-abu-800 rounded-xl hover:bg-gray-300 transition-all font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Halaman Sebelumnya
                    </button>
                </div>
                
                <!-- Kotak Pencarian -->
                <div class="border-t border-gray-200 pt-8">
                    <h3 class="text-xl font-bold text-abu-900 mb-4">Atau cari artikel yang Anda inginkan:</h3>
                    <form method="GET" action="/beranda.php" class="max-w-md mx-auto">
                        <div class="relative">
                            <input type="text" 
                                   name="pencarian" 
                                   class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-merah-utama transition-all text-lg" 
                                   placeholder="Cari artikel...">
                            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 gradient-bg text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Saran Artikel -->
            <div class="mt-12 bg-blue-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-blue-900 mb-4">💡 Mungkin Anda tertarik dengan:</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-blue-800">
                    <a href="/beranda.php" class="flex items-center p-4 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        <span>Artikel Terbaru</span>
                    </a>
                    <a href="/beranda.php?pencarian=teknologi" class="flex items-center p-4 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                        <span>Kategori Teknologi</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-abu-900 text-white py-12 mt-16">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 Bloggua. Semua hak dilindungi undang-undang.</p>
            <p class="text-gray-400 mt-2">Blog modern dengan nuansa merah putih yang elegan</p>
        </div>
    </footer>

    <script>
        // Auto focus pada search input
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="pencarian"]');
            if (searchInput) {
                searchInput.focus();
            }
        });
    </script>
</body>
</html>