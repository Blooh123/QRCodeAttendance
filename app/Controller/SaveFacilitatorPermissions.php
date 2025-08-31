<?php

namespace Controller;
require_once '../app/core/config.php';
require_once '../app/Model/User.php';
use Model\User;

class SaveFacilitatorPermissions extends \Controller
{
    public function index(): void
    {
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
            echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
            return;
        }

        $userId = $input['user_id'] ?? null;
        $permissions = $input['permissions'] ?? null;

        if (!$userId || !$permissions) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Missing required data']);
            return;
        }

        // Validate user exists and is a facilitator
        $user = new User();
        $userData = $user->getUserDataWithPersonalInfo($userId);
        
        if (!$userData) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'User not found']);
            return;
        }

        if ($userData['roles'] !== 'Facilitator') {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'User is not a facilitator']);
            return;
        }

        // Process permissions into array format
        $permissionsArray = [];
        
        // Add management permissions
        if ($permissions['manageStudents'] ?? false) {
            $permissionsArray[] = 'manage students';
        }
        
        if ($permissions['manageAttendance'] ?? false) {
            $permissionsArray[] = 'manage attendance';
        }
        
        if ($permissions['manageUsers'] ?? false) {
            $permissionsArray[] = 'manage users';
        }
        if($permissions['addStudent'] ?? false){
            $permissionsArray[] = 'add student';
        }
        if($permissions['deleteStudent'] ?? false){
            $permissionsArray[] = 'delete student';
        }
        if($permissions['deleteAttendance'] ?? false){
            $permissionsArray[] = 'delete attendance';
        }
        if($permissions['editAttendance'] ?? false){
            $permissionsArray[] = 'edit attendance';
        }
        if($permissions['addAttendance'] ?? false){
            $permissionsArray[] = 'add attendance';
        }
        // add  user
        if($permissions['addUser'] ?? false){
            $permissionsArray[] = 'add user';
        }

        // Add program permissions
        if (isset($permissions['programs']) && is_array($permissions['programs'])) {
            foreach ($permissions['programs'] as $program) {
                $permissionsArray[] = $program;
            }
        }

        // Convert to JSON string for database storage
        $permissionsJson = json_encode($permissionsArray);

        // Debug: Log what we're saving
        error_log("Saving permissions for user $userId: " . $permissionsJson);

        // Save to database
        if ($user->updateUserPermissions($userId, $permissionsJson)) {
            echo json_encode(['success' => true, 'message' => 'Permissions saved successfully', 'debug' => $permissionsArray]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to save permissions']);
        }
    }
}

$savePermissions = new SaveFacilitatorPermissions();
$savePermissions->index();
