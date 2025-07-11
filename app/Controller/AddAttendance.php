<?php

namespace Controller;
require_once "../app/Model/Student.php";
require_once "../app/Model/User.php";
require_once "../app/Model/Attendances.php";
require_once '../vendor/autoload.php';

use Controller;
use Model\Attendances;
use Model\Student;
use Model\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
class AddAttendance extends Controller
{
    public function index($data): void
    {
        $this->loadViewWithData('add_attendance',$data);
    }
    
    public function sendAttendanceNotificationOptimized($programs, $years, $eventName, $description, $sanction): array
    {
        try {
            // Get students based on programs and years
            $student = new Student();
            $students = $student->getStudentsByProgramAndYear($programs, $years);
            
            if (empty($students)) {
                return [
                    'success' => false,
                    'message' => 'No students found for the specified programs and years.',
                    'total_students' => 0,
                    'sent' => 0,
                    'failed' => 0
                ];
            }
            
            $totalStudents = count($students);
            
            // For localhost, use smaller batches and show progress
            if ($totalStudents > 100) {
                return $this->sendLargeBatchEmails($students, $eventName, $description, $sanction, $programs, $years);
            } else {
                return $this->sendSmallBatchEmails($students, $eventName, $description, $sanction, $programs, $years);
            }
            
        } catch (Exception $e) {
            error_log("Failed to send attendance notifications: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send email notifications: ' . $e->getMessage(),
                'total_students' => 0,
                'sent' => 0,
                'failed' => 0
            ];
        }
    }
    
    private function sendLargeBatchEmails($students, $eventName, $description, $sanction, $programs, $years): array
    {
        $batchSize = 20; // Smaller batches for localhost
        $totalStudents = count($students);
        $successCount = 0;
        $errorCount = 0;
        
        // Initialize PHPMailer once
        $mail = $this->initializePHPMailer($eventName, $description, $sanction, $programs, $years);
        
        for ($i = 0; $i < $totalStudents; $i += $batchSize) {
            $batch = array_slice($students, $i, $batchSize);
            $batchNumber = floor($i / $batchSize) + 1;
            $totalBatches = ceil($totalStudents / $batchSize);
            
            // Process batch
            foreach ($batch as $studentData) {
                try {
                    $mail->clearAddresses();
                    $studentName = $studentData['name'];
                    $mail->addAddress($studentData['email'], $studentName);
                    
                    if ($mail->send()) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                    
                    // Small delay for localhost
                    usleep(50000); // 0.05 seconds
                    
                } catch (Exception $e) {
                    $errorCount++;
                    error_log("Email error for " . $studentData['email'] . ": " . $e->getMessage());
                }
            }
            
            // Progress update for large batches
            if ($totalStudents > 200) {
                $progress = round(($i + $batchSize) / $totalStudents * 100);
                error_log("Email progress: {$progress}% ({$successCount} sent, {$errorCount} failed)");
            }
            
            // Delay between batches
            if ($i + $batchSize < $totalStudents) {
                usleep(200000); // 0.2 seconds
            }
        }
        
        $message = "Email notifications sent: {$successCount} successful, {$errorCount} failed out of {$totalStudents} students.";
        
        return [
            'success' => $successCount > 0,
            'message' => $message,
            'total_students' => $totalStudents,
            'sent' => $successCount,
            'failed' => $errorCount
        ];
    }
    
    private function sendSmallBatchEmails($students, $eventName, $description, $sanction, $programs, $years): array
    {
        $successCount = 0;
        $errorCount = 0;
        $totalStudents = count($students);
        
        // Initialize PHPMailer
        $mail = $this->initializePHPMailer($eventName, $description, $sanction, $programs, $years);
        
        foreach ($students as $studentData) {
            try {
                $mail->clearAddresses();
                $studentName = $studentData['name'];
                $mail->addAddress($studentData['email'], $studentName);
                
                if ($mail->send()) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
                
                // Small delay for localhost
                usleep(30000); // 0.03 seconds
                
            } catch (Exception $e) {
                $errorCount++;
                error_log("Email error for " . $studentData['email'] . ": " . $e->getMessage());
            }
        }
        
        $message = "Email notifications sent: {$successCount} successful, {$errorCount} failed out of {$totalStudents} students.";
        
        return [
            'success' => $successCount > 0,
            'message' => $message,
            'total_students' => $totalStudents,
            'sent' => $successCount,
            'failed' => $errorCount
        ];
    }
    
