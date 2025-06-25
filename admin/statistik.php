<?php
// Halaman dashboard statistik dengan grafik interaktif
// Menampilkan analytics views, post populer, trend, dan kategori

require_once '../config/session.php';
requireLogin();

require_once '../config/database_auto.php';
require_once '../models/Analytics.php';
require_once '../includes/functions.php';

$analytics = new Analytics();

// Ambil data statistik untuk dashboard
$overviewStats = $analytics->getOverviewStats();
$dailyViews = $analytics->getDailyViewsChart(30);
$popularPosts = $analytics->getPopularPostsChart(10);
$categoryDistribution = $analytics->getCategoryDistributionChart();
$monthlyTrend = $analytics->getMonthlyTrendChart(12);
$hourlyStats = $analytics->getHourlyStats();

// Format data untuk JavaScript
$dailyViewsJson = json_encode($dailyViews);
$popularPostsJson = json_encode($popularPosts);
$categoryDistributionJson = json_encode($categoryDistribution);
$monthlyTrendJson = json_encode($monthlyTrend);
$hourlyStatsJson = json_encode($hourlyStats);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik & Analytics - Bloggua Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'merah-utama': '#dc2626',
                        'merah-gelap': '#b91c1c',
                        'merah-muda': '#fef2f2',
                        'hijau': '#16a34a',
                        'kuning': '#eab308',
                        'biru': '#2563eb',
                        'ungu': '#7c3aed',
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
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
        .chart-container { position: relative; height: 400px; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: scale(1.02); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include 'includes/sidebar-modern.php'; ?>
        
        <!-- Konten Utama -->
        <main class="flex-1 ml-64 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-abu-900">Statistik & Analytics</h1>
                        <p class="text-abu-600 mt-1">Dashboard analitik website dengan grafik interaktif</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-hijau text-white px-4 py-2 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-300 rounded-full mr-2 animate-pulse"></div>
                                <span class="text-sm font-medium">Live Data</span>
                            </div>
                        </div>
                        <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-abu-800 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Konten Dashboard -->
            <div class="p-8 overflow-y-auto">
                <!-- Kartu Statistik Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Views -->
                    <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-t-4 border-merah-utama">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-abu-600 text-sm font-medium">Total Views</p>
                                <p class="text-3xl font-bold text-abu-900 mt-1"><?php echo number_format($overviewStats['total_views']); ?></p>
                            </div>
                            <div class="bg-merah-muda p-3 rounded-xl">
                                <svg class="w-8 h-8 text-merah-utama" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-hijau font-medium">+<?php echo $overviewStats['views_today']; ?></span>
                            <span class="text-abu-600 ml-2">hari ini</span>
                        </div>
                    </div>
                    
                    <!-- Views Bulan Ini -->
                    <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-t-4 border-biru">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-abu-600 text-sm font-medium">Views Bulan Ini</p>
                                <p class="text-3xl font-bold text-abu-900 mt-1"><?php echo number_format($overviewStats['views_month']); ?></p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-biru" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-biru font-medium"><?php echo number_format($overviewStats['unique_visitors_month']); ?></span>
                            <span class="text-abu-600 ml-2">unique visitors</span>
                        </div>
                    </div>
                    
                    <!-- Total Posts -->
                    <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-t-4 border-hijau">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-abu-600 text-sm font-medium">Total Posts</p>
                                <p class="text-3xl font-bold text-abu-900 mt-1"><?php echo number_format($overviewStats['total_posts']); ?></p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-hijau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-hijau font-medium">+<?php echo $overviewStats['posts_month']; ?></span>
                            <span class="text-abu-600 ml-2">bulan ini</span>
                        </div>
                    </div>
                    
                    <!-- Total Komentar -->
                    <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-t-4 border-ungu">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-abu-600 text-sm font-medium">Total Komentar</p>
                                <p class="text-3xl font-bold text-abu-900 mt-1"><?php echo number_format($overviewStats['total_comments']); ?></p>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-xl">
                                <svg class="w-8 h-8 text-ungu" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-ungu font-medium">+<?php echo $overviewStats['comments_month']; ?></span>
                            <span class="text-abu-600 ml-2">bulan ini</span>
                        </div>
                    </div>
                </div>
                
                <!-- Grid Grafik -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Grafik Views Harian -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                        <h3 class="text-xl font-bold text-abu-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-merah-utama mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Views Harian (30 Hari)
                        </h3>
                        <div class="chart-container">
                            <canvas id="dailyViewsChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Grafik Post Populer -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                        <h3 class="text-xl font-bold text-abu-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-hijau mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Post Populer
                        </h3>
                        <div class="chart-container">
                            <canvas id="popularPostsChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Grid Grafik Bawah -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Distribusi Kategori -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                        <h3 class="text-xl font-bold text-abu-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-biru mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                            Distribusi Kategori
                        </h3>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Trend Bulanan -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift">
                        <h3 class="text-xl font-bold text-abu-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-ungu mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                            Trend Posts & Komentar (12 Bulan)
                        </h3>
                        <div class="chart-container">
                            <canvas id="monthlyTrendChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik Views Per Jam Hari Ini -->
                <div class="bg-white rounded-2xl shadow-lg p-6 hover-lift mb-8">
                    <h3 class="text-xl font-bold text-abu-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-kuning mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Activity per Jam Hari Ini
                    </h3>
                    <div class="chart-container">
                        <canvas id="hourlyChart"></canvas>
                    </div>
                </div>
                
                <!-- Tabel Post Populer Detail -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-abu-900">Detail Post Populer</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase">Rank</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase">Judul Post</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase">Views</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-abu-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($popularPosts as $index => $post): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <span class="<?php echo $index < 3 ? 'bg-kuning text-white' : 'bg-gray-200 text-abu-800'; ?> w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">
                                                    <?php echo $index + 1; ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-abu-900">
                                                <?php echo htmlspecialchars($post['title']); ?>
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-2xl font-bold text-merah-utama">
                                                <?php echo number_format($post['view_count']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <a href="edit-post.php?id=<?php echo $post['id']; ?>" 
                                                   class="text-kuning hover:text-yellow-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <a href="../artikel.php?slug=<?php echo urlencode($post['slug']); ?>" target="_blank"
                                                   class="text-hijau hover:text-green-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($popularPosts)): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-abu-600">
                                            Belum ada data views untuk ditampilkan
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Data untuk grafik
        const dailyViewsData = <?php echo $dailyViewsJson; ?>;
        const popularPostsData = <?php echo $popularPostsJson; ?>;
        const categoryData = <?php echo $categoryDistributionJson; ?>;
        const monthlyTrendData = <?php echo $monthlyTrendJson; ?>;
        const hourlyData = <?php echo $hourlyStatsJson; ?>;
        
        // Konfigurasi warna
        const colors = {
            primary: '#dc2626',
            secondary: '#2563eb', 
            success: '#16a34a',
            warning: '#eab308',
            purple: '#7c3aed',
            gray: '#6b7280'
        };
        
        // Grafik Views Harian
        const dailyViewsCtx = document.getElementById('dailyViewsChart').getContext('2d');
        new Chart(dailyViewsCtx, {
            type: 'line',
            data: {
                labels: dailyViewsData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Total Views',
                    data: dailyViewsData.map(item => item.views),
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Unique Visitors',
                    data: dailyViewsData.map(item => item.unique_visitors),
                    borderColor: colors.secondary,
                    backgroundColor: colors.secondary + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            color: '#f3f4f6'
                        }
                    }
                }
            }
        });
        
        // Grafik Post Populer
        const popularPostsCtx = document.getElementById('popularPostsChart').getContext('2d');
        new Chart(popularPostsCtx, {
            type: 'bar',
            data: {
                labels: popularPostsData.map(item => {
                    return item.title.length > 20 ? item.title.substring(0, 20) + '...' : item.title;
                }),
                datasets: [{
                    label: 'Views',
                    data: popularPostsData.map(item => item.view_count),
                    backgroundColor: [
                        colors.primary,
                        colors.secondary,
                        colors.success,
                        colors.warning,
                        colors.purple,
                        colors.gray
                    ].slice(0, popularPostsData.length),
                    borderWidth: 0,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Grafik Distribusi Kategori
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.category_name),
                datasets: [{
                    data: categoryData.map(item => item.post_count),
                    backgroundColor: [
                        colors.primary,
                        colors.secondary,
                        colors.success,
                        colors.warning,
                        colors.purple,
                        colors.gray
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                }
            }
        });
        
        // Grafik Trend Bulanan
        const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: monthlyTrendData.map(item => item.month_name),
                datasets: [{
                    label: 'Posts',
                    data: monthlyTrendData.map(item => item.posts),
                    borderColor: colors.success,
                    backgroundColor: colors.success + '20',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4
                }, {
                    label: 'Komentar',
                    data: monthlyTrendData.map(item => item.comments),
                    borderColor: colors.purple,
                    backgroundColor: colors.purple + '20',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            color: '#f3f4f6'
                        }
                    }
                }
            }
        });
        
        // Grafik Activity per Jam
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: hourlyData.map(item => item.hour_display),
                datasets: [{
                    label: 'Views',
                    data: hourlyData.map(item => item.views),
                    backgroundColor: colors.warning + '80',
                    borderColor: colors.warning,
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Auto refresh data setiap 5 menit
        setTimeout(function() {
            location.reload();
        }, 300000); // 5 menit
        
        // Real-time update untuk overview stats
        function updateOverviewStats() {
            fetch('api/analytics-data.php?action=overview')
                .then(response => response.json())
                .then(data => {
                    // Update stats cards jika perlu
                    console.log('Stats updated:', data);
                })
                .catch(error => console.error('Error updating stats:', error));
        }
        
        // Update stats setiap 60 detik
        setInterval(updateOverviewStats, 60000);
    </script>
    
    <script src="../assets/js/admin-modern.js"></script>
</body>
</html>