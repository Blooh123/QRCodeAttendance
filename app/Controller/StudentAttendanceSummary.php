<?php

namespace Controller;
require_once '../app/Model/Sanction.php';
require_once '../app/Model/Attendances.php';
require_once '../app/Model/User.php';
require_once '../app/Model/Student.php';

use Model\Sanction;
use Model\Attendances;
use Model\User;
use Model\Student;

class StudentAttendanceSummary extends \Controller
{
  

    public function index(): void
    {
        $sanction = new Sanction();
        $attendance = new Attendances();
        $student = new Student();
    
    
        $userID = $_GET['student_id'];
        // Accept a year start for academic year filtering (e.g. 2025 => AY 2025-2026)
        $yearStart = isset($_GET['year']) && ctype_digit($_GET['year']) ? (int)$_GET['year'] : null;
        $sanctionList = $sanction->getSanctionSummary($userID, $yearStart);
        $attendanceRecord = $attendance->StudentAttendanceRecord($userID, $yearStart);
        $studentInfo = $student->getStudentData($userID);
        $notAttended = $attendance->getNotAttendedEvents($userID, $yearStart);

        if(isset($_POST['id']) && isset($_POST['studentID'])){
            $sanction->deleteSanction($_POST['id']);
            // Redirect to refresh the page and show updated data
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        }

        // Pass the raw 'year' GET param to the view so we can distinguish between
        // (a) no 'year' param provided => default to 2025 (selected in UI)
        // (b) 'year' param present and empty string => user selected 'All'
        $selectedYearForView = array_key_exists('year', $_GET) ? $_GET['year'] : '2025';

        $data = [
            'sanctionList' => $sanctionList,
            'attendanceRecord' => $attendanceRecord,
            'studentInfo' => $studentInfo,
            'notAttended' => $notAttended,
            'selectedYear' => $selectedYearForView
        ];
        $this->loadViewWithData('student_attendance_summary', $data);
    }
    
}
$user = new User();
$userData = $user->checkSession('sanctions_summary');
if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
    $uri = str_replace('/sanctions_summary', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

$studentAttendanceSummary = new StudentAttendanceSummary();
$studentAttendanceSummary->index();

