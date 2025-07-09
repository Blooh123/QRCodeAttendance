<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) exit('No data');

$username = preg_replace('/[^a-zA-Z0-9_]/', '', $data['username']);
$imgNum = intval($data['imgNum']);
$imgData = $data['imgData'];

// FTP Configuration
$ftp_server = "89.116.133.79";
$ftp_username = "u753706103.usep-qrattendance.site";
$ftp_password = "q1O>^Lo@cGGnHvey";
$ftp_port = 21;

// Connect to FTP
$conn_id = ftp_connect($ftp_server, $ftp_port);
if (!$conn_id) {
    exit('Failed to connect to FTP server');
}

// Login to FTP
$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);
if (!$login_result) {
    exit('Failed to login to FTP server');
}

// Enable passive mode (often needed for hosting providers)
ftp_pasv($conn_id, true);

// Create temporary local file
$temp_dir = sys_get_temp_dir();
$temp_file = $temp_dir . '/' . uniqid() . '.jpg';

// Process image data
$img = str_replace('data:image/jpeg;base64,', '', $imgData);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);

// Save to temporary file
if (file_put_contents($temp_file, $fileData) === false) {
    ftp_close($conn_id);
    exit('Failed to create temporary file');
}

// Create remote directory path
$remote_dir = "/public_html/public/assets/js/labels/" . $username;
$remote_file = $remote_dir . "/" . $imgNum . ".jpg";

// Try to create directory if it doesn't exist
$current_dir = "/public_html/public/assets/js/labels";
$dirs = explode('/', trim($username, '/'));
foreach ($dirs as $dir) {
    if (!empty($dir)) {
        $current_dir .= "/" . $dir;
        // Try to create directory (ignore if it already exists)
        @ftp_mkdir($conn_id, $current_dir);
    }
}

// Upload file to FTP
if (ftp_put($conn_id, $remote_file, $temp_file, FTP_BINARY)) {
    echo json_encode(['success' => true, 'message' => 'Image saved successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload file to FTP']);
}

// Clean up temporary file
unlink($temp_file);

// Close FTP connection
ftp_close($conn_id);
?>


