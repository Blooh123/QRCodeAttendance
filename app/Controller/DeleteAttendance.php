<?php

namespace Controller;
require "../app/Model/Attendances.php";
require "../app/Model/User.php";
use Model\Attendances;
use Model\User;

class DeleteAttendance
{

    public function deleteAttendance(): void
    {
        $attendance = new Attendances();
        echo $_GET['id'];
        if (!empty($_GET['id'])) {
            $attendanceID = htmlspecialchars($_GET['id']); // Sanitize input
            $attendance->deleteAttendance($attendanceID);
            $uri = str_replace('/delete_attendance', '/adminHome?page=Attendance', $_SERVER['REQUEST_URI']);
            header('Location: ' . $uri);

        }
    }
}

// Check permissions before allowing deletion
$user = new User();
$userData = $user->checkSession('delete_attendance');

// Allow admin or facilitator with manage attendance permission
if (!$userData || !isset($userData['role'])) {
    $uri = str_replace('/delete_attendance', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

if ($userData['role'] === 'admin') {
    // Admin has access
} elseif ($userData['role'] === 'Facilitator') {
    // Check if facilitator has manage attendance permission
    $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
    if (!in_array('manage attendance', $facilitatorPermissions)) {
        $uri = str_replace('/delete_attendance', '/login', $_SERVER['REQUEST_URI']);
        header('Location: '. $uri);
        exit();
    }
} else {
    // Neither admin nor authorized facilitator
    $uri = str_replace('/delete_attendance', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

$delete_attendance = new DeleteAttendance();
$delete_attendance->deleteAttendance();