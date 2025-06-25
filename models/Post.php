<?php
// Database connection handled by calling file

class Post {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllPosts($limit = 10, $offset = 0, $search = '') {
        $sql = "SELECT p.*, c.name as category_name, 
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id AND status = 'approved') as comment_count
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'published'";
        
        if (!empty($search)) {
            $sql .= " AND (p.title LIKE :search OR p.content LIKE :search)";
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        if (!empty($search)) {
            $searchTerm = "%{$search}%";
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getPostById($id) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = :id AND p.status = 'published'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function getPostBySlug($slug) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.slug = :slug AND p.status = 'published'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function getTotalPosts($search = '') {
        $sql = "SELECT COUNT(*) FROM posts WHERE status = 'published'";
        
        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR content LIKE :search)";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($search)) {
            $searchTerm = "%{$search}%";
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    // Admin methods
    public function getAllPostsAdmin($limit = 10, $offset = 0) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function createPost($data) {
        $sql = "INSERT INTO posts (title, slug, content, excerpt, category_id, thumbnail, status, created_at, updated_at) 
                VALUES (:title, :slug, :content, :excerpt, :category_id, :thumbnail, :status, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':content' => $data['content'],
            ':excerpt' => $data['excerpt'],
            ':category_id' => $data['category_id'],
            ':thumbnail' => $data['thumbnail'],
            ':status' => $data['status']
        ]);
    }
    
    public function updatePost($id, $data) {
        $sql = "UPDATE posts SET 
                title = :title, 
                slug = :slug, 
                content = :content, 
                excerpt = :excerpt, 
                category_id = :category_id, 
                thumbnail = :thumbnail, 
                status = :status, 
                updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':content' => $data['content'],
            ':excerpt' => $data['excerpt'],
            ':category_id' => $data['category_id'],
            ':thumbnail' => $data['thumbnail'],
            ':status' => $data['status']
        ]);
    }
    
    public function deletePost($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    private function slugExists($slug) {
        $sql = "SELECT COUNT(*) FROM posts WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }
}
?>