<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) exit('No data');

$username = preg_replace('/[^a-zA-Z0-9_]/', '', $data['username']);
$imgNum = intval($data['imgNum']);
$imgData = $data['imgData'];

// Directory to save images
$dir =  '../public/assets/js/labels/' . $username;
if (!is_dir($dir)) {
    if (!mkdir($dir, 0777, true)) {
        exit('Failed to create directory: ' . $dir);
    }
}

$img = str_replace('data:image/jpeg;base64,', '', $imgData);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);

if (file_put_contents("$dir/$imgNum.jpg", $fileData) === false) {
    exit('Failed to save file: ' . "$dir/$imgNum.jpg");
}

echo "<script>alert('Image saved successfully!');</script>";


