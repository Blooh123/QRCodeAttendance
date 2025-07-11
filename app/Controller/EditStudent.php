<?php

namespace Controller;
require_once "../app/Model/Student.php";
require_once '../app/Model/User.php';
require_once '../app/Model/Sanction.php';
require_once '../app/core/config.php';


use DateTime;
use DateTimeZone;
use Model\Sanction;
use Model\Student;
use Model\User;

class EditStudent extends \Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('edit_student',$data);
    }
}

$user = new User();
$userData = $user->checkSession('edit_student');
if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
    $uri = str_replace('/edit_student', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

$student = new Student();
$sanction = new Sanction();
$studentData = $student->getStudentData($_GET['id']);
$sanctionList = $sanction->getStudentSanctions($_GET['id']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';

    if ($formType === 'apply_sanction') {
        if (!empty($_POST['sanctionH']) && isset($_POST['reason'])) {
            $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
            $formattedTime = $date->format('Y-m-d H:i:s');
            $sanction->insertSanction($_POST['id'], $_POST['reason'], $_POST['sanctionH'], $formattedTime);
            header("Location: " . "edit_student?id=".$_GET['id'] . "&removed=2");
        }
    }

    if ($formType === 'update_student') {
        if (!empty($_POST['id'])) {
            $student->updateStudent($_POST['id'], $_POST['name'], $_POST['program'], $_POST['acad_year'], $_POST['email']);
            $user->updateUser($_POST['id'], $_POST['email']);
            header("Location: " . "edit_student?id=".$_GET['id'] . "&removed=2");
        }
    }

    // Refresh the data
    $studentData = $student->getStudentData($_GET['id']);
    $sanctionList = $sanction->getStudentSanctions($_GET['id']);
}


$data = [
    'studentData' => $studentData,
    'sanctionList' => $sanctionList,
];
$editStudent = new EditStudent();
$editStudent->index($data);