<?php

namespace Controller;
require_once '../app/Model/Student.php';
require_once '../app/Model/User.php';
use Controller;
use Model\Student;
use Model\User;
use PDO;

class Students extends Controller
{
    public function index($data){
        $this->loadViewWithData("studentsAdmin", $data);
    }


}
$studentsInstance = new Students();
$student = new Student();
// Check user session and permissions
$user = new User();
$userData = $user->checkSession('students');

// Allow admin or facilitator with manage students permission
if (!$userData || !isset($userData['role'])) {
    $uri = str_replace('/students', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

if ($userData['role'] === 'admin') {
    // Admin has access to all programs
    $programList = $student->getAllProgram();
    $yearList = $student->getAllYear();
} elseif ($userData['role'] === 'Facilitator') {
    // Check if facilitator has manage students permission
    $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
    if (!in_array('manage students', $facilitatorPermissions)) {
        $uri = str_replace('/students', '/login', $_SERVER['REQUEST_URI']);
        header('Location: '. $uri);
        exit();
    }
    
    // Filter programs based on facilitator's course permissions
    $allPrograms = $student->getAllProgram();
    $allYears = $student->getAllYear();
    
    // Get facilitator's course permissions (assuming they are stored in permissions)
    $facilitatorCoursePermissions = [];
    foreach ($facilitatorPermissions as $permission) {
        // Check if permission contains course/program information
        // Handle both formats: "course:BSIT" and direct course names like "Bachelor of Science in Information Technology"
        if (strpos($permission, 'course:') === 0) {
            $course = str_replace('course:', '', $permission);
            $facilitatorCoursePermissions[] = $course;
        } elseif (!in_array($permission, ['manage students', 'manage attendance', 'manage users'])) {
            // If it's not a management permission, assume it's a course name
            $facilitatorCoursePermissions[] = $permission;
        }
    }
    
    // If no specific course permissions, show all programs (or restrict access)
    if (empty($facilitatorCoursePermissions)) {
        // Option 1: Show all programs (current behavior)
        $programList = $allPrograms;
        $yearList = $allYears;
        // Option 2: Restrict access completely
        // $uri = str_replace('/students', '/login', $_SERVER['REQUEST_URI']);
        // header('Location: '. $uri);
        // exit();
    } else {
        // Filter programs based on facilitator's permissions
        $programList = array_filter($allPrograms, function($program) use ($facilitatorCoursePermissions) {
            return in_array($program['program'], $facilitatorCoursePermissions);
        });
        $yearList = $allYears; // Keep all years for now
    }
} else {
    // Neither admin nor authorized facilitator
    $uri = str_replace('/students', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}



//pagination stuff
//$limit = 6; // Number of users per page
//$page = isset($_POST['page1']) ? (int)$_POST['page1'] : 1;
//$offset = ($page - 1) * $limit;
//$totalUsers = $student->getUserCount();
//$totalPages = ceil($totalUsers / $limit);
//
$studentsList = '';
$numOfStudent  = $student->getUserCount();
$isFiltered = !empty($_GET['search']) || !empty($_GET['program']) || !empty($_GET['year']);

//searching stuff
$facilitatorCoursePermissions = [];
$facilitatorPermissions = [];

// Get facilitator's course permissions if they are a facilitator
if ($userData['role'] === 'Facilitator') {
    $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
    $facilitatorCoursePermissions = [];
    foreach ($facilitatorPermissions as $permission) {
        // Check if permission contains course/program information
        // Handle both formats: "course:BSIT" and direct course names like "Bachelor of Science in Information Technology"
        if (strpos($permission, 'course:') === 0) {
            $course = str_replace('course:', '', $permission);
            $facilitatorCoursePermissions[] = $course;
        } elseif (!in_array($permission, ['manage students', 'manage attendance', 'manage users'])) {
            // If it's not a management permission, assume it's a course name
            $facilitatorCoursePermissions[] = $permission;
        }
    }
}

if (!empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $studentsList = $student->searchStudents($searchQuery);
    
    // Filter search results for facilitators based on their course permissions
    if ($userData['role'] === 'Facilitator' && !empty($facilitatorCoursePermissions)) {
        $studentsList = array_filter($studentsList, function($student) use ($facilitatorCoursePermissions) {
            return in_array($student['program'], $facilitatorCoursePermissions);
        });
        $numOfStudent = count($studentsList);
    }
} else if (!empty($_GET['program']) || !empty($_GET['year'])){
    $program = $_GET['program'] ?? null;
    $year = $_GET['year'] ?? null;
    
    // For facilitators, ensure they can only filter by their permitted courses
    if ($userData['role'] === 'Facilitator' && !empty($facilitatorCoursePermissions)) {
        if ($program && !in_array($program, $facilitatorCoursePermissions)) {
            // If facilitator tries to access a course they don't have permission for, redirect
            $uri = str_replace('/students', '/login', $_SERVER['REQUEST_URI']);
            header('Location: '. $uri);
            exit();
        }
    }
    
    $studentsList = $student->getFilteredStudents($program, $year);
    $numOfStudent = $student->countFilteredStudents($program, $year);
    
    // Additional filtering for facilitators to ensure they only see students from their permitted courses
    if ($userData['role'] === 'Facilitator' && !empty($facilitatorCoursePermissions)) {
        $studentsList = array_filter($studentsList, function($student) use ($facilitatorCoursePermissions) {
            return in_array($student['program'], $facilitatorCoursePermissions);
        });
        $numOfStudent = count($studentsList);
    }
} else {
    // No search or filter - show all students or filtered by facilitator permissions
    if ($userData['role'] === 'Facilitator' && !empty($facilitatorCoursePermissions)) {
        // For facilitators, show only students from their permitted courses
        $allStudents = $student->getAllStudents();
        $studentsList = array_filter($allStudents, function($student) use ($facilitatorCoursePermissions) {
            return in_array($student['program'], $facilitatorCoursePermissions);
        });
        $numOfStudent = count($studentsList);
    }
}

$data = [
    'programList' => $programList,
    'yearList' => $yearList,
    'isFiltered' => $isFiltered,
    'studentsList' => $studentsList,
    'numOfStudent' => $numOfStudent,
    'userRole' => $userData['role'],
    'facilitatorCoursePermissions' => $facilitatorCoursePermissions,
    'facilitatorPermissions' => $facilitatorPermissions
];
$studentsInstance->index($data);