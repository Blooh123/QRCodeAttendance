<?php

use Random\RandomException;

require_once 'Database.php';
Trait Model
{
    use Database;

    /**
     * @throws DateMalformedStringException
     */
    function createSession($userId, $role, $token): void
    {
        // Generate a secure token
        $token1 = $token;

        // Get accurate IP address
        $ipAddress = $this->getUserIP();

        // Get device information (OS & Browser)
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $deviceInfo = $this->getDeviceInfo($userAgent);

        // Create a DateTime object now in Asia/Manila
        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $created = $now->format('Y-m-d H:i:s'); // Save 'created_at' first

        // Clone the $now object to calculate expiry
        $expiry = clone $now;
        if ($role === 'student') {
            $expiry->modify('+20 minutes');
        } else {
            $expiry->modify('+2 days');
        }
        $expiresAt = $expiry->format('Y-m-d H:i:s');

        // Insert into database
        $stmt = $this->connect()->prepare("INSERT INTO user_sessions (user_id, role, token, user_agent, ip_address, deviceInfo, created_at, expires_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $role, $token1, $userAgent, $ipAddress, $deviceInfo, $created, $expiresAt]);
    }


// Function to get the actual IP address
    function getUserIP(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            // Convert IPv6 localhost (::1) to 127.0.0.1
            if ($ip === '::1') {
                return '127.0.0.1';
            }
            return $ip;
        }
        return 'UNKNOWN';
    }


// Function to detect user device (OS & Browser)
    function getDeviceInfo($userAgent): string
    {
        $os = "Unknown OS";
        $browser = "Unknown Browser";

        // Detect OS
        if (preg_match('/Windows/i', $userAgent)) $os = "Windows";
        elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) $os = "Mac OS";
        elseif (preg_match('/Linux/i', $userAgent)) $os = "Linux";
        elseif (preg_match('/Android/i', $userAgent)) $os = "Android";
        elseif (preg_match('/iPhone|iPad|iPod/i', $userAgent)) $os = "iOS";

        // Detect Browser
        if (preg_match('/Chrome/i', $userAgent)) $browser = "Google Chrome";
        elseif (preg_match('/Firefox/i', $userAgent)) $browser = "Mozilla Firefox";
        elseif (preg_match('/Safari/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) $browser = "Apple Safari";
        elseif (preg_match('/Edge/i', $userAgent)) $browser = "Microsoft Edge";
        elseif (preg_match('/MSIE|Trident/i', $userAgent)) $browser = "Internet Explorer";
        elseif (preg_match('/Opera|OPR/i', $userAgent)) $browser = "Opera";

        return "$os - $browser";
    }



    //validate user
    public function validateLogIn($username, $password){
        $query = 'CALL sp_validate_user(:username, :password)';
        $result = $this->query($query, ['username' => $username, 'password' => $password]);
        return $result[0];
    }

    public function checkSession($userID){
        $query = 'CALL sp_check_session(:id)';
        $result = $this->query($query, ['id' => $userID]);
        return $result[0] ?? null;
    }

    //dashboard stuff
    public function getAllStudentsCount() {
        $stmt = $this->connect()->prepare("SELECT * FROM countstudents");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_NUM);
        return $result[0];
    }
    public function getAllAttendanceCount() {
        $stmt = $this->connect()->prepare("SELECT * FROM countattendance");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_NUM);
        return $result[0];
    }
    public function getAllFaciCount() {
        $stmt = $this->connect()->prepare("SELECT * FROM countfaci");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_NUM);
        return $result[0];
    }

    public function getAllFaci(): array
    {
        $stmt = $this->connect()->prepare("SELECT * FROM viewfaci");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

    public function getAllAttendance(): array
    {
        $stmt = $this->connect()->prepare("SELECT * FROM viewattendance");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }


    public function updateStatus($username, $status): void
    {
        $query = "UPDATE users SET state = ? WHERE username = ?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$status, $username]);
    }


    //delete attendance record of student
    public function deleteAttendanceRecord($id): bool|array
    {
        $query2 = "DELETE FROM attendance_record WHERE student_id = :id";
        $params2 = [
            ':id' => $id
        ];

        return $this->query($query2, $params2);

    }





}