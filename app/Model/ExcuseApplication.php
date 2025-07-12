<?php

namespace Model;

// Get the project root directory
$projectRoot = dirname(dirname(__DIR__));
require_once $projectRoot . '/app/core/Database.php';

use Database;
use PDO;

class ExcuseApplication
{
    use Database;

    public function insertExcuseApplication($attenId, $studentId, $description, $document1 = null, $document2 = null): bool
    {
        try {
            $query = "INSERT INTO excuse_application (atten_id, student_id, application_description, document1, document2, application_status) 
                      VALUES (:atten_id, :student_id, :description, :document1, :document2, 0)";
            
            $params = [
                ':atten_id' => $attenId,
                ':student_id' => $studentId,
                ':description' => $description,
                ':document1' => $document1,
                ':document2' => $document2
            ];
            
            $result = $this->query($query, $params);
            return $result !== false;
        } catch (Exception $e) {
            error_log("Error inserting excuse application: " . $e->getMessage());
            return false;
        }
    }

    public function getExcuseApplicationsByStudent($studentId): array
    {
        try {
            $query = "SELECT ea.*, a.event_name, a.date_created as event_date 
                      FROM excuse_application ea 
                      INNER JOIN attendance a ON ea.atten_id = a.atten_id 
                      WHERE ea.student_id = :student_id 
                      ORDER BY ea.id DESC";
            
            $params = [':student_id' => $studentId];
            $result = $this->query($query, $params);
            
            return is_array($result) ? $result : [];
        } catch (Exception $e) {
            error_log("Error getting excuse applications: " . $e->getMessage());
            return [];
        }
    }

    public function getExcuseApplicationById($id): ?array
    {
        try {
            $query = "SELECT ea.*, a.event_name, a.date_created as event_date 
                      FROM excuse_application ea 
                      INNER JOIN attendance a ON ea.atten_id = a.atten_id 
                      WHERE ea.id = :id";
            
            $params = [':id' => $id];
            $result = $this->query($query, $params);
            
            if (is_array($result) && !empty($result)) {
                return $result[0];
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Error getting excuse application by ID: " . $e->getMessage());
            return null;
        }
    }

    public function updateExcuseApplicationStatus($id, $status): bool
    {
        try {
            $query = "UPDATE excuse_application SET application_status = :status WHERE id = :id";
            $params = [
                ':id' => $id,
                ':status' => $status
            ];
            
            $result = $this->query($query, $params);
            return $result !== false;
        } catch (Exception $e) {
            error_log("Error updating excuse application status: " . $e->getMessage());
            return false;
        }
    }

    public function getAllExcuseApplications(): array
    {
        try {
            $query = "SELECT ea.*, a.event_name, a.date_created as event_date, 
                             s.f_name, s.l_name, s.program, s.acad_year
                      FROM excuse_application ea 
                      INNER JOIN attendance a ON ea.atten_id = a.atten_id 
                      INNER JOIN students s ON ea.student_id = s.student_id 
                      ORDER BY ea.id DESC";
            
            $result = $this->query($query);
            return is_array($result) ? $result : [];
        } catch (Exception $e) {
            error_log("Error getting all excuse applications: " . $e->getMessage());
            return [];
        }
    }

    public function getPendingExcuseApplications(): array
    {
        try {
            $query = "SELECT ea.*, a.event_name, a.date_created as event_date, 
                             s.f_name, s.l_name, s.program, s.acad_year
                      FROM excuse_application ea 
                      INNER JOIN attendance a ON ea.atten_id = a.atten_id 
                      INNER JOIN students s ON ea.student_id = s.student_id 
                      WHERE ea.application_status = 0
                      ORDER BY ea.id DESC";
            
            $result = $this->query($query);
            return is_array($result) ? $result : [];
        } catch (Exception $e) {
            error_log("Error getting pending excuse applications: " . $e->getMessage());
            return [];
        }
    }

    public function checkExistingApplication($attenId, $studentId): bool
    {
        try {
            $query = "SELECT COUNT(*) as count FROM excuse_application 
                      WHERE atten_id = :atten_id AND student_id = :student_id";
            
            $params = [
                ':atten_id' => $attenId,
                ':student_id' => $studentId
            ];
            
            $result = $this->query($query, $params);
            
            if (is_array($result) && !empty($result)) {
                return $result[0]['count'] > 0;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Error checking existing application: " . $e->getMessage());
            return false;
        }
    }

    public function getDocument($id, $documentNumber): ?string
    {
        try {
            $column = $documentNumber == 1 ? 'document1' : 'document2';
            $query = "SELECT $column FROM excuse_application WHERE id = :id";
            
            $params = [':id' => $id];
            $result = $this->query($query, $params);
            
            if (is_array($result) && !empty($result) && isset($result[0][$column])) {
                return $result[0][$column];
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Error getting document: " . $e->getMessage());
            return null;
        }
    }
} 