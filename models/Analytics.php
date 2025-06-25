<?php
// Model untuk mengelola data analytics dan statistik blog
// Menyediakan fungsi untuk tracking views, statistik, dan data grafik

class Analytics {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Catat view artikel
    public function recordPostView($postId, $ipAddress, $userAgent) {
        try {
            // Cek apakah IP sudah view artikel ini dalam 24 jam terakhir (untuk menghindari spam)
            $sql = "SELECT COUNT(*) FROM post_views 
                    WHERE post_id = :post_id AND ip_address = :ip_address 
                    AND viewed_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
            $stmt->bindValue(':ip_address', $ipAddress, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->fetchColumn() == 0) {
                // Record view baru
                $sql = "INSERT INTO post_views (post_id, ip_address, user_agent) 
                        VALUES (:post_id, :ip_address, :user_agent)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
                $stmt->bindValue(':ip_address', $ipAddress, PDO::PARAM_STR);
                $stmt->bindValue(':user_agent', $userAgent, PDO::PARAM_STR);
                $stmt->execute();
                
                // Update daily stats
                $this->updateDailyStats();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Update statistik harian
    public function updateDailyStats() {
        try {
            $today = date('Y-m-d');
            
            // Hitung statistik hari ini
            $totalViews = $this->db->query("SELECT COUNT(*) FROM post_views WHERE DATE(viewed_at) = '$today'")->fetchColumn();
            $uniqueVisitors = $this->db->query("SELECT COUNT(DISTINCT ip_address) FROM post_views WHERE DATE(viewed_at) = '$today'")->fetchColumn();
            $newPosts = $this->db->query("SELECT COUNT(*) FROM posts WHERE DATE(created_at) = '$today'")->fetchColumn();
            $newComments = $this->db->query("SELECT COUNT(*) FROM comments WHERE DATE(created_at) = '$today'")->fetchColumn();
            
            // Insert atau update daily stats
            $sql = "INSERT INTO daily_stats (stat_date, total_views, unique_visitors, new_posts, new_comments) 
                    VALUES (:stat_date, :total_views, :unique_visitors, :new_posts, :new_comments)
                    ON DUPLICATE KEY UPDATE 
                    total_views = :total_views,
                    unique_visitors = :unique_visitors,
                    new_posts = :new_posts,
                    new_comments = :new_comments,
                    updated_at = CURRENT_TIMESTAMP";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':stat_date', $today, PDO::PARAM_STR);
            $stmt->bindValue(':total_views', $totalViews, PDO::PARAM_INT);
            $stmt->bindValue(':unique_visitors', $uniqueVisitors, PDO::PARAM_INT);
            $stmt->bindValue(':new_posts', $newPosts, PDO::PARAM_INT);
            $stmt->bindValue(':new_comments', $newComments, PDO::PARAM_INT);
            $stmt->execute();
            
        } catch (PDOException $e) {
            // Log error jika perlu
        }
    }
    
    // Ambil statistik overview untuk dashboard
    public function getOverviewStats() {
        try {
            $stats = [];
            
            // Total views seluruh waktu
            $stats['total_views'] = $this->db->query("SELECT COUNT(*) FROM post_views")->fetchColumn();
            
            // Views hari ini
            $stats['views_today'] = $this->db->query("SELECT COUNT(*) FROM post_views WHERE DATE(viewed_at) = CURDATE()")->fetchColumn();
            
            // Views minggu ini
            $stats['views_week'] = $this->db->query("SELECT COUNT(*) FROM post_views WHERE viewed_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn();
            
            // Views bulan ini
            $stats['views_month'] = $this->db->query("SELECT COUNT(*) FROM post_views WHERE MONTH(viewed_at) = MONTH(CURDATE()) AND YEAR(viewed_at) = YEAR(CURDATE())")->fetchColumn();
            
            // Unique visitors bulan ini
            $stats['unique_visitors_month'] = $this->db->query("SELECT COUNT(DISTINCT ip_address) FROM post_views WHERE MONTH(viewed_at) = MONTH(CURDATE()) AND YEAR(viewed_at) = YEAR(CURDATE())")->fetchColumn();
            
            // Total posts published
            $stats['total_posts'] = $this->db->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
            
            // Posts bulan ini
            $stats['posts_month'] = $this->db->query("SELECT COUNT(*) FROM posts WHERE status = 'published' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn();
            
            // Total komentar approved
            $stats['total_comments'] = $this->db->query("SELECT COUNT(*) FROM comments WHERE status = 'approved'")->fetchColumn();
            
            // Komentar bulan ini
            $stats['comments_month'] = $this->db->query("SELECT COUNT(*) FROM comments WHERE status = 'approved' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            return [
                'total_views' => 0,
                'views_today' => 0,
                'views_week' => 0,
                'views_month' => 0,
                'unique_visitors_month' => 0,
                'total_posts' => 0,
                'posts_month' => 0,
                'total_comments' => 0,
                'comments_month' => 0
            ];
        }
    }
    
    // Data untuk grafik views harian (30 hari terakhir)
    public function getDailyViewsChart($days = 30) {
        try {
            $sql = "SELECT 
                        DATE(viewed_at) as date,
                        COUNT(*) as views,
                        COUNT(DISTINCT ip_address) as unique_visitors
                    FROM post_views 
                    WHERE viewed_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                    GROUP BY DATE(viewed_at)
                    ORDER BY DATE(viewed_at)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Pastikan semua tanggal ada (isi yang kosong dengan 0)
            $result = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $found = false;
                
                foreach ($data as $row) {
                    if ($row['date'] === $date) {
                        $result[] = [
                            'date' => $date,
                            'views' => (int)$row['views'],
                            'unique_visitors' => (int)$row['unique_visitors']
                        ];
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $result[] = [
                        'date' => $date,
                        'views' => 0,
                        'unique_visitors' => 0
                    ];
                }
            }
            
            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Data untuk grafik post populer
    public function getPopularPostsChart($limit = 10) {
        try {
            $sql = "SELECT 
                        p.id,
                        p.title,
                        p.slug,
                        COUNT(pv.id) as view_count
                    FROM posts p
                    LEFT JOIN post_views pv ON p.id = pv.post_id
                    WHERE p.status = 'published'
                    GROUP BY p.id, p.title, p.slug
                    ORDER BY view_count DESC
                    LIMIT :limit";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Data untuk grafik distribusi kategori
    public function getCategoryDistributionChart() {
        try {
            $sql = "SELECT 
                        c.name as category_name,
                        COUNT(p.id) as post_count,
                        COALESCE(SUM(view_stats.view_count), 0) as total_views
                    FROM categories c
                    LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
                    LEFT JOIN (
                        SELECT post_id, COUNT(*) as view_count
                        FROM post_views
                        GROUP BY post_id
                    ) view_stats ON p.id = view_stats.post_id
                    GROUP BY c.id, c.name
                    HAVING post_count > 0
                    ORDER BY post_count DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Data untuk grafik trend bulanan
    public function getMonthlyTrendChart($months = 12) {
        try {
            $sql = "SELECT 
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        COUNT(*) as posts_count
                    FROM posts 
                    WHERE status = 'published' 
                    AND created_at >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY month";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':months', $months, PDO::PARAM_INT);
            $stmt->execute();
            
            $postsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Ambil data komentar per bulan
            $sql = "SELECT 
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        COUNT(*) as comments_count
                    FROM comments 
                    WHERE status = 'approved' 
                    AND created_at >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY month";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':months', $months, PDO::PARAM_INT);
            $stmt->execute();
            
            $commentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Gabungkan data posts dan comments
            $result = [];
            for ($i = $months - 1; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthName = date('M Y', strtotime("-$i months"));
                
                $posts = 0;
                $comments = 0;
                
                foreach ($postsData as $row) {
                    if ($row['month'] === $month) {
                        $posts = (int)$row['posts_count'];
                        break;
                    }
                }
                
                foreach ($commentsData as $row) {
                    if ($row['month'] === $month) {
                        $comments = (int)$row['comments_count'];
                        break;
                    }
                }
                
                $result[] = [
                    'month' => $month,
                    'month_name' => $monthName,
                    'posts' => $posts,
                    'comments' => $comments
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // Ambil post views untuk artikel tertentu
    public function getPostViews($postId) {
        try {
            $sql = "SELECT COUNT(*) FROM post_views WHERE post_id = :post_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
            $stmt->execute();
            
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    // Statistik per jam dalam sehari (untuk real-time dashboard)
    public function getHourlyStats($date = null) {
        if (!$date) $date = date('Y-m-d');
        
        try {
            $sql = "SELECT 
                        HOUR(viewed_at) as hour,
                        COUNT(*) as views,
                        COUNT(DISTINCT ip_address) as unique_visitors
                    FROM post_views 
                    WHERE DATE(viewed_at) = :date
                    GROUP BY HOUR(viewed_at)
                    ORDER BY hour";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Isi semua jam (0-23) dengan data
            $result = [];
            for ($hour = 0; $hour < 24; $hour++) {
                $found = false;
                foreach ($data as $row) {
                    if ((int)$row['hour'] === $hour) {
                        $result[] = [
                            'hour' => $hour,
                            'hour_display' => sprintf('%02d:00', $hour),
                            'views' => (int)$row['views'],
                            'unique_visitors' => (int)$row['unique_visitors']
                        ];
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $result[] = [
                        'hour' => $hour,
                        'hour_display' => sprintf('%02d:00', $hour),
                        'views' => 0,
                        'unique_visitors' => 0
                    ];
                }
            }
            
            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>