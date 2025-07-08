<?php
  require_once '../app/core/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Face Detection</title>

<script defer src="<?= ROOT ?>assets/js/face-api.min.js"></script>
<script defer src="<?= ROOT ?>assets/js/script.js"></script>
<link rel="stylesheet" href="<?= ROOT ?>assets/css/style.css">

<style>
body {
  font-family: Arial, sans-serif;
  background: #f5f7fa;
  text-align: center;
  padding: 20px;
}

#video {
  border: 4px solid #ddd;
  border-radius: 10px;
  margin-top: 20px;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

.status-message {
  margin-top: 20px;
  font-size: 18px;
  font-weight: bold;
  color: #555;
}

.status-detecting {
  color: #3498db;
}

.status-success {
  color: #27ae60;
}

.status-failed {
  color: #e74c3c;
}

.scanning-border {
  animation: scanning 2s infinite;
}

@keyframes scanning {
  0% { box-shadow: 0 0 20px #3498db; }
  50% { box-shadow: 0 0 40px #3498db; }
  100% { box-shadow: 0 0 20px #3498db; }
}
</style>
</head>

<body>

<h1>👁 Face Detection</h1>

<div class="status-message status-detecting" id="status">
  Detecting face…
</div>

<video id="video" width="600" height="450" autoplay></video>

</body>
</html>
