<?php

namespace Controller;
require "../app/Model/Attendances.php";
require "../app/Model/User.php";
require_once '../app/core/Controller.php';
use Controller;
use Model\Attendances;
use Model\User;

class Attendance extends Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('attendanceAdmin', $data);
    }
}


// Check user session and permissions
$user = new User();
$userData = $user->checkSession('attendance');

// Allow admin or facilitator with manage attendance permission
if (!$userData || !isset($userData['role'])) {
    $uri = str_replace('/attendance', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}
$facilitatorPermissions = [];

if ($userData['role'] === 'admin') {
    // Admin has access to all attendance records
    $facilitatorCoursePermissions = [];
} elseif ($userData['role'] === 'Facilitator') {
    // Check if facilitator has manage attendance permission
    $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
    if (!in_array('manage attendance', $facilitatorPermissions)) {
        $uri = str_replace('/attendance', '/login', $_SERVER['REQUEST_URI']);
        header('Location: '. $uri);
        exit();
    }
    
    // Get facilitator's course permissions
    $facilitatorCoursePermissions = [];
    foreach ($facilitatorPermissions as $permission) {
        if (strpos($permission, 'course:') === 0) {
            $course = str_replace('course:', '', $permission);
            $facilitatorCoursePermissions[] = $course;
        }
    }
} else {
    // Neither admin nor authorized facilitator
    $uri = str_replace('/attendance', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

$attendance = new Attendances();
$attendanceList = $attendance->getAllAttendance();

// Filter attendance records for facilitators based on their course permissions
if ($userData['role'] === 'Facilitator' && !empty($facilitatorCoursePermissions)) {
    // This assumes attendance records have program/course information
    // You may need to modify the attendance model to include this filtering
    $attendanceList = array_filter($attendanceList, function($record) use ($facilitatorCoursePermissions) {
        // Adjust this based on your attendance record structure
        return isset($record['program']) && in_array($record['program'], $facilitatorCoursePermissions);
    });
}

if (!empty($_GET['search'])){
    $searchResults = $attendance->searchAttendance($_GET['search']);
    
    // Filter search results for facilitators
    if ($userData['role'] === 'Facilitator' && !empty($facilitatorCoursePermissions)) {
        $searchResults = array_filter($searchResults, function($record) use ($facilitatorCoursePermissions) {
            return isset($record['program']) && in_array($record['program'], $facilitatorCoursePermissions);
        });
    }
    
    $attendanceList = $searchResults;
}

$data = [
    'attendanceList' => $attendanceList,
    'userRole' => $userData['role'],
    'facilitatorCoursePermissions' => $facilitatorCoursePermissions,
    'facilitatorPermissions' => $facilitatorPermissions
];

$attendanceInstance = new Attendance();
$attendanceInstance->index($data);