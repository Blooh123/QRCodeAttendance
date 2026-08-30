<?php
namespace Controller;
require_once '../app/Model/User.php';
require_once '../app/Model/ActivityLog.php';
use Model\User;
use Model\ActivityLog;
use Exception;
session_start();

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
    exit();
}

$imageId = isset($input['image_id']) ? trim((string) $input['image_id']) : '';
$userId = isset($input['user_id']) ? trim((string) $input['user_id']) : '';

// Validate input
if ($imageId === '' || $userId === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit();
}

try {
    // Create User model instance directly
    $userModel = new User();
    $userData = $userModel->checkSession('delete_facial_image');

    if (!$userData || !isset($userData['user_id'], $userData['role'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
        exit();
    }

    $activityLog = new ActivityLog();
    // Check if the image belongs to the user
    $image = $userModel->getFacialImageById($imageId);
    
    if (!$image) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Image not found']);
        exit();
    }

    // Verify the image belongs to the specified user
    if ((string) $image['user_id'] !== $userId) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Image does not belong to this user']);
        exit();
    }

    // Delete the image
    $result = $userModel->deleteFacialImage($imageId, $userId);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
        $activityLog->createActivityLog($userData['user_id'], $userData['role'], 'Deleted facial image: ' . $imageId, 'delete_facial_image');
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to delete image']);
    }

} catch (Exception $e) {
    error_log('Error deleting facial image: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Internal server error']);
}
