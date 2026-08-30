<?php

namespace Controller;
require_once '../app/core/Database.php';
require_once '../app/core/config.php';
require_once '../app/Model/Attendances.php';
require_once '../app/Model/Student.php';
require_once '../app/Model/Sanction.php';
require_once '../app/Model/QRCode.php';
require_once '../app/Model/ExcuseApplication.php';
require_once '../app/Model/ActivityLog.php';
require_once '../app/Model/User.php';
use Database;
use DateTime;
use DateTimeZone;
use Exception;
use Model\Attendances;
use Model\QRCode;
use Model\Sanction;
use Model\Student;
use Model\ExcuseApplication;
use Model\ActivityLog;
use PDOException;
use Model\User;
class UpdateAttendance
{
    use Database;
    private $excuseApp;
    
    /**
     * Check if a student has an approved excuse application for a specific event
     */
    // private function hasApprovedExcuse($studentId, $eventId): bool
    // {
    //     try {
            
    //         $query = "SELECT COUNT(*) as count FROM excuse_application 
    //                   WHERE student_id = :student_id AND atten_id = :event_id AND application_status = 1";
            
    //         $stmt = $this->connect()->prepare($query);
    //         $stmt->bindParam(':student_id', $studentId);
    //         $stmt->bindParam(':event_id', $eventId);
    //         $stmt->execute();
            
    //         $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //         return $result['count'] > 0;
    //     } catch (Exception $e) {
    //         error_log("Error checking approved excuse: " . $e->getMessage());
    //         return false;
    //     }
    // }
    
    public function updateAttendance(): void
    {
        $activityLog = new ActivityLog();
        $this->excuseApp = new ExcuseApplication();
        $user = new User();
        $userData = $user->checkSession('update_attendance');
        // Check if the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the event ID and action from the request
            $eventId = $_POST['atten_id'] ?? null;
            $action = $_POST['action'] ?? null;
            $eventName = $_POST['eventName'] ?? null;
            $sanctionInputHours = $_POST['sanction'] ?? null;

            // Validate event ID and action
            if (!$eventId || !$action) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid request data.']);
                exit;
            }

