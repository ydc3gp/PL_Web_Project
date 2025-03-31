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
    
    public function csvToJson($filePath) {
        if (!file_exists($filePath)) {
            return false;
        }
        
        $jsonArray = [];
        
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Read header row
            $header = fgetcsv($handle, 0, ',', '"', '');
            
            // Read data rows
            while (($data = fgetcsv($handle, 0, ',', '"', '')) !== FALSE) {
                if (count($data) < 6) {
                    continue; // Skip rows with insufficient columns
                }
                
                // Create associative array using headers as keys
                $row = [];
                foreach ($header as $index => $fieldName) {
                    $row[$fieldName] = isset($data[$index]) ? $data[$index] : null;
                }
                
                $jsonArray[] = $row;
            }
            
            fclose($handle);
        }
        
        return json_encode($jsonArray);
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
                name, city, zipcode, ranking_display_rank, 
                sat_avg, test_avg_range_2
            ) VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            
            // Insert data
            $rowCount = 0;
            foreach ($colleges as $college) {
                $rankingDisplayRank = str_replace('#', '', $college['ranking.displayRank']);
                
                $params = [
                    $college['institution.displayName'],
                    $college['institution.city'],
                    $college['institution.zip'],
                    $rankingDisplayRank,
                    $college['searchData.satAvg.rawValue'],
                    $college['searchData.testAvgs.displayValue.1.value']
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
        // Check if file exists
        if (!file_exists($filePath)) {
            return ['success' => false, 'message' => 'CSV file not found'];
        }
        
        // Create colleges table if it doesn't exist
        $this->createCollegesTable();
        
        // Open the CSV file
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Read header row with escape parameter to avoid deprecation warnings
            $header = fgetcsv($handle, 0, ',', '"', '');
            
            // Begin transaction
            $this->db->getConnection()->beginTransaction();
            
            try {
                // Clear existing data
                $this->db->executeQuery("DELETE FROM colleges");
                
                // Prepare insert statement for the new CSV format
                $sql = "INSERT INTO colleges (
                    name, city, zip, ranking_display_rank, 
                    sat_avg, test_avg_range_2
                ) VALUES (?, ?, ?, ?, ?, ?)";
                
                $stmt = $this->db->getConnection()->prepare($sql);
                
                // Read data rows with escape parameter
                $rowCount = 0;
                while (($data = fgetcsv($handle, 0, ',', '"', '')) !== FALSE) {
                    if (count($data) < 6) {
                        continue; // Skip rows that don't have enough columns
                    }
                    
                    // Process ranking_display_rank to remove '#' prefix
                    $rankingDisplayRank = str_replace('#', '', $data[3]);
                    
                    // Map CSV columns to database fields
                    $params = [
                        $data[0],  // name (institution.displayName)
                        $data[1],  // city (institution.city)
                        $data[2],  // zip (institution.zip)
                        $rankingDisplayRank, // ranking_display_rank (ranking.displayRank)
                        $data[4],  // sat_avg (searchData.satAvg.rawValue)
                        $data[5]   // test_avg_range_2 (searchData.testAvgs.displayValue.1.value)
                    ];
                    
                    $stmt->execute($params);
                    $rowCount++;
                }
                
                // Commit transaction
                $this->db->getConnection()->commit();
                
                fclose($handle);
                return ['success' => true, 'message' => "Imported $rowCount colleges successfully"];
            } catch (Exception $e) {
                // Rollback transaction on error
                $this->db->getConnection()->rollBack();
                fclose($handle);
                return ['success' => false, 'message' => 'Error importing data: ' . $e->getMessage()];
            }
        } else {
            return ['success' => false, 'message' => 'Could not open CSV file'];
        }
    }
    
    private function createCollegesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS colleges (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            city VARCHAR(100),
            zip VARCHAR(10),
            ranking_display_rank VARCHAR(20),
            sat_avg VARCHAR(10),
            test_avg_range_2 VARCHAR(20),
            state VARCHAR(2) DEFAULT NULL,
            school_type VARCHAR(100) DEFAULT NULL,
            ranking_sort_rank INTEGER DEFAULT NULL,
            tuition VARCHAR(20) DEFAULT NULL,
            hs_gpa_avg VARCHAR(10) DEFAULT NULL,
            enrollment VARCHAR(20) DEFAULT NULL
        )";
        
        $this->db->executeQuery($sql);
    }
}
