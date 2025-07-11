<?php

namespace Controller;

require_once '../app/core/Database.php';
require_once "../app/Model/Attendances.php";
require_once "../app/Model/Student.php";
require_once '../app/Model/User.php';
require_once '../app/Model/ActivityLog.php';

use Model\ActivityLog;
use Model\Attendances;
use Model\Student;
use Model\User;

session_start();

class EditAttendance extends \Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('edit_attendance', $data);
    }
}

$user = new User();
$user_de = $user->checkSession('edit_attendance');

// Redirect if not an admin
if (!$user_de || !isset($user_de['role']) || $user_de['role'] !== 'admin') {
    $uri = str_replace('/edit_attendance', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

// Ensure `id` and `eventName` are provided in the URL
if (!isset($_GET['id']) || !isset($_GET['eventName'])) {
    header('Location: /404');
    exit();
}

$student = new Student();
$attendances = new Attendances();
$attendanceDetails = $attendances->getAttendanceDetails($_GET['id'], $_GET['eventName']);

$activityLog = new ActivityLog();
$activityListLog = $activityLog->getActivityLogForUser($_GET['eventName']);

$buttonLabel = '';
$buttonClass = '';
$buttonAction = '';

if ($attendanceDetails['atten_status'] === 'not started') {
    $buttonLabel = 'Start Attendances';
    $buttonClass = 'bg-green-600 hover:bg-green-700 focus:ring-green-300';
    $buttonAction = 'start';
} elseif ($attendanceDetails['atten_status'] === 'on going') {
    $buttonLabel = 'Stop Attendances';
    $buttonClass = 'bg-red-600 hover:bg-red-700 focus:ring-red-300';
    $buttonAction = 'stopped';
} elseif ($attendanceDetails['atten_status'] === 'stopped') {
    $buttonLabel = 'Continue Attendances';
    $buttonClass = 'bg-green-600 hover:bg-green-700 focus:ring-green-300';
    $buttonAction = 'continue';
}elseif ($attendanceDetails['atten_status'] === 'finished') {
    $buttonLabel = 'Continue Attendances';
    $buttonClass = 'hidden';
    $buttonAction = 'finished';
}

// Get required attendees from the new required_attendees table
$requiredAttendeesData = $attendances->getRequiredAttendees($_GET['id']);

// Separate programs and years into arrays for the view
$requiredAttendees = [];
$acad_year = [];

foreach ($requiredAttendeesData as $requirement) {
    $requiredAttendees[] = $requirement['program'];
    $acad_year[] = $requirement['acad_year'];
}

$year = $student->getAllYear();
$programList = $student->getAllProgram();

$bannerBase64 = null;
if (!empty($attendanceDetails['atten_id'])) {
    $bannerBase64 = $attendances->getBannerAsBase64($attendanceDetails['atten_id']);
}

$data = [
    'buttonLabel' => $buttonLabel,
    'buttonClass' => $buttonClass,
    'buttonAction' => $buttonAction,
    'attendanceDetails' => $attendanceDetails,
    'requiredAttendees' => $requiredAttendees,
    'acad_year' => $acad_year,
    'year' => $year,
    'programList' => $programList,
    'activityListLog' => $activityListLog,
    'bannerBase64' => $bannerBase64
];

$editAttendance = new EditAttendance();
$editAttendance->index($data);
