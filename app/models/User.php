<?php
/**
 * User Model
 */

class User {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function create($email, $password, $username, $bio = null, $avatarUrl = null) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            INSERT INTO users (email, password, username, bio, avatar_url) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([$email, $hashedPassword, $username, $bio, $avatarUrl]);
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'password') {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }
    
    public function getAll($limit = 100, $offset = 0) {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    public function searchByUsername($query, $limit = 20, $excludeUserId = null) {
        $searchTerm = '%' . $query . '%';
        
        if ($excludeUserId) {
            $stmt = $this->db->prepare("
                SELECT * FROM users 
                WHERE username LIKE ? AND id != ?
                ORDER BY username ASC 
                LIMIT ?
            ");
            $stmt->execute([$searchTerm, $excludeUserId, $limit]);
        } else {
            $stmt = $this->db->prepare("
                SELECT * FROM users 
                WHERE username LIKE ?
                ORDER BY username ASC 
                LIMIT ?
            ");
            $stmt->execute([$searchTerm, $limit]);
        }
        
        return $stmt->fetchAll();
    }
}



