<?php
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
}
session_start();
header('Content-Type: application/json');

try {
    // Direct database connection
    $dsn = "mysql:host=" . DBHOST . ";port=" . DBPORT . ";dbname=" . DBNAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the username from session
    $username = $_SESSION['username'] ?? null;
    
    if (!$username) {
        echo json_encode(['error' => 'No user session found']);
        exit;
    }
    
    // Get user ID by username
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }
    
    $userId = $user['id'];
    
    // Get facial images for this user
    $stmt = $pdo->prepare("SELECT img FROM facilitator_facial_images WHERE user_id = ?");
    $stmt->execute([$userId]);
    $facialImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($facialImages)) {
        echo json_encode(['error' => 'No facial images found for this user']);
        exit;
    }
    
    // Convert images to base64 data URLs
    $imageDataUrls = [];
    foreach ($facialImages as $index => $image) {
        // Check if image data exists and has content
        if (!empty($image['img'])) {
            $imageData = $image['img'];
            
            // If the data is already base64 encoded (from registration), use it directly
            if (strpos($imageData, 'data:image/jpeg;base64,') === 0) { 
                // Already a data URL, use as is
                $imageDataUrls[] = [
                    'id' => $index + 1,
                    'dataUrl' => $imageData
                ];
            } else {
                // Binary data, encode to base64
                $base64Data = base64_encode($imageData);
                $imageDataUrls[] = [
                    'id' => $index + 1,
                    'dataUrl' => "data:image/jpeg;base64," . $base64Data
                ];
            }
        } else {
            // Log empty image data
            error_log("Empty image data for user $userId, image $index");
        }
    }
    
    if (empty($imageDataUrls)) {
        echo json_encode(['error' => 'No valid image data found']);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'images' => $imageDataUrls,
        'username' => $username,
        'count' => count($imageDataUrls)
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch facial images: ' . $e->getMessage()]);
}
