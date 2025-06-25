<?php
// API endpoint untuk mengambil data analytics secara real-time
// Mendukung AJAX untuk refresh data tanpa reload halaman

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../config/session.php';
requireLogin();

require_once '../../config/database_auto.php';
require_once '../../models/Analytics.php';

$analytics = new Analytics();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'overview':
        $data = $analytics->getOverviewStats();
        break;
        
    case 'daily_views':
        $days = (int)($_GET['days'] ?? 30);
        $data = $analytics->getDailyViewsChart($days);
        break;
        
    case 'popular_posts':
        $limit = (int)($_GET['limit'] ?? 10);
        $data = $analytics->getPopularPostsChart($limit);
        break;
        
    case 'category_distribution':
        $data = $analytics->getCategoryDistributionChart();
        break;
        
    case 'monthly_trend':
        $months = (int)($_GET['months'] ?? 12);
        $data = $analytics->getMonthlyTrendChart($months);
        break;
        
    case 'hourly_stats':
        $date = $_GET['date'] ?? null;
        $data = $analytics->getHourlyStats($date);
        break;
        
    default:
        $data = ['error' => 'Invalid action'];
        http_response_code(400);
}

echo json_encode($data, JSON_PRETTY_PRINT);
?>