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
}



