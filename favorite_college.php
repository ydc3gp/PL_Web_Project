<?php
require_once __DIR__ . '/../../private/includes/session.php';
require_once __DIR__ . '/../../private/includes/db.php';

// Return JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!Session::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to favorite a college.']);
    exit;
}

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['college_name']) || empty($_POST['location'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required college data.']);
    exit;
}

$collegeName = trim($_POST['college_name']);
$location = trim($_POST['location']);
$userId = Session::get('user_id');

// Save to DB
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    // Check if already saved
    $checkSql = "SELECT 1 FROM sprint3_saved_colleges WHERE user_id = :user_id AND college_name = :college_name AND location = :location";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute([
        ':user_id' => $userId,
        ':college_name' => $collegeName,
        ':location' => $location,
    ]);

    if ($checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'College already in your favorites.']);
        exit;
    }

    // Insert favorite
    $insertSql = "INSERT INTO sprint3_saved_colleges (user_id, college_name, location) VALUES (:user_id, :college_name, :location)";
    $stmt = $conn->prepare($insertSql);
    $stmt->execute([
        ':user_id' => $userId,
        ':college_name' => $collegeName,
        ':location' => $location,
    ]);

    echo json_encode(['success' => true, 'message' => 'College added to favorites!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error saving favorite: ' . $e->getMessage()]);
}
?>
