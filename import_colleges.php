<?php
require_once 'includes/db.php';
require_once 'src/Models/College.php';

// Path to CSV file
$csvFile = 'processed_data.csv';

// Create College instance
$college = new College();

// Display header
echo "<h1>College Import Result</h1>";

// Step 1: Convert CSV to JSON
$jsonData = $college->csvToJson($csvFile);

if ($jsonData === false) {
    echo "<div style='color: red;'>Error: Could not read CSV file or convert to JSON.</div>";
    exit;
}

// Step 2: Import JSON data to database
$result = $college->importFromJson($jsonData);

// Display result with formatting
if ($result['success']) {
    echo "<div style='color: green; padding: 15px; background-color: #f0fff0; border-radius: 5px; margin: 20px 0;'>";
    echo "<strong>Success!</strong> " . $result['message'];
    echo "</div>";
    
    echo "<p>You can now view and search colleges on the <a href='search.php' style='color: #0066cc; text-decoration: none; font-weight: bold;'>Search Colleges</a> page.</p>";
} else {
    echo "<div style='color: #721c24; padding: 15px; background-color: #f8d7da; border-radius: 5px; margin: 20px 0;'>";
    echo "<strong>Error:</strong> " . $result['message'];
    echo "</div>";
    
    echo "<p>Please check your CSV file format and try again.</p>";
}
?>

<style>
body {
    font-family: 'Figtree', sans-serif;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}
h1 {
    color: #0066cc;
}
a:hover {
    text-decoration: underline !important;
}
</style>
