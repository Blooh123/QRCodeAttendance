<?php

namespace Controller;
use JetBrains\PhpStorm\NoReturn;
use Model\QRCode;
use Model\Sanction;
use Model\Student;
use Model\User;
use Model\ActivityLog;
require '../app/core/config.php';
require '../app/core/Model.php';
require '../app/Model/QRCode.php';
require '../app/Model/User.php';
require '../app/Model/Student.php';
require '../app/Model/Sanction.php';
require '../app/Model/ActivityLog.php';
class DeleteStudent
{
    use \Model;
    #[NoReturn] public function index(): void
    {
        $qrcode = new QrCode();
        $student = new Student();
        $user = new User();
        $sanction = new Sanction();
        $activityLog = new ActivityLog();
        $userData = $user->checkSession('delete_student');
        if (!empty($_GET['id'])) {
            $studentId = htmlspecialchars($_GET['id']); // Sanitize input
            $qrcode->deleteQRcode($studentId);//delete qrcode
            $this->deleteAttendanceRecord($studentId);
            $sanction->deleteSanction2($studentId);
            $student->deleteStudent($studentId);
            $user->deleteUsers($studentId);
            $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Deleted student: ' . $studentId, 'delete_student');   
        }

        // Redirect back to the home page or list view
        header("Location: " . ROOT . "adminHome?page=Students");
        exit;
    }

}

// Check permissions before allowing deletion
$user = new User();
$userData = $user->checkSession('delete_student');

// Allow admin or facilitator with manage students permission
if (!$userData || !isset($userData['role'])) {
    $uri = str_replace('/delete_student', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

if ($userData['role'] === 'admin') {
    // Admin has access
} elseif ($userData['role'] === 'Facilitator') {
    // Check if facilitator has manage students permission
    $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
    if (!in_array('manage students', $facilitatorPermissions)) {
        $uri = str_replace('/delete_student', '/login', $_SERVER['REQUEST_URI']);
        header('Location: '. $uri);
        exit();
    }
} else {
    // Neither admin nor authorized facilitator
    $uri = str_replace('/delete_student', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

$deleteStudent = new DeleteStudent();
$deleteStudent->index();