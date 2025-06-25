<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - Bloggua</title>
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
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <div style="font-size: 6rem; color: var(--primary-red); font-weight: bold; margin-bottom: 1rem;">404</div>
            <h1 style="font-size: 2rem; color: var(--gray-900); margin-bottom: 1rem;">Halaman Tidak Ditemukan</h1>
            <p style="color: var(--gray-600); font-size: 1.1rem; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan atau dihapus.
            </p>
            
            <div class="btn-group" style="justify-content: center;">
                <a href="/" class="btn btn-primary">← Kembali ke Beranda</a>
                <a href="javascript:history.back()" class="btn" style="background: var(--gray-600); color: white;">Halaman Sebelumnya</a>
            </div>
            
            <!-- Search Box -->
            <div style="margin-top: 3rem;">
                <h3 style="margin-bottom: 1rem; color: var(--gray-800);">Atau cari artikel yang Anda inginkan:</h3>
                <form method="GET" action="/" class="search-box" style="max-width: 400px; margin: 0 auto;">
                    <input type="text" name="search" class="search-input" placeholder="Cari artikel...">
                    <button type="submit" class="search-btn">Cari</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer style="background: var(--gray-900); color: white; text-align: center; padding: 2rem; margin-top: 4rem;">
        <p>&copy; 2025 Bloggua. Semua hak dilindungi undang-undang.</p>
        <p>Blog sederhana dengan nuansa merah putih yang elegan</p>
    </footer>
</body>
</html>