            // Update the attendance status in the database based on the action
            try {
                $attendance = new Attendances();
                switch ($action) {
                    case 'start':
                        if (!$attendance->checkAttendanceOnGoing()){
                            $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
                            $formattedTime = $date->format('Y-m-d H:i:s'); // FULL Date and Time
                            $stmt = $this->connect()->prepare("UPDATE attendance SET atten_status = 'on going', atten_started = :date WHERE atten_id = :eventId");
                            $stmt->bindParam(':eventId', $eventId);
                            $stmt->bindParam(':date', $formattedTime);
                            $stmt->execute();
                            $message = 'Attendance started successfully.';
                            $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Started attendance for event: ' . $eventName, 'update_attendance');
                        }else{
                            $message = 'Oops! only one attendance at a time...';
                        }

                        break;
                    case 'continue':
                        if (!$attendance->checkAttendanceOnGoing()){
                            $stmt = $this->connect()->prepare("UPDATE attendance SET atten_status = 'on going', atten_OnTimeCheck = 1 WHERE atten_id = :eventId");
                            $stmt->bindParam(':eventId', $eventId);
                            $stmt->execute();
                            $message = 'Attendance continued successfully.';
                            $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Continued attendance for event: ' . $eventName, 'update_attendance');
                        }else{
                            $message = 'Oops! only one attendance at a time...';
                        }
                        break;

                    case 'save changes of':
                        $eventName = $_POST['eventName'] ?? '';
                        $sanction = $_POST['sanction'] ?? 0;
                        $latitude = $_POST['latitude'] ?? null;
                        $longitude = $_POST['longitude'] ?? null;
                        $radius = $_POST['radius'] ?? null;

                        $stmt = $this->connect()->prepare("
                        UPDATE attendance 
                        SET event_name = :eventName, 
                            sanction = :sanction,
                            latitude = :latitude,
                            longitude = :longitude,
                            radius = :radius
                        WHERE atten_id = :eventId
                    ");
                        $stmt->execute([
                            ':eventId' => $eventId,
                            ':eventName' => $eventName,
                            ':sanction' => $sanction,
                            ':latitude' => $latitude,
                            ':longitude' => $longitude,
                            ':radius' => $radius
                        ]);
                        $message = 'Attendance updated successfully.';
                        $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Updated attendance for event: ' . $eventName, 'update_attendance');
                        break;

                    case 'stopped':
                        $stmt = $this->connect()->prepare("UPDATE attendance SET atten_status = 'stopped' WHERE atten_id = :eventId");
                        $stmt->bindParam(':eventId', $eventId);
                        $stmt->execute();
                        $message = 'Attendance stopped successfully.';
                        $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Stopped attendance for event: ' . $eventName, 'update_attendance');
                        break;

                    case 'finished':
                        $stmt = $this->connect()->prepare("UPDATE attendance SET atten_status = 'finished', atten_ended = NOW() WHERE atten_id = :eventId");
                        $stmt->bindParam(':eventId', $eventId);
                        $stmt->execute();

                        // OPTIMIZATION: Use batch operations to reduce database connections
                        $sanction = new Sanction();
                        $student = new Student();
                        $attendances = new Attendances();
                        $qrCode = new QRCode();
                        
                        // BATCH FETCH 1: Get all required data in ONE query each (not per-student)
                        $attendanceDetails = $attendances->getAttendanceDetails($eventId, $eventName);
                        $requiredAttendeesData = $attendances->getRequiredAttendees($eventId);
                        $requiredAttendance = json_decode($attendanceDetails['required_attenRecord'] ?? '[]', true);
                        $requiredAttendance = is_array($requiredAttendance) ? $requiredAttendance : [];
                        $sanctionHours = is_numeric($attendanceDetails['sanction'] ?? null)
                            ? (int)$attendanceDetails['sanction']
                            : ((is_numeric($sanctionInputHours) ? (int)$sanctionInputHours : 0));

                        $studentList = $student->getAllStudent();
                        $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
                        $formattedTime = $date->format('Y-m-d H:i:s');

                        // CRITICAL OPTIMIZATION: Batch fetch instead of per-student queries
                        // Before: 3+ connections × number of students
                        // After: 3 total connections for entire batch
                        $excusedStudentIds = $this->excuseApp->getApprovedExcuseStudentIds($eventId);
                        $attendanceRecordRows = $attendances->AttendanceRecord2($eventId);
                        $attendanceRecordList = is_array($attendanceRecordRows)
                            ? array_map('strval', array_column($attendanceRecordRows, 'student_id'))
                            : [];
                        $attendanceRecords = $qrCode->getAttendanceRecordsByEvent($eventId);
                        $studentsWithoutTimeOut = is_array($qrCode->getStudentsWithoutTimeOut($eventId))
                            ? $qrCode->getStudentsWithoutTimeOut($eventId)
                            : [];
                        $studentsWithoutTimeIn = is_array($qrCode->getStudentsWithoutTimeIn($eventId))
                            ? $qrCode->getStudentsWithoutTimeIn($eventId)
                            : [];

                        // Build array of sanctions to bulk insert (ONE query for all instead of N queries)
                        $sanctionsToInsert = [];

                        // Check if AllStudents is required
                        $hasAllStudents = false;
                        if (empty($requiredAttendeesData)) {
                            error_log("UpdateAttendance finished: no required_attendees found for atten_id={$eventId}. Assuming all students.");
                            $hasAllStudents = true;
                        } else {
                            foreach ($requiredAttendeesData as $requirement) {
                                if (isset($requirement['program']) && $requirement['program'] === 'AllStudents') {
                                    $hasAllStudents = true;
                                    break;
                                }
                            }
                        }

                        // OPTIMIZED LOOP: Only PHP logic, no database queries inside
                        if ($hasAllStudents) {
                            foreach ($studentList as $student) {
                                $student_id = (string) $student['student_id'];
                                
                                // Skip if excused (using array_search, not DB query)
                                if (in_array($student_id, $excusedStudentIds, true)) {
                                    continue;
                                }
                                
                                if (in_array('time_out', $requiredAttendance)) {
                                    if (in_array($student_id, $attendanceRecordList, true)) {
                                        // Check using pre-fetched data (not DB query)
                                        if (in_array($student_id, $studentsWithoutTimeOut, true)) {
                                            $sanctionsToInsert[] = [
                                                'student_id' => $student_id,
                                                'reason' => 'Unable to time out ' . $eventName . ' event',
                                                'hours' => 1,
                                                'date_applied' => $formattedTime
                                            ];
                                        }
                                        if (in_array($student_id, $studentsWithoutTimeIn, true)) {
                                            $sanctionsToInsert[] = [
                                                'student_id' => $student_id,
                                                'reason' => 'Unable to time in ' . $eventName . ' event',
                                                'hours' => 1,
                                                'date_applied' => $formattedTime
                                            ];
                                        }
                                    }
                                }
                                if (!in_array($student_id, $attendanceRecordList, true)) {
                                    $sanctionsToInsert[] = [
                                        'student_id' => $student_id,
                                        'reason' => 'Unable to attend ' . $eventName . ' event',
                                        'hours' => 2,
                                        'date_applied' => $formattedTime
                                    ];
                                }
                            }
                        } else {
                            foreach ($studentList as $student) {
                                $student_id = (string) $student['student_id'];
                                $student_program = (string) $student['program'];
                                $student_year = (string) $student['acad_year'];

                                $studentIsRequired = false;

                                // Check if student is required based on required_attendees (PHP logic, not DB)
                                foreach ($requiredAttendeesData as $requirement) {
                                    $requiredProgram = $requirement['program'];
                                    $requiredYear = $requirement['acad_year'];

                                    if ($student_program === $requiredProgram) {
                                        if (empty($requiredYear) || $requiredYear === '' || $requiredYear === null) {
                                            $studentIsRequired = true;
                                            break;
                                        }
                                        if ($requiredYear === $student_year) {
                                            $studentIsRequired = true;
                                            break;
                                        }
                                    }
                                }

                                if ($studentIsRequired && !in_array($student_id, $excusedStudentIds, true)) {
                                    if (in_array('time_out', $requiredAttendance)) {
                                        if (in_array($student_id, $attendanceRecordList, true)) {
                                            if (in_array($student_id, $studentsWithoutTimeOut, true)) {
                                                $sanctionsToInsert[] = [
                                                    'student_id' => $student_id,
                                                    'reason' => 'Unable to time out ' . $eventName . ' event',
                                                    'hours' => $hours,
                                                    'date_applied' => $formattedTime
                                                ];
                                            }
                                        }
                                    }
                                    if (!in_array($student_id, $attendanceRecordList, true)) {
                                        $sanctionsToInsert[] = [
                                            'student_id' => $student_id,
                                            'reason' => 'Unable to attend ' . $eventName . ' event',
                                            'hours' => $hours,
                                            'date_applied' => $formattedTime
                                        ];
                                    }
                                }
                            }
                        }

                        // BULK INSERT: All sanctions in ONE query instead of N queries
                        if (!empty($sanctionsToInsert)) {
                            $inserted = $sanction->bulkInsertSanctions($sanctionsToInsert);
                            if ($inserted === false) {
                                error_log("UpdateAttendance failed to insert sanctions for atten_id={$eventId}");
                            }
                        } else {
                            error_log("UpdateAttendance finished with no sanctions to insert for atten_id={$eventId}");
                        }

                        $message = 'Attendance finished successfully.';
                        $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Finished attendance for event: ' . $eventName, 'update_attendance');
                        break;

                    default:
                        throw new Exception('Invalid action.');
                }
                if ($action === 'finished') {
                    $redirectUrl = ROOT . "public/view_record2?eventName=" . urlencode($eventName) . "&id=" . urlencode($eventId);
                } else {
                    $redirectUrl = str_replace('/update_attendance', '/adminHome?page=Attendance', $_SERVER['REQUEST_URI']);
                }

                echo "<script>
                    alert('$message');
                    window.location.href = '$redirectUrl';
                </script>";
                exit;


            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update attendance: ' . $e->getMessage()]);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        }
    }

}

$updateAttendance = new UpdateAttendance();
$updateAttendance->updateAttendance();