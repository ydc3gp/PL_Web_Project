<?php
require_once __DIR__ . '/../../includes/db.php';

class College {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllColleges() {
        $sql = "SELECT * FROM colleges ORDER BY ranking_display_rank ASC";
        $stmt = $this->db->executeQuery($sql);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCollegesByFilter($filters = []) {
        $sql = "SELECT * FROM colleges WHERE 1=1";
        $params = [];
        
        if (!empty($filters['state'])) {
            $sql .= " AND state = ?";
            $params[] = $filters['state'];
        }

        if (!empty($filters['max_tuition'])) {
            $sql .= " AND tuition <= ?";
            $params[] = $filters['max_tuition'];
        }
        
        $sql .= " ORDER BY ranking_display_rank ASC";
        
        $stmt = $this->db->executeQuery($sql, $params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function importFromJson($jsonData) {
        $colleges = json_decode($jsonData, true);
        
        if (!$colleges) {
            return ['success' => false, 'message' => 'Invalid JSON data'];
        }
        
        // Create colleges table if it doesn't exist
        $this->createCollegesTable();
        
        // Begin transaction
        $this->db->getConnection()->beginTransaction();
        
        try {
            // Clear existing data
            $this->db->executeQuery("DELETE FROM colleges");
            
            // Prepare insert statement
            $sql = "INSERT INTO colleges (
                name, state, city, zip, is_public, ranking_display_rank, acceptance_rate, tuition, 
                hs_gpa_avg, enrollment, 
                test_avg_range_1, test_avg_range_2
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            
            // Insert data
            $rowCount = 0;
            foreach ($colleges as $college) {
                // Skip header row if it exists
                if ($rowCount == 0 && isset($college[0]) && $college[0] == "institution.displayName") {
                    $rowCount++;
                    continue;
                }
                
                // Process ranking to remove '#' prefix if needed
                $rankingDisplayRank = isset($college[5]) ? str_replace('#', '', $college[5]) : '';
                
                $params = [
                    substr($college[0] ?? '', 0, 255),  // name (institution.displayName)
                    substr($college[1] ?? '', 0, 2),    // state (institution.state)
                    substr($college[2] ?? '', 0, 100),  // city (institution.city)
                    substr($college[3] ?? '', 0, 100),   // zip (institution.zip)
                    substr($college[4] ?? '', 0, 30),   // is_public (institution.institutionalControl)
                    substr($rankingDisplayRank, 0, 20), // ranking_display_rank (ranking.displayRank)
                    substr($college[6] ?? '', 0, 20),   // acceptance_rate (searchData.acceptanceRate.rawValue)
                    substr($college[7] ?? '', 0, 20),   // tuition (searchData.tuition.rawValue)
                    substr($college[8] ?? '', 0, 30),   // hs_gpa_avg (searchData.hsGpaAvg.rawValue)
                    substr($college[9] ?? '', 0, 20),   // enrollment (searchData.enrollment.rawValue)
                    substr($college[10] ?? '', 0, 20),  // test_avg_range_1 (searchData.testAvgs.displayValue.0.value)
                    substr($college[11] ?? '', 0, 20)   // test_avg_range_2 (searchData.testAvgs.displayValue.1.value)
                ];
                
                $stmt->execute($params);
                $rowCount++;
            }
            
            $this->db->getConnection()->commit();
            
            return ['success' => true, 'message' => "Imported $rowCount colleges successfully"];
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            return ['success' => false, 'message' => 'Error importing data: ' . $e->getMessage()];
        }
    }
    
    public function importFromCSV($filePath) {
        // Convert CSV to JSON
        $jsonData = $this->csvToJson($filePath);
        if (!$jsonData) {
            return ['success' => false, 'message' => 'Failed to convert CSV to JSON'];
        }
        
        // Use the importFromJson method to import the data
        return $this->importFromJson($jsonData);
    }
    
    public function csvToJson($filePath) {
        if (!file_exists($filePath)) {
            return false;
        }
        
        // Read file contents
        $csv = file_get_contents($filePath);
        
        // Convert to array
        $array = array_map(function($line) {
            return str_getcsv($line, ",", '"', "");
        }, explode("\n", $csv));
        
        // Return JSON encoded array
        return json_encode($array);
    }
    
    private function createCollegesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS colleges (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            state VARCHAR(2),
            city VARCHAR(100),
            zip VARCHAR(100),
            is_public VARCHAR(30),
            ranking_display_rank VARCHAR(20),
            acceptance_rate VARCHAR(20),
            tuition VARCHAR(20),
            hs_gpa_avg VARCHAR(30),
            enrollment VARCHAR(20),
            test_avg_range_1 VARCHAR(20),
            test_avg_range_2 VARCHAR(20)
        )";
        
        $this->db->executeQuery($sql);
    }
}
