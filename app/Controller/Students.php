<?php

namespace Controller;
require_once '../app/core/Model.php';
require_once '../app/Model/Student.php';
use Controller;
use Model;
use Model\Student;
use PDO;

class Students extends Controller
{
    use Model;
    public function index($data){
        $this->loadViewWithData("studentsAdmin", $data);
    }
}

$studentsInstance = new Students();
$student = new Student();
$programList = $student->getAllProgram();
$yearList = $student->getAllYear();

// Load all students data for JavaScript-based filtering
$allStudents = $student->getAllStudents();
$numOfStudent = count($allStudents);

$data = [
    'programList' => $programList,
    'yearList' => $yearList,
    'allStudents' => $allStudents,
    'numOfStudent' => $numOfStudent
];

$studentsInstance->index($data);