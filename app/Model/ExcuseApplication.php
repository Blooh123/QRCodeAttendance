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
            // date time asia/manila
            $dateTime = new \DateTime('Asia/Manila');
            $dateTime->setTimezone(new \DateTimeZone('Asia/Manila'));
            $dateTimeString = $dateTime->format('Y-m-d H:i:s');
            
            $query = "INSERT INTO excuse_application (atten_id, student_id, application_description, document1, document2, application_status, date_submitted) 
                      VALUES (:atten_id, :student_id, :description, :document1, :document2, 0, :date_submitted)";
            
            $params = [
                ':atten_id' => $attenId,
                ':student_id' => $studentId,
                ':description' => $description,
                ':document1' => $document1,
                ':document2' => $document2,
                ':date_submitted' => $dateTimeString,
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

    public function updateExcuseApplicationStatus($id, $status, $remarks = ''): bool
    {
        try {
            $query = "UPDATE excuse_application SET application_status = :status, admin_remarks = :remarks WHERE id = :id";
            $params = [
                ':id' => $id,
                ':status' => $status,
                ':remarks' => $remarks
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
                             s.name, s.program, s.acad_year
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
                             s.name, s.program, s.acad_year
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

    public function getApprovedExcuseApplications(): array
    {
        try {
            $query = "SELECT ea.*, a.event_name, a.date_created as event_date, 
                             s.name, s.program, s.acad_year
                      FROM excuse_application ea 
                      INNER JOIN attendance a ON ea.atten_id = a.atten_id 
                      INNER JOIN students s ON ea.student_id = s.student_id 
                      WHERE ea.application_status = 1
                      ORDER BY ea.id DESC";
            
            $result = $this->query($query);
            return is_array($result) ? $result : [];
        } catch (Exception $e) {
            error_log("Error getting approved excuse applications: " . $e->getMessage());
            return [];
        }
    }

    public function getRejectedExcuseApplications(): array
    {
        try {
            $query = "SELECT ea.*, a.event_name, a.date_created as event_date, 
                             s.name, s.program, s.acad_year
                      FROM excuse_application ea 
                      INNER JOIN attendance a ON ea.atten_id = a.atten_id 
                      INNER JOIN students s ON ea.student_id = s.student_id 
                      WHERE ea.application_status = 2
                      ORDER BY ea.id DESC";
            
            $result = $this->query($query);
            return is_array($result) ? $result : [];
        } catch (Exception $e) {
            error_log("Error getting rejected excuse applications: " . $e->getMessage());
            return [];
        }
    }

    public function searchApplication($searchQuery){
        try{
            $query = "SELECT ea.*, a.event_name, a.date_created as event_date, 
                      s.name, s.program, s.acad_year
                      FROM excuse_application ea 
                      INNER JOIN attendance a ON ea.atten_id = a.atten_id 
                      INNER JOIN students s ON ea.student_id = s.student_id 
                      WHERE ea.application_description LIKE '%$searchQuery%' OR
                      a.event_name LIKE '%$searchQuery%' OR
                      s.name LIKE '%$searchQuery%' OR
                      s.program LIKE '%$searchQuery%' OR
                      s.acad_year LIKE '%$searchQuery%'
                      ORDER BY ea.id DESC";
            $result = $this->query($query);         
            return is_array($result) ? $result : [];
        }
        catch(Exception $e){
            error_log("Error searching application: " . $e->getMessage());
            return [];
        }
    }

    public function getExcuseApplicationsByStatus($status): array
    {
        try {
            $query = "SELECT ea.*, a.event_name, a.date_created as event_date, 
                             s.name, s.program, s.acad_year
                      FROM excuse_application ea 
                      INNER JOIN attendance a ON ea.atten_id = a.atten_id 
                      INNER JOIN students s ON ea.student_id = s.student_id 
                      WHERE ea.application_status = :status
                      ORDER BY ea.id DESC";
            
            $params = [':status' => $status];
            $result = $this->query($query, $params);
            return is_array($result) ? $result : [];
        } catch (Exception $e) {
            error_log("Error getting excuse applications by status: " . $e->getMessage());
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
                $document = $result[0][$column];
                // Debug: Check if document is not null and has content
                if ($document && strlen($document) > 0) {
                    return $document;
                } else {
                    error_log("Document is empty or null for ID: $id, Document: $documentNumber");
                    return null;
                }
            }
            
            error_log("No document found for ID: $id, Document: $documentNumber");
            return null;
        } catch (Exception $e) {
            error_log("Error getting document: " . $e->getMessage());
            return null;
        }
    }

    public function getDocumentInfo($id, $documentNumber): ?array
    {
        try {
            $document = $this->getDocument($id, $documentNumber);
            if (!$document) {
                return null;
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $document);
            finfo_close($finfo);

            $size = strlen($document);
            $extension = 'bin';
            $icon = 'fas fa-file';

            switch ($mimeType) {
                case 'application/pdf':
                    $extension = 'pdf';
                    $icon = 'fas fa-file-pdf';
                    break;
                case 'image/jpeg':
                case 'image/jpg':
                    $extension = 'jpg';
                    $icon = 'fas fa-file-image';
                    break;
                case 'image/png':
                    $extension = 'png';
                    $icon = 'fas fa-file-image';
                    break;
                case 'image/gif':
                    $extension = 'gif';
                    $icon = 'fas fa-file-image';
                    break;
                case 'application/msword':
                    $extension = 'doc';
                    $icon = 'fas fa-file-word';
                    break;
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    $extension = 'docx';
                    $icon = 'fas fa-file-word';
                    break;
                case 'application/vnd.ms-excel':
                    $extension = 'xls';
                    $icon = 'fas fa-file-excel';
                    break;
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    $extension = 'xlsx';
                    $icon = 'fas fa-file-excel';
                    break;
            }

            return [
                'mime_type' => $mimeType,
                'extension' => $extension,
                'size' => $size,
                'size_formatted' => $this->formatFileSize($size),
                'icon' => $icon
            ];
        } catch (Exception $e) {
            error_log("Error getting document info: " . $e->getMessage());
            return null;
        }
    }

    private function formatFileSize($bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
} 