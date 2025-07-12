<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/core/Model.php';
require_once '../app/Model/User.php';
require_once '../app/Model/Attendances.php';
require_once '../app/Model/ExcuseApplication.php';

use Controller;
use Model\User;
use Model\Attendances;
use Model\ExcuseApplication;

session_start();

class ApplyExcuse extends \Controller{

    public function index(){
        $user = new User();
        $userData = $user->checkSession('student');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'student') {
            $uri = str_replace('/apply-excuse', '/login', $_SERVER['REQUEST_URI']);
            header('Location: ' . $uri);
            exit();
        }

        // Get available events for the student
        $attendances = new Attendances();
        $events = $attendances->getAllAttendance();
        
        // Get student's existing excuse applications
        $excuseApp = new ExcuseApplication();
        $studentId = $userData['user_id'] ?? $userData['id'];
        $studentApplications = $excuseApp->getExcuseApplicationsByStudent($studentId);
        
        // Get the selected event ID from GET parameter
        $selectedEventId = $_GET['id'] ?? null;
        
        // Initialize variables
        $selectedEvent = null;
        $existingApplication = null;
        
        // If a specific event is selected, get the event details and check if student already has an application
        if ($selectedEventId) {
            // Get the specific event details
            $selectedEvent = $attendances->getAttendanceByID($selectedEventId);
            $existingApplication = $excuseApp->checkExistingApplication($selectedEventId, $studentId);
        }
        
        $this->loadViewWithData('apply-excuse', [
            'events' => $events,
            'studentApplications' => $studentApplications,
            'userData' => $userData,
            'selectedEventId' => $selectedEventId,
            'selectedEvent' => $selectedEvent,
            'existingApplication' => $existingApplication
        ]);
    }

    public function submit(){
        $user = new User();
        $userData = $user->checkSession('student');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'student') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $attenId = $_POST['atten_id'] ?? null;
        $description = $_POST['description'] ?? '';
        
        if (!$attenId || empty($description)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            return;
        }

        $excuseApp = new ExcuseApplication();
        
        // Use user_id instead of id
        $studentId = $userData['user_id'] ?? $userData['id'];
        
        // Check if student already has an application for this event
        if ($excuseApp->checkExistingApplication($attenId, $studentId)) {
            echo json_encode(['success' => false, 'message' => 'You have already submitted an excuse application for this event']);
            return;
        }

        // Handle file uploads
        $document1 = null;
        $document2 = null;

        if (isset($_FILES['document1']) && $_FILES['document1']['error'] === UPLOAD_ERR_OK) {
            $document1 = file_get_contents($_FILES['document1']['tmp_name']);
        }

        if (isset($_FILES['document2']) && $_FILES['document2']['error'] === UPLOAD_ERR_OK) {
            $document2 = file_get_contents($_FILES['document2']['tmp_name']);
        }

        $result = $excuseApp->insertExcuseApplication($attenId, $studentId, $description, $document1, $document2);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Excuse application submitted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to submit excuse application']);
        }
    }

    public function viewDocument($id, $documentNumber) {
        $user = new User();
        $userData = $user->checkSession('student');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'student') {
            http_response_code(403);
            return;
        }

        $excuseApp = new ExcuseApplication();
        $application = $excuseApp->getExcuseApplicationById($id);
        
        $studentId = $userData['user_id'] ?? $userData['id'];
        if (!$application || $application['student_id'] !== $studentId) {
            http_response_code(403);
            return;
        }

        $document = $excuseApp->getDocument($id, $documentNumber);
        
        if ($document) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="document' . $documentNumber . '.pdf"');
            echo $document;
        } else {
            http_response_code(404);
        }
    }
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    $applyExcuse = new ApplyExcuse();
    
    switch ($_GET['action']) {
        case 'submit':
            $applyExcuse->submit();
            break;
        case 'viewDocument':
            $id = $_GET['id'] ?? null;
            $docNum = $_GET['doc'] ?? 1;
            if ($id) {
                $applyExcuse->viewDocument($id, $docNum);
            }
            break;
        default:
            $applyExcuse->index();
    }
} else {
$applyExcuse = new ApplyExcuse();
$applyExcuse->index();
}