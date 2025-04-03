<?php
require_once __DIR__ . '/../includes/db.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function register($email, $password, $firstName, $lastName, $phone = null, $state = null) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (email, password, first_name, last_name, phone_number, state_residence) 
                VALUES (?, ?, ?, ?, ?, ?) RETURNING id";
        
        $stmt = $this->db->executeQuery($sql, [$email, $hashedPassword, $firstName, $lastName, $phone, $state]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['id'] ?? false;
    }
    
    public function login($email, $password) {
        $sql = "SELECT id, email, password, first_name, last_name FROM users WHERE email = ?";
        $stmt = $this->db->executeQuery($sql, [$email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function getUserById($id) {
        $sql = "SELECT id, email, first_name, last_name, phone_number, state_residence FROM users WHERE id = ?";
        $stmt = $this->db->executeQuery($sql, [$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateProfile($id, $firstName, $lastName, $email, $phone, $state) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone_number = ?, state_residence = ? 
                WHERE id = ?";
        
        $stmt = $this->db->executeQuery($sql, [$firstName, $lastName, $email, $phone, $state, $id]);
        
        return $stmt->rowCount() > 0;
    }
}
