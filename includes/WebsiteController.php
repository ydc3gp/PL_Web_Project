<?php
require_once('/students/yrg9mn/students/yrg9mn/private/sprint3/Models/User.php');
require_once('/students/yrg9mn/students/yrg9mn/private/sprint3/Models/College.php');
require_once('/students/yrg9mn/students/yrg9mn/private/sprint3/Models/Academics.php');
require_once('/students/yrg9mn/students/yrg9mn/private/sprint3/session.php');

class WebsiteController {
    private $userModel;
    private $collegeModel;
    private $academicsModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->collegeModel = new College();
        $this->academicsModel = new Academics();
    }
    
    // User related methods
    public function registerUser($email, $password, $firstName, $lastName, $phone, $state) {
        return $this->userModel->register($email, $password, $firstName, $lastName, $phone, $state);
    }
    
    public function loginUser($email, $password) {
        $user = $this->userModel->login($email, $password);
        
        if ($user) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['first_name']);
            Session::set('email', $user['email']);
            return true;
        }
        
        return false;
    }
    
    public function getUserProfile($userId) {
        return $this->userModel->getUserById($userId);
    }
    
    public function updateUserProfile($userId, $firstName, $lastName, $email, $phone, $state) {
        return $this->userModel->updateProfile($userId, $firstName, $lastName, $email, $phone, $state);
    }
    
    // College related methods
    public function getAllColleges() {
        return $this->collegeModel->getAllColleges();
    }
    
    public function getFilteredColleges($filters) {
        return $this->collegeModel->getCollegesByFilter($filters);
    }
    
    public function importColleges($filePath) {
        return $this->collegeModel->importFromCSV($filePath);
    }
    
    // Academics related methods
    public function getUserAcademics($userId) {
        return $this->academicsModel->getAcademicsByUserId($userId);
    }
    
    public function updateAcademics($userId, $gpa, $classRank, $classSize, $satScore, $actScore) {
        return $this->academicsModel->updateAcademics($userId, $gpa, $classRank, $classSize, $satScore, $actScore);
    }
    
    // Session methods
    public function isUserLoggedIn() {
        return Session::isLoggedIn();
    }
    
    public function logoutUser() {
        Session::destroy();
    }
}
