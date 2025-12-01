<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/core/Database.php';
require_once '../app/Model/User.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Random\RandomException;
session_start();
class ForgotPassword extends \Controller
{
    use \Database;

    public function index(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['email'])) {
                $email = trim($_POST['email']);
                
                // Check if email exists in database
                $stmt = $this->connect()->prepare("SELECT id, username FROM users WHERE username = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user) {
                    // Generate OTP
                    $otp = sprintf("%06d", random_int(0, 999999));
                    
                    // Store OTP in session
                    $_SESSION['reset_otp'] = $otp;
                    $_SESSION['reset_user_id'] = $user['id'];
                    $_SESSION['reset_expiry'] = time() + (15 * 60); // 15 minutes expiry

                    // Send OTP via email
                    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                    try {
                        // Server settings
                        $mail->SMTPDebug = SMTP::DEBUG_OFF;
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'ddtiongson00006@usep.edu.ph'; // usep.qrattendance
                        $mail->Password = 'cwqt aoet uyew snsk'; // vvyg egpy egtv ajms
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Recipients
                        $mail->setFrom('usep.qrattendance@gmail.com', 'USeP QR Attendance');
                        $mail->addAddress($email, $user['username']);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset OTP';
                        $mail->Body = "
                            <h2>Password Reset Request</h2>
                            <p>Hello {$user['username']},</p>
                            <p>Your OTP for password reset is: <strong>{$otp}</strong></p>
                            <p>This OTP will expire in 15 minutes.</p>
                            <p>If you didn't request this, please ignore this email.</p>
                        ";

                        $mail->send();
                        $_SESSION['success'] = "OTP has been sent to your email.";
                        header('Location: ' . ROOT . 'verify-otp');
                        exit();
                    } catch (Exception $e) {
                        $_SESSION['error'] = "Failed to send OTP. Please try again.";
                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Email not found in our records.";
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit();
                }
            }
        }
        $this->loadView('forgot-password');
    }
}

$forgotPassword = new ForgotPassword();
try {
    $forgotPassword->index();
} catch (RandomException $e) {
    $_SESSION['error'] = "An error occurred. Please try again.";
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
} 