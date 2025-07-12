<?php

require_once '../app/core/config.php';
require_once '../app/core/Model.php';
require_once '../app/Model/User.php';
require_once '../app/Model/ExcuseApplication.php';

use Model\User;

session_start();

class DocumentHandler {
    public function viewDocument($id, $documentNumber) {
        $user = new User();
        $userData = $user->checkSession('admin');

        if (!$userData || !isset($userData['role']) || $userData['role'] !== 'admin') {
            http_response_code(403);
            echo "Unauthorized";
            return;
        }

        $excuseApp = new \Model\ExcuseApplication();
        $document = $excuseApp->getDocument($id, $documentNumber);
        
        // Debug logging
        error_log("DocumentHandler viewDocument: Requesting document ID: $id, Document: $documentNumber");
        error_log("DocumentHandler viewDocument: Document found: " . ($document ? 'Yes' : 'No'));
        if ($document) {
            error_log("DocumentHandler viewDocument: Document size: " . strlen($document));
        }
        
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
            echo "Unauthorized";
            return;
        }

        $excuseApp = new \Model\ExcuseApplication();
        $document = $excuseApp->getDocument($id, $documentNumber);
        
        // Debug logging
        error_log("DocumentHandler downloadDocument: Requesting document ID: $id, Document: $documentNumber");
        error_log("DocumentHandler downloadDocument: Document found: " . ($document ? 'Yes' : 'No'));
        if ($document) {
            error_log("DocumentHandler downloadDocument: Document size: " . strlen($document));
        }
        
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

// Handle direct document requests
if (isset($_GET['action']) && isset($_GET['id'])) {
    $handler = new DocumentHandler();
    $id = $_GET['id'];
    $docNum = $_GET['doc'] ?? 1;
    
    // Debug logging
    error_log("DocumentHandler: Received request - Action: " . $_GET['action'] . ", ID: $id, Doc: $docNum");
    
    switch ($_GET['action']) {
        case 'view':
            $handler->viewDocument($id, $docNum);
            break;
        case 'download':
            $handler->downloadDocument($id, $docNum);
            break;
        default:
            http_response_code(400);
            echo "Invalid action";
    }
} else {
    http_response_code(400);
    echo "Missing parameters";
} 