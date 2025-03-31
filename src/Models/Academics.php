<?php
require_once __DIR__ . '/../../includes/db.php';

class Academics {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAcademicsByUserId($userId) {
        $sql = "SELECT * FROM academics WHERE user_id = ?";
        $stmt = $this->db->executeQuery($sql, [$userId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createAcademics($userId, $gpa, $classRank, $classSize, $satScore, $actScore) {
        $sql = "INSERT INTO academics (user_id, gpa, class_rank, class_size, sat_score, act_score) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->executeQuery($sql, [$userId, $gpa, $classRank, $classSize, $satScore, $actScore]);
        
        return $stmt->rowCount() > 0;
    }
    
    public function updateAcademics($userId, $gpa, $classRank, $classSize, $satScore, $actScore) {
        // First check if record exists
        $existingRecord = $this->getAcademicsByUserId($userId);
        
        if ($existingRecord) {
            // Update existing record
            $sql = "UPDATE academics SET gpa = ?, class_rank = ?, class_size = ?, 
                    sat_score = ?, act_score = ?, updated_at = CURRENT_TIMESTAMP 
                    WHERE user_id = ?";
            
            $stmt = $this->db->executeQuery($sql, [$gpa, $classRank, $classSize, $satScore, $actScore, $userId]);
            
            return $stmt->rowCount() > 0;
        } else {
            // Create new record
            return $this->createAcademics($userId, $gpa, $classRank, $classSize, $satScore, $actScore);
        }
    }
}
