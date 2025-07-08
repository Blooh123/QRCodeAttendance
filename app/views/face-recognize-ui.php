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
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  background: #f8f9fa;
  color: #333;
  text-align: center;
  padding: 1rem;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

h1 {
  font-size: 1.8rem;
  color: #a31d1d;
  margin-bottom: 1rem;
}

.status-message {
  margin-bottom: 1rem;
  font-size: 1rem;
  font-weight: 600;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  display: inline-block;
}

.status-detecting {
  background-color: #eaf4fc;
  color: #3498db;
}

.status-success {
  background-color: #e8f8f1;
  color: #27ae60;
}

.status-failed {
  background-color: #fdecea;
  color: #e74c3c;
}

#video-container {
  position: relative;
  max-width: 90%;
  margin: auto;
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

#video {
  width: 100%;
  height: auto;
  display: block;
}

.scanning-border {
  animation: scanning 2s infinite;
}

@keyframes scanning {
  0% { box-shadow: 0 0 15px #3498db; }
  50% { box-shadow: 0 0 30px #3498db; }
  100% { box-shadow: 0 0 15px #3498db; }
}

/* Responsive */
@media (min-width: 768px) {
  h1 {
    font-size: 2rem;
  }
  .status-message {
    font-size: 1.2rem;
  }
  #video-container {
    max-width: 600px;
  }
}
</style>
</head>

<body>

<h1>👁 Face Detection</h1>

<div id="status" class="status-message status-detecting">
  Detecting face…
</div>

<div id="video-container" class="scanning-border">
  <video id="video" autoplay muted playsinline></video>
</div>

</body>
</html>
