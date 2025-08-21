<?php

namespace Controller;

// Include the User model using absolute path
require_once __DIR__ . '/../Model/User.php';
use Model\User;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) exit('No data');

$username = preg_replace('/[^a-zA-Z0-9_]/', '', $data['username']);
$imgNum = intval($data['imgNum']);
$imgData = $data['imgData'];

// Create User instance
$user = new User();

// Get user ID by username (you'll need to implement this method or pass user ID directly)
// Assuming you have a method to get user ID by username
$userId = $user->getUserIdByUsername($username);

if (!$userId) {
    exit('User not found');
}

// Clean the image data (remove data URL prefix and decode base64)
$cleanImageData = str_replace('data:image/jpeg;base64,', '', $imgData);
$cleanImageData = str_replace(' ', '+', $cleanImageData);

// Decode base64 to binary data for storage
$binaryImageData = base64_decode($cleanImageData);

if ($binaryImageData === false) {
    exit('Invalid image data');
}

// Save image to database using the uploadFacialImage function
if ($user->uploadFacialImage($userId, $binaryImageData)) {
    echo json_encode(['success' => true, 'message' => 'Image saved successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save image to database']);
}


