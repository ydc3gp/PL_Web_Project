<?php
require_once __DIR__ . '/../../private/includes/db.php';
require_once __DIR__ . '/../../private/Models/College.php';

// Path to CSV file
$csvFile = 'final.csv';

// Create College instance
$college = new College();

// Import CSV file directly
$result = $college->importFromCSV($csvFile);

echo "<h1>College Import Result</h1>";
echo "<p>{$result['message']}</p>";

if ($result['success']) {
    echo "<p>Database table created and populated successfully.</p>";
    
    echo "<h2>Imported Colleges</h2>";
    $colleges = $college->getAllColleges();
    echo "<p>Total colleges imported: " . count($colleges) . "</p>";
    
    if (count($colleges) > 0) {
        echo "<h3>Sample of imported colleges:</h3>";
        echo "<ul>";
        $sampleSize = min(10, count($colleges));
        for ($i = 0; $i < $sampleSize; $i++) {
            echo "<li>{$colleges[$i]['name']} - Rank: {$colleges[$i]['ranking_display_rank']}</li>";
        }
        echo "</ul>";
    }
}
?>
