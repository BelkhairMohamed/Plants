<?php
/**
 * Plant Care Event Model
 */

class PlantCareEvent {
    private $db;
    
    public function __construct() {
        $this->db = getDBConnection();
    }
    
    public function create($userPlantId, $eventType, $eventDate, $notes = null) {
        $stmt = $this->db->prepare("
            INSERT INTO plant_care_events (user_plant_id, event_type, event_date, notes) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$userPlantId, $eventType, $eventDate, $notes]);
    }
    
    public function findByUserPlantId($userPlantId, $limit = 50) {
        $stmt = $this->db->prepare("
            SELECT * FROM plant_care_events 
            WHERE user_plant_id = ? 
            ORDER BY event_date DESC, created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$userPlantId, $limit]);
        return $stmt->fetchAll();
    }
    
    public function getRecentByUserId($userId, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT pce.*, up.nickname_for_plant, pc.common_name
            FROM plant_care_events pce
            JOIN user_plants up ON pce.user_plant_id = up.id
            JOIN plant_catalog pc ON up.plant_catalog_id = pc.id
            WHERE up.user_id = ?
            ORDER BY pce.event_date DESC, pce.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
    
    public function getCareEventsByType($userId, $days = 30) {
        $stmt = $this->db->prepare("
            SELECT pce.event_type, COUNT(*) as count
            FROM plant_care_events pce
            JOIN user_plants up ON pce.user_plant_id = up.id
            WHERE up.user_id = ?
            AND pce.event_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY pce.event_type
            ORDER BY count DESC
        ");
        $stmt->execute([$userId, $days]);
        return $stmt->fetchAll();
    }
    
    public function getCareEventsByDay($userId, $days = 30) {
        $stmt = $this->db->prepare("
            SELECT DATE(pce.event_date) as date, COUNT(*) as count
            FROM plant_care_events pce
            JOIN user_plants up ON pce.user_plant_id = up.id
            WHERE up.user_id = ?
            AND pce.event_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(pce.event_date)
            ORDER BY date ASC
        ");
        $stmt->execute([$userId, $days]);
        return $stmt->fetchAll();
    }
}



