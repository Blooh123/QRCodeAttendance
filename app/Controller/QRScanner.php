<?php

namespace Controller;


require_once '../app/Model/QRCode.php';
require_once '../app/Model/Student.php';
require_once '../app/Model/User.php';
require_once '../app/Model/ActivityLog.php';
require_once '../app/Model/Attendances.php';
use Controller;


use DateTime;
use Exception;
use Model\ActivityLog;
use Model\QRCode;
use Model\Student;
use Model\User;
use Model\Attendances;

session_start();
class QRScanner extends Controller
{

    public function processScannedData($data, $attenId, $requiredAttendees, $onTimeCheck, $confirm = false): void
    {
        global $EventName;
        $userSessions = json_decode($_COOKIE['user_data'], true);

        $qrcode = new QRCode();
        $activityLog = new ActivityLog();

        try {
            $result = $qrcode->getQRData($data);

            if (!empty($result) && isset($result[0]['student_id'])) {
                $studentId = $result[0]['student_id'];
                $studentName = $result[0]['name'];

                $studentData = $qrcode->getStudentData($studentId);

                if (!empty($studentData)) {
                    $student = $studentData[0];

                    // Convert BLOB to base64
                    $studentProfileBase64 = !empty($student['studentProfile']) ? base64_encode($student['studentProfile']) : null;
                    $name = $student['name'];
                    $program = $student['program'];
                    $acad_year = $student['acad_year'];
                    
                    if (!$confirm) {
                        echo json_encode([
                            "status" => "success",
                            "student" => $name,
                            "studentProfile" => $studentProfileBase64,
                            "program" => $program

                        ]);
                        exit;
                    }

                    // Check if the student has already scanned
                    $attendanceExists = $qrcode->checkAttendance($attenId, $studentId);
                    if($onTimeCheck == 0){

                        if (!empty($attendanceExists)) {
                            echo json_encode([
                                "status" => "error",
                                "student" => $name,
                                "message" => "Student has already scanned!"
                            ]);
                            exit;
                        }
                    }elseif ($onTimeCheck == 1){
                        $attendanceExists1 = $qrcode->checkAttendance2($attenId, $studentId);

                        //check kung naka time in
                        if(empty($attendanceExists)){
                            echo json_encode([
                                "status" => "error",
                                "student" => $name,
                                "message" => "Student did not time in!"
                            ]);
                            exit;
                        }

                        if (!empty($attendanceExists1)) {
                            echo json_encode([
                                "status" => "error",
                                "student" => $name,
                                "message" => "Student has already scanned!"
                            ]);
                            exit;
                        }
                    }

                    // Check if student is required to attend based on required_attendees table
                    $isRequired = $this->checkStudentRequirement($attenId, $program, $acad_year);

                    if ($isRequired) {
                        try {
                            if ($onTimeCheck == 0){
                                $qrcode->recordAttendance($attenId, $studentId);

                                $activityLog->createActivityLog($_SESSION['user_id'], $_SESSION['role'],$_SESSION['username'] .' Scanned student: '. $studentName . ' (Time in)',$EventName);
                                echo json_encode([
                                    "status" => "success",
                                    "student" => $name,
                                    "message" => "QR Code Scanned Successfully! (Time in)"
                                ]);
                            }else{
                                $qrcode->recordAttendance2($attenId, $studentId);
                                $activityLog->createActivityLog($_SESSION['user_id'], $_SESSION['role'],$_SESSION['username'] .' Scanned student: '. $studentName . ' (Time out)',$EventName );
                                echo json_encode([
                                    "status" => "success",
                                    "student" => $name,
                                    "message" => "QR Code Scanned Successfully! (Time out)"
                                ]);
                            }

                        } catch (Exception $e) {
                            echo json_encode([
                                "status" => "error",
                                "message" => "Database error: " . $e->getMessage()
                            ]);
                        }
                    } else {
                        echo json_encode([
                            "status" => "error",
                            "student" => $name,
                            "message" => "Student is not required to attend!"
                        ]);
                    }

                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Student data not found!"
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Student not found!"
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "An error occurred: " . $e->getMessage()
            ]);
        }
        exit;
    }

