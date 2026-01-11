<?php

namespace Controller;
require_once '../app/core/config.php';
class FacilitatorRegister extends \Controller{
    public function index(){
        $this->loadView('facilitator_register');
    }
    
    public function validateAccount(){
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT . 'registration17236463?error=invalid_request');
            return;
        }
        
        // Get POST data
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        // Validate input
        if (empty($username) || empty($password)) {
            header('Location: ' . ROOT . 'registration17236463?error=missing_data');
            return;
        }
        
  
            // Database configuration
            if($_SERVER['SERVER_NAME'] == 'localhost'){
                define('DBNAME', 'qrcode_attendance_system');
                define('DBUSER', 'root');
                define('DBPASS', '');
                define('DBHOST', 'localhost');
                define('DBPORT', '3306');
            }else{
                define('DBNAME', 'u753706103_qr_attendance');
                define('DBUSER', 'u753706103_christian');
                define('DBPASS', 'mZ2~G76JP1s5=B=Cy1L*');
                define('DBHOST', 'localhost');
                define('DBPORT', '3306');
            }
            
            // Create database connection
            $pdo = new \PDO(
                "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";port=" . DBPORT,
                DBUSER,
                DBPASS,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            
            // Check if user exists and validate credentials
            $query = "SELECT id, username, pass, roles FROM users WHERE username = :username";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch();
            print_r($user);
            
            if (!$user) {
                header('Location: ' . ROOT . 'registration17236463?error=user_not_found');
                return;
            }
            
            // Debug: Print role and hashed password using print_r
            echo "<pre>";
            echo "DEBUG - User Role: ";
            print_r($user['roles']);
            echo "\nDEBUG - Stored Hash: ";
            print_r($user['pass']);
            echo "\nDEBUG - Input Password: ";
            print_r($password);
            echo "\nDEBUG - Input Password Hash: ";
            print_r(hash('sha256', $password));
            echo "</pre>";
            
            // Check if user is a facilitator
            if ($user['roles'] !== 'Facilitator') {
                header('Location: ' . ROOT . 'registration17236463?error=invalid_role');
                return;
            }
            
            // Verify password using SHA256 hash directly in SQL query
            $query = "SELECT id, username, pass, roles FROM users WHERE username = :username AND pass = SHA2(:password, 256)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $user = $stmt->fetch();
            
            if (!$user) {
                header('Location: ' . ROOT . 'registration17236463?error=invalid_credentials');
                return;
            }
            
            // Check if user already has facial images
            $facialQuery = "SELECT COUNT(*) as count FROM facilitator_facial_images WHERE user_id = :user_id";
            $facialStmt = $pdo->prepare($facialQuery);
            $facialStmt->bindParam(':user_id', $user['id']);
            $facialStmt->execute();
            $facialCount = $facialStmt->fetch();
            
            if ($facialCount['count'] > 0) {
                header('Location: ' . ROOT . 'registration17236463?error=already_registered');
                return;
            }
            
            // Validation successful - redirect to face-register page
            header('Location: ' . ROOT . 'face-register?user_id=' . $user['id'] . '&username=' . urlencode($user['username']));
            return;
            

    }
}

$facilitatorRegister = new FacilitatorRegister();
// if request id POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $facilitatorRegister->validateAccount();
}else{
    $facilitatorRegister->index();
}