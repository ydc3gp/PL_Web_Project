<?php
// Create this as setup_db.php and run it once to set up your tables

// Author - Chris Dai
require_once 'includes/db.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Create users table
$users_table = "
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone_number VARCHAR(20),
    state_residence VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

// Create academics table
$academics_table = "
CREATE TABLE IF NOT EXISTS academics (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    gpa NUMERIC(3,2),
    class_rank INTEGER,
    class_size INTEGER,
    sat_score INTEGER,
    act_score INTEGER,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

// Create saved_colleges table
$colleges_table = "
CREATE TABLE IF NOT EXISTS saved_colleges (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    college_name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

try {
    $conn->exec($users_table);
    echo "Users table created successfully<br>";
    
    $conn->exec($academics_table);
    echo "Academics table created successfully<br>";
    
    $conn->exec($colleges_table);
    echo "Saved colleges table created successfully<br>";
    
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
