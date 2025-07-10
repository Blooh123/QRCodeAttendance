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
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  position: relative;
  overflow-x: hidden;
}

/* Animated background particles */
body::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: 
    radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
    radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
  animation: backgroundShift 20s ease-in-out infinite;
  z-index: -1;
}

@keyframes backgroundShift {
  0%, 100% { transform: translate(0, 0) rotate(0deg); }
  25% { transform: translate(-10px, -10px) rotate(1deg); }
  50% { transform: translate(10px, -5px) rotate(-1deg); }
  75% { transform: translate(-5px, 10px) rotate(0.5deg); }
}

/* Main container animations */
.main-container {
  animation: slideInUp 0.8s ease-out;
  backdrop-filter: blur(10px);
  background: rgba(255, 255, 255, 0.95);
  border-radius: 20px;
  box-shadow: 
    0 20px 40px rgba(0, 0, 0, 0.1),
    0 0 0 1px rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(50px) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Title animation */
.title-animation {
  background: linear-gradient(45deg, #a31d1d, #ff6b6b, #a31d1d);
  background-size: 200% 200%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: gradientShift 3s ease-in-out infinite;
}

@keyframes gradientShift {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

/* Video container enhancements */
.video-container {
  position: relative;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 
    0 10px 30px rgba(0, 0, 0, 0.2),
    inset 0 0 0 1px rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.video-container:hover {
  transform: translateY(-5px);
  box-shadow: 
    0 20px 40px rgba(0, 0, 0, 0.3),
    inset 0 0 0 1px rgba(255, 255, 255, 0.2);
}

/* Enhanced scanning border */
.scanning-border {
  position: relative;
  animation: scanning 2s infinite;
}

.scanning-border::before {
  content: '';
  position: absolute;
  top: -2px;
  left: -2px;
  right: -2px;
  bottom: -2px;
  background: linear-gradient(45deg, #a31d1d, #ff6b6b, #a31d1d, #ff6b6b);
  background-size: 400% 400%;
  border-radius: 17px;
  z-index: -1;
  animation: borderGlow 2s ease-in-out infinite;
}

@keyframes borderGlow {
  0%, 100% { 
    background-position: 0% 50%;
    box-shadow: 0 0 20px rgba(163, 29, 29, 0.5);
  }
  50% { 
    background-position: 100% 50%;
    box-shadow: 0 0 30px rgba(163, 29, 29, 0.8);
  }
}

@keyframes scanning {
  0% { 
    box-shadow: 0 0 20px rgba(163, 29, 29, 0.5);
    transform: scale(1);
  }
  50% { 
    box-shadow: 0 0 30px rgba(163, 29, 29, 0.8);
    transform: scale(1.02);
  }
  100% { 
    box-shadow: 0 0 20px rgba(163, 29, 29, 0.5);
    transform: scale(1);
  }
}

/* Status indicator animations */
.status-indicator {
  position: relative;
  overflow: hidden;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.status-indicator::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% { left: -100%; }
  100% { left: 100%; }
}

/* Floating animation for elements */
.floating {
  animation: floating 3s ease-in-out infinite;
}

@keyframes floating {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

/* Pulse animation */
.pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

/* Bounce animation */
.bounce {
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 20%, 53%, 80%, 100% { transform: translate3d(0, 0, 0); }
  40%, 43% { transform: translate3d(0, -30px, 0); }
  70% { transform: translate3d(0, -15px, 0); }
  90% { transform: translate3d(0, -4px, 0); }
}

/* Glow effect */
.glow {
  animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
  from { box-shadow: 0 0 20px rgba(163, 29, 29, 0.5); }
  to { box-shadow: 0 0 30px rgba(163, 29, 29, 0.8); }
}

/* Responsive design */
@media (max-width: 768px) {
  .main-container {
    margin: 1rem;
    padding: 1.5rem;
  }
  
  .title-animation {
    font-size: 1.5rem;
  }
}
</style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen p-4">

  <!-- Animated background particles -->
  <div class="fixed inset-0 pointer-events-none">
    <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white rounded-full opacity-20 floating" style="animation-delay: 0s;"></div>
    <div class="absolute top-1/3 right-1/4 w-1 h-1 bg-white rounded-full opacity-30 floating" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-1/4 left-1/3 w-1.5 h-1.5 bg-white rounded-full opacity-25 floating" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 right-1/3 w-1 h-1 bg-white rounded-full opacity-20 floating" style="animation-delay: 0.5s;"></div>
  </div>

  <div class="main-container p-8 max-w-md w-full space-y-6 relative z-10">
    
    <!-- Animated title -->
    <div class="text-center">
      <h1 class="title-animation text-3xl font-bold mb-2 floating">
        Facial Recognition
      </h1>
      <p class="text-gray-600 text-sm pulse">AI-Powered Authentication System</p>
    </div>

    <!-- Video container with enhanced styling -->
    <div class="video-container">
      <div id="video-container" class="relative rounded-lg overflow-hidden border-4 border-[#a31d1d] scanning-border">
        <video id="video" autoplay muted playsinline width="600" height="450" class="w-full h-auto"></video>
        <!-- Canvas will be added here -->
        
        <!-- Scanning overlay effect -->
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#a31d1d] to-transparent opacity-50 animate-pulse"></div>
          <div class="absolute bottom-0 right-0 w-1 h-full bg-gradient-to-b from-transparent via-[#a31d1d] to-transparent opacity-50 animate-pulse" style="animation-delay: 0.5s;"></div>
        </div>
      </div>
    </div>

    <!-- Enhanced status indicator -->
    <div id="status" class="status-indicator px-6 py-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 font-semibold text-center border border-blue-200 glow">
      <div class="flex items-center justify-center space-x-2">
        <div class="w-2 h-2 bg-blue-500 rounded-full pulse"></div>
        <span>Detecting face…</span>
      </div>
    </div>

    <!-- Additional info cards -->
    <div class="grid grid-cols-2 gap-4 mt-6">
      <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-xl border border-green-200 text-center floating" style="animation-delay: 0.2s;">
        <div class="text-green-600 text-2xl mb-1">🔒</div>
        <div class="text-green-700 text-sm font-medium">Secure</div>
      </div>
      <div class="bg-gradient-to-br from-purple-50 to-violet-50 p-4 rounded-xl border border-purple-200 text-center floating" style="animation-delay: 0.4s;">
        <div class="text-purple-600 text-2xl mb-1">⚡</div>
        <div class="text-purple-700 text-sm font-medium">Fast</div>
      </div>
    </div>

  </div>

</body>
</html>
