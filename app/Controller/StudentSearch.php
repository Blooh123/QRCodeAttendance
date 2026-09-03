<?php

namespace Controller;

require_once '../app/Model/Student.php';
require_once '../app/Model/User.php';

use Model\Student;
use Model\User;

$user = new User();
$userData = $user->checkSession('students');

if (!$userData || !isset($userData['role'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$searchQuery = trim($_GET['search'] ?? '');
if ($searchQuery === '') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'students' => []]);
    exit();
}

$studentModel = new Student();
$students = $studentModel->searchStudents($searchQuery);

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
