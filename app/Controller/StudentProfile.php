<?php

namespace Controller;
require_once '../app/Model/Student.php';
require_once '../app/Model/User.php';
use Model\Student;
use Model\User;

class StudentProfile extends \Controller
{
    public function studentProfile($data): void
    {
        $this->loadViewWithData('studentProfile',$data);
    }

}
$student = new Student();
$studentInfo = $student->getStudentInfo();
$uploadError = '';
$passwordMessage = '';
$studentId = $studentInfo['student_id'] ?? null;
$response = '';
// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {

    if ($studentId && $_FILES['profile_picture']['error'] == 0) {
        $imageData = file_get_contents($_FILES['profile_picture']['tmp_name']);

        if (!$student->updateProfilePicture($studentId, $imageData)) {
            $uploadError = "Failed to upload image.";
        }
    } else {
        $uploadError = "Invalid file or student ID missing.";
    }
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Fetch user password from database
    $user = new User();

    if ($user->password_verify($currentPassword, $studentId)) {
        if ($newPassword === $confirmPassword) {
            $user->updatePassword($studentId, $newPassword);
            $response = "✅ Password changed successfully!";
        } else {
            $response = "❌ New passwords do not match.";
        }
    } else {
        $response = "❌ Incorrect current password.";
    }
}


$data = [
    'studentInfo' => $studentInfo,
    'uploadError' => $uploadError,
    'Message' => $response,


];

$studentProfile = new StudentProfile();
$studentProfile->studentProfile($data);