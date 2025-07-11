<?php

// Test script to verify paths are working
echo "Testing path resolution...\n";

// Get the project root directory
$projectRoot = dirname(__DIR__);
echo "Project root: " . $projectRoot . "\n";

// Test if vendor/autoload.php exists
$vendorPath = $projectRoot . '/vendor/autoload.php';
echo "Vendor path: " . $vendorPath . "\n";
echo "Vendor exists: " . (file_exists($vendorPath) ? 'YES' : 'NO') . "\n";

// Test if app/core/Database.php exists
$databasePath = $projectRoot . '/app/core/Database.php';
echo "Database path: " . $databasePath . "\n";
echo "Database exists: " . (file_exists($databasePath) ? 'YES' : 'NO') . "\n";

// Test if app/core/config.php exists
$configPath = $projectRoot . '/app/core/config.php';
echo "Config path: " . $configPath . "\n";
echo "Config exists: " . (file_exists($configPath) ? 'YES' : 'NO') . "\n";

// Test if app/Model/Student.php exists
$studentPath = $projectRoot . '/app/Model/Student.php';
echo "Student path: " . $studentPath . "\n";
echo "Student exists: " . (file_exists($studentPath) ? 'YES' : 'NO') . "\n";

// Test if app/Model/Attendances.php exists
$attendancesPath = $projectRoot . '/app/Model/Attendances.php';
echo "Attendances path: " . $attendancesPath . "\n";
echo "Attendances exists: " . (file_exists($attendancesPath) ? 'YES' : 'NO') . "\n";

echo "\nAll path tests completed.\n"; 