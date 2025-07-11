<?php
require_once 'config.php';
//osas logo
$imageOSASPath = ROOT."assets/Images/OSASLogo.png";
$imageOSASData = base64_encode(file_get_contents($imageOSASPath));
$OSASLogo = 'data:image/png;base64,'.$imageOSASData;


$imagePath = ROOT."assets/Images/LOGO_QRCODE_v2.png";
$imageData = base64_encode(file_get_contents($imagePath));
$imageSource = 'data:image/png;base64,' . $imageData;

$imagePath4 = ROOT."assets/Images/QR.png";
$imageData4 = base64_encode(file_get_contents($imagePath4));
$imageSource4 = 'data:image/png;base64,' . $imageData4;

//illustration
$imagePath2 = ROOT."assets/Images/illustration.png";
$imageData2 = base64_encode(file_get_contents($imagePath2));
$imageSource2 = 'data:image/png;base64,' . $imageData2;

$imagePath3 = ROOT."assets/Images/scanQR.png";
$imageData3 = base64_encode(file_get_contents($imagePath3));
$imageSource3 = 'data:image/png;base64,' . $imageData3;

$imagePath5 = ROOT."assets/Images/Default.png";
$imageData5 = base64_encode(file_get_contents($imagePath5));
$imageSource5 = 'data:image/png;base64,' . $imageData5;

$imagePath6 = ROOT."assets/Images/bilat.png";
$imageData6 = base64_encode(file_get_contents($imagePath6));
$imageSource6 = 'data:image/png;base64,' . $imageData6; 
