<?php

namespace Model;

// Get the project root directory
$projectRoot = dirname(dirname(__DIR__));
require_once $projectRoot . '/app/core/Database.php';

use Database;
use PDO;

if (!class_exists('Model\Attendances')) {
    class Attendances
{
    use Database;

    public function insertAttendance($name, $requiredAttendees, $year, $requiredAttendanceRecord, $sanction, $latitude, $longitude, $radius, $description, $allow_excuse): false|string
    {
        $status = 'not started';  // default
        
        $requiredAttendanceRecordJson = json_encode($requiredAttendanceRecord);

        $query = "CALL sp_insert_attendance(:name, :status, :sanction, :requiredAttendanceRecord, :latitude, :longitude, :radius, :description, :allow_excuse)";
        $params = [
            ':name' => $name,
            ':status' => $status,
            ':requiredAttendanceRecord' => $requiredAttendanceRecordJson,
            ':sanction' => $sanction,
            ':latitude' => $latitude,
            ':longitude' => $longitude,
            ':radius' => $radius,
            ':description' => $description,
            ':allow_excuse' => $allow_excuse
        ];

        // Insert attendance and get the new ID
        $result = $this->query2($query, $params);
        if (!$result) return false;

        return $result;
    }

    public function updateBanner($attenId, $bannerData): bool
    {
        try {
            error_log("Updating banner for attendance ID: " . $attenId);
            error_log("Banner data size: " . strlen($bannerData));
            
            $query = "UPDATE attendance SET banner = :banner WHERE atten_id = :atten_id";
            $params = [
                ':banner' => $bannerData,
                ':atten_id' => $attenId
            ];
            $result = $this->query($query, $params);
            
            error_log("Banner update result: " . ($result ? 'success' : 'failed'));
            return $result !== false;
        } catch (\Exception $e) {
            error_log("Error updating banner: " . $e->getMessage());
            return false;
        }
    }

    public function getLastAttendanceId() {
        // Adjust this query to match your DBMS and schema
        $query = "SELECT MAX(atten_id) as last_id FROM attendance";
        $result = $this->query($query);
        return $result[0]['last_id'] ?? null;
    }

    public function insertRequiredAttendee($atten_id, $program, $acad_year) {
        $query = "INSERT INTO required_attendees (atten_id, program, acad_year) VALUES (:atten_id, :program, :acad_year)";
        $params = [
            ':atten_id' => $atten_id,
            ':program' => $program,
            ':acad_year' => $acad_year
        ];
        $result = $this->query($query, $params);
        if (!$result) {
            error_log("Failed to insert required_attendee: atten_id=$atten_id, program=$program, acad_year=$acad_year");
        }
        return $result;
    }

    public function getRequiredAttendees($atten_id): array
    {
        try {
            $query = "SELECT program, acad_year FROM required_attendees WHERE atten_id = :atten_id";
            $params = [
                ':atten_id' => $atten_id
            ];
            $result = $this->query($query, $params);
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            error_log("Error in getRequiredAttendees: " . $e->getMessage());
            return [];
        }
    }

    public function deleteAttendance($id): bool|array
    {
        $query = "DELETE FROM attendance WHERE atten_id = :id";
        $query2 = "DELETE FROM attendance_record WHERE atten_id = :id";
        $query3 = "DELETE FROM required_attendees WHERE atten_id = :id";
        $query4 = "DELETE FROM excuse_application WHERE atten_id = :id";

        $params2 = [
            ':id' => $id
        ];
        $params = [
            ':id' => $id
        ];
        $params3 = [
            ':id' => $id
        ];
        $params4 = [
            ':id' => $id
        ];

        $this->query($query4, $params4);    
        $this->query($query3, $params3);    
        $this->query($query2, $params2);
        return $this->query($query, $params);
    }

    function getAllAttendance(): array
    {
        $query = "SELECT * FROM attendance ORDER BY date_created DESC";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAttendanceDetails($id, $eventName): bool|array
    {
        $query = "CALL sp_get_attendance_details(:id, :event_name)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":event_name", $eventName);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAttendanceByID($id){
        $query = 'SELECT * FROM attendance WHERE atten_id = :id';
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countAttendanceRecord($eventID){
        $qury = "CALL sp_count_student_attend(:eventID)";
        $stmt = $this->connect()->prepare($qury);
        $stmt->bindParam(":eventID", $eventID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function AttendanceRecord($program,$year,$atten_id): bool|array
    {
        $query = "CALL sp_get_attendance_record(?,?,?)";
        return $this->query($query,[$program,$year,$atten_id]);
    }

    public function getAttendanceRecord($eventID, $searchQuery = ''): array
    {
        // Ensure eventID is valid
        if (!is_numeric($eventID) || empty($eventID)) {
            error_log("Invalid eventID provided to getAttendanceRecord: " . var_export($eventID, true));
            return [];
        }
        
        // Ensure searchQuery is a string and has a default value
        if (is_array($searchQuery)) {
            $searchQuery = implode(' ', $searchQuery);
        } elseif (!is_string($searchQuery)) {
            $searchQuery = '';
        }
        
        // Trim and sanitize the search query
        $searchQuery = trim($searchQuery);
        if (empty($searchQuery)) {
            $searchQuery = '';
        }

        $sql = "CALL sp_get_student_attendance_record(?, ?, ?)";
        $sql2 = "CALL sp_get_student_attendance_record2(?, ?, ?)";

        try {
            $attendanceRecords = $this->query($sql, [$searchQuery, $searchQuery, $eventID]);
            // $attendanceRecords = $this->query($sql2, [$searchQuery, $searchQuery, $eventID]);

            // Ensure query result is an array
            return is_array($attendanceRecords) ? $attendanceRecords : [];
        } catch (\Exception $e) {
            error_log("Error in getAttendanceRecord: " . $e->getMessage());
            return [];
        }
    }


    public function deleteAttendanceRecord($id1, $id2): bool|array{
        $query = "DELETE FROM attendance_record WHERE atten_id = :id1 AND student_id = :id2";
        $params = [
            ':id1' => $id1,
            ':id2' => $id2
        ];
        return $this->query($query, $params);
    }

    public function AttendanceRecord2($atten_id): array
    {
        try {
            $sql = 'CALL sp_attendance_record(:id)';
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(":id", $atten_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return is_array($result) ? $result : [];
        } catch (\PDOException $e) {
            error_log("AttendanceRecord2 error for atten_id={$atten_id}: " . $e->getMessage());
            return [];
        }
    }

    public function checkAttendanceOnGoing(): bool|array
    {
        $sql = "CALL sp_check_attendance_on_going()";
        return $this->query($sql);
    }

    public function searchAttendance($searchQuery): array{
        $sql = "CALL sp_search_attendance(:searchQuery)";
        $search = '%'.$searchQuery.'%';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':searchQuery', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentsWhoDidNotAttend($eventID, $program, $year): array
    {
        $query = "CALL sp_get_student_not_attended(:eventID,:program,:year)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":eventID", $eventID);
        $stmt->bindParam(":program", $program);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function vwStudentSanctioned($event): array
    {
        $query = 'CALL sp_view_sanctioned(:event)';
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":event", $event);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get student attendance records. Optionally filter by academic year start (YYYY -> YYYY-07-01 to YYYY+1-06-30).
     * @param string|int $studentId
     * @param int|null $yearStart
     * @return array
     */
    public function StudentAttendanceRecord($studentId, $yearStart = null)
    {
        $sql = "SELECT CONCAT(s.name) as Name, s.student_id, a.event_name, a.atten_started, att.time_in, att.time_out, a.date_created
                FROM students s
                INNER JOIN attendance_record att ON s.student_id = att.student_id
                INNER JOIN attendance a ON a.atten_id = att.atten_id
                WHERE s.student_id = :id";

        if ($yearStart) {
            $start = sprintf('%04d-07-01', (int)$yearStart);
            $end = sprintf('%04d-06-30', ((int)$yearStart) + 1);
            $sql .= " AND a.date_created BETWEEN :start AND :end";
        }

        $sql .= " ORDER BY a.atten_started DESC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(':id', $studentId);
        if ($yearStart) {
            $stmt->bindValue(':start', $start);
            $stmt->bindValue(':end', $end);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getNotAttendedEvents($studentID, $yearStart = null): array
    {
        $query = "SELECT 
                    a.atten_id,
                    a.event_name,
                    a.date_created
                FROM attendance a
                INNER JOIN required_attendees ra 
                    ON ra.atten_id = a.atten_id
                INNER JOIN students s 
                    ON s.student_id = :student_id
                LEFT JOIN attendance_record ar 
                    ON ar.atten_id = a.atten_id 
                    AND ar.student_id = s.student_id
                WHERE ar.atten_id IS NULL
                AND (
                        ra.program = 'AllStudents'
                        OR (ra.program = s.program AND ra.acad_year = s.acad_year)
                    )";

        // apply academic year filter (July 1 -> next year June 30) when provided
        if ($yearStart) {
            $start = sprintf('%04d-07-01', (int)$yearStart);
            $end = sprintf('%04d-06-30', ((int)$yearStart) + 1);
            $query .= " AND a.date_created BETWEEN :start AND :end";
        }

        $query .= ";";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":student_id", $studentID);
        if ($yearStart) {
            $stmt->bindValue(':start', $start);
            $stmt->bindValue(':end', $end);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBannerImage($attenId): ?string
    {
        try {
            $query = "SELECT banner FROM attendance WHERE atten_id = :atten_id";
            $params = [
                ':atten_id' => $attenId
            ];
            $result = $this->query($query, $params);
            
            if (is_array($result) && !empty($result) && isset($result[0]['banner'])) {
                return $result[0]['banner'];
            }
            
            return null;
        } catch (\Exception $e) {
            error_log("Error getting banner image: " . $e->getMessage());
            return null;
        }
    }

    public function getBannerAsBase64($attenId): ?string
    {
        try {
            $bannerData = $this->getBannerImage($attenId);
            if ($bannerData) {
                // Convert to base64 for display
                $base64 = base64_encode($bannerData);
                // You might want to detect MIME type or store it separately
                return "data:image/jpeg;base64,$base64";
            }
            return null;
        } catch (\Exception $e) {
            error_log("Error converting banner to base64: " . $e->getMessage());
            return null;
        }
    }

    public function getAttendanceWithBanner($attenId): ?array
    {
        try {
            $query = "SELECT atten_id, event_name, banner FROM attendance WHERE atten_id = :atten_id";
            $params = [
                ':atten_id' => $attenId
            ];
            $result = $this->query($query, $params);
            
            if (is_array($result) && !empty($result)) {
                return $result[0];
            }
            
            return null;
        } catch (\Exception $e) {
            error_log("Error getting attendance with banner: " . $e->getMessage());
            return null;
        }
    }


}
}