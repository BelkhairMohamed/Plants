<?php
/**
 * Post Model (Social Feed)
 */

class Post {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function create($userId, $contentText, $imageUrl = null, $relatedUserPlantId = null, $postType = 'normal') {
        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, content_text, image_url, related_user_plant_id, post_type) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $contentText, $imageUrl, $relatedUserPlantId, $postType]);
    }
    
    public function getAll($limit = 20, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.avatar_url,
                   COUNT(DISTINCT pl.id) as like_count,
                   COUNT(DISTINCT c.id) as comment_count
            FROM posts p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN post_likes pl ON p.id = pl.post_id
            LEFT JOIN comments c ON p.id = c.post_id
            GROUP BY p.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.avatar_url, u.bio,
                   up.nickname_for_plant, pc.common_name
            FROM posts p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN user_plants up ON p.related_user_plant_id = up.id
            LEFT JOIN plant_catalog pc ON up.plant_catalog_id = pc.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function findByUserId($userId, $limit = 20) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   COUNT(DISTINCT pl.id) as like_count,
                   COUNT(DISTINCT c.id) as comment_count
            FROM posts p
            LEFT JOIN post_likes pl ON p.id = pl.post_id
            LEFT JOIN comments c ON p.id = c.post_id
            WHERE p.user_id = ?
            GROUP BY p.id
            ORDER BY p.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function toggleLike($postId, $userId) {
        // Check if already liked
        $stmt = $this->db->prepare("SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Unlike
            $stmt = $this->db->prepare("DELETE FROM post_likes WHERE post_id = ? AND user_id = ?");
            $stmt->execute([$postId, $userId]);
            return false;
        } else {
            // Like
            $stmt = $this->db->prepare("INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)");
            $stmt->execute([$postId, $userId]);
            return true;
        }
    }
    
    public function isLikedByUser($postId, $userId) {
        $stmt = $this->db->prepare("SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        return $stmt->fetch() !== false;
    }
    
    public function getLikeCount($postId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}



