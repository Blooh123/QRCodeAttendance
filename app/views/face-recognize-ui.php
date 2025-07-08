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

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<style>
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f8f9fa;
}
.scanning-border {
  animation: scanning 2s infinite;
}
@keyframes scanning {
  0% { box-shadow: 0 0 10px #3498db; }
  50% { box-shadow: 0 0 20px #3498db; }
  100% { box-shadow: 0 0 10px #3498db; }
}
</style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen p-4">

  <h1 class="text-3xl font-bold text-[#a31d1d] mb-6">
    👁 Face Detection
  </h1>

  <div class="bg-white p-6 rounded-xl shadow-lg border border-black max-w-md w-full space-y-4">
    <div id="video-container" class="rounded-lg overflow-hidden border-4 border-[#a31d1d] scanning-border">
      <video id="video" autoplay muted playsinline width="600" height="450"></video>
    </div>

    <div id="status" class="px-4 py-2 rounded-md bg-blue-100 text-blue-700 font-semibold">
      Detecting face…
    </div>

  </div>

</body>
</html>
