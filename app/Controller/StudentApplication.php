<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/core/Model.php';
require_once '../app/Model/User.php';
require_once '../app/Model/ExcuseApplication.php';

use Controller;
use Model\User;



class StudentApplication extends Controller{
    public function index(): void
    {
        $user = new User();
        $userData = $user->checkSession('admin');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
            $uri = str_replace('/student_application', '/login', $_SERVER['REQUEST_URI']);
            header('Location: ' . $uri);
            exit();
        }

        // Handle POST requests for approve/reject actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'filter' || $action === 'search') {
                // Handle filter/search - reload the page with filtered data
                $this->loadFilteredApplications($userData);
                return;
            } else {
                // Handle approve/reject actions
                $this->handlePostAction();
                return;
            }
        }

        // Default: show pending applications
        $this->loadFilteredApplications($userData);
    }

    private function loadFilteredApplications($userData): void
    {
        $excuseApp = new \Model\ExcuseApplication();
        $filter = $_POST['filter'] ?? '0'; // Default to pending
        $searchQuery = $_POST['search_query'] ?? '';
        
        // Get applications based on filter
        switch ($filter) {
            case '0':
                $applications = $excuseApp->getPendingExcuseApplications();
                break;
            case '1':
                $applications = $excuseApp->getApprovedExcuseApplications();
                break;
            case '2':
                $applications = $excuseApp->getRejectedExcuseApplications();
                break;
            case 'all':
            default:
                $applications = $excuseApp->getAllExcuseApplications();
                break;
        }
        
        // Apply search filter if provided
        if (!empty($searchQuery)) {
            $searchQuery = strtolower($searchQuery);
            $applications = $excuseApp->searchApplication($searchQuery);
        }
        
        // Get counts for stats
        $pendingCount = count($excuseApp->getPendingExcuseApplications());
        $approvedCount = count($excuseApp->getApprovedExcuseApplications());
        $rejectedCount = count($excuseApp->getRejectedExcuseApplications());
        
        $this->loadViewWithData('student_application', [
            'applications' => $applications,
            'userData' => $userData,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'currentFilter' => $filter,
            'searchQuery' => $searchQuery
        ]);
    }

    private function handlePostAction(): void
    {
        $applicationId = $_POST['application_id'] ?? null;
        $status = $_POST['status'] ?? null;
        $remarks = $_POST['remarks'] ?? '';

        if (!$applicationId || !in_array($status, ['1', '2'])) {
            $_SESSION['error'] = 'Invalid parameters';
            header('Location: ' . ROOT . 'student_application');
            exit();
        }

        $excuseApp = new \Model\ExcuseApplication();
        
        if ($status === '1') {
            // Approve action
            $result = $excuseApp->updateExcuseApplicationStatus($applicationId, $status, $remarks);
            if ($result) {
                $_SESSION['success'] = 'Application approved successfully';
            } else {
                $_SESSION['error'] = 'Failed to approve application';
            }
        } else {
            // Reject action
            if (empty(trim($remarks))) {
                $_SESSION['error'] = 'Remarks are required for rejection';
                header('Location: ' . ROOT . 'student_application');
                exit();
            }
            
            $result = $excuseApp->updateExcuseApplicationStatus($applicationId, $status, $remarks);
            if ($result) {
                $_SESSION['success'] = 'Application rejected successfully';
            } else {
                $_SESSION['error'] = 'Failed to reject application';
            }
        }

    }

    public function updateStatus(): void
    {
        $user = new User();
        $userData = $user->checkSession('admin');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $applicationId = $_POST['application_id'] ?? null;
        $status = $_POST['status'] ?? null;
        $remarks = $_POST['remarks'] ?? '';

        if (!$applicationId || !in_array($status, ['1', '2'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            return;
        }

        $excuseApp = new \Model\ExcuseApplication();
        $result = $excuseApp->updateExcuseApplicationStatus($applicationId, $status, $remarks);
        
        if ($result) {
            $statusText = $status == '1' ? 'approved' : 'rejected';
            echo json_encode(['success' => true, 'message' => "Application {$statusText} successfully"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update application status']);
        }
    }

    public function viewDocument($id, $documentNumber) {
        $user = new User();
        $userData = $user->checkSession('admin');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
            http_response_code(403);
            return;
        }

        $excuseApp = new \Model\ExcuseApplication();
        $document = $excuseApp->getDocument($id, $documentNumber);
        
        if ($document) {
            // Try to detect file type from the binary data
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $document);
            finfo_close($finfo);
            
            // Set appropriate headers based on detected MIME type
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . strlen($document));
            header('Content-Disposition: inline; filename="document' . $documentNumber . '"');
            header('Cache-Control: public, max-age=3600');
            
            echo $document;
        } else {
            http_response_code(404);
            echo "Document not found";
        }
    }

    public function downloadDocument($id, $documentNumber) {
        $user = new User();
        $userData = $user->checkSession('admin');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
            http_response_code(403);
            return;
        }

        $excuseApp = new \Model\ExcuseApplication();
        $document = $excuseApp->getDocument($id, $documentNumber);
        
        if ($document) {
            // Try to detect file type from the binary data
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $document);
            finfo_close($finfo);
            
            // Determine file extension based on MIME type
            $extension = 'bin';
            switch ($mimeType) {
                case 'application/pdf':
                    $extension = 'pdf';
                    break;
                case 'image/jpeg':
                case 'image/jpg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/gif':
                    $extension = 'gif';
                    break;
                case 'application/msword':
                    $extension = 'doc';
                    break;
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    $extension = 'docx';
                    break;
                case 'application/vnd.ms-excel':
                    $extension = 'xls';
                    break;
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    $extension = 'xlsx';
                    break;
            }
            
            // Set appropriate headers for download
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . strlen($document));
            header('Content-Disposition: attachment; filename="document' . $documentNumber . '.' . $extension . '"');
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            
            echo $document;
        } else {
            http_response_code(404);
            echo "Document not found";
        }
    }


}

// Handle AJAX requests and document actions
if (isset($_GET['action'])) {
    $studentApplication = new StudentApplication();
    
    switch ($_GET['action']) {
        case 'updateStatus':
            $studentApplication->updateStatus();
            break;
        case 'viewDocument':
            $id = $_GET['id'] ?? null;
            $docNum = $_GET['doc'] ?? 1;
            if ($id) {
                $studentApplication->viewDocument($id, $docNum);
            }
            break;
        case 'downloadDocument':
            $id = $_GET['id'] ?? null;
            $docNum = $_GET['doc'] ?? 1;
            if ($id) {
                $studentApplication->downloadDocument($id, $docNum);
            }
            break;
        default:
            $studentApplication->index();
    }
} else {
    $studentApplication = new StudentApplication();
    $studentApplication->index();
}