<?php
/**
 * Plant Catalog Model
 */

class PlantCatalog {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function getAll($filters = [], $limit = 50, $offset = 0) {
        $where = [];
        $params = [];
        
        if (!empty($filters['difficulty_level'])) {
            $where[] = "difficulty_level = ?";
            $params[] = $filters['difficulty_level'];
        }
        
        if (!empty($filters['light_requirement'])) {
            $where[] = "light_requirement = ?";
            $params[] = $filters['light_requirement'];
        }
        
        if (!empty($filters['water_requirement'])) {
            $where[] = "water_requirement = ?";
            $params[] = $filters['water_requirement'];
        }
        
        if (!empty($filters['recommended_for_beginners'])) {
            $where[] = "recommended_for_beginners = ?";
            $params[] = $filters['recommended_for_beginners'] ? 1 : 0;
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(common_name LIKE ? OR scientific_name LIKE ? OR description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $sql = "SELECT * FROM plant_catalog $whereClause ORDER BY common_name LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM plant_catalog WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $fields = ['common_name', 'scientific_name', 'description', 'difficulty_level', 
                   'light_requirement', 'water_requirement', 'humidity_preference', 
                   'temperature_min', 'temperature_max', 'image_url', 
                   'recommended_for_beginners', 'default_watering_interval_days', 
                   'default_fertilizing_interval_days', 'seed_guide', 'mature_plant_guide'];
        
        $fieldList = [];
        $placeholders = [];
        $values = [];
        
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $fieldList[] = $field;
                $placeholders[] = '?';
                $values[] = $data[$field];
            }
        }
        
        $sql = "INSERT INTO plant_catalog (" . implode(', ', $fieldList) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        $allowedFields = ['common_name', 'scientific_name', 'description', 'difficulty_level', 
                         'light_requirement', 'water_requirement', 'humidity_preference', 
                         'temperature_min', 'temperature_max', 'image_url', 
                         'recommended_for_beginners', 'default_watering_interval_days', 
                         'default_fertilizing_interval_days', 'seed_guide', 'mature_plant_guide'];
        
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
        $sql = "UPDATE plant_catalog SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM plant_catalog WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function count($filters = []) {
        $where = [];
        $params = [];
        
        if (!empty($filters['difficulty_level'])) {
            $where[] = "difficulty_level = ?";
            $params[] = $filters['difficulty_level'];
        }
        
        if (!empty($filters['light_requirement'])) {
            $where[] = "light_requirement = ?";
            $params[] = $filters['light_requirement'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = "(common_name LIKE ? OR scientific_name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $sql = "SELECT COUNT(*) as total FROM plant_catalog $whereClause";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    /**
     * Get all images for a plant
     */
    public function getImages($plantId) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, image_url, display_order 
                FROM plant_catalog_images 
                WHERE plant_catalog_id = ? 
                ORDER BY display_order ASC, id ASC
            ");
            $stmt->execute([$plantId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            // Table might not exist yet, return empty array
            return [];
        }
    }
    
    /**
     * Add an image to a plant
     */
    public function addImage($plantId, $imageUrl, $displayOrder = 0) {
        try {
            // Check if table exists first
            $checkTable = $this->db->query("SHOW TABLES LIKE 'plant_catalog_images'");
            if ($checkTable->rowCount() === 0) {
                // Table doesn't exist
                error_log("plant_catalog_images table does not exist. Run migration_add_plant_images_table.sql");
                return false;
            }
            
            $stmt = $this->db->prepare("
                INSERT INTO plant_catalog_images (plant_catalog_id, image_url, display_order) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$plantId, $imageUrl, $displayOrder]);
        } catch (PDOException $e) {
            error_log("Error adding plant image: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete an image
     */
    public function deleteImage($imageId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM plant_catalog_images WHERE id = ?");
            return $stmt->execute([$imageId]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete all images for a plant
     */
    public function deleteAllImages($plantId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM plant_catalog_images WHERE plant_catalog_id = ?");
            return $stmt->execute([$plantId]);
        } catch (Exception $e) {
            return false;
        }
    }
}



