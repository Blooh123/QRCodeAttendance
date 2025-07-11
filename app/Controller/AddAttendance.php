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

        $description = !empty($_POST['description']) ? $_POST['description'] : '';
        
        // Handle banner image upload
        $banner = null;
        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
            $uploadedFile = $_FILES['banner_image'];
            
            // Debug: Check file upload
            error_log("File upload detected: " . print_r($uploadedFile, true));
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = mime_content_type($uploadedFile['tmp_name']);
            
            error_log("File type: " . $fileType);
            
            if (!in_array($fileType, $allowedTypes)) {
                echo "<script>alert('Invalid file type. Please upload a valid image (JPEG, PNG, GIF, or WebP).');</script>";
                $attendance = new AddAttendance();
                $attendance->index($data);
                return;
            }
            
            // Validate file size (max 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($uploadedFile['size'] > $maxSize) {
                echo "<script>alert('File size too large. Please upload an image smaller than 5MB.');</script>";
                $attendance = new AddAttendance();
                $attendance->index($data);
                return;
            }
            
            // Read image data directly (same as StudentProfile)
            $banner = file_get_contents($uploadedFile['tmp_name']);
            
            // Debug: Check banner data
            error_log("Banner data size: " . strlen($banner));
            error_log("Banner data first 100 chars: " . substr(bin2hex($banner), 0, 100));
        } else {
            error_log("No file upload or upload error: " . ($_FILES['banner_image']['error'] ?? 'no file'));
        }
        
        // Validate required fields
        if (empty($_POST['eventName']) || empty($_POST['sanction']) || empty($description)) {
            echo "<script>alert('Please fill in all required fields including event name, sanction, and description!');</script>";
            $attendance = new AddAttendance();
            $attendance->index($data);
            return;
        }
        
        $result = $attendance->insertAttendance($_POST['eventName'], $programs, $years, $requiredAttendanceRecord, $_POST['sanction'], $latitude, $longitude, $radius, $description);
        $last_id = $attendance->getLastAttendanceId();
        
        // Update banner if image was uploaded
        if ($banner && $last_id) {
            if (!$attendance->updateBanner($last_id, $banner)) {
                error_log("Failed to update banner for attendance ID: " . $last_id);
            }
        }
        
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