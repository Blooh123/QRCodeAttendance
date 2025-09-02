<?php

namespace Controller;
require_once "../app/Model/Attendances.php";
require_once "../app/Model/ActivityLog.php";
require_once "../app/Model/User.php";
use Controller;
use Model\ActivityLog;
use Model\Attendances;
use Model\User;
session_start();
class DeleteAttendanceRecord extends Controller
{
    public function index(){
        $attendance = new Attendances();
        $activityLog = new ActivityLog();
        $user = new User();
        $userData = $user->checkSession('delete_attendance_record');
        $attendance->deleteAttendanceRecord($_GET['atten_id'], $_GET['student_id']);
        $activityLog->createActivityLog($userData['user_id'], $userData['role'],$userData['username'] .'<p style="color:red;"> Deleted attendance record: <p>'. $_GET['student_id'] . ' for event: ' . $_SESSION['evnt_name'], 'delete_attendance_record');
    }
}

$deleteAttendanceRecord = new DeleteAttendanceRecord();
$deleteAttendanceRecord->index();