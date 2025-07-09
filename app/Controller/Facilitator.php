<?php

namespace Controller;
require '../app/core/Model.php';
require_once '../app/core/imageConfig.php';
require '../app/Model/Attendances.php';
require '../app/Model/Student.php';
require '../app/Model/User.php';
require '../app/Model/ActivityLog.php';


use DateTime;
use Model\ActivityLog;
use Model\Attendances;
use Model\Student;
use Model\User;

session_start();
class Facilitator extends \Controller
{
    public function index($data): void
    {



        $this->loadViewWithData('facilitatorHome', $data);
    }
}
$user = new User();
$userData = $user->checkSession('facilitator');

if (!$userData || !isset($userData['role']) || $userData['role'] !== 'Facilitator') {
    $uri = str_replace('/facilitator', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}
$userSessions = json_decode($_COOKIE['user_data'], true);

// Ensure it's a valid array and contains sessions
if (!is_array($userSessions) || empty($userSessions)) {
    header('Location: /logout');
    exit();
}

// Iterate through each session stored in the cookie
foreach ($userSessions as $session) {
    $_SESSION['role'] = $session['role'];
    $_SESSION['username'] = $session['username'];
    $_SESSION['user_id'] = $session['user_id'];
}


$student = new Student();
$programList = $student->getAllProgram();

$attendances = new Attendances();
$attendanceList = $attendances->getAllAttendance();
$attendanceRecordList = [];

$activityLog = new ActivityLog();



$EventName = 'No Event';
$EventDate = 'No Date';
$EventTime = 'No Time';
$EventID = '';
$selectedProgram = [];
$EventLocation = 'No Location';

foreach ($attendanceList as $attendance) {
    if ($attendance['atten_status'] == 'on going') {
        $EventName = $attendance['event_name'];

        try {
            $dateTime = new DateTime($attendance['atten_started']);
        } catch (\DateMalformedStringException $e) {

        }
        
        // Get required attendees from the new required_attendees table
        $requiredAttendeesData = $attendances->getRequiredAttendees($attendance['atten_id']);
        $selectedProgram = [];
        foreach ($requiredAttendeesData as $requirement) {
            $selectedProgram[] = $requirement['program'];
        }
        
        $EventDate = $dateTime->format('F j, Y'); // Example: January 31, 2025
        $EventTime = $dateTime->format('h:i A');  // Example: 09:01 PM
        $EventID = $attendance['atten_id'];

        break;
    }
}
$activityLogList = $activityLog->getActivityLogForFaci($_SESSION['user_id'], $EventName);
$_SESSION['evnt_name'] = $EventName;


    if (!empty($_GET['student'])) {
        foreach ($attendanceList as $attendance) {
            if ($attendance['atten_status'] == 'on going') {
                $EventName = $attendance['event_name'];

                // Convert the date string to a formatted date and time
                try {
                    $dateTime = new DateTime($attendance['atten_started']);
                } catch (\DateMalformedStringException $e) {

                }
                
                // Get required attendees from the new required_attendees table
                $requiredAttendeesData = $attendances->getRequiredAttendees($attendance['atten_id']);
                $selectedProgram = [];
                foreach ($requiredAttendeesData as $requirement) {
                    $selectedProgram[] = $requirement['program'];
                }
                
                $EventDate = $dateTime->format('F j, Y'); // Example: January 31, 2025
                $EventTime = $dateTime->format('h:i A');  // Example: 09:01 PM
                $EventID = $attendance['atten_id'];

                // Only fetch attendance records if a program is selected
                if (!empty($selectedProgram)) {
                    $attendanceRecordList = $attendances->getAttendanceRecord($selectedProgram, $EventID, '%'.$_GET['student'].'%');
                } else {
                    $attendanceRecordList = $attendances->getAttendanceRecord('AllStudents', $EventID,'%'.$_GET['student'].'%');
                }
                unset($_POST['student']);
                break;
            }
        }
    }

$attendance = new Attendances();
$attendanceList2 = $attendance->getAllAttendance();

$data = [
    'attendanceList' => $attendanceList,
    'EventName' => $EventName,
    'EventDate' => $EventDate,
    'EventTime' => $EventTime,
    'EventLocation' => $EventLocation,
    'attendanceRecordList' => $attendanceRecordList,
    'programList' => $programList,
    'selectedProgram' => $selectedProgram,
    'EventID' => $EventID,
    'activityLogList' => $activityLogList,
    'attendanceList2' => $attendanceList2
];

$facilitator = new Facilitator();
$facilitator->index($data);