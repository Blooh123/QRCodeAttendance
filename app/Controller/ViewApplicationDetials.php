<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/core/Model.php';
require_once '../app/Model/User.php';
require_once '../app/Model/ExcuseApplication.php';

use Controller;
use Model\User;

class ViewApplicationDetials extends Controller {
    
    public function index($applicationId = null): void
    {
        $user = new User();
        $userData = $user->checkSession('admin');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
            $uri = str_replace('/view_application', '/login', $_SERVER['REQUEST_URI']);
            header('Location: ' . $uri);
            exit();
        }

        if (!$applicationId) {
            $_SESSION['error'] = 'Application ID is required';
            header('Location: ' . ROOT . 'adminHome?page=StudentApplication');
            exit();
        }

        $excuseApp = new \Model\ExcuseApplication();
        $application = $excuseApp->getExcuseApplicationById($applicationId);

        if (!$application) {
            $_SESSION['error'] = 'Application not found';
            header('Location: ' . ROOT . 'adminHome?page=StudentApplication');
            exit();
        }

        // Handle POST requests for approve/reject actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostAction($applicationId);
            // Redirect back to the same page to show updated status
            header('Location: ' . ROOT . 'view_application?id=' . $applicationId);
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

        $this->loadViewWithData('view_application_detials', [
            'application' => $application,
            'userData' => $userData,
            'document1Info' => $document1Info,
            'document2Info' => $document2Info
        ]);
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
            
            // Check if it's an image
            if (strpos($mimeType, 'image/') === 0) {
                // For images, return base64 data for display
                $base64 = base64_encode($document);
                echo json_encode([
                    'type' => 'image',
                    'mime_type' => $mimeType,
                    'data' => $base64
                ]);
            } else {
                // For non-images, return the document directly
                header('Content-Type: ' . $mimeType);
                header('Content-Length: ' . strlen($document));
                header('Content-Disposition: inline; filename="document' . $documentNumber . '"');
                header('Cache-Control: public, max-age=3600');
                
                echo $document;
            }
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
    $viewApplicationDetials = new ViewApplicationDetials();
    
    switch ($_GET['action']) {
        case 'updateStatus':
            $viewApplicationDetials->updateStatus();
            break;
        case 'viewDocument':
            $id = $_GET['id'] ?? null;
            $docNum = $_GET['doc'] ?? 1;
            if ($id) {
                $viewApplicationDetials->viewDocument($id, $docNum);
            }
            break;
        case 'downloadDocument':
            $id = $_GET['id'] ?? null;
            $docNum = $_GET['doc'] ?? 1;
            if ($id) {
                $viewApplicationDetials->downloadDocument($id, $docNum);
            }
            break;
        default:
            if (isset($_GET['id'])) {
                $viewApplicationDetials->index($_GET['id']);
            } else {
                $viewApplicationDetials->index();
            }
    }
} else {
    $viewApplicationDetials = new ViewApplicationDetials();
    if (isset($_GET['id'])) {
        $viewApplicationDetials->index($_GET['id']);
    } else {
        $viewApplicationDetials->index();
    }
}