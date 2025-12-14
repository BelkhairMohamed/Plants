<?php
/**
 * Notification Model
 */

class Notification {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function create($userId, $type, $message, $linkUrl = null) {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, message, link_url) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $type, $message, $linkUrl]);
    }
    
    public function findByUserId($userId, $unreadOnly = false, $limit = 50) {
        $where = "user_id = ?";
        $params = [$userId];
        
        if ($unreadOnly) {
            $where .= " AND is_read = 0";
        }
        
        $params[] = $limit;
        
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE $where 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM notifications 
            WHERE user_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
    
    public function markAsRead($id) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function markAllAsRead($userId) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
}



