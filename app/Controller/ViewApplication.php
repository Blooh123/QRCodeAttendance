<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/core/Model.php';
require_once '../app/Model/User.php';
require_once '../app/Model/ExcuseApplication.php';
require_once '../app/Model/Attendances.php';

use Controller;
use Model\User;
use Model\Attendances;

class ViewApplication extends Controller {
    
    public function index($applicationId = null): void
    {
        $user = new User();
        $userData = $user->checkSession('admin');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
            $uri = str_replace('/view_application', '/login', $_SERVER['REQUEST_URI']);
            header('Location: ' . $uri);
            exit();
        }

        $excuseApp = new \Model\ExcuseApplication();

        if (!$applicationId) {
            $this->handleCreateAction($excuseApp);
            $attendances = new Attendances();
            $events = $attendances->getAllAttendance();
            $selectedEventId = $_GET['event_id'] ?? $_POST['atten_id'] ?? null;
            $searchQuery = trim($_GET['search'] ?? $_POST['search'] ?? '');
            $students = $selectedEventId
                ? $excuseApp->searchStudentsForEvent($selectedEventId, $searchQuery)
                : [];

            $this->loadViewWithData('view_application', [
                'application' => null,
                'userData' => $userData,
                'events' => $events,
                'selectedEventId' => $selectedEventId,
                'searchQuery' => $searchQuery,
                'students' => $students
            ]);
            return;
        }
        $application = $excuseApp->getExcuseApplicationById($applicationId);

        if (!$application) {
            $_SESSION['error'] = 'Application not found';
            header('Location: ' . ROOT . 'student_application');
            exit();
        }

        // Handle POST requests for approve/reject actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostAction($applicationId);
            // Redirect back to the same page to show updated status
            header('Location: ' . ROOT . 'view_application/' . $applicationId);
            exit();
        }

        // Get document information
        $document1Info = null;
        $document2Info = null;
        
        if ($application['document1']) {
            $document1Info = $excuseApp->getDocumentInfo($applicationId, 1);
        }
        
        if ($application['document2']) {
            $document2Info = $excuseApp->getDocumentInfo($applicationId, 2);
        }

        $this->loadViewWithData('view_application', [
            'application' => $application,
            'userData' => $userData,
            'document1Info' => $document1Info,
            'document2Info' => $document2Info
        ]);
    }

    private function handleCreateAction(\Model\ExcuseApplication $excuseApp): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'create_excuse') {
            return;
        }

        $attenId = trim($_POST['atten_id'] ?? '');
        $studentId = trim($_POST['student_id'] ?? '');
        $description = trim($_POST['application_description'] ?? '');

        if ($attenId === '' || $studentId === '' || $description === '') {
            $_SESSION['error'] = 'Event, student, and reason are required.';
            return;
        }

        if ($excuseApp->checkExistingApplication($attenId, $studentId)) {
            $_SESSION['error'] = 'This student already has an excuse application for the selected event.';
            return;
        }

        if ($excuseApp->insertAdminExcuseApplication($attenId, $studentId, $description)) {
            $_SESSION['success'] = 'Student excused successfully for the selected event.';
        } else {
            $_SESSION['error'] = 'Failed to create the excuse application.';
        }
    }

    private function handlePostAction($applicationId): void
    {
        $status = $_POST['status'] ?? null;
        $remarks = $_POST['remarks'] ?? '';

        if (!$status || !in_array($status, ['1', '2'])) {
            $_SESSION['error'] = 'Invalid status';
            return;
        }

        $excuseApp = new \Model\ExcuseApplication();
        
        if ($status === '2' && empty(trim($remarks))) {
            $_SESSION['error'] = 'Remarks are required for rejection';
            return;
        }
        
        $result = $excuseApp->updateExcuseApplicationStatus($applicationId, $status, $remarks);
        
        if ($result) {
            $statusText = $status == '1' ? 'approved' : 'rejected';
            $_SESSION['success'] = "Application {$statusText} successfully";
        } else {
            $_SESSION['error'] = 'Failed to update application status';
        }
    }
}

// Handle the application view
if (isset($_GET['id'])) {
    $viewApplication = new ViewApplication();
    $viewApplication->index($_GET['id']);
} else {
    $viewApplication = new ViewApplication();
    $viewApplication->index();
}
