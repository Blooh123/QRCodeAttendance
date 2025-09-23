<?php

namespace Controller;

require_once '../app/Model/User.php';
use Controller;
use Model\User;

session_start();
class AdminHome extends Controller
{
    public function index($data): void
    {

        $this->loadViewWithData('adminHome', $data);
    }
}
//if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
//    $uri = str_replace('/adminHome', '/login', $_SERVER['REQUEST_URI']);
//    header('Location: ' . $uri);
//}
//$user = new User();
//$user_de = $user->checkSession('adminHome');
//if ($user_de['role'] !== 'admin') {
//    $uri = str_replace('/adminHome', '/404', $_SERVER['REQUEST_URI']);
//    header('Location: ' . $uri);
//}

$user = new User();
$userData = $user->checkSession('adminHome');
if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
    $uri = str_replace('/adminHome', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

// Example for AdminHome Controller
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'lock') {
    setcookie('system_lock', 'true', time() + 3600, '/');
    setcookie('locked_user', $userData['username'], time() - 3600, '/'); // Clear the locked user cookie
    // Optionally set locked_user cookie here if needed
    exit;
}

// Handle AJAX GET to check system lock status
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
    $locked = isset($_COOKIE['system_lock']) && $_COOKIE['system_lock'] === 'true';
    header('Content-Type: application/json');
    echo json_encode(['locked' => $locked]);
    exit;
}
 

$userSessions = json_decode($_COOKIE['user_data'], true);
$username = $userSessions[0]['username']; // Get the first logged-in user


$data=[
    'username'=>$username,
];

$adminHome = new AdminHome();
$adminHome->index($data);