<?php

namespace Controller;
use JetBrains\PhpStorm\NoReturn;

session_start();

require_once '../app/core/config.php';
require_once '../app/core/Database.php';
require_once '../app/Model/ActivityLog.php';
require_once '../app/Model/User.php';
use Model\ActivityLog;
use Model\User;

class Logout
{
    use \Database;

    public function updateStatus($userId, $status): void
    {
        $query = "UPDATE users SET state = ? WHERE id = ?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$status, $userId]);

    }

    #[NoReturn] function logout(): void
    {
         if (isset($_COOKIE['user_data'])) {
            // Decode JSON cookie (handle multiple users)
            $userSessions = json_decode($_COOKIE['user_data'], true);

            if (!is_array($userSessions) || empty($userSessions)) {
                // No valid session found, clear cookies and redirect
                $this->clearSessionAndRedirect();
            }


             foreach ($userSessions as $index => $session) {
                 if (!isset($session['auth_token'], $session['user_id'])) {
                     continue; // Skip invalid entries
                 }

                 $token = $session['auth_token'];
                 $userId = $session['user_id'];
                 $role = $session['role'];

                 // Check if this session exists in the database
                 $stmt = $this->connect()->prepare("SELECT user_id FROM user_sessions WHERE token = ?");
                 $stmt->execute([$token]);
                 $user = $stmt->fetch();

                 if ($user) {
                     // Update user status to 'offline'
                     $this->updateStatus($userId, 'offline');
                     $activityLog = new ActivityLog();
                     $activityLog->createActivityLog($userId, $role, 'Logged out from session: ' . $userId, 'logout');
                     // Delete this session from the database
                     $stmt = $this->connect()->prepare("DELETE FROM user_sessions WHERE token = ?");
                     $stmt->execute([$token]);

                     // Remove this user from the array
                     unset($userSessions[$index]);
                 }
             }


// Clear session and redirect **after processing all users**
             $this->clearSessionAndRedirect();

         } else {
            $this->clearSessionAndRedirect();
        }
    }

    #[NoReturn] private function clearSessionAndRedirect(): void
    {
        if (isset($_COOKIE['user_data'])) {
            unset($_COOKIE['user_data']);
            setcookie('user_data', '', time() - 3600, '/', '', isset($_SERVER["HTTPS"]), true);
        }

        if (isset($_COOKIE['auth_token'])) {
            unset($_COOKIE['auth_token']);
            setcookie('auth_token', '', time() - 3600, '/', '', isset($_SERVER["HTTPS"]), true);
        }

        // Destroy session properly
        session_unset();  // Unset all session variables
        session_destroy(); // Destroy the session
        session_write_close(); // Ensure changes are written
        setcookie(session_name(), '', time() - 3600, '/', '', isset($_SERVER["HTTPS"]), true);

        // Redirect to login
        header('Location: ' . ROOT . 'login');
        exit();
    }

}

$logout = new Logout();
$logout->logout();
