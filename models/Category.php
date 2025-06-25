<?php
// Database connection handled by calling file

class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllCategories() {
        $sql = "SELECT c.*, COUNT(p.id) as post_count 
                FROM categories c 
                LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
                GROUP BY c.id 
                ORDER BY c.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function createCategory($name, $description = '') {
        $sql = "INSERT INTO categories (name, description, created_at) VALUES (:name, :description, NOW())";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);
    }
    
    public function updateCategory($id, $name, $description = '') {
        $sql = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':description' => $description
        ]);
    }
    
    public function deleteCategory($id) {
        // First check if category has posts
        $sql = "SELECT COUNT(*) FROM posts WHERE category_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Cannot delete category with posts
        }
        
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function categoryExists($name, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM categories WHERE name = :name";
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        
        if ($excludeId) {
            $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>