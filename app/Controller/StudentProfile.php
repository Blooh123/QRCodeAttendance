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
$uploadMessage = '';
$passwordMessage = '';
$studentId = $studentInfo['student_id'] ?? null;
$response = '';
$profileUploadAllowed = in_array((string) $studentId, ['2023-00274', '2023-00006'], true);
// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {

    $uploadedFile = $_FILES['profile_picture'];
    if (!$profileUploadAllowed) {
        $uploadError = "Profile picture upload is not available for this student.";
    } elseif ($uploadedFile['error'] !== UPLOAD_ERR_OK || $uploadedFile['size'] > 5 * 1024 * 1024) {
        $uploadError = "Please select an image smaller than 5 MB.";
    } elseif (@getimagesize($uploadedFile['tmp_name']) === false) {
        $uploadError = "The selected file is not a valid image.";
    } else {
        $imageData = file_get_contents($uploadedFile['tmp_name']);

        if ($imageData === false || !$student->updateProfilePicture($studentId, $imageData)) {
            $uploadError = "Failed to upload image.";
        } else {
            $uploadMessage = "Profile picture uploaded successfully.";
        }
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
    'uploadMessage' => $uploadMessage,
    'profileUploadAllowed' => $profileUploadAllowed,
    'Message' => $response,


];

$studentProfile = new StudentProfile();
$studentProfile->studentProfile($data);