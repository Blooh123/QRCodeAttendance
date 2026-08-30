<?php

namespace Model;

require_once __DIR__ . '/../core/Database.php';

use PDO;

if (!class_exists('Model\User')) {

class User
{
    use \Database;

    /*
    |--------------------------------------------------------------------------
    | USER STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus($id, $status): void
    {
        $query = "UPDATE user_account SET state = ? WHERE id = ?";

        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$status, $id]);

        $stmt->closeCursor();
        $stmt = null;
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

    public function getAllUsers(): array
    {
        $stmt = $this->connect()->prepare("CALL sp_get_user_detail()");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | USER COUNT
    |--------------------------------------------------------------------------
    */

    public function getUserCount()
    {
        $stmt = $this->connect()->prepare("SELECT COUNT(*) as total FROM countusers");
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result['total'];
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH USERS
    |--------------------------------------------------------------------------
    */

    public function searchUsers($searchQuery): array
    {
        $query = "CALL sp_search_users(:searchQuery)";

        $stmt = $this->connect()->prepare($query);

        $searchTerm = "%$searchQuery%";
        $stmt->bindParam(':searchQuery', $searchTerm);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT USER
    |--------------------------------------------------------------------------
    */

    public function insertUser($id, $username, $password, $role): bool|array
    {
        $query = "
            INSERT INTO user_account 
            (id, username, pass, roles, state)
            VALUES 
            (:id, :username, :password, :role, :state)
        ";

        $hashed_pass = hash('sha256', $password);

        $params = [
            ':id' => $id,
            ':username' => $username,
            ':password' => $hashed_pass,
            ':role' => $role,
            ':state' => 'offline'
        ];

        return $this->query($query, $params);
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT PERSONAL INFO
    |--------------------------------------------------------------------------
    */

    public function insertPersonalInformation($id, $name, $email): bool|array
    {
        $query = "
            INSERT INTO user_personal_info 
            (id, name, email)
            VALUES 
            (:id, :name, :email)
        ";

        $params = [
            ':id' => $id,
            ':name' => $name,
            ':email' => $email
        ];

        return $this->query($query, $params);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function updateUser($id, $username): bool|array
    {
        $query = "
            UPDATE user_account 
            SET username = :username 
            WHERE id = :id
        ";

        $params = [
            ':id' => $id,
            ':username' => $username
        ];

        return $this->query($query, $params);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function updatePassword($id, $password): bool|array
    {
        $query = "
            UPDATE user_account 
            SET pass = SHA2(:password,256)
            WHERE id = :id
        ";

        $params = [
            ':id' => $id,
            ':password' => $password
        ];

        return $this->query($query, $params);
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY PASSWORD
    |--------------------------------------------------------------------------
    */

    public function password_verify($current_password, $id)
    {
        $query = "CALL sp_verify_pass(?, ?)";

        $stmt = $this->connect()->prepare($query);
        $stmt->execute([$current_password, $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK SESSION
    |--------------------------------------------------------------------------
    */

    public function checkSession($url)
    {
        if (!isset($_COOKIE['user_data'])) {
            return null;
        }

        $userSessions = json_decode($_COOKIE['user_data'], true);

        if (!is_array($userSessions) || empty($userSessions)) {
            header('Location: /logout');
            exit();
        }

        foreach ($userSessions as $session) {

            if (
                !isset(
                    $session['auth_token'],
                    $session['user_id'],
                    $session['role']
                )
            ) {
                continue;
            }

            $token = $session['auth_token'];
            $role = $session['role'];

            $stmt = $this->connect()->prepare(
                "CALL sp_check_session(?)"
            );

            $stmt->execute([$token]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // VERY IMPORTANT
            $stmt->closeCursor();
            $stmt = null;

            if ($user) {

                $this->updateStatus(
                    $user['user_id'],
                    'login'
                );

                if ($user['role'] === $role) {
                    return $user;
                }
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER DATA
    |--------------------------------------------------------------------------
    */

    public function getUserData($id): array
    {
        $sql = "CALL sp_get_user_data(:id)";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK USERNAME EXISTS
    |--------------------------------------------------------------------------
    */

    public function checkIfUserNameExists($id, $username)
    {
        $stmt = $this->connect()->prepare(
            "CALL sp_check_if_user_name_exists(?, ?)"
        );

        $stmt->execute([$username, $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER DATA WITH PERSONAL INFO
    |--------------------------------------------------------------------------
    */

    public function getUserDataWithPersonalInfo($id): array
    {
        $sql = "
            SELECT * 
            FROM user_account
            LEFT JOIN user_personal_info 
            ON user_account.id = user_personal_info.id
            WHERE user_account.id = :id
        ";

        $stmt = $this->connect()->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result ?: [];
    }
}

}