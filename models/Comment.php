<?php
// Database connection handled by calling file

class Comment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getCommentsByPostId($postId) {
        $sql = "SELECT * FROM comments 
                WHERE post_id = :post_id AND status = 'approved' 
                ORDER BY created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function createComment($postId, $author, $email, $content) {
        $sql = "INSERT INTO comments (post_id, author_name, author_email, content, status, created_at) 
                VALUES (:post_id, :author, :email, :content, 'pending', NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':post_id' => $postId,
            ':author' => $author,
            ':email' => $email,
            ':content' => $content
        ]);
    }
    
    // Admin methods
    public function getAllComments($limit = 20, $offset = 0) {
        $sql = "SELECT c.*, p.title as post_title 
                FROM comments c 
                LEFT JOIN posts p ON c.post_id = p.id 
                ORDER BY c.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getCommentById($id) {
        $sql = "SELECT c.*, p.title as post_title 
                FROM comments c 
                LEFT JOIN posts p ON c.post_id = p.id 
                WHERE c.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function updateCommentStatus($id, $status) {
        $sql = "UPDATE comments SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }
    
    public function deleteComment($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getPendingCommentsCount() {
        $sql = "SELECT COUNT(*) FROM comments WHERE status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    public function getTotalComments() {
        $sql = "SELECT COUNT(*) FROM comments";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
}
?>