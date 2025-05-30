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

    public function getSanctionSummary(): array
    {
        $sql = "CALL sp_get_sanctions_summary()";
        $stm = $this->connect()->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

}