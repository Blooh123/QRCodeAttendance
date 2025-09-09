<?php

namespace Controller;
require_once "../app/core/init.php";
require_once "../app/core/Database.php";
require_once "../app/Model/User.php";
require_once "../app/Model/ActivityLog.php";
use Model\User;
use Database;
use Model\ActivityLog;

class BackupPage extends \Controller
{
    use Database;
    
    private $userData;

    public function __construct()
    {
        // Check if user is logged in and is admin
        $user = new User();
        $this->userData = $user->checkSession('backup-page');
        if (!isset($this->userData['user_id']) || $this->userData['role'] !== 'admin') {
            header("Location: " . ROOT . "login");
            exit();
        }
    }

    public function index()
    {
        $this->loadView('backupPage');
    }
}

$backupPage = new BackupPage();
$backupPage->index();
?>
