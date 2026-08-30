<?php

namespace Model;

require_once __DIR__ . '/../core/Database.php';

use PDO;
use PDOException;

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

    public function getUserPermissions($userId): array
    {
        $stmt = $this->connect()->prepare(
            "SELECT permissions FROM user_account WHERE id = ?"
        );

        $stmt->execute([$userId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result['permissions'] ? json_decode($result['permissions'], true) ?? [] : [];
    }

    public function updatePersonalInfo($id, $name, $email): bool|array
    {
        $query = "
            UPDATE user_personal_info
            SET name = :name, email = :email
            WHERE id = :id
        ";

        $params = [
            ':id' => $id,
            ':name' => $name,
            ':email' => $email
        ];

        return $this->query($query, $params);
    }

    public function deleteUsers($id): bool|array
    {
        $queries = [
            "DELETE FROM user_sessions WHERE user_id = :id",
            "DELETE FROM facilitator_facial_images WHERE user_id = :id",
            "DELETE FROM user_personal_info WHERE id = :id",
            "DELETE FROM user_account WHERE id = :id"
        ];

        try {
            $conn = $this->connect();
            $conn->beginTransaction();

            foreach ($queries as $query) {
                $stmt = $conn->prepare($query);
                $stmt->execute([':id' => $id]);
            }

            $conn->commit();
            return true;
        } catch (PDOException $e) {
            if (isset($conn) && $conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log('Failed to delete user: ' . $e->getMessage());
            return false;
        }
    }

    public function getUserSession($userId): array
    {
        $sql = "
            SELECT *
            FROM user_sessions
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT 1
        ";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result ?: [];
    }

    public function getFacialImages($userId): array
    {
        $sql = "
            SELECT *
            FROM facilitator_facial_images
            WHERE user_id = :user_id
        ";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result;
    }

    public function getFacialImageById($imageId): ?array
    {
        $sql = "
            SELECT *
            FROM facilitator_facial_images
            WHERE id = :id
            LIMIT 1
        ";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':id', $imageId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        $stmt = null;

        return $result ?: null;
    }

    public function deleteFacialImage($imageId, $userId): bool
    {
        $sql = "
            DELETE FROM facilitator_facial_images
            WHERE id = :id AND user_id = :user_id
        ";

        return (bool) $this->query($sql, [
            ':id' => $imageId,
            ':user_id' => $userId
        ]);
    }

    public function updateUserPermissions($userId, $permissionsJson): bool|array
    {
        $query = "
            UPDATE user_account
            SET permissions = :permissions
            WHERE id = :id
        ";

        $params = [
            ':id' => $userId,
            ':permissions' => $permissionsJson
        ];

        return $this->query($query, $params);
    }
}

}