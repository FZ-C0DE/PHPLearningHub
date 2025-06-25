<?php
// Widget mini statistik untuk dashboard utama
// Menampilkan ringkasan analytics dalam bentuk kompak

require_once '../config/database_auto.php';
require_once '../models/Analytics.php';

$analytics = new Analytics();
$stats = $analytics->getOverviewStats();
?>

<!-- Widget Statistik Mini -->
<div class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg">
    <h3 class="text-lg font-bold mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        Analytics Ringkas
    </h3>
    
    <div class="grid grid-cols-2 gap-4">
        <div class="text-center">
            <div class="text-2xl font-bold"><?php echo number_format($stats['total_views']); ?></div>
            <div class="text-red-100 text-sm">Total Views</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold"><?php echo number_format($stats['views_today']); ?></div>
            <div class="text-red-100 text-sm">Hari Ini</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold"><?php echo number_format($stats['views_month']); ?></div>
            <div class="text-red-100 text-sm">Bulan Ini</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold"><?php echo number_format($stats['unique_visitors_month']); ?></div>
            <div class="text-red-100 text-sm">Visitors</div>
        </div>
    </div>
    
    <div class="mt-4 pt-4 border-t border-red-400">
        <a href="statistik.php" class="flex items-center justify-center text-red-100 hover:text-white transition-colors">
            <span class="mr-2">Lihat Detail Analytics</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>