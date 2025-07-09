<?php

namespace Model;
require_once '../app/core/Database.php';
use Database;
use PDO;

class Attendances
{
    use Database;

    public function insertAttendance($name, $requiredAttendees, $year, $requiredAttendanceRecord, $sanction, $latitude = null, $longitude = null, $radius = null): false|string
    {
        $status = 'not started';  // default

        $requiredAttendanceRecordJson = json_encode($requiredAttendanceRecord);

        $query = "CALL sp_insert_attendance(:name, :status, :sanction, :requiredAttendanceRecord, :latitude, :longitude, :radius)";
        $params = [
            ':name' => $name,
            ':status' => $status,
            ':requiredAttendanceRecord' => $requiredAttendanceRecordJson,
            ':sanction' => $sanction,
            ':latitude' => $latitude,
            ':longitude' => $longitude,
            ':radius' => $radius
        ];

        // Insert attendance and get the new ID
        $result = $this->query2($query, $params);
        if (!$result) return false;


        // $atten_id = $this->getLastAttendanceId();
        // $found = false;
        // // Insert required attendees
        // foreach ($requiredAttendees as $i => $program) {
        //     $found = true;
        //     $acad_year = $year[$i] ?? '';
        //     print_r($program);
        //     print_r($acad_year);
        //     $this->insertRequiredAttendee($atten_id, $program, $acad_year);
        // }

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
        } catch (Exception $e) {
            error_log("Error in getRequiredAttendees: " . $e->getMessage());
            return [];
        }
    }

    public function deleteAttendance($id): bool|array
    {
        $query = "DELETE FROM attendance WHERE atten_id = :id";
        $query2 = "DELETE FROM attendance_record WHERE atten_id = :id";
        $query3 = "DELETE FROM required_attendees WHERE atten_id = :id";
        $params2 = [
            ':id' => $id
        ];
        $params = [
            ':id' => $id
        ];
        $params3 = [
            ':id' => $id
        ];
        $this->query($query3, $params3);    
        $this->query($query2, $params2);
        return $this->query($query, $params);
    }

    function getAllAttendance(): array
    {
        $query = "SELECT * FROM viewattendance";
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

    public function getAttendanceRecord($programJson, $eventID, $searchQuery): array
    {
        // Ensure $programJson is a valid JSON string or convert it to an array
        if (is_string($programJson)) {
            $programs = json_decode($programJson, true);
        } elseif (is_array($programJson)) {
            $programs = $programJson;
        } else {
            $programs = []; // Default to an empty array
        }

        if (empty($programs)) {
            return []; // Prevent errors if decoding fails
        }

        $sql = "CALL sp_get_student_attendance_record(?, ?, ?, ?, ?)";
        $sql2 = "CALL sp_get_student_attendance_record2(?, ?, ?, ?)";

        if (!in_array('AllStudents', $programs)) {
            $programList = json_encode($programs); // Ensure valid JSON for MySQL JSON functions
            $attendanceRecords = $this->query($sql, [$searchQuery, $searchQuery, $searchQuery, $programList, $eventID]);
        } else {
            $attendanceRecords = $this->query($sql2, [$searchQuery, $searchQuery, $searchQuery, $eventID]);
        }

        // Ensure query result is an array
        return is_array($attendanceRecords) ? $attendanceRecords : [];
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
        $sql = 'CALL sp_attendance_record(:id)';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id", $atten_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function StudentAttendanceRecord($id): array
    {
        $query = "CALL student_attendance_record(:student_id)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(":student_id", $id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}