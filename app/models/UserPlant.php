<?php
/**
 * User Plant Model (Personal Collection)
 */

class UserPlant {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO user_plants 
            (user_id, plant_catalog_id, nickname_for_plant, is_from_marketplace, 
             purchase_date, acquisition_type, room_location, custom_watering_interval_days, 
             custom_fertilizing_interval_days, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['user_id'],
            $data['plant_catalog_id'],
            $data['nickname_for_plant'] ?? null,
            $data['is_from_marketplace'] ?? false,
            $data['purchase_date'] ?? null,
            $data['acquisition_type'] ?? 'unknown',
            $data['room_location'] ?? null,
            $data['custom_watering_interval_days'] ?? null,
            $data['custom_fertilizing_interval_days'] ?? null,
            $data['notes'] ?? null
        ]);
    }
    
    public function findByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT up.*, pc.common_name, pc.scientific_name, pc.image_url, 
                   pc.default_watering_interval_days, pc.default_fertilizing_interval_days
            FROM user_plants up
            JOIN plant_catalog pc ON up.plant_catalog_id = pc.id
            WHERE up.user_id = ?
            ORDER BY up.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT up.*, pc.common_name, pc.scientific_name, pc.description, 
                   pc.difficulty_level, pc.light_requirement, pc.water_requirement, 
                   pc.humidity_preference, pc.temperature_min, pc.temperature_max, 
                   pc.image_url, pc.default_watering_interval_days, 
                   pc.default_fertilizing_interval_days, pc.seed_guide, pc.mature_plant_guide
            FROM user_plants up
            JOIN plant_catalog pc ON up.plant_catalog_id = pc.id
            WHERE up.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        $allowedFields = ['nickname_for_plant', 'room_location', 'last_watering_date', 
                         'last_fertilizing_date', 'custom_watering_interval_days', 
                         'custom_fertilizing_interval_days', 'notes'];
        
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
        $sql = "UPDATE user_plants SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function markWatered($id) {
        $stmt = $this->db->prepare("
            UPDATE user_plants 
            SET last_watering_date = CURDATE() 
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
    
    public function markFertilized($id) {
        $stmt = $this->db->prepare("
            UPDATE user_plants 
            SET last_fertilizing_date = CURDATE() 
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
    
    public function getPlantsNeedingWater($userId) {
        $stmt = $this->db->prepare("
            SELECT up.*, pc.common_name, pc.image_url,
                   COALESCE(up.custom_watering_interval_days, pc.default_watering_interval_days) as watering_interval,
                   DATE_ADD(COALESCE(up.last_watering_date, up.created_at), 
                           INTERVAL COALESCE(up.custom_watering_interval_days, pc.default_watering_interval_days) DAY) as next_watering_date
            FROM user_plants up
            JOIN plant_catalog pc ON up.plant_catalog_id = pc.id
            WHERE up.user_id = ?
            AND DATE_ADD(COALESCE(up.last_watering_date, up.created_at), 
                        INTERVAL COALESCE(up.custom_watering_interval_days, pc.default_watering_interval_days) DAY) <= CURDATE()
            ORDER BY next_watering_date ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getPlantsNeedingFertilizer($userId) {
        $stmt = $this->db->prepare("
            SELECT up.*, pc.common_name, pc.image_url,
                   COALESCE(up.custom_fertilizing_interval_days, pc.default_fertilizing_interval_days) as fertilizing_interval,
                   DATE_ADD(COALESCE(up.last_fertilizing_date, up.created_at), 
                           INTERVAL COALESCE(up.custom_fertilizing_interval_days, pc.default_fertilizing_interval_days) DAY) as next_fertilizing_date
            FROM user_plants up
            JOIN plant_catalog pc ON up.plant_catalog_id = pc.id
            WHERE up.user_id = ?
            AND DATE_ADD(COALESCE(up.last_fertilizing_date, up.created_at), 
                        INTERVAL COALESCE(up.custom_fertilizing_interval_days, pc.default_fertilizing_interval_days) DAY) <= CURDATE()
            ORDER BY next_fertilizing_date ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM user_plants WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function userOwnsPlant($userId, $plantCatalogId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM user_plants 
            WHERE user_id = ? AND plant_catalog_id = ?
        ");
        $stmt->execute([$userId, $plantCatalogId]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}



