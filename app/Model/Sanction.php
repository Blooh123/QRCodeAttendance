<?php

namespace Model;
require_once '../app/core/Database.php';
use PDO;
class Sanction
{
    use \Database;
    public function insertSanction($student_id, $reason, $hours, $date): bool|array
    {
        $sql = "CALL sp_insert_sanction(?,?,?,?)";
        $params = [
            $student_id,
            $reason,
            $hours,
            $date
        ];
        return $this->query2($sql, $params);
    }


    public function deleteSanction($sanction_id): bool|array
    {
        $sql = "CALL sp_delete_sanction(?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $sanction_id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteSanction2($student_id): bool|array
    {
        $sql = "DELETE FROM sanction WHERE student_id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $student_id);
        $stmt->execute();
        return $stmt->rowCount();
    }


    public function getStudentSanctions($student_id): array
    {
        $sql = "CALL sp_get_student_sanctions(:id)";
        $stm = $this->connect()->prepare($sql);
        $stm->bindParam(":id", $student_id);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

// ...existing code...
    public function getSanctionSummary($student_id = null, $yearStart = null): array
    {
        // If no filters provided, keep existing stored-proc behaviour
        if ($student_id === null && $yearStart === null) {
            $sql = "CALL sp_get_sanctions_summary()";
            $stm = $this->connect()->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        }

        // Build dynamic query with optional student and academic-year filter (July 1 -> next year June 30)
        $sql = "SELECT * FROM sanction WHERE 1=1";
        if ($student_id !== null) {
            $sql .= " AND student_id = :id";
        }
        if ($yearStart !== null) {
            $start = sprintf('%04d-07-01', (int)$yearStart);
            $end = sprintf('%04d-06-30', ((int)$yearStart) + 1);
            $sql .= " AND date_applied BETWEEN :start AND :end";
        }
        $sql .= " ORDER BY date_applied DESC";

        $stm = $this->connect()->prepare($sql);
        if ($student_id !== null) {
            $stm->bindValue(':id', $student_id);
        }
        if ($yearStart !== null) {
            $stm->bindValue(':start', $start);
            $stm->bindValue(':end', $end);
        }
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
// ...existing code...

}