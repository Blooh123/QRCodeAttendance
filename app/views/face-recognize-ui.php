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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

<body class="flex flex-col items-center justify-center min-h-screen p-4 bg-gray-100">

  <!-- Loading Screen -->
  <div id="loading-screen" class="fixed inset-0 bg-white bg-opacity-95 flex flex-col items-center justify-center z-50">
    <div class="text-center space-y-6">
      <!-- Loading Animation -->
      <div class="relative">
        <div class="w-24 h-24 border-4 border-gray-200 border-t-[#a31d1d] rounded-full animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center">
          <i class="fas fa-brain text-[#a31d1d] text-2xl"></i>
        </div>
      </div>
      
      <!-- Loading Text -->
      <div class="space-y-2">
        <h2 class="text-2xl font-bold text-[#a31d1d]">Loading AI Models</h2>
        <p class="text-gray-600">Please wait while we initialize the facial recognition system...</p>
      </div>
      
      <!-- Progress Steps -->
      <div class="max-w-md mx-auto space-y-3">
        <div id="step-1" class="flex items-center space-x-3 text-sm">
          <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center">
            <i class="fas fa-spinner fa-spin text-gray-400"></i>
          </div>
          <span class="text-gray-500">Loading face detection model...</span>
        </div>
        <div id="step-2" class="flex items-center space-x-3 text-sm">
          <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center">
            <i class="fas fa-spinner fa-spin text-gray-400"></i>
          </div>
          <span class="text-gray-500">Loading face recognition model...</span>
        </div>
        <div id="step-3" class="flex items-center space-x-3 text-sm">
          <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center">
            <i class="fas fa-spinner fa-spin text-gray-400"></i>
          </div>
          <span class="text-gray-500">Loading face landmarks model...</span>
        </div>
        <div id="step-4" class="flex items-center space-x-3 text-sm">
          <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center">
            <i class="fas fa-spinner fa-spin text-gray-400"></i>
          </div>
          <span class="text-gray-500">Processing registered faces...</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content (Hidden during loading) -->
  <div id="main-content" class="hidden">
    <h1 class="text-3xl font-bold text-[#a31d1d] mb-6">
      Facial recognition authentication
    </h1>

    <div class="bg-white p-6 rounded-xl shadow-lg border border-black max-w-md w-full space-y-4">
      <div id="video-container" class="relative rounded-lg overflow-hidden border-4 border-[#a31d1d] scanning-border">
        <video id="video" autoplay muted playsinline width="600" height="450"></video>
        <!-- Canvas will be added here -->
      </div>

      <div id="status" class="px-4 py-2 rounded-md bg-blue-100 text-blue-700 font-semibold">
        Detecting face…
      </div>
    </div>
  </div>

</body>
</html>
