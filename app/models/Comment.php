<?php
/**
 * Comment Model
 */

class Comment {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function create($postId, $userId, $contentText) {
        $stmt = $this->db->prepare("
            INSERT INTO comments (post_id, user_id, content_text) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$postId, $userId, $contentText]);
    }
    
    public function findByPostId($postId) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username, u.avatar_url
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}



