<?php
/**
 * Product Model (Marketplace)
 */

class Product {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function getAll($filters = [], $limit = 50, $offset = 0) {
        $where = [];
        $params = [];
        
        if (!empty($filters['category'])) {
            $where[] = "category = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(name LIKE ? OR description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $sql = "SELECT * FROM products $whereClause ORDER BY name LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO products 
            (name, description, category, price, image_url, stock, is_seed, related_plant_catalog_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['category'],
            $data['price'],
            $data['image_url'] ?? null,
            $data['stock'] ?? 0,
            $data['is_seed'] ?? false,
            $data['related_plant_catalog_id'] ?? null
        ]);
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        $allowedFields = ['name', 'description', 'category', 'price', 'image_url', 
                         'stock', 'is_seed', 'related_plant_catalog_id'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $values[] = $id;
        $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function updateStock($id, $quantity) {
        $stmt = $this->db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        return $stmt->execute([$quantity, $id]);
    }
}




