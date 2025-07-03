<?php
require_once '../app/core/config.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script defer src="<?= ROOT ?>assets/js/register.js"></script>
</head>
<body>
      <video id="video" width="600" height="450" autoplay></video>
        <input type="text" id="username" placeholder="Enter your name" />
        <button id="registerBtn">Register</button>
        <span id="registerStatus"></span>
</body>
</html>