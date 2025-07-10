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

if (!isset($_SESSION['username'], $_SESSION['role'], $_SESSION['user_id'], $_SESSION['auth_token'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No session']);
    exit;
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

// Get user agent and IP address
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$ipAddress = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

// Convert IPv6 localhost to IPv4
if ($ipAddress === '::1') {
    $ipAddress = '127.0.0.1';
}

// Get device information
function getDeviceInfo($userAgent) {
    $deviceInfo = 'Unknown';
    
    if (preg_match('/Windows/i', $userAgent)) {
        $deviceInfo = 'Windows';
    } elseif (preg_match('/Mac/i', $userAgent)) {
        $deviceInfo = 'Mac';
    } elseif (preg_match('/Linux/i', $userAgent)) {
        $deviceInfo = 'Linux';
    } elseif (preg_match('/Android/i', $userAgent)) {
        $deviceInfo = 'Android';
    } elseif (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
        $deviceInfo = 'iOS';
    }
    
    return $deviceInfo;
}

$deviceInfo = getDeviceInfo($userAgent);

// Set timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Create DateTime objects
$now = new DateTime('now', new DateTimeZone('Asia/Manila'));
$created = $now->format('Y-m-d H:i:s');

// Calculate expiry time based on role
$expiry = clone $now;
if ($_SESSION['role'] === 'student') {
    $expiry->modify('+10 minutes');
} else {
    $expiry->modify('+2 days');
}
$expiresAt = $expiry->format('Y-m-d H:i:s');

// Insert session into database
try {
    $stmt = $pdo->prepare("
        INSERT INTO user_sessions (
            user_id, 
            role, 
            token, 
            user_agent, 
            ip_address, 
            deviceInfo, 
            created_at, 
            expires_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $_SESSION['role'],
        $_SESSION['auth_token'],
        $userAgent,
        $ipAddress,
        $deviceInfo,
        $created,
        $expiresAt
    ]);
    
    // Update user status to 'login'
    $updateStmt = $pdo->prepare("UPDATE users SET state = 'login' WHERE id = ?");
    $updateStmt->execute([$_SESSION['user_id']]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to insert session']);
    exit;
}

// Set cookie for client-side access
$userSessions = [
    [
        'role' => $_SESSION['role'],
        'username' => $_SESSION['username'],
        'user_id' => $_SESSION['user_id'],
        'auth_token' => $_SESSION['auth_token']
    ]
];

$cookieExpiry = time() + 60 * 60 * 24 * 2; // 2 days

setcookie(
    'user_data',
    json_encode($userSessions),
    $cookieExpiry,
    '/',
    '',
    isset($_SERVER['HTTPS']),
    true
);

echo json_encode(['redirect' => ROOT . 'facilitator']);
exit;
