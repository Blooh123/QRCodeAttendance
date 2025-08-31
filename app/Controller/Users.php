<?php

namespace Controller;
require_once '../app/Model/User.php';
require_once '../app/core/Controller.php';

use Model\User;

class Users extends \Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('userAdmin',$data);
    }

}

// Check user session and permissions
$userAdmin = new Users();
$user = new User();
$userData = $user->checkSession('users');

// Allow admin or facilitator with manage users permission
if (!$userData || !isset($userData['role'])) {
    $uri = str_replace('/users', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}
$facilitatorPermissions = [];
if ($userData['role'] === 'admin') {
    // Admin has access to all users
    $facilitatorCoursePermissions = [];
} elseif ($userData['role'] === 'Facilitator') {
    // Check if facilitator has manage users permission
    $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
    if (!in_array('manage users', $facilitatorPermissions)) {
        $uri = str_replace('/users', '/login', $_SERVER['REQUEST_URI']);
        header('Location: '. $uri);
        exit();
    }
    
    // Get facilitator's course permissions
    $facilitatorCoursePermissions = [];
    foreach ($facilitatorPermissions as $permission) {
        // Handle both formats: "course:BSIT" and direct course names like "Bachelor of Science in Information Technology"
        if (strpos($permission, 'course:') === 0) {
            $course = str_replace('course:', '', $permission);
            $facilitatorCoursePermissions[] = $course;
        } elseif (!in_array($permission, ['manage students', 'manage attendance', 'manage users', 'add user'])) {
            // If it's not a management permission, assume it's a course name
            $facilitatorCoursePermissions[] = $permission;
        }
    }
} else {
    // Neither admin nor authorized facilitator
    $uri = str_replace('/users', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

$userList = $user->getAllUsers();





$data = [
    'userList' => $userList,
    'userRole' => $userData['role'],
    'facilitatorCoursePermissions' => $facilitatorCoursePermissions,
    'facilitatorPermissions' => $facilitatorPermissions
];

$userAdmin->index($data);