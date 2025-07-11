<?php

namespace Controller;
require_once "../app/Model/User.php";
require_once "../app/Model/Student.php";
require_once "../app/Model/Attendances.php";
use Controller;
use Model\Attendances;
use Model\Student;
use Model\User;

class ViewAttendanceRecord extends Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('view_attendance_record',$data);
    }
}

$user = new User();
$userRole = ['admin','Facilitator'];
$user_de = $user->checkSession('add_attendance');
if (!in_array($user_de['role'],$userRole)) {
    $uri = str_replace('/view_records', '/404', $_SERVER['REQUEST_URI']);
    header('Location: ' . $uri);
}
$attendance = new Attendances();
$student = new Student();

$attendanceDetails = $attendance->getAttendanceDetails($_GET['id'], $_GET['eventName']);
$EventName = $attendanceDetails['event_name'];
$EventID = $attendanceDetails['atten_id'];

// Get required attendees using the proper function
$requiredAttendees = $attendance->getRequiredAttendees($EventID);
$requireProgram = $requiredAttendees;

$totalStudents = $student->getUserCount();
$attendedCount = $attendance->countAttendanceRecord($attendanceDetails['atten_id']);

$year = $student->getAllYear();
$programList = $student->getAllProgram();
$attendanceList = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['year']) && isset($_POST['program']) && !empty($_POST['year']) && !empty($_POST['program'])) {
        // Filter by program and year
        $attendanceList = $attendance->AttendanceRecord($_POST['program'], $_POST['year'], $_GET['id']);
    } elseif (isset($_POST['search']) && !empty($_POST['search'])) {
        // Search functionality - use required attendees from database
        $attendanceList = $attendance->getAttendanceRecord($requireProgram, $_GET['id'], $_POST['search']);
    } else {
        // Default: show all students for the event
        $attendanceList = $attendance->getAttendanceRecord($requireProgram, $_GET['id'], '');
    }
} elseif (isset($_GET['view']) && $_GET['view'] === 'not_attended') {
    // Get students who did NOT attend
    if (isset($_GET['program']) && isset($_GET['year']) && !empty($_GET['program']) && !empty($_GET['year'])) {
        $attendanceList = $attendance->getStudentsWhoDidNotAttend($EventID, $_GET['program'], $_GET['year']);
    } else {
        // If no program/year specified, show empty list or handle appropriately
        $attendanceList = [];
    }
} else {
    // Default: show students based on required attendees when page loads
    $attendanceList = $attendance->getAttendanceRecord($requireProgram, $_GET['id'], '');
}

$data = [
    'year' => $year,
    'programList' => $programList,
    'attendanceList' => $attendanceList,
    'EventName' => $EventName,
    'EventID' => $EventID,
    'totalStudents' =>  $totalStudents,
    'attendedCount' => $attendedCount
];

$viewAttendanceRecord = new ViewAttendanceRecord();
$viewAttendanceRecord->index($data);