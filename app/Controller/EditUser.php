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
if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
    $uri = str_replace('/edit_user', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

$userId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = $_POST['actionType'] ?? '';

    switch ($actionType) {
        case 'saveChanges':
            $newUsername = trim($_POST['username']);
            if (empty($newUsername)) {
                header("Location: edit_user?id=$userId&error=emptyUsername");
                exit();
            }

            if ($user->updateUser($userId, $newUsername)) {
                header("Location: edit_user?id=$userId&success=1");
                exit();
            }
            break;

        case 'changePassword':
            $newPassword = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['confirmPassword']);

            if (empty($newPassword) || empty($confirmPassword)) {
                header("Location: edit_user?id=$userId&error=emptyPassword");
                exit();
            }

            if ($newPassword !== $confirmPassword) {
                header("Location: edit_user?id=$userId&error=passwordMismatch");
                exit();
            }

            if ($user->updatePassword($userId, $newPassword)) {
                header("Location: edit_user?id=$userId&success=1");
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

    header("Location: edit_user?id=$userId&error=1");
    exit();
}


$userData = $user->getUserData($_GET['id']);

$data = [
    'userData' => $userData
];

$editUser = new EditUser();
$editUser->index($data);