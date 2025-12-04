<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/core/Model.php';
require_once '../app/Model/User.php';
session_start();
class VerifyOTP extends \Controller
{
    use \Database;

    public function index(): void
    {
       

  

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['otp']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
                $otp = trim($_POST['otp']);
                $newPassword = trim($_POST['new_password']);
                $confirmPassword = trim($_POST['confirm_password']);
                $userId = $_SESSION['reset_user_id'];

                if ($newPassword !== $confirmPassword) {
                    $_SESSION['error'] = "Passwords do not match.";
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit();
                }

                // Verify OTP from session
                if ($otp === $_SESSION['reset_otp']) {
                    // Update password using SHA256
                    $hashedPassword = hash('sha256', $newPassword);
                    $stmt = $this->connect()->prepare("UPDATE user_account SET pass = ? WHERE id = ?");
                    $stmt->execute([$hashedPassword, $userId]);

                    // Clear session
                    unset($_SESSION['reset_otp']);
                    unset($_SESSION['reset_user_id']);
                    unset($_SESSION['reset_expiry']);

                    $_SESSION['success'] = "Password has been reset successfully. Please login with your new password.";
                    header('Location: ' . ROOT . 'login');
                    exit();
                } else {
                    $_SESSION['error'] = "Invalid OTP.";
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit();
                }
            }
        }
        $this->loadView('verify-otp');
    }
}

$verifyOTP = new VerifyOTP();
$verifyOTP->index(); 