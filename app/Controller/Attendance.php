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
            
        } else {
            // Neither admin nor authorized facilitator
            return [
                'error' => 'unauthorized',
                'redirect' => str_replace('/attendance', '/login', $_SERVER['REQUEST_URI'])
            ];
        }

        $attendance = new Attendances();
        $attendanceList = $attendance->getAllAttendance();


        if (!empty($_GET['search'])){
            $searchResults = $attendance->searchAttendance($_GET['search']);
            

            
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