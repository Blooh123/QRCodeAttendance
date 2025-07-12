<?php
require_once 'app/core/config.php';
require_once 'app/core/Model.php';
require_once 'app/Model/ExcuseApplication.php';

echo "<h1>Document Test</h1>";

$excuseApp = new \Model\ExcuseApplication();

// Get all applications
$applications = $excuseApp->getAllExcuseApplications();

echo "<h2>All Applications:</h2>";
echo "<pre>";
print_r($applications);
echo "</pre>";

if (!empty($applications)) {
    $firstApp = $applications[0];
    echo "<h2>Testing Document Retrieval for Application ID: " . $firstApp['id'] . "</h2>";
    
    // Test document 1
    $doc1 = $excuseApp->getDocument($firstApp['id'], 1);
    echo "<h3>Document 1:</h3>";
    echo "Found: " . ($doc1 ? 'Yes' : 'No') . "<br>";
    if ($doc1) {
        echo "Size: " . strlen($doc1) . " bytes<br>";
        echo "First 100 characters: " . substr(bin2hex($doc1), 0, 100) . "<br>";
    }
    
    // Test document 2
    $doc2 = $excuseApp->getDocument($firstApp['id'], 2);
    echo "<h3>Document 2:</h3>";
    echo "Found: " . ($doc2 ? 'Yes' : 'No') . "<br>";
    if ($doc2) {
        echo "Size: " . strlen($doc2) . " bytes<br>";
        echo "First 100 characters: " . substr(bin2hex($doc2), 0, 100) . "<br>";
    }
} else {
    echo "<p>No applications found in database.</p>";
}
?> 