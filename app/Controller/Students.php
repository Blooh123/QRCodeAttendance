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

    if (!empty($_GET['search'])) {
        $searchQuery = $_GET['search'];
        $studentsList = $student->searchStudents($searchQuery);
    } else if (!empty($_GET['program']) || !empty($_GET['year'])){
        $program = $_GET['program'] ?? null;
        $year = $_GET['year'] ?? null;
        $studentsList = $student->getFilteredStudents($program, $year);
        $numOfStudent =$student->countFilteredStudents($program, $year);

    }

$data = [
    'programList' => $programList,
    'yearList' => $yearList,
    'isFiltered' => $isFiltered,
    'studentsList' => $studentsList,
    'numOfStudent' => $numOfStudent
];
$studentsInstance->index($data);