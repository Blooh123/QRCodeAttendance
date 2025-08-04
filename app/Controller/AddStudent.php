<?php

namespace Controller;
require_once '../vendor/autoload.php'; // Load PhpSpreadsheet
require_once '../app/Model/QRCode.php';
require_once '../app/Model/Student.php';
require_once '../app/Model/User.php';
session_start();
use Model\QRCode;
use Model\Student;
use Model\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Random\RandomException;

class AddStudent extends \Controller
{
    /**
     * @throws RandomException
     */
    public function index(): void
    {
        $student = new Student();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_FILES['excelFile'])) {
                $this->importFromExcel($_FILES['excelFile']);
            } else {
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && str_ends_with($_POST['email'], '@usep.edu.ph')) {
                    if ($student->checkIfEmailExists($_POST['email'])) {
                        echo "<script>alert('Email already exists. Please use another email.');</script>";
                    } else {
                        if ($student->getStudentId($_POST['student_id'])) {
                            echo "<script>alert('Student ID already exists. Please use another student ID.');</script>";
                        } else {
                            $student->insertStudent(
                                $_POST['student_id'],
                                $_POST['name'],
                                $_POST['program'],
                                $_POST['year'],
                                $_POST['email']
                            );
                            $qrcode = new QrCode();
                            $qrCode = $qrcode->generateQRCode($_POST['student_id']);
                            $qrcode->insertQRCode($qrCode, $_POST['student_id']);
                            $user = new User();
                            $user->insertUser($_POST['student_id'], $_POST['email'],$_POST['student_id'] ,'student');
                            echo "<script>alert('Added Successfully!.');</script>";
                        }
                    }
                } else {
                    // Display a pop-up error message
                    echo "<script>alert('Invalid email. Please use an email ending with @use.edu.ph.');</script>";
                }
            }
        }
        $programs = $student->getAllProgram();
        $years = $student->getAllYear();

        $this->loadViewWithData('add_student',['programs' => $programs, 'years' => $years]);
    }


    /**
     * @throws RandomException
     */
    private function importFromExcel($file): void
    {
        $student = new Student();
        $qrcode = new QrCode();
        $user = new User();

        $allowedMimeTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel'
        ];

        if (!in_array($file['type'], $allowedMimeTypes)) {
            echo "<script>alert('Invalid file type. Please upload an Excel file.');</script>";
            exit;
        }

        $spreadsheet = IOFactory::load($file['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Required headers
        $requiredHeaders = [
            'email', 'student id', 'name', 'program', 'year'
        ];

        // Get the header row (1st row)
//        $headers = [];
//        if (!empty($data[0]) && array_filter($data[0], function ($val) {
//                return $val !== null && trim((string)$val) !== '';
//            })) {
//            // Convert to lowercase safely
//            $headers = array_map('strtolower', $data[0]);
//        }





        // Check if all required headers are present
        foreach ($requiredHeaders as $required) {
            if (!in_array($required, $requiredHeaders)) {
                echo "<script>alert('Missing required column: $required');</script>";
                exit();
            }
        }



        // Get the index of each column
        $indices = array_flip($requiredHeaders);

        // Loop through each row of data starting from the second row
        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];

            $student_id = trim($row[$indices['student id']]);
            $name = $row[$indices['name']];
            $program = $row[$indices['program']];
            $year = $row[$indices['year']];
            $email = trim($row[$indices['email']]);


            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@usep.edu.ph')) {
                echo "<script>alert('Invalid email: $email. Skipping entry.');</script>";
                continue;
            }

            if ($student->getStudentId($student_id)) {
                // Update student if exists
                $student->updateStudent($student_id, $name, $program, $year, $email);
                $user->updateUser($student_id, $email);
                $qrcode->updateQrCode($student_id);
                continue;
            }

            // Insert new student
            $student->insertStudent($student_id, $name, $program, $year, $email);
            $qrCode = $qrcode->generateQRCode($student_id);
            $qrcode->insertQRCode($qrCode, $student_id);
            $user->insertUser($student_id, $email, $student_id, 'student');
        }

        echo "<script>alert('Import successful!'); </script>";
    }


}

//if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
//    $uri = str_replace('/add_student', '/login', $_SERVER['REQUEST_URI']);
//    header('Location: ' . $uri);
//}
$user = new User();
$user_de = $user->checkSession('add_student');
if ($user_de['role'] !== 'admin') {
    $uri = str_replace('/add_student', '/login', $_SERVER['REQUEST_URI']);
    header('Location: ' . $uri);
}


$addStudent = new AddStudent();
try {
    $addStudent->index();
} catch (RandomException $e) {

}