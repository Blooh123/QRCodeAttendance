<?php

namespace Controller;



use DateMalformedStringException;
use DateTime;
use DateTimeZone;
use PDO;
class DeleteSessions
{
    public function connect(): PDO
    {
        $string = "mysql:host="."localhost".";dbname="."u753706103_qr_attendance";
        $con = new PDO($string, "u753706103_christian", "mZ2~G76JP1s5=B=Cy1L*");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
        return $con;
    }

    /**
     * @throws DateMalformedStringException
     */
    public function deleteSession(): bool|array
    {

        // Get current Philippine time
        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $philippineNow = $now->format('Y-m-d H:i:s');

        // Delete sessions where expires_at is earlier than Philippine time
        $query = "DELETE FROM user_sessions WHERE expires_at < :now";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':now', $philippineNow);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}

$deleteSession = new DeleteSessions();
try {
    $deleteSession->deleteSession();
} catch (DateMalformedStringException $e) {

}