    private function checkStudentRequirement($attenId, $studentProgram, $studentYear): bool
    {
        try {
            $attendance = new Attendances();
            
            // Get required attendees for this attendance event
            $requiredAttendees = $attendance->getRequiredAttendees($attenId);
            
            if (empty($requiredAttendees)) {
                return false;
            }
            
            foreach ($requiredAttendees as $requirement) {
                $requiredProgram = $requirement['program'];
                $requiredYear = $requirement['acad_year'];
                
                // Check if program matches
                if ($requiredProgram === 'AllStudents' || $requiredProgram === $studentProgram) {
                    // If year is empty/null, it means all years for this program
                    if (empty($requiredYear) || $requiredYear === '' || $requiredYear === null) {
                        return true;
                    }
                    // If year is specified, check if it matches
                    if ($requiredYear === $studentYear) {
                        return true;
                    }
                }
            }
            
            return false;
        } catch (Exception $e) {
            // Log the error and return false to prevent attendance recording
            error_log("Error checking student requirement: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($username, $status): void
    {
        $user = new User();
        $user->updateStatus($username, $status);
    }


    public function index($data): void
    {
        $this->loadViewWithData('scanner', $data);
    }
}

//make sure this page is protected
//$user = new User();
//$user_de = $user->checkSession('scanner');
//if ($user_de['role'] !== 'Facilitator') {
//    $uri = str_replace('/scanner', '/404', $_SERVER['REQUEST_URI']);
//    header('Location: ' . $uri);
//}
$user = new User();
$userData = $user->checkSession('canner');

if (!$userData || !isset($userData['role']) || $userData['role'] !== 'Facilitator') {
    $uri = str_replace('/facilitator', '/login', $_SERVER['REQUEST_URI']);
    header('Location: ' . $uri);
    exit();
}


$qrcode = new QRCode();
$qrCodeScanner = new QRScanner();
$attendanceList = $qrcode->getAttendance();

$AttendanceID = '';
$EventName = 'No Event';
$EventDate = 'No Date';
$EventTime = 'No Time';
$onTimeCheck = 0;
$isOngoing = false;
$longitude = '';
$latitude = '';
$radius = '';

foreach ($attendanceList as $attendance) {
    if ($attendance['atten_status'] == 'on going') {
        $EventName = htmlspecialchars($attendance['event_name']);
        try {
            $dateTime = new DateTime($attendance['atten_started']);
        } catch (\DateMalformedStringException $e) {

        }
        $EventDate = $dateTime->format('F j, Y');
        $EventTime = $dateTime->format('h:i A');
        $AttendanceID = htmlspecialchars($attendance['atten_id']);
        $isOngoing = true;
        $onTimeCheck = $attendance['atten_OnTimeCheck'];
        //get the coordinates
        $longitude = $attendance['longitude'] ?? null;
        $latitude = $attendance['latitude'] ?? null;
        $radius = $attendance['radius'] ?? null;
        
        // Debug: Log the geofence data
        error_log("Geofence Data for Event {$EventName}: lat={$latitude}, lng={$longitude}, radius={$radius}");
        
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['qrData']) && isset($_POST['atten_id'])) {
    $confirm = isset($_POST['confirm']) && $_POST['confirm'] === 'true';
    $fetchStudent = isset($_POST['fetchStudent']) && $_POST['fetchStudent'] === 'true';
    
    // If it's just fetching student data (not confirming attendance)
    if ($fetchStudent) {
        $qrCodeScanner->processScannedData($_POST['qrData'], $_POST['atten_id'], null, $onTimeCheck, false);
    } else {
        // If it's confirming attendance
        $qrCodeScanner->processScannedData($_POST['qrData'], $_POST['atten_id'], null, $onTimeCheck, $confirm);
    }
}


$data = [
    "attendanceList" => $attendanceList,
    "attendanceID" => $AttendanceID,
    "EventName" => $EventName,
    "EventDate" => $EventDate,
    "EventTime" => $EventTime,
    "isOngoing" => $isOngoing,
    "longitude" => $longitude,
    "latitude" => $latitude,
    "radius" => $radius

];


$qrCodeScanner->index($data);