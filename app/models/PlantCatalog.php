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
        
        if (!empty($filters['humidity_preference'])) {
            $where[] = "humidity_preference = ?";
            $params[] = $filters['humidity_preference'];
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
        
        // Handle category filtering (multiple categories)
        $categoryJoin = "";
        if (!empty($filters['categories']) && is_array($filters['categories'])) {
            $categoryIds = array_filter(array_map('intval', $filters['categories']));
            if (!empty($categoryIds)) {
                $categoryPlaceholders = implode(',', array_fill(0, count($categoryIds), '?'));
                $categoryJoin = "
                    INNER JOIN plant_category_assignments pca ON plant_catalog.id = pca.plant_catalog_id
                    INNER JOIN categories c ON pca.category_id = c.id
                ";
                $where[] = "c.id IN ($categoryPlaceholders)";
                $params = array_merge($params, $categoryIds);
            }
        } elseif (!empty($filters['category_slug'])) {
            // Support for single category by slug (for URL links)
            $categoryJoin = "
                INNER JOIN plant_category_assignments pca ON plant_catalog.id = pca.plant_catalog_id
                INNER JOIN categories c ON pca.category_id = c.id
            ";
            $where[] = "c.slug = ?";
            $params[] = $filters['category_slug'];
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        // Handle sorting
        $sortBy = $filters['sort'] ?? 'featured';
        $orderBy = "ORDER BY ";
        switch ($sortBy) {
            case 'name_asc':
                $orderBy .= "common_name ASC";
                break;
            case 'name_desc':
                $orderBy .= "common_name DESC";
                break;
            case 'created_desc':
                $orderBy .= "created_at DESC";
                break;
            case 'created_asc':
                $orderBy .= "created_at ASC";
                break;
            default: // 'featured'
                $orderBy .= "recommended_for_beginners DESC, common_name ASC";
                break;
        }
        
        $params[] = $limit;
        $params[] = $offset;
        
        // Use DISTINCT when joining with categories to avoid duplicates
        $selectFields = !empty($categoryJoin) ? "DISTINCT plant_catalog.*" : "plant_catalog.*";
        
        $sql = "SELECT $selectFields FROM plant_catalog $categoryJoin $whereClause $orderBy LIMIT ? OFFSET ?";
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
        
        // Handle category filtering first to determine if we need alias
        $categoryJoin = "";
        if (!empty($filters['categories']) && is_array($filters['categories'])) {
            $categoryIds = array_filter(array_map('intval', $filters['categories']));
            if (!empty($categoryIds)) {
                $categoryPlaceholders = implode(',', array_fill(0, count($categoryIds), '?'));
                $categoryJoin = "
                    INNER JOIN plant_category_assignments pca ON pc.id = pca.plant_catalog_id
                    INNER JOIN categories c ON pca.category_id = c.id
                ";
                $where[] = "c.id IN ($categoryPlaceholders)";
                $params = array_merge($params, $categoryIds);
            }
        } elseif (!empty($filters['category_slug'])) {
            $categoryJoin = "
                INNER JOIN plant_category_assignments pca ON pc.id = pca.plant_catalog_id
                INNER JOIN categories c ON pca.category_id = c.id
            ";
            $where[] = "c.slug = ?";
            $params[] = $filters['category_slug'];
        }
        
        // Use alias 'pc' if we have category join, otherwise use table name directly
        $tableAlias = !empty($categoryJoin) ? "pc" : "plant_catalog";
        
        if (!empty($filters['difficulty_level'])) {
            $where[] = "$tableAlias.difficulty_level = ?";
            $params[] = $filters['difficulty_level'];
        }
        
        if (!empty($filters['light_requirement'])) {
            $where[] = "$tableAlias.light_requirement = ?";
            $params[] = $filters['light_requirement'];
        }
        
        if (!empty($filters['water_requirement'])) {
            $where[] = "$tableAlias.water_requirement = ?";
            $params[] = $filters['water_requirement'];
        }
        
        if (!empty($filters['humidity_preference'])) {
            $where[] = "$tableAlias.humidity_preference = ?";
            $params[] = $filters['humidity_preference'];
        }
        
        if (!empty($filters['recommended_for_beginners'])) {
            $where[] = "$tableAlias.recommended_for_beginners = ?";
            $params[] = $filters['recommended_for_beginners'] ? 1 : 0;
        }
        
        if (!empty($filters['search'])) {
            $where[] = "($tableAlias.common_name LIKE ? OR $tableAlias.scientific_name LIKE ? OR $tableAlias.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        $tableName = !empty($categoryJoin) ? "plant_catalog pc" : "plant_catalog";
        $selectFields = !empty($categoryJoin) ? "COUNT(DISTINCT pc.id)" : "COUNT(*)";
        
        $sql = "SELECT $selectFields as total FROM $tableName $categoryJoin $whereClause";
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
    
    /**
     * Get all categories
     */
    public function getAllCategories() {
        try {
            $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get categories for a specific plant
     */
    public function getPlantCategories($plantId) {
        try {
            $stmt = $this->db->prepare("
                SELECT c.* 
                FROM categories c
                INNER JOIN plant_category_assignments pca ON c.id = pca.category_id
                WHERE pca.plant_catalog_id = ?
                ORDER BY c.name ASC
            ");
            $stmt->execute([$plantId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Assign categories to a plant
     */
    public function assignCategories($plantId, $categoryIds) {
        try {
            // First, remove all existing assignments
            $stmt = $this->db->prepare("DELETE FROM plant_category_assignments WHERE plant_catalog_id = ?");
            $stmt->execute([$plantId]);
            
            // Then add new assignments
            if (!empty($categoryIds) && is_array($categoryIds)) {
                $stmt = $this->db->prepare("
                    INSERT INTO plant_category_assignments (plant_catalog_id, category_id) 
                    VALUES (?, ?)
                ");
                foreach ($categoryIds as $categoryId) {
                    $stmt->execute([$plantId, intval($categoryId)]);
                }
            }
            return true;
        } catch (Exception $e) {
            error_log("Error assigning categories: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get category by slug
     */
    public function getCategoryBySlug($slug) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = ?");
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }
}



