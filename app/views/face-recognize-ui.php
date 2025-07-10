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
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
body {
    font-family: 'Poppins', sans-serif;
    background-image:
        radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
        linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
    background-size: 24px 24px;
    background-color: #f8f9fa;
}
.glass-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.hover-card {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-card:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
}

/* Main container animations */
.main-container {
    animation: slideInUp 0.8s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced scanning border */
.scanning-border {
    animation: scanning 2s infinite;
}

@keyframes scanning {
    0% { 
        box-shadow: 0 0 10px rgba(163, 29, 29, 0.3);
    }
    50% { 
        box-shadow: 0 0 20px rgba(163, 29, 29, 0.6);
    }
    100% { 
        box-shadow: 0 0 10px rgba(163, 29, 29, 0.3);
    }
}

/* Status indicator */
.status-indicator {
    transition: all 0.3s ease;
}

/* Subtle pulse animation */
.pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

/* Responsive design */
@media (max-width: 768px) {
    .main-container {
        margin: 1rem;
        padding: 1.5rem;
    }
}
</style>
</head>

<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-2xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-camera text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Facial Recognition</h1>
    </div>
</header>

<div class="max-w-2xl mx-auto glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 flex flex-col items-center space-y-6 main-container">
    
    <!-- Video container -->
    <div class="w-full flex flex-col items-center">
        <div id="video-container" class="relative rounded-xl border-2 border-[#a31d1d] shadow scanning-border overflow-hidden">
            <video id="video" autoplay muted playsinline width="600" height="450" class="w-full h-auto"></video>
            <!-- Canvas will be added here -->
        </div>
        
        <!-- Status indicator -->
        <div id="status" class="status-indicator mt-4 px-6 py-3 rounded-lg bg-blue-100 text-blue-700 font-semibold text-center border border-blue-200">
            <div class="flex items-center justify-center space-x-2">
                <div class="w-2 h-2 bg-blue-500 rounded-full pulse"></div>
                <span>Detecting face…</span>
            </div>
        </div>
    </div>

    <!-- Info section -->
    <div class="w-full text-center space-y-4">
        <p class="text-gray-600 text-sm pulse">AI-Powered Authentication System</p>
        
        <!-- Back button -->
        <a href="<?php echo ROOT ?>login" class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2 mx-auto w-fit">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

</div>

</body>
</html>
