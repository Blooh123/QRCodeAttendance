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

    public function handleAttendanceRequest(): array
    {
        // Check user session and permissions
        $user = new User();
        $userData = $user->checkSession('attendance');

        // Allow admin or facilitator with manage attendance permission
        if (!$userData || !isset($userData['role'])) {
            // Return error data instead of redirecting
            return [
                'error' => 'unauthorized',
                'redirect' => str_replace('/attendance', '/login', $_SERVER['REQUEST_URI'])
            ];
        }

        $facilitatorPermissions = [];

        if ($userData['role'] === 'admin') {
            // Admin has access to all attendance records
            $facilitatorCoursePermissions = [];
        } elseif ($userData['role'] === 'Facilitator') {
            // Check if facilitator has manage attendance permission
            $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
            if (!in_array('manage attendance', $facilitatorPermissions)) {
                // Return error data instead of redirecting
                return [
                    'error' => 'unauthorized',
                    'redirect' => str_replace('/attendance', '/login', $_SERVER['REQUEST_URI'])
                ];
            }
            
            // Get facilitator's course permissions
            $facilitatorCoursePermissions = [];
            foreach ($facilitatorPermissions as $permission) {
                // Handle both formats: "course:BSIT" and direct course names like "Bachelor of Science in Information Technology"
                if (strpos($permission, 'course:') === 0) {
                    $course = str_replace('course:', '', $permission);
                    $facilitatorCoursePermissions[] = $course;
                } elseif (!in_array($permission, ['manage students', 'manage attendance', 'manage users'])) {
                    // If it's not a management permission, assume it's a course name
                    $facilitatorCoursePermissions[] = $permission;
                }
            }
        } else {
            // Neither admin nor authorized facilitator
            return [
                'error' => 'unauthorized',
                'redirect' => str_replace('/attendance', '/login', $_SERVER['REQUEST_URI'])
            ];
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

        return [
            'attendanceList' => $attendanceList,
            'userRole' => $userData['role'],
            'facilitatorCoursePermissions' => $facilitatorCoursePermissions,
            'facilitatorPermissions' => $facilitatorPermissions
        ];
    }
}

// Handle the request and check for errors
$attendanceInstance = new Attendance();
$result = $attendanceInstance->handleAttendanceRequest();

if (isset($result['error']) && $result['error'] === 'unauthorized') {
    // Use JavaScript redirect instead of header redirect
    echo "<script>window.location.href = '" . htmlspecialchars($result['redirect']) . "';</script>";
    exit();
}

$attendanceInstance->index($result);