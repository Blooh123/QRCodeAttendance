<?php
require_once '../app/core/config.php';
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) exit('No data');

$username = preg_replace('/[^a-zA-Z0-9_]/', '', $data['username']);
$imgNum = intval($data['imgNum']);
$imgData = $data['imgData'];

// Save images to public/assets/js/labels/{username}
$dir = ROOT . 'assets/js/labels/' . $username;
if (!is_dir($dir)) mkdir($dir, 0777, true);

$img = str_replace('data:image/jpeg;base64,', '', $imgData);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);

file_put_contents("$dir/$imgNum.jpg", $fileData);

echo "Saved $dir/$imgNum.jpg";


