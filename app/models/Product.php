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
        $products = $stmt->fetchAll();
        
        // Add image count to each product
        foreach ($products as &$product) {
            $product['image_count'] = count($this->getImages($product['id']));
        }
        
        return $products;
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getImages($productId) {
        try {
            $stmt = $this->db->prepare("
                SELECT image_url 
                FROM product_images 
                WHERE product_id = ? 
                ORDER BY display_order ASC, id ASC
            ");
            $stmt->execute([$productId]);
            $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $images;
        } catch (Exception $e) {
            // Table might not exist yet, return empty array
            return [];
        }
    }
    
    public function addImage($productId, $imageUrl, $displayOrder = 0) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO product_images (product_id, image_url, display_order) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$productId, $imageUrl, $displayOrder]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function deleteImage($imageId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM product_images WHERE id = ?");
            return $stmt->execute([$imageId]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function deleteAllImages($productId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM product_images WHERE product_id = ?");
            return $stmt->execute([$productId]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO products 
            (name, description, category, price, image_url, stock, related_plant_catalog_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['category'],
            $data['price'],
            $data['image_url'] ?? null,
            $data['stock'] ?? 0,
            $data['related_plant_catalog_id'] ?? null
        ]);
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        $allowedFields = ['name', 'description', 'category', 'price', 'image_url', 
                         'stock', 'related_plant_catalog_id'];
        
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



