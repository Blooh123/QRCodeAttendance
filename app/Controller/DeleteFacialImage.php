<?php
namespace Controller;
require_once '../app/Model/User.php';
use Model\User;
session_start();
class DeleteFacialImage extends \Controller
{
    public function index()
    {
        // Check if user is logged in and has admin privileges
        if (!isset($_SESSION['user_id']) || $_SESSION['roles'] !== 'Admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
            return;
        }

        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
            return;
        }

        $imageId = $input['image_id'] ?? null;
        $userId = $input['user_id'] ?? null;

        // Validate input
        if (!$imageId || !$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
            return;
        }

        try {
            // Load the User model
            $userModel = $this->loadModel('User');
            
            // Check if the image belongs to the user
            $image = $userModel->getFacialImageById($imageId);
            
            if (!$image) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Image not found']);
                return;
            }

            // Verify the image belongs to the specified user
            if ($image['user_id'] != $userId) {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'Image does not belong to this user']);
                return;
            }

            // Delete the image
            $result = $userModel->deleteFacialImage($imageId);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to delete image']);
            }

        } catch (Exception $e) {
            error_log('Error deleting facial image: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Internal server error']);
        }
    }
}
