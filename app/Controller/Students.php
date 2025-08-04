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

// Check if there are any filters applied
$isFiltered = !empty($_GET['search']) || !empty($_GET['program']) || !empty($_GET['year']);

// Initialize studentsList with all students for JavaScript processing
$studentsList = $allStudents;

$data = [
    'programList' => $programList,
    'yearList' => $yearList,
    'allStudents' => $allStudents,
    'studentsList' => $studentsList,
    'numOfStudent' => $numOfStudent,
    'isFiltered' => $isFiltered
];

$studentsInstance->index($data);