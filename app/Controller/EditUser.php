<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/Model/User.php';
use Controller;
use Model\User;

class EditUser extends Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('edit_user', $data);
    }
}

$user = new User();
$userData = $user->checkSession('edit_user');

// Allow admin or facilitator with manage users permission
if (!$userData || !isset($userData['role'])) {
    $uri = str_replace('/edit_user', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

if ($userData['role'] === 'admin') {
    // Admin has access
} elseif ($userData['role'] === 'Facilitator') {
    // Check if facilitator has manage users permission
    $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
    if (!in_array('manage users', $facilitatorPermissions)) {
        $uri = str_replace('/edit_user', '/login', $_SERVER['REQUEST_URI']);
        header('Location: '. $uri);
        exit();
    }
} else {
    // Neither admin nor authorized facilitator
    $uri = str_replace('/edit_user', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

// Get user_id from GET parameter
$userId = $_GET['user_id'] ?? null;

if (!$userId) {
    header('Location: ' . ROOT . 'adminHome?page=Users&error=missing_user_id');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = $_POST['actionType'] ?? '';

    switch ($actionType) {
        case 'saveChanges':
            $newUsername = trim($_POST['username']);
            $newName = trim($_POST['name']);
            $newEmail = trim($_POST['email']);
            
            if (empty($newUsername) || empty($newName) || empty($newEmail)) {
                header("Location: edit_user?user_id=$userId&error=emptyFields");
                exit();
            }

            // Update username
            $usernameUpdated = $user->updateUser($userId, $newUsername);
            
            // Update personal information
            $personalInfoUpdated = $user->updatePersonalInfo($userId, $newName, $newEmail);

            
            if ($usernameUpdated && $personalInfoUpdated) {
                header("Location: edit_user?user_id=$userId&success=1");
                exit();
            }
            break;

        case 'changePassword':
            $newPassword = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['confirmPassword']);

            if (empty($newPassword) || empty($confirmPassword)) {
                header("Location: edit_user?user_id=$userId&error=emptyPassword");
                exit();
            }

            if ($newPassword !== $confirmPassword) {
                header("Location: edit_user?user_id=$userId&error=passwordMismatch");
                exit();
            }

            if ($user->updatePassword($userId, $newPassword)) {
                header("Location: edit_user?user_id=$userId&success=1");
                exit();
            }
            break;

        case 'deleteUser':
            if ($user->deleteUsers($userId)) {
                header("Location: adminHome?page=Users&userDeleted=1");
                exit();
            }
            break;
    }

    header("Location: edit_user?user_id=$userId&error=1");
    exit();
}

// Get user data
$userData = $user->getUserDataWithPersonalInfo($userId);

// Check if user exists
if (!$userData) {
    header('Location: ' . ROOT . 'adminHome?page=Users&error=user_not_found');
    exit();
}

$userSession = $user->getUserSession($userId);
$facialImages = $user->getFacialImages($userId);
$userPermissions = $user->getUserPermissions($userId);

$data = [
    'userData' => $userData,
    'userSession' => $userSession,
    'facialImages' => $facialImages,
    'userPermissions' => $userPermissions
];

$editUser = new EditUser();
$editUser->index($data);