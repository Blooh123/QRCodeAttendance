<?php

// Database configuration
if($_SERVER['SERVER_NAME'] == 'localhost'){
    defined('ROOT') or define("ROOT", 'https://localhost/QRCodeAttendance/QRCodeAttendance/public/');
    define('DBNAME', 'qrcode_attendance_system');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBHOST', 'localhost');
    define('DBPORT', '3306');
}else{
    defined('ROOT') or define("ROOT", 'https://usep-qrattendance.site/public/');
    define('DBNAME', 'u753706103_qr_attendance');
    define('DBUSER', 'u753706103_christian');
    define('DBPASS', 'mZ2~G76JP1s5=B=Cy1L*');
    define('DBHOST', 'localhost');
    define('DBPORT', '3306');

    //     define('DBNAME', 'qrcode_attendance_system');//u753706103_qr_attendance
    // define('DBUSER', 'root');//u753706103_christian
    // define('DBPASS', '');//mZ2~G76JP1s5=B=Cy1L*
    // define('DBHOST', 'localhost');
    // define('DBPORT', '3306');
    // defined('ROOT') or define("ROOT", 'http://192.168.104.67/QRCodeAttendance/QRCodeAttendance/public/');
    // // defined('ROOT') or define("ROOT", 'https://usep-qrattendance.site/public/');
}

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";port=" . DBPORT,
        DBUSER,
        DBPASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) exit('No data');

$username = preg_replace('/[^a-zA-Z0-9_]/', '', $data['username']);
$imgNum = intval($data['imgNum']);
$imgData = $data['imgData'];

// Get user ID by username (direct query instead of using User model)
function getUserIdByUsername($pdo, $username) {
    $query = "SELECT id FROM user_account WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['id'] : null;
}

$userId = getUserIdByUsername($pdo, $username);

if (!$userId) {
    exit('User not found');
}

// Clean the image data (remove data URL prefix and decode base64)
$cleanImageData = str_replace('data:image/jpeg;base64,', '', $imgData);
$cleanImageData = str_replace(' ', '+', $cleanImageData);

// Decode base64 to binary data for storage
$binaryImageData = base64_decode($cleanImageData);

if ($binaryImageData === false) {
    exit('Invalid image data');
}

// Save image to database (direct query instead of using User model)
function uploadFacialImage($pdo, $userId, $imageData) {
    $query = "INSERT INTO facilitator_facial_images (user_id, img) VALUES (:user_id, :image_data)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':image_data', $imageData, PDO::PARAM_LOB);
    
    try {
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

if (uploadFacialImage($pdo, $userId, $binaryImageData)) {
    echo json_encode(['success' => true, 'message' => 'Image saved successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save image to database']);
}


