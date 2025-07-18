<?php

namespace Model;

// Get the project root directory
$projectRoot = dirname(dirname(__DIR__));
require_once $projectRoot . '/app/core/Database.php';

use PDO;

class Student
{
    use \Database;
    public function checkIfEmailExists($email): bool
    {
        $query = "SELECT * FROM students WHERE email = :email";
        $params = [
            ':email' => $email
        ];
        $result = $this->query($query, $params);
        if($result){
            return true;
        }
        return false;
    }
    public function getStudentId($id): ?string
    {
        $query = "SELECT * FROM vw_students WHERE student_id = :id";
        $params = [
            ':id' => $id
        ];
        $result = $this->query($query, $params);


        // Check if the result is an array and contains at least one row
        if (is_array($result) && !empty($result)) {
            return (string) $result[0]['student_id']; // Return the 'id' as a string
        }

        // If no result is found, return null or an empty string
        return null; // or return '';
    }
    public function getAllStudents(): array
    {
        // Fetch all students from the database
        $query = "CALL sp_get_all_students()";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserCount() {
        $stmt = $this->connect()->prepare("SELECT * FROM countstudents");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
    }

    public function getFilteredStudents($program, $year): array
    {
        $query = "CALL sp_filter_students(:program, :year)";
        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam(':program', $program);
        $stmt->bindParam(':year', $year);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countFilteredStudents($program, $year) {
        $query = "CALL sp_count_filtered_students(:program, :year)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':program', $program);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
    }
    public function searchStudents($searchQuery): array
    {
        $query = "CALL sp_search_Students(:searchQuery)";
        $stmt = $this->connect()->prepare($query);
        $searchTerm = "%$searchQuery%";
        $stmt->bindParam(':searchQuery', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProgram(): array
    {
        $query = "CALL sp_get_all_program()";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllYear(): array
    {
        $query = "CALL sp_get_all_year()";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteStudent($id): bool|array
    {
        $query = "DELETE FROM students WHERE student_id = :id";
        $params = [
            ':id' => $id
        ];
        return $this->query($query, $params);
    }

    public function insertStudent($id, $f_name, $program, $acad_year, $email): bool|array
    {
        // Define the SQL query
        $query = "INSERT INTO students (student_id, name, program, acad_year, email, notified) 
              VALUES (:id, :name, :program, :acad_year, :email, 0)";

        // Define the parameters
        $params = [
            ':id' => $id,
            ':name' => $f_name,
            ':program' => $program,
            ':acad_year' => $acad_year, // Added missing parameter
            ':email' => $email
        ];

        // Execute the query
        return $this->query($query, $params);
    }

    public function updateStudent($id, $name, $program, $acad_year, $email): bool|array
    {
        $query = "UPDATE students SET name = :name, program = :program, acad_year = :year, email = :email WHERE student_id = :id";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':program' => $program,
            ':year' => $acad_year,
            ':email' => $email
        ];
        return $this->query($query, $params);
    }

    public function getStudentData($id): array
    {
        $sql = "CALL sp_get_student_data(:id)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: [];
    }


    public function getStudentInfo() {
        $userSessions = json_decode($_COOKIE['user_data'], true);
        $username = $userSessions[0]['username']; // Get the first logged-in user
        $email = $username;
        $sql = "SELECT * FROM students WHERE email = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllStudent(): array
    {
        $query = 'SELECT * FROM students';
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStudentsByProgramAndYear($programs, $years): array
    {
        $students = [];
        
        foreach ($programs as $i => $program) {
            $year = $years[$i] ?? '';
            
            if ($program === 'AllStudents') {
                // Get all students
                $query = "SELECT email, name, program, acad_year FROM students";
                $stmt = $this->connect()->prepare($query);
                $stmt->execute();
            } else {
                // Get students by specific program and year
                if (!empty($year)) {
                    $query = "SELECT email, name, program, acad_year FROM students WHERE program = :program AND acad_year = :year";
                    $stmt = $this->connect()->prepare($query);
                    $stmt->bindParam(':program', $program);
                    $stmt->bindParam(':year', $year);
                } else {
                    $query = "SELECT email, name, program, acad_year FROM students WHERE program = :program";
                    $stmt = $this->connect()->prepare($query);
                    $stmt->bindParam(':program', $program);
                }
                $stmt->execute();
            }
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $students = array_merge($students, $result);
        }
        
        // Remove duplicates based on email
        $uniqueStudents = [];
        $seenEmails = [];
        foreach ($students as $student) {
            if (!in_array($student['email'], $seenEmails)) {
                $uniqueStudents[] = $student;
                $seenEmails[] = $student['email'];
            }
        }
        
        return $uniqueStudents;
    }
    // Update profile picture in database
    public function updateProfilePicture($student_id, $imageData): bool
    {
        try {
            $sql = "UPDATE students SET studentProfile = :profile_picture WHERE student_id = :student_id";
            $stmt = $this->connect()->prepare($sql);
            $result = $stmt->execute([':profile_picture' => $imageData, ':student_id' => $student_id]);
            
            // Debug: Log the result
            error_log("UpdateProfilePicture - Student ID: $student_id, Result: " . ($result ? 'true' : 'false'));
            error_log("UpdateProfilePicture - Rows affected: " . $stmt->rowCount());
            
            return $result;
        } catch (Exception $e) {
            error_log("UpdateProfilePicture Error: " . $e->getMessage());
            return false;
        }
    }
}