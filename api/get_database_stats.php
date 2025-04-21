<?php
require_once '../src/Models/College.php';

header('Content-Type: application/json');

$college = new College();
$rawColleges = $college->getAllColleges();

// Filter out invalid college entries (those without a name)
$allColleges = array_filter($rawColleges, function($c) {
    return !empty(trim($c['name'] ?? ''));
});

// Generate college statistics
$stats = [
    'total_colleges' => count($allColleges),
    'public_colleges' => 0,
    'private_colleges' => 0,
    'proprietary_colleges' => 0,
    'other_colleges' => 0
];

// Calculate statistics
foreach ($allColleges as $c) {
    // Count colleges by type based on the actual values in the database
    if (!empty($c['is_public'])) {
        $type = strtolower(trim($c['is_public']));
        
        switch ($type) {
            case 'public':
                $stats['public_colleges']++;
                break;
            case 'private':
                $stats['private_colleges']++;
                break;
            case 'proprietary':
                $stats['proprietary_colleges']++;
                break;
            default:
                $stats['other_colleges']++;
                break;
        }
    } else {
        // Count colleges with no type as "other"
        $stats['other_colleges']++;
    }
}

// Add any additional statistics
$stats['last_updated'] = date('F j, Y');

echo json_encode($stats);
?>