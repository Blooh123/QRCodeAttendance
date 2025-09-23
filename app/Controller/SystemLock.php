<?php

namespace Controller;
require_once '../app/Model/User.php';
require_once '../app/core/Model.php';
use Model\User;
use Model;
class SystemLock extends \Controller
{
    use Model;
    public function index(){
                // check if POST request to unlock
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $password = $input['password'] ?? '';
            $user = new User();
            $userSessions = json_decode($_COOKIE['user_data'], true);
            $validate = $this->validateLogIn(trim($userSessions[0]['username']), trim($password));
            if ($validate) {
                // Password is correct, unlock the system
                setcookie('system_lock', '', time() - 3600, '/'); // Clear the lock cookie
                setcookie('locked_user', '', time() - 3600, '/'); // Clear the locked user cookie

                echo json_encode(['success' => true]);
                exit();
            } else {
                echo json_encode(['success' => false]);
                exit();
            }
        }
    }
}
$user = new User();
$userData = $user->checkSession('adminHome');
if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
    $uri = str_replace('/adminHome', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}


$systemLockInstance = new SystemLock();
$systemLockInstance->index();