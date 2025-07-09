<?php

namespace Controller;
require_once "../app/Model/Student.php";
require_once "../app/Model/User.php";
require_once "../app/Model/Attendances.php";
use Controller;
use Model\Attendances;
use Model\Student;
use Model\User;

session_start();
class AddAttendance extends Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('add_attendance',$data);
    }
}
$attendance = new Attendances();
$user = new User();
$user_de = $user->checkSession('add_attendance');
if ($user_de['role'] !== 'admin') {
    $uri = str_replace('/add_attendance', '/login', $_SERVER['REQUEST_URI']);
    header('Location: ' . $uri);
}
$student = new Student();
$program = $student->getAllProgram();
$year = $student->getAllYear();

$data = [
    'programs' => $program,
    'years' => $year
];
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!$attendance->getAttendanceDetails(0,$_POST['eventName'])){

        // Get arrays as received from form - no filtering or deduplication
        $programs = $_POST['program'] ?? [];
        $years = $_POST['year'] ?? [];
        $requiredAttendanceRecord = $_POST['required_attendance'] ?? [];

        // Ensure both arrays are the same length (pad years with empty strings if needed)
        if (count($programs) > count($years)) {
            $years = array_pad($years, count($programs), '');
        }


        // Get geofence parameters (optional)
        $latitude = !empty($_POST['latitude']) ? floatval($_POST['latitude']) : null;
        $longitude = !empty($_POST['longitude']) ? floatval($_POST['longitude']) : null;
        $radius = !empty($_POST['radius']) ? intval($_POST['radius']) : null;
        
        $result = $attendance->insertAttendance($_POST['eventName'], $programs, $years, $requiredAttendanceRecord, $_POST['sanction'], $latitude, $longitude, $radius);
        $last_id = $attendance->getLastAttendanceId();
        
        foreach ($programs as $i => $program) {
            $acad_year = $years[$i] ?? '';
            $attendance->insertRequiredAttendee($last_id, $program, $acad_year);
        }
        $_SESSION['success_message'] = 'Attendance successfully added!';
    }else{
        echo "<script>alert('Invalid event name. Event already exists!');</script>";
    }
}
$attendance = new AddAttendance();
$attendance->index($data);