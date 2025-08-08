<?php

namespace Model;
require_once '../app/core/Database.php';
use Database;
use DateMalformedStringException;
use DateTime;
use DateTimeZone;
use Random\RandomException;

class QRCode
{
    use Database;

    public function updateQrCode($id): bool|array
    {
        $query = "UPDATE qr_code SET student_id = :id WHERE student_id = :id";
        $params = [
            ':id' => $id
        ];
        return $this->query($query, $params);
    }
    public function updateStudentQrCode($id): bool|array
    {
        $query = "UPDATE qr_code SET qr_value = :newQRCode WHERE student_id = :id";
        $newQRCode = $this->generateQRCode($id);
        $params = [
            ':id' => $id,
            ':newQRCode' => $newQRCode
        ];
        return $this->query($query, $params);
    }
    public function getQRValue($username) {
        $id = $this->getStudentId($username);

        if ($id === null) {
            return null; // Return null if no student ID is found
        }

        // Fetch the QR code data for the student
        $query = "SELECT qr_value FROM qr_code WHERE student_id = :id";
        $params = [':id' => $id];
        $result = $this->query($query, $params);

        // Check if a result is returned and return the QR value
        if (is_array($result) && !empty($result)) {
            return $result[0]['qr_value'];
        }

        return null; // Return null if no result is found
    }

    public function checkIfStudentHasProfile($username) {
        $query = "SELECT studentProfile FROM students WHERE email = :username";
        $params = [':username' => $username];
        $result = $this->query($query, $params);

        // Check if a result is returned and return the studentProfile
        if (is_array($result) && !empty($result)) {
            return $result[0]['studentProfile'];
        }

        return null; // Return null if no result is found
    }

    public function getStudentId($email): ?string
    {
        $query = "SELECT * FROM students WHERE email = :id";
        $params = [
            ':id' => $email
        ];
        $result = $this->query($query, $params);


        // Check if the result is an array and contains at least one row
        if (is_array($result) && !empty($result)) {
            return (string) $result[0]['student_id']; // Return the 'id' as a string
        }

        // If no result is found, return null or an empty string
        return null; // or return '';
    }
    public function deleteQRcode($id): bool|array
    {
        $query = "DELETE FROM qr_code WHERE student_id = :id";
        $params = [
            ':id' => $id
        ];
        return $this->query($query, $params);
    }

    public function getAttendance(): bool|array
    {
        $query = "SELECT * FROM attendance";
        return $this->query($query);
    }

    public function getQRData($data): bool|array
    {
        $query = 'SELECT q.*, s.name, s.program, s.acad_year FROM qr_code q INNER JOIN students s ON q.student_id = s.student_id WHERE qr_value = ?';
        return $this->query($query, [$data]);
    }

    public function getStudentData($studentId): bool|array
    {
        $query = 'SELECT name, studentProfile, program, acad_year FROM students WHERE student_id = ?';
        return $this->query($query, [$studentId]);
    }

    public function checkAttendance($attenId, $studentId): bool|array
    {
        $query = 'SELECT * FROM attendance_record WHERE atten_id = ? AND student_id = ?';
        return $this->query($query, [$attenId, $studentId]);
    }
    public function checkAttendance2($attenId, $studentId): bool|array
    {
        $query = 'SELECT * FROM attendance_record WHERE atten_id = ? AND student_id = ? AND time_out is not null';
        return $this->query($query, [$attenId, $studentId]);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function recordAttendance($attenId, $studentId): bool|array
    {
        try {
            $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
            $formattedTime = $date->format('h:i:s A'); // 12-hour format with AM/PM

            $query = 'INSERT INTO attendance_record (atten_id, student_id, time_in) VALUES (?,?,?);';
            $result = $this->query($query, [$attenId, $studentId, $formattedTime]);
            
            if ($result === false) {
                error_log("Error in recordAttendance: Failed to insert attendance record");
                return false;
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error in recordAttendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @throws DateMalformedStringException
     */
    public function recordAttendance2($attenId, $studentId): bool|array
    {
        try {
            $date = new DateTime("now", new DateTimeZone('Asia/Manila'));
            $formattedTime = $date->format('h:i:s A'); // 12-hour format with AM/PM

            $query = 'CALL sp_insert_attendance_record_TimeOut(?, ?, ?)';
            $result = $this->query($query, [$attenId, $studentId, $formattedTime]);
            
            if ($result === false) {
                error_log("Error in recordAttendance2: Failed to insert attendance record");
                return false;
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error in recordAttendance2: " . $e->getMessage());
            return false;
        }
    }

    public function insertQRCode($code, $student_id): bool|array
    {
        $query = "INSERT INTO qr_code (qr_value, student_id) VALUES (:code, :student_id)";
        $params = [
            ':code' => $code,
            ':student_id' => $student_id
        ];
        return $this->query($query, $params);
    }

    /**
     * @throws RandomException
     */
    public function generateQRCode($student_id): string
    {
        return strtoupper(bin2hex(random_bytes(4))) . '-' . $student_id;
    }

}