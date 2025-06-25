<?php
// Demo halaman untuk memperlihatkan desain Bloggua tanpa database
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Bloggua - Platform Blog Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #dc2626, #b91c1c); }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        
        /* Hero Slider Styles */
        .slide {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .slide.active { display: block; }
        .slide-content { height: 100%; display: flex; align-items: center; justify-content: center; }
        .slide-title { animation: slideInFromBottom 0.8s ease-out; }
        .slide-description { animation: slideInFromBottom 0.8s ease-out 0.2s both; }
        .slide-indicator.active { background-color: white !important; }
        
        @keyframes slideInFromBottom {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header Modern -->
    <header class="gradient-bg shadow-2xl sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <nav class="flex items-center justify-between">
                <a href="demo.php" class="text-3xl font-bold text-white hover:text-red-100 transition-colors">
                    Bloggua
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="demo.php" class="text-white hover:text-red-100 transition-colors font-medium">Beranda</a>
                    <span class="bg-white text-red-600 px-6 py-2 rounded-full font-semibold">
                        Demo Mode
                    </span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Slider Section -->
    <section class="relative overflow-hidden">
        <div id="hero-slider" class="relative h-96 md:h-[500px]">
            <div class="slide active" 
                 style="background-image: linear-gradient(rgba(220, 38, 38, 0.7), rgba(185, 28, 28, 0.7)), url('https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
                <div class="slide-content">
                    <div class="container mx-auto px-4 h-full flex items-center">
                        <div class="max-w-4xl mx-auto text-center text-white">
                            <h1 class="text-4xl md:text-5xl font-bold mb-6 slide-title">
                                Selamat Datang di Bloggua
                            </h1>
                            <p class="text-lg md:text-xl mb-8 opacity-90 slide-description">
                                Platform blog modern untuk berbagi cerita dan pengalaman terbaik Anda
                            </p>
                            <div class="flex justify-center space-x-4">
                                <button class="bg-white text-red-600 px-6 md:px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                                    Setup Database
                                </button>
                                <button class="border-2 border-white text-white px-6 md:px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition duration-300">
                                    Lihat Panduan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Database Setup Info -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Setup Database MySQL</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl">
                        <div class="text-4xl mb-4">🔧</div>
                        <h3 class="text-xl font-semibold mb-3">Install XAMPP</h3>
                        <p class="text-gray-700">Download dan install XAMPP untuk menjalankan Apache dan MySQL server</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl">
                        <div class="text-4xl mb-4">🗄️</div>
                        <h3 class="text-xl font-semibold mb-3">Buat Database</h3>
                        <p class="text-gray-700">Buat database 'db_blog' dan import file schema.sql via phpMyAdmin</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl">
                        <div class="text-4xl mb-4">🚀</div>
                        <h3 class="text-xl font-semibold mb-3">Jalankan Blog</h3>
                        <p class="text-gray-700">Akses http://localhost/bloggua/beranda.php untuk menggunakan blog</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Features -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Fitur Bloggua</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover-lift">
                        <div class="h-48 bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                            <span class="text-white text-4xl">📝</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-3">Panel Admin Modern</h3>
                            <p class="text-gray-600 mb-4">Dashboard lengkap dengan manajemen post, kategori, komentar, dan slider</p>
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Admin Panel</span>
                        </div>
                    </article>

                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover-lift">
                        <div class="h-48 bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                            <span class="text-white text-4xl">🖼️</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-3">Sistem Slider</h3>
                            <p class="text-gray-600 mb-4">Kelola gambar slider beranda dengan mudah dari admin panel</p>
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Slider</span>
                        </div>
                    </article>

                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover-lift">
                        <div class="h-48 bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                            <span class="text-white text-4xl">📱</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-3">Desain Responsif</h3>
                            <p class="text-gray-600 mb-4">Tampilan modern dengan Tailwind CSS yang responsive di semua device</p>
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Responsive</span>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="gradient-bg text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <h3 class="text-2xl font-bold mb-4">Bloggua</h3>
            <p class="text-red-100 mb-6">Platform blog modern dengan sistem manajemen yang lengkap</p>
            <div class="flex justify-center space-x-6">
                <span class="text-red-200">📖 Blog System</span>
                <span class="text-red-200">🔐 Admin Panel</span>
                <span class="text-red-200">🎨 Modern Design</span>
            </div>
        </div>
    </footer>

    <script>
        // Demo notification
        document.addEventListener('DOMContentLoaded', function() {
            // Show demo notification
            setTimeout(() => {
                const notification = document.createElement('div');
                notification.innerHTML = `
                    <div style="position: fixed; top: 20px; right: 20px; background: #dc2626; color: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); z-index: 1000; max-width: 300px;">
                        <div style="font-weight: bold; margin-bottom: 5px;">🚀 Setup Database</div>
                        <div style="font-size: 14px;">Install XAMPP dan setup database 'db_blog' untuk menggunakan Bloggua</div>
                        <button onclick="this.parentElement.remove()" style="position: absolute; top: 5px; right: 10px; background: none; border: none; color: white; font-size: 18px; cursor: pointer;">×</button>
                    </div>
                `;
                document.body.appendChild(notification);
                
                // Auto remove after 10 seconds
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 10000);
            }, 2000);
        });
    </script>
</body>
</html>