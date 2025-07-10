<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/core/Model.php';
require_once '../app/Model/User.php';

session_set_cookie_params([
    'lifetime' => 31536000, // 1 year in seconds
    'path' => '/',
    'domain' => '',
    'secure' => true,       // Use true if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

use Controller;
use Model;
use Random\RandomException;

class LoginPage extends Controller
{
    use Model;

    /**
     * @throws RandomException
     * @throws \DateMalformedStringException
     */
    public function index(): void
    {
        $user = new Model\User();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if there is an existing login session
            if (isset($_COOKIE['user_data'])) {
                $userSessions = json_decode($_COOKIE['user_data'], true);

                // Ensure $userSessions is an array and not empty
                if (is_array($userSessions) && !empty($userSessions)) {
                    // Verify if the session is still active in the database
                    $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM user_sessions WHERE user_id = ? AND token = ?");
                    $stmt->execute([$userSessions[0]['user_id'], $userSessions[0]['auth_token']]);
                    $activeSession = $stmt->fetchColumn();

                    if ($activeSession > 0) {
                        $_SESSION['error'] = "Another user is currently logged in. Please log out first.";
                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        exit();
                    }
                }
            }

            // Proceed with login validation
            $validate = $this->validateLogIn(trim($_POST['username']), trim($_POST['password']));
        

            if ($validate) {
                $role = $validate['roles'];
                $_SESSION['role'] = $role;
                $_SESSION['username'] = $validate['username'];
                $_SESSION['user_id'] = $validate['id'];

                $checkLogSession = $this->checkSession($validate['id']);

                if (!$checkLogSession) {
                    $username = $validate['username'];
                    $authToken = bin2hex(random_bytes(32)); // Secure 64-character token
                    $_SESSION['auth_token'] = $authToken;



                    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

                    // Store user session in cookie
                    $userSessions = [
                        [
                            'role' => $role,
                            'username' => $username,
                            'user_id' => $userId,
                            'auth_token' => $authToken
                        ]
                    ];

                    // Set cookie expiration based on role
                    $cookieExpiry = ($role == 'student') ? time() + (60 * 10) : time() + (60 * 60 * 24 * 2);



                    $uri = '';
                    // Redirect based on role
                    if ($role == 'admin') {
                                            // Call createSession() to generate a secure token
                        $userId = $validate['id'];
                        $this->createSession($userId, $role, $authToken);
                        setcookie(
                            'user_data',
                            json_encode($userSessions),
                            $cookieExpiry, // 10 mins for students, 2 days for others
                            '/',
                            '',      // domain
                            isset($_SERVER['HTTPS']), // secure flag
                            true     // HttpOnly
                        );
                        $uri = str_replace('/login', '/adminHome', $_SERVER['REQUEST_URI']);
                    } elseif ($role == 'Facilitator') {
                        $uri = str_replace('/login', '/face-recognize', $_SERVER['REQUEST_URI']);
                    } elseif ($role == 'student') {
                                            // Call createSession() to generate a secure token
                        $userId = $validate['id'];
                        $this->createSession($userId, $role, $authToken);
                        setcookie(
                            'user_data',
                            json_encode($userSessions),
                            $cookieExpiry, // 10 mins for students, 2 days for others
                            '/',
                            '',      // domain
                            isset($_SERVER['HTTPS']), // secure flag
                            true     // HttpOnly
                        );
                        $uri = str_replace('/login', '/student', $_SERVER['REQUEST_URI']);
                    }

                    header('Location: '. $uri);
                    exit();
                }else{
                    $_SESSION['error'] = "This user is already log in.";
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                    exit();
                }



            } else {
                $_SESSION['error'] = "Username or Password is invalid";
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }
        }elseif (isset($_COOKIE['user_data'])) {
            $userDetails = $user->checkSession('none');

            // Ensure $userDetails is an array and has the role key
            if (is_array($userDetails) && isset($userDetails['role'])) {
                if ($userDetails['role'] == 'admin') {
                    $uri = str_replace('/login', '/adminHome', $_SERVER['REQUEST_URI']);
                } elseif ($userDetails['role'] == 'Facilitator') {
                    $uri = str_replace('/login', '/facilitator', $_SERVER['REQUEST_URI']);
                } elseif ($userDetails['role'] == 'student') {
                    $uri = str_replace('/login', '/student', $_SERVER['REQUEST_URI']);
                }

                // Redirect if $uri is set
                if (isset($uri)) {
                    header('Location: ' . $uri);
                    exit();
                }
            }
        }
        $this->loadView('login');
    }

}

$login = new LoginPage();
try {
    $login->index();
} catch (RandomException $e) {

}
