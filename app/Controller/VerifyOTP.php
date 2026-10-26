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
                $userId = $_SESSION['reset_user_id'] ?? null;

                if ($userId === null || !isset($_SESSION['reset_otp'])
                    || time() > ($_SESSION['reset_expiry'] ?? 0)) {
                    $_SESSION['error'] = "OTP expired. Please request a new one.";
                    header('Location: ' . ROOT . 'forgot-password');
                    exit();
                }

                if ($newPassword !== $confirmPassword) {
                    $_SESSION['error'] = "Passwords do not match.";
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit();
                }

                // Verify OTP from session
                if (hash_equals((string)$_SESSION['reset_otp'], $otp)) {
                    // Same hashing as login (SHA2-256 in MySQL)
                    try {
                        $stmt = $this->connect()->prepare("UPDATE user_account SET pass = SHA2(?, 256) WHERE id = ?");
                        $stmt->execute([$newPassword, $userId]);
                        $verify = $this->connect()->prepare("SELECT COUNT(*) FROM user_account WHERE id = ? AND pass = SHA2(?, 256)");
                        $verify->execute([$userId, $newPassword]);
                        $saved = (int)$verify->fetchColumn() > 0;
                    } catch (\PDOException $e) {
                        error_log("Password reset error: " . $e->getMessage());
                        $saved = false;
                    }

                    if (!$saved) {
                        $_SESSION['error'] = "Failed to update password. Please try again.";
                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        exit();
                    }

                    // Clear session
                    unset($_SESSION['reset_otp']);
                    unset($_SESSION['reset_user_id']);
                    unset($_SESSION['reset_expiry']);

                    $nameStmt = $this->connect()->prepare("SELECT username FROM user_account WHERE id = ?");
                    $nameStmt->execute([$userId]);
                    $exactUsername = $nameStmt->fetchColumn();

                    $_SESSION['success'] = "Password has been reset successfully. Please login with username \"" . htmlspecialchars((string)$exactUsername) . "\" (exact spelling) and your new password.";
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