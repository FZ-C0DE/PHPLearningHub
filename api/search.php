<?php
// API endpoint untuk live search artikel
// Mengembalikan hasil pencarian dalam format JSON

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database_auto.php';
require_once '../includes/functions.php';

$query = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';

if (empty($query) || strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Search dalam title, excerpt, dan content
    $sql = "SELECT 
                p.id, 
                p.title, 
                p.slug, 
                p.excerpt,
                c.name as category_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published' 
            AND (
                p.title LIKE :query1 
                OR p.excerpt LIKE :query2 
                OR p.content LIKE :query3
            )
            ORDER BY 
                CASE 
                    WHEN p.title LIKE :query4 THEN 1
                    WHEN p.excerpt LIKE :query5 THEN 2  
                    ELSE 3
                END,
                p.created_at DESC
            LIMIT 10";
    
    $searchTerm = '%' . $query . '%';
    $titleTerm = $query . '%'; // Prioritas tinggi untuk judul yang dimulai dengan query
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':query1', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':query2', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':query3', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':query4', $titleTerm, PDO::PARAM_STR);
    $stmt->bindValue(':query5', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Generate excerpt jika tidak ada
    foreach ($results as &$result) {
        if (empty($result['excerpt'])) {
            $result['excerpt'] = generateExcerpt($result['content'] ?? '', 120);
        }
        // Hapus content dari response untuk menghemat bandwidth
        unset($result['content']);
    }
    
    echo json_encode($results);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>