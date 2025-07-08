<?php
  require_once '../app/core/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Face Detection</title>

<script defer src="<?= ROOT ?>assets/js/face-api.min.js"></script>
<script defer src="<?= ROOT ?>assets/js/script.js"></script>

<script src="https://cdn.tailwindcss.com"></script>
<style>
  .scanning-border {
    animation: scanning 2s infinite;
  }
  @keyframes scanning {
    0% { box-shadow: 0 0 15px #3498db; }
    50% { box-shadow: 0 0 30px #3498db; }
    100% { box-shadow: 0 0 15px #3498db; }
  }
</style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4 py-6 font-sans">

  <h1 class="text-3xl font-bold text-[#a31d1d] mb-4">
    👁 Face Detection
  </h1>

  <div id="status" class="px-4 py-2 mb-4 rounded-lg font-medium text-blue-600 bg-blue-100 shadow">
    Detecting face…
  </div>

  <div id="video-container" class="max-w-full sm:max-w-[600px] rounded-xl overflow-hidden bg-white shadow-lg scanning-border">
    <video id="video" autoplay muted playsinline class="w-full h-auto"></video>
  </div>

</body>
</html>
