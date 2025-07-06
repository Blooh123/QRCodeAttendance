<?php

namespace Controller;
require_once '../app/Model/User.php';

use Model\User;

class AddUser extends \Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate input
            $username = $_POST['username'];
            $id = $_POST['id'];
            $password = $_POST['Password'];
            $confirmPassword = $_POST['ConfirmPassword'];
            $role = $_POST['role'];

            // Check if password matches confirmation password
            if ($password !== $confirmPassword) {
                echo "<script>alert('Password and confirmation password do not match.');</script>";
                $this->loadView('add_user');
                return;
            }

            $user = new User();

            if ($user->checkIfUserNameExists($id,$username)) {
                echo "<script>alert('Username or ID already exists.');</script>";
            }else{
                // Insert the new user
                if ($user->insertUser($id, $username, $confirmPassword,$role)) {
                    $user->insertPersonalInformation($id, $_POST['fullname'], $_POST['email']);
                    echo "<script>alert('User added successfully!');</script>";
                } else {
                    echo "<script>alert('Failed to add user.');</script>";
                }
            }

        }
        $this->loadView('add_user');
    }
}


$user1 = new User();
$userData = $user1->checkSession('add_user');
if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
    $uri = str_replace('/add_user', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

$addUser = new AddUser();
$addUser->index();