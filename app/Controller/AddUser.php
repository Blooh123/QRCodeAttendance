<?php

namespace Controller;
require_once '../app/Model/User.php';
require_once '../app/Model/ActivityLog.php';
require_once '../vendor/autoload.php';

use Model\User;
use Model\ActivityLog;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AddUser extends \Controller
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user1 = new User();
            $userData = $user1->checkSession('add_user');
            // Validate input
            $username = $_POST['username'];
            $id = $_POST['id'];
            $password = $_POST['Password'];
            $confirmPassword = $_POST['ConfirmPassword'];
            $role = $_POST['role'];

            // Check if password matches confirmation password
            if ($password !== $confirmPassword) {
                echo "<script>alert('Password and confirmation password do not match.');</script>";
                $this->loadView('add_user');
                return;
            }

            $user = new User();
            $activityLog = new ActivityLog();
            if ($user->checkIfUserNameExists($id,$username)) {
                echo "<script>alert('Username or ID already exists.');</script>";
            }else{
                // Insert the new user
                if ($user->insertUser($id, $username, $confirmPassword,$role)) {
                    $fullName = trim($_POST['fullname'] ?? '');
                    $emailAddress = trim($_POST['email'] ?? '');
                    $user->insertPersonalInformation($id, $fullName, $emailAddress);
                    $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Added user: ' . $id, 'add_user');

                    if (!empty($emailAddress)) {
                        $mail = new PHPMailer(true);

                        try {
                            $mail->SMTPDebug = SMTP::DEBUG_OFF;
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'ddtiongson00006@usep.edu.ph';
                            $mail->Password = 'cwqt aoet uyew snsk';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $mail->setFrom('ddtiongson00006@usep.edu.ph', 'QR Code Attendance Development Team');
                            $mail->addAddress($emailAddress, $fullName ?: $username);

                            $mail->isHTML(true);
                            $mail->Subject = 'QR Code Attendance System – Facilitator Account Credentials';
                            $registrationLink = 'https://usep-qrattendance.com/public/registration17236463';
                            $mail->Body = '
                                <h2>📧 QR Code Attendance System – Facilitator Account Credentials</h2>
                                <p><strong>Hello, ' . htmlspecialchars($fullName ?: $username) . '!</strong></p>
                                <p>Good day!</p>
                                <p>We are pleased to inform you that your <strong>Facilitator Account for the QR Code Attendance System</strong> has been successfully created.</p>
                                <p>Below are your login credentials:</p>
                                <p><strong>Username:</strong> ' . htmlspecialchars($username) . '</p>
                                <p><strong>Password:</strong> ' . htmlspecialchars($password) . '</p>
                                <p>You may use these credentials to log in to the <strong>QR Code Attendance System</strong>.</p>
                                <p>After successfully logging in, please proceed with your <strong>facial registration</strong> using this link:</p>
                                <p><a href="' . $registrationLink . '" target="_blank" rel="noopener noreferrer">' . $registrationLink . '</a></p>
                                <p>This step is required to fully activate your facilitator account and allow you to use the attendance system.</p>
                                <p><strong>🔐 Important:</strong></p>
                                <ul>
                                    <li>Please keep your login credentials confidential.</li>
                                    <li>Do not share your username or password with other students.</li>
                                    <li>Make sure to change or secure your credentials if instructed by the system administrator.</li>
                                    <li>If you encounter any issues logging in or registering your face, please contact the <strong>QR Code Attendance Development Team</strong> for assistance.</li>
                                </ul>
                                <p>Thank you, and welcome to the <strong>QR Code Attendance System</strong>!</p>
                                <p>Best regards,<br><strong>QR Code Attendance Development Team</strong><br>USeP Tagum-Mabini Campus</p>
                            ';
                            $mail->AltBody = "Hello, " . ($fullName ?: $username) . "!\n\nGood day!\n\nWe are pleased to inform you that your Facilitator Account for the QR Code Attendance System has been successfully created.\n\nBelow are your login credentials:\n\nUsername: " . $username . "\nPassword: " . $password . "\n\nYou may use these credentials to log in to the QR Code Attendance System.\n\nAfter successfully logging in, please proceed with your facial registration using this link:\n" . $registrationLink . "\n\nThis step is required to fully activate your facilitator account and allow you to use the attendance system.\n\nImportant: keep your login credentials confidential, do not share them, and contact the QR Code Attendance Development Team for assistance.\n\nThank you, and welcome to the QR Code Attendance System!\n\nBest regards,\nQR Code Attendance Development Team\nUSeP Tagum-Mabini Campus";

                            $mail->send();
                        } catch (Exception $e) {
                            error_log('Failed to send facilitator credentials email: ' . $mail->ErrorInfo);
                        }
                    }

                    echo "<script>alert('User added successfully!');</script>";
                } else {
                    echo "<script>alert('Failed to add user.');</script>";
                }
            }

        }
        $userSessions = json_decode($_COOKIE['user_data'], true);
        $username = $userSessions[0]['username']; // Get the first logged-in user
        $data=[
            'username'=>$username,
];
        $this->loadViewWithData('add_user',$data);
    }
}


$user1 = new User();
$userData = $user1->checkSession('add_user');

// Allow admin or facilitator with manage users permission
if (!$userData || !isset($userData['role'])) {
    $uri = str_replace('/add_user', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

if ($userData['role'] === 'admin') {
    // Admin has access
} elseif ($userData['role'] === 'Facilitator') {
    // Check if facilitator has manage users permission
    $facilitatorPermissions = $user1->getUserPermissions($userData['user_id']);
    if (!in_array('manage users', $facilitatorPermissions)) {
        $uri = str_replace('/add_user', '/login', $_SERVER['REQUEST_URI']);
        header('Location: '. $uri);
        exit();
    }
} else {
    // Neither admin nor authorized facilitator
    $uri = str_replace('/add_user', '/login', $_SERVER['REQUEST_URI']);
    header('Location: '. $uri);
    exit();
}

// Example for AdminHome Controller
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'lock') {
    setcookie('system_lock', 'true', time() + 3600, '/');
    setcookie('locked_user', $userData['username'], time() - 3600, '/'); // Clear the locked user cookie
    // Optionally set locked_user cookie here if needed
    exit;
}

// Handle AJAX GET to check system lock status
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
    $locked = isset($_COOKIE['system_lock']) && $_COOKIE['system_lock'] === 'true';
    header('Content-Type: application/json');
    echo json_encode(['locked' => $locked]);
    exit;
}

$addUser = new AddUser();
$addUser->index();