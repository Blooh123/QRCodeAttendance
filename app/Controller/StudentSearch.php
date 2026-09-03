<?php

namespace Controller;

require_once '../app/Model/Student.php';
require_once '../app/Model/User.php';
require_once '../app/Model/ExcuseApplication.php';

use Model\Student;
use Model\User;
use Model\ExcuseApplication;

$user = new User();
$userData = $user->checkSession('students');

if (!$userData || !isset($userData['role'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$searchQuery = trim($_GET['search'] ?? '');
$attenId = trim($_GET['atten_id'] ?? '');

if ($attenId !== '') {
    try {
        $students = (new ExcuseApplication())->searchStudentsForEvent($attenId, $searchQuery);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'students' => $students]);
    } catch (\Throwable $exception) {
        error_log('Event student search failed: ' . $exception->getMessage());
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Event student search is temporarily unavailable.']);
    }
    exit();
}

if ($searchQuery === '') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'students' => []]);
    exit();
}

$studentModel = new Student();
try {
    $students = $studentModel->searchStudents($searchQuery);
} catch (\Throwable $exception) {
    error_log('Student search failed: ' . $exception->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Student search is temporarily unavailable.']);
    exit();
}

if ($userData['role'] === 'Facilitator') {
    $permissions = $user->getUserPermissions($userData['user_id']);
    $coursePermissions = [];
    foreach ($permissions as $permission) {
        if (strpos($permission, 'course:') === 0) {
            $coursePermissions[] = str_replace('course:', '', $permission);
        } elseif (!in_array($permission, ['manage students', 'manage attendance', 'manage users'])) {
            $coursePermissions[] = $permission;
        }
    }

    if (!empty($coursePermissions)) {
        $students = array_values(array_filter($students, function ($student) use ($coursePermissions) {
            return in_array($student['program'], $coursePermissions, true);
        }));
    }
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'students' => array_values($students)]);
