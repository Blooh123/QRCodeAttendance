<?php

namespace Model;
require_once '../app/core/Database.php';

use PDO;

class User
{
    use \Database;
    public function deleteUsers($id): bool|array
    {
        $query = "DELETE FROM user_personal_info WHERE id = :id";
        $params = [
            ':id' => $id
        ];
        $this->query($query, $params);
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
        $stmt = $this->connect()->prepare("CALL sp_get_user_detail()");
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
        $query = "CALL sp_search_users(:searchQuery)";
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
    public function insertPersonalInformation($id, $name, $email): bool|array{
        $query = "INSERT INTO user_personal_info (id, name, email) VALUES (:id, :name, :email)";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':email' => $email
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
        $query = 'CALL sp_verify_pass(?,?)';
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$current_password, $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id): bool|array{
        $query = "DELETE FROM user_personal_info WHERE id = :id";
        $params = [
            ':id' => $id
        ];
        $this->query($query, $params);
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


    public function deleteSession(): bool|array{
        $query = "DELETE FROM user_sessions WHERE expires_at < NOW()";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                CALL sp_check_session(?)
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
        $sql = 'CALL sp_get_user_data(:id)';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkIfUserNameExists($id, $username){
        // Call the stored procedure to check if the username or ID exists
        $stmt = $this->connect()->prepare("CALL sp_check_if_user_name_exists(?, ?)");
        $stmt->execute([$username, $id]);

        // Fetch the result from the stored procedure
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePersonalInfo($id, $name, $email): bool|array
    {
        $query = "UPDATE user_personal_info SET name = :name, email = :email WHERE id = :id";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':email' => $email
        ];
        return $this->query($query, $params);
    }

    public function getUserDataWithPersonalInfo($id): array
    {
        $sql = 'CALL sp_get_user_data_with_personal_info(:id)';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }





}