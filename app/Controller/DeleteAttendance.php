<?php

namespace Controller;
require "../app/Model/Attendances.php";
use Model\Attendances;

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

$delete_attendance = new DeleteAttendance();
$delete_attendance->deleteAttendance();