    private function initializePHPMailer($eventName, $description, $sanction, $programs, $years): PHPMailer
    {
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'usep.qrattendance@gmail.com';
        $mail->Password = 'vvyg egpy egtv ajms';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Set sender
        $mail->setFrom('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
        $mail->addReplyTo('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
        
        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Attendance Event: ' . htmlspecialchars($eventName);
        
        // Create email body
        $emailBody = $this->createEmailBody($eventName, $description, $sanction, $programs, $years);
        $mail->Body = $emailBody;
        $mail->AltBody = $this->createPlainTextBody($eventName, $description, $sanction, $programs, $years);
        
        return $mail;
    }
    
    public function sendAttendanceNotificationAsync($programs, $years, $eventName, $description, $sanction): void
    {
        try {
            // Get students based on programs and years
            $student = new Student();
            $students = $student->getStudentsByProgramAndYear($programs, $years);
            
            if (empty($students)) {
                error_log("No students found for the specified programs and years");
                return;
            }
            
            // Store email data in a file for background processing
            $emailData = [
                'students' => $students,
                'eventName' => $eventName,
                'description' => $description,
                'sanction' => $sanction,
                'programs' => $programs,
                'years' => $years,
                'timestamp' => time(),
                'total_students' => count($students)
            ];
            
            $emailQueueFile = '../app/temp/email_queue_' . time() . '.json';
            $emailQueueDir = '../app/temp/';
            
            // Create temp directory if it doesn't exist
            if (!is_dir($emailQueueDir)) {
                mkdir($emailQueueDir, 0755, true);
            }
            
            // Save email data to file
            file_put_contents($emailQueueFile, json_encode($emailData));
            
            // Trigger background processing (non-blocking)
            $this->triggerBackgroundEmailProcessing($emailQueueFile);
            
            error_log("Email queue created for " . count($students) . " students. File: " . $emailQueueFile);
            
        } catch (Exception $e) {
            error_log("Failed to create email queue: " . $e->getMessage());
        }
    }
    
    private function triggerBackgroundEmailProcessing($emailQueueFile): void
    {
        // Use different methods based on server capabilities
        if (function_exists('exec') && is_callable('exec')) {
            // Method 1: Using exec (Linux/Unix)
            $phpPath = PHP_BINARY;
            $scriptPath = realpath('../app/scripts/process_email_queue.php');
            $command = "nohup $phpPath $scriptPath $emailQueueFile > /dev/null 2>&1 &";
            exec($command);
        } elseif (function_exists('shell_exec') && is_callable('shell_exec')) {
            // Method 2: Using shell_exec
            $phpPath = PHP_BINARY;
            $scriptPath = realpath('../app/scripts/process_email_queue.php');
            $command = "start /B $phpPath $scriptPath $emailQueueFile > NUL 2>&1";
            shell_exec($command);
        } else {
            // Method 3: Fallback - process in smaller batches
            $this->processEmailQueueInBatches($emailQueueFile);
        }
    }
    
    private function processEmailQueueInBatches($emailQueueFile): void
    {
        // Read email data
        $emailData = json_decode(file_get_contents($emailQueueFile), true);
        if (!$emailData) return;
        
        $students = $emailData['students'];
        $batchSize = 50; // Process 50 emails at a time
        $totalStudents = count($students);
        
        // Process in batches
        for ($i = 0; $i < $totalStudents; $i += $batchSize) {
            $batch = array_slice($students, $i, $batchSize);
            $this->sendBatchEmails($batch, $emailData['eventName'], $emailData['description'], $emailData['sanction'], $emailData['programs'], $emailData['years']);
            
            // Small delay between batches
            usleep(500000); // 0.5 second delay
        }
        
        // Clean up
        unlink($emailQueueFile);
    }
    
    private function sendBatchEmails($students, $eventName, $description, $sanction, $programs, $years): void
    {
        try {
            // Initialize PHPMailer
            $mail = new PHPMailer(true);
            
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'usep.qrattendance@gmail.com';
            $mail->Password = 'vvyg egpy egtv ajms';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Set sender
            $mail->setFrom('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
            $mail->addReplyTo('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
            
            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'New Attendance Event: ' . htmlspecialchars($eventName);
            
            // Create email body
            $emailBody = $this->createEmailBody($eventName, $description, $sanction, $programs, $years);
            $mail->Body = $emailBody;
            $mail->AltBody = $this->createPlainTextBody($eventName, $description, $sanction, $programs, $years);
            
            // Send emails to each student in batch
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($students as $studentData) {
                try {
                    // Clear previous recipients
                    $mail->clearAddresses();
                    
                    // Add student as recipient
                    $studentName = $studentData['name'];
                    $mail->addAddress($studentData['email'], $studentName);
                    
                    // Send email
                    if ($mail->send()) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        error_log("Failed to send email to: " . $studentData['email']);
                    }
                    
                    // Small delay to avoid overwhelming the SMTP server
                    usleep(50000); // 0.05 second delay (reduced for batch processing)
                    
                } catch (Exception $e) {
                    $errorCount++;
                    error_log("Email error for " . $studentData['email'] . ": " . $e->getMessage());
                }
            }
            
            // Log batch results
            error_log("Batch email sent: {$successCount} successful, {$errorCount} failed");
            
        } catch (Exception $e) {
            error_log("Failed to send batch emails: " . $e->getMessage());
        }
    }
    
    public function sendAttendanceNotification($programs, $years, $eventName, $description, $sanction): void
    {
        try {
            // Get students based on programs and years
            $student = new Student();
            $students = $student->getStudentsByProgramAndYear($programs, $years);
            
            if (empty($students)) {
                error_log("No students found for the specified programs and years");
                return;
            }
            
            // Initialize PHPMailer
            $mail = new PHPMailer(true);
            
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'usep.qrattendance@gmail.com';
            $mail->Password = 'vvyg egpy egtv ajms';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Set sender
            $mail->setFrom('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
            $mail->addReplyTo('usep.qrattendance@gmail.com', 'USeP QR Attendance System');
            
            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'New Attendance Event: ' . htmlspecialchars($eventName);
            
            // Create email body
            $emailBody = $this->createEmailBody($eventName, $description, $sanction, $programs, $years);
            $mail->Body = $emailBody;
            $mail->AltBody = $this->createPlainTextBody($eventName, $description, $sanction, $programs, $years);
            
            // Send emails to each student
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($students as $studentData) {
                try {
                    // Clear previous recipients
                    $mail->clearAddresses();
                    
                    // Add student as recipient
                    $studentName = $studentData['name'];
                    $mail->addAddress($studentData['email'], $studentName);
                    
                    // Send email
                    if ($mail->send()) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        error_log("Failed to send email to: " . $studentData['email']);
                    }
                    
                    // Small delay to avoid overwhelming the SMTP server
                    usleep(100000); // 0.1 second delay
                    
                } catch (Exception $e) {
                    $errorCount++;
                    error_log("Email error for " . $studentData['email'] . ": " . $e->getMessage());
                }
            }
            
            // Log results
            error_log("Attendance notification sent: {$successCount} successful, {$errorCount} failed");
            
        } catch (Exception $e) {
            error_log("Failed to send attendance notifications: " . $e->getMessage());
        }
    }
    
    private function createEmailBody($eventName, $description, $sanction, $programs, $years): string
    {
        $programsList = [];
        foreach ($programs as $i => $program) {
            $year = $years[$i] ?? '';
            if ($program === 'AllStudents') {
                $programsList[] = 'All Students';
            } else {
                $yearDisplay = !empty($year) ? " ({$year})" : '';
                $programsList[] = $program . $yearDisplay;
            }
        }
        
        $programsText = implode(', ', $programsList);
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #a31d1d; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background-color: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
                .event-name { font-size: 24px; font-weight: bold; color: #a31d1d; margin-bottom: 15px; }
                .info-item { margin-bottom: 15px; }
                .label { font-weight: bold; color: #555; }
                .description { background-color: white; padding: 15px; border-radius: 5px; border-left: 4px solid #a31d1d; }
                .footer { margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📅 New Attendance Event</h1>
                    <p>USeP QR Attendance System</p>
                </div>
                <div class='content'>
                    <div class='event-name'>" . htmlspecialchars($eventName) . "</div>
                    
                    <div class='info-item'>
                        <span class='label'>📚 Programs:</span> " . htmlspecialchars($programsText) . "
                    </div>
                    
                    <div class='info-item'>
                        <span class='label'>⏰ Sanction:</span> " . htmlspecialchars($sanction) . " hours
                    </div>
                    
                    <div class='info-item'>
                        <span class='label'>📝 Description:</span>
                        <div class='description'>" . $description . "</div>
                    </div>
                    
                    <div class='info-item'>
                        <span class='label'>⚠️ Important:</span>
                        <ul>
                            <li>Please attend this event on time</li>
                            <li>Use the QR code scanner to mark your attendance</li>
                            <li>Late attendance may result in sanctions</li>
                        </ul>
                    </div>
                    
                    <div class='footer'>
                        <p>This is an automated notification from the USeP QR Attendance System.</p>
                        <p>If you have any questions, please contact your administrator.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function createPlainTextBody($eventName, $description, $sanction, $programs, $years): string
    {
        $programsList = [];
        foreach ($programs as $i => $program) {
            $year = $years[$i] ?? '';
            if ($program === 'AllStudents') {
                $programsList[] = 'All Students';
            } else {
                $yearDisplay = !empty($year) ? " ({$year})" : '';
                $programsList[] = $program . $yearDisplay;
            }
        }
        
        $programsText = implode(', ', $programsList);
        
        // Strip HTML tags from description for plain text
        $plainDescription = strip_tags($description);
        
        return "
NEW ATTENDANCE EVENT

Event Name: " . $eventName . "
Programs: " . $programsText . "
Sanction: " . $sanction . " hours

Description:
" . $plainDescription . "

Important:
- Please attend this event on time
- Use the QR code scanner to mark your attendance
- Late attendance may result in sanctions

This is an automated notification from the USeP QR Attendance System.
If you have any questions, please contact your administrator.";
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
        
        // Validate required fields
        if (empty($_POST['eventName']) || empty($_POST['sanction']) || empty($description)) {
            echo "<script>alert('Please fill in all required fields including event name, sanction, and description!');</script>";
            $attendance = new AddAttendance();
            $attendance->index($data);
            return;
        }
        
        $result = $attendance->insertAttendance($_POST['eventName'], $programs, $years, $requiredAttendanceRecord, $_POST['sanction'], $latitude, $longitude, $radius, $description);
        $last_id = $attendance->getLastAttendanceId();
        
        foreach ($programs as $i => $program) {
            $acad_year = $years[$i] ?? '';
            $attendance->insertRequiredAttendee($last_id, $program, $acad_year);
        }
        
        // Send email notifications to students (optimized for localhost)
        $addAttendanceController = new AddAttendance();
        $emailResult = $addAttendanceController->sendAttendanceNotificationOptimized($programs, $years, $_POST['eventName'], $description, $_POST['sanction']);
        
        if ($emailResult['success']) {
            $_SESSION['success_message'] = 'Attendance successfully added! ' . $emailResult['message'];
        } else {
            $_SESSION['success_message'] = 'Attendance successfully added! ' . $emailResult['message'];
        }
    }else{
        echo "<script>alert('Invalid event name. Event already exists!');</script>";
    }
}
$attendance = new AddAttendance();
$attendance->index($data);