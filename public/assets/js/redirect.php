<?php
if($_SERVER['SERVER_NAME'] == 'localhost'){

    defined('ROOT') or define("ROOT", 'https://localhost/QRCodeAttendance/QRCodeAttendance/public/');

}else{

    defined('ROOT') or define("ROOT", 'https://usep-qrattendance.site/public/');
}
session_start();

$userSessions = [
                        [
                            'role' => $_SESSION['role'],
                            'username' => $_SESSION['username'],
                            'user_id' => $_SESSION['user_id'],
                            'auth_token' => $_SESSION['auth_token']
                        ]
                    ];

if (!isset($_SESSION['username'], $_SESSION['role'], $_SESSION['user_id'], $_SESSION['auth_token'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No session']);
    exit;
}

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
