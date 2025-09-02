<?php

namespace Controller;

require_once '../app/core/config.php';
require_once '../app/core/Database.php';
require_once '../app/Model/ActivityLog.php';
require_once '../app/Model/User.php';
use Model\ActivityLog;
use Model\User;
class Logout2
{
    use \Database;
    public function updateStatus($userId, $status): void
    {
        $query = "UPDATE users SET state = ? WHERE id = ?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$status, $userId]);

    }

    public function index(): void
    {
            $userId = $_GET['sessionID'];
            // Update user status to 'offline'
            $this->updateStatus($_GET['user_id'], 'offline');
            $activityLog = new ActivityLog();
            $user = new User();
            $userData = $user->checkSession('delete_user_session');
            $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Logged out from session: ' . $userId, 'delete_user_session');  
            // Delete this session from the database
            $stmt = $this->connect()->prepare("DELETE FROM user_sessions WHERE id = ?");
            $stmt->execute([$userId]);
            header('Location: ' . ROOT . 'adminHome?page=Users');
    }

}

$logout2 = new Logout2();
$logout2->index();