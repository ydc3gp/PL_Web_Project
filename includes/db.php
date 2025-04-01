<?php
 require_once 'config.php';
 
 class Database {
     private static $instance = null;
     private $pdo;
 
     private function __construct() {
         $config = Config::$db;
         $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
         
         try {
             $this->pdo = new PDO($dsn, $config['user'], $config['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
         } catch (PDOException $e) {
             die("Database connection failed: " . $e->getMessage());
         }
     }
 
     public static function getInstance() {
         if (self::$instance === null) {
             self::$instance = new Database();
         }
         return self::$instance;
     }
 
     public function getConnection() {
         return $this->pdo;
     }
     
     public function executeQuery($sql, $params = []) {
         try {
             $stmt = $this->pdo->prepare($sql);
             $stmt->execute($params);
             return $stmt;
         } catch (PDOException $e) {
             die("Query execution failed: " . $e->getMessage());
         }
     }
 }
