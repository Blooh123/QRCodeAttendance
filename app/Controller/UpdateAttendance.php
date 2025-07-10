<?php

namespace Controller;
require_once '../app/core/Database.php';
require_once '../app/core/config.php';
require_once '../app/Model/Attendances.php';
require_once '../app/Model/Student.php';
require_once '../app/Model/Sanction.php';
require_once '../app/Model/QRCode.php';
use Database;
use DateTime;
use DateTimeZone;
use Exception;
use Model\Attendances;
use Model\QRCode;
use Model\Sanction;
use Model\Student;
use PDOException;

class UpdateAttendance
{
    use Database;
    public function updateAttendance(): void
    {
        // Check if the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the event ID and action from the request
            $eventId = $_POST['atten_id'] ?? null;
            $action = $_POST['action'] ?? null;
            $eventName = $_POST['eventName'] ?? null;
            $hours = $_POST['sanction'] ?? null;

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
                        break;

                    case 'stopped':
                        $stmt = $this->connect()->prepare("UPDATE attendance SET atten_status = 'stopped' WHERE atten_id = :eventId");
                        $stmt->bindParam(':eventId', $eventId);
                        $stmt->execute();
                        $message = 'Attendance stopped successfully.';
                        break;

                    case 'finished':
                        $stmt = $this->connect()->prepare("UPDATE attendance SET atten_status = 'finished', atten_ended = NOW() WHERE atten_id = :eventId");
                        $stmt->bindParam(':eventId', $eventId);
                        $stmt->execute();

                        //add sanction to students
                        $sanction = new Sanction();
                        $student = new Student();
                        $attendances = new Attendances();
                        $qrCode = new QRCode();
                        $attendanceDetails = $attendances->getAttendanceDetails($eventId, $eventName);
                        
                        // Get required attendees from the new required_attendees table
                        $requiredAttendeesData = $attendances->getRequiredAttendees($eventId);
                        $requiredAttendance = json_decode($attendanceDetails['required_attenRecord'], true);

                        $studentList = $student->getAllStudent(); // Fetch students as associative arrays

                        $attendanceRecordList = array_map('strval', array_column($attendances->AttendanceRecord2($eventId), 'student_id'));
                        $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
                        $formattedTime = $date->format('Y-m-d H:i:s'); // FULL Date and Time

                        // Check if AllStudents is required
                        $hasAllStudents = false;
                        foreach ($requiredAttendeesData as $requirement) {
                            if ($requirement['program'] === 'AllStudents') {
                                $hasAllStudents = true;
                                break;
                            }
                        }

                        if ($hasAllStudents) {
                            foreach ($studentList as $student) {
                                $student_id = (string) $student['student_id'];
                                if(in_array('time_out', $requiredAttendance)){
                                    if(in_array($student_id, $attendanceRecordList, true)){
                                        //check if naka time out
                                        if(!$qrCode->checkAttendance2($eventId, $student_id)){
                                            $sanction->insertSanction($student_id, 'Unable to attend ' . $eventName . ' event', $hours, $formattedTime);
                                        }
                                    }
                                }
                                if (!in_array($student_id, $attendanceRecordList, true)){
                                    $sanction->insertSanction($student_id, 'Unable to attend ' . $eventName . ' event', $hours, $formattedTime);
                                }
                            }
                        } else {
                            foreach ($studentList as $student) {
                                $student_id = (string) $student['student_id'];
                                $student_program = (string) $student['program'];
                                $student_year = (string) $student['acad_year'];

                                $studentIsRequired = false;

                                // Check if student is required based on required_attendees table
                                foreach ($requiredAttendeesData as $requirement) {
                                    $requiredProgram = $requirement['program'];
                                    $requiredYear = $requirement['acad_year'];

                                    if ($student_program === $requiredProgram) {
                                        // If year is empty/null, it means all years for this program
                                        if (empty($requiredYear) || $requiredYear === '' || $requiredYear === null) {
                                            $studentIsRequired = true;
                                            break;
                                        }
                                        // If year is specified, check if it matches
                                        if ($requiredYear === $student_year) {
                                            $studentIsRequired = true;
                                            break;
                                        }
                                    }
                                }

                                // If student is required but did NOT attend
                                if ($studentIsRequired && in_array($student_id, $attendanceRecordList, true)) {
                                    if(in_array('time_out',$requiredAttendance)){
                                        if(in_array($student_id, $attendanceRecordList, true)){
                                            //check if naka time out
                                            if(!$qrCode->checkAttendance2($eventId, $student_id)){
                                                $sanction->insertSanction($student_id, 'Unable to time out ' . $eventName . ' event', $hours, $formattedTime);
                                            }
                                        }
                                    }
                                }elseif($studentIsRequired && !in_array($student_id, $attendanceRecordList, true)){
                                    $sanction->insertSanction($student_id, 'Unable to attend ' . $eventName . ' event', $hours, $formattedTime);
                                }
                            }
                        }

                        $message = 'Attendance finished successfully.';
                        break;

                    default:
                        throw new Exception('Invalid action.');
                }
                echo "<script>
                    alert('$message');
                    window.location.href = '" . str_replace('/update_attendance', '/adminHome?page=Attendance', $_SERVER['REQUEST_URI']) . "';
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