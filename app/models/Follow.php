<?php
/**
 * Follow Model
 */

class Follow {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    /**
     * Follow a user
     */
    public function follow($followerId, $followingId) {
        // Prevent self-follow
        if ($followerId == $followingId) {
            return false;
        }
        
        // Check if already following
        if ($this->isFollowing($followerId, $followingId)) {
            return false;
        }
        
        $stmt = $this->db->prepare("
            INSERT INTO follows (follower_id, following_id) 
            VALUES (?, ?)
        ");
        
        return $stmt->execute([$followerId, $followingId]);
    }
    
    /**
     * Unfollow a user
     */
    public function unfollow($followerId, $followingId) {
        $stmt = $this->db->prepare("
            DELETE FROM follows 
            WHERE follower_id = ? AND following_id = ?
        ");
        
        return $stmt->execute([$followerId, $followingId]);
    }
    
    /**
     * Toggle follow status
     */
    public function toggleFollow($followerId, $followingId) {
        if ($this->isFollowing($followerId, $followingId)) {
            return $this->unfollow($followerId, $followingId);
        } else {
            return $this->follow($followerId, $followingId);
        }
    }
    
    /**
     * Check if user is following another user
     */
    public function isFollowing($followerId, $followingId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM follows 
            WHERE follower_id = ? AND following_id = ?
        ");
        $stmt->execute([$followerId, $followingId]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Get followers count for a user
     */
    public function getFollowersCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM follows 
            WHERE following_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return intval($result['count']);
    }
    
    /**
     * Get following count for a user
     */
    public function getFollowingCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM follows 
            WHERE follower_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return intval($result['count']);
    }
    
    /**
     * Get list of users that a user is following
     */
    public function getFollowing($userId, $limit = 100) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.username, u.avatar_url, u.bio
            FROM follows f
            JOIN users u ON f.following_id = u.id
            WHERE f.follower_id = ?
            ORDER BY f.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get list of users following a user
     */
    public function getFollowers($userId, $limit = 100) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.username, u.avatar_url, u.bio
            FROM follows f
            JOIN users u ON f.follower_id = u.id
            WHERE f.following_id = ?
            ORDER BY f.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
}


