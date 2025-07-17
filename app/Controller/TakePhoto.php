<?php

namespace Controller;

require_once '../app/core/config.php';
require_once '../app/Model/Student.php';
use Model\Student;

class TakePhoto extends \Controller{
    public function index(){
        $studentID = $_GET['id'];
        $this->loadViewWithData('take-photo', ['studentID' => $studentID]);
    }

    public function capturePhoto($studentID) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imageData = $_POST['image_data'];
            
            // Debug: Log the received data
            error_log("Student ID: " . $studentID);
            error_log("Image data length: " . strlen($imageData));
            
            // Remove the data URL prefix to get just the base64 data
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            
            // Decode base64 to binary
            $imageData = base64_decode($imageData);
            
            // Debug: Check if decoding was successful
            if ($imageData === false) {
                echo json_encode(['success' => false, 'message' => 'Failed to decode image data']);
                return;
            }
            
            error_log("Decoded image data length: " . strlen($imageData));
            
            // Create a Student model instance
            $studentModel = new Student();
            
            // Debug: Check if student exists
            $studentExists = $studentModel->getStudentId($studentID);
            if (!$studentExists) {
                echo json_encode(['success' => false, 'message' => 'Student not found']);
                return;
            }
            
            // Update the profile picture in database
            $result = $studentModel->updateProfilePicture($studentID, $imageData);
            
            error_log("Database update result: " . ($result ? 'true' : 'false'));
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Photo captured successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save photo to database']);
            }
        }
    }
}

// Handle the request
$takePhoto = new TakePhoto();

if (isset($_GET['action']) && $_GET['action'] === 'capture') {
    $takePhoto->capturePhoto($_GET['id']);
} else {
    $takePhoto->index();
}