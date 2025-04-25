<?php

namespace Model;
require_once '../app/core/Database.php';

use PDO;

class User
{
    use \Database;
    public function deleteUsers($id): bool|array
    {
        $query = "DELETE FROM users WHERE id = :id";
        $params = [
            ':id' => $id
        ];
        return $this->query($query, $params);
    }

    public function updateStatus($id, $status): void
    {
        $query = "UPDATE users SET state = ? WHERE id = ?";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$status, $id]);
    }


    //pagination stuff
    public function getAllUsers(): array
    {
        $stmt = $this->connect()->prepare("CALL GetUserDetails()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

// Count total users for pagination
    public function getUserCount() {
        $stmt = $this->connect()->prepare("SELECT * FROM countusers");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
    }

    public function searchUsers($searchQuery): array
    {
        $query = "CALL searchUsers(:searchQuery)";
        $stmt = $this->connect()->prepare($query);
        $searchTerm = "%$searchQuery%";
        $stmt->bindParam(':searchQuery', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertUser($id, $username, $password,$role): bool|array
    {
        $query = "INSERT INTO users (id, username, pass, roles, state) VALUES (:id, :username, :password, :role, :state)";
        $pass = $password;
        $hashed_pass = hash('sha256', $pass);
        $params = [
            ':id' => $id,
            ':username' => $username,
            ':password' => $hashed_pass,
            ':role' => $role,
            ':state' => 'offline'
        ];
        return $this->query($query, $params);
    }
    public function updateUser($id, $username): bool|array
    {
        $query = "UPDATE users SET username = :username WHERE id = :id";
        $params = [
            ':id' => $id,
            ':username' => $username
        ];
        return $this->query($query, $params);
    }

    public function updatePassword($id, $password): bool|array{
        $query = 'UPDATE users SET pass = SHA2(:password,256) WHERE id = :id';
        $params = [
            ':id' => $id,
            ':password' => $password
        ];
        return $this->query($query, $params);
    }
    public function password_verify($current_password, $id) {
        $query = 'CALL verify_pass(?,?)';
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$current_password, $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id): bool|array{
        $query = "DELETE FROM users WHERE id = :id";
        $query2 = "DELETE FROM user_sessions WHERE user_id = :id";
        $params = [
            ':id' => $id
        ];
        $params2 = [
            ':id' => $id
        ];
        $this->query($query2, $params2);//delete all sessions
        return $this->query($query, $params);
    }


    function checkSession($url) {
        if (isset($_COOKIE['user_data'])) {

            // Decode JSON cookie into an array
            $userSessions = json_decode($_COOKIE['user_data'], true);

            // Ensure it's a valid array and contains sessions
            if (!is_array($userSessions) || empty($userSessions)) {
                header('Location: /logout');
                exit();
            }

            // Iterate through each session stored in the cookie
            foreach ($userSessions as $session) {
                // Ensure session data contains required fields
                if (!isset($session['auth_token'], $session['user_id'], $session['role'])) {
                    continue; // Skip invalid session entries
                }

                $token = $session['auth_token'];
                $userId = $session['user_id'];
                $role = $session['role'];

                // Query to check if token exists in user_sessions table
                $stmt = $this->connect()->prepare("
                SELECT user_id, role FROM user_sessions 
                WHERE token = ?
            ");
                $stmt->execute([$token]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Update user status to 'login'
                    $this->updateStatus($user['user_id'], 'login');

                    // Ensure role matches the required access for the page
                    if ($user['role'] === $role) {
                        return $user; // Return the valid session
                    }
                }
            }

        }
        return null;
    }

    public function getUserData($id): array
    {
        $sql = 'CALL getUserData(:id)';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkIfUserNameExists($id, $username){
        // Call the stored procedure to check if the username or ID exists
        $stmt = $this->connect()->prepare("CALL checkIfUserNameExists(?, ?)");
        $stmt->execute([$username, $id]);

        // Fetch the result from the stored procedure
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }





}