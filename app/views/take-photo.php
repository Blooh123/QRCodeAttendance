
<?php
    require_once '../app/core/config.php';
    $studentID = $data['studentID'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="format-detection" content="telephone=no">
  <title>Take Photo</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-image:
            radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
            linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
        background-size: 24px 24px;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        min-height: 100vh;
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

    /* Capture button animations */
    #captureBtn {
        transition: all 0.3s ease;
        transform: scale(1);
    }

    #captureBtn:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
    }

    #captureBtn:active {
        transform: scale(0.95);
    }

    #captureBtn.capturing {
        animation: capturing 1s infinite;
    }

    @keyframes capturing {
        0%, 100% { 
            background-color: #4CAF50;
            transform: scale(1);
        }
        50% { 
            background-color: #45a049;
            transform: scale(1.05);
        }
    }

    /* Video container styling */
    .video-container {
        position: relative;
        display: inline-block;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    #video {
        display: block;
        border-radius: 16px;
        width: 100%;
        height: auto;
    }

    /* Status message styling */
    #status {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 12px 24px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        display: none;
        z-index: 1000;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        animation: slideInDown 0.3s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }

    #status.success {
        background: linear-gradient(135deg, #4CAF50, #45a049);
    }

    #status.error {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .main-container {
            margin: 1rem;
            padding: 1.5rem;
        }
        
        #video {
            width: 100%;
            height: auto;
        }
        
        .video-container {
            width: 100%;
            max-width: 400px;
        }
    }
</style>

<body class="p-4 md:p-6">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-2xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-camera text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Take Profile Photo</h1>
    </div>
    <p class="text-gray-600 mt-2">Position yourself in the camera and click capture when ready</p>
</header>

<div class="max-w-2xl mx-auto glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 flex flex-col items-center space-y-6 main-container">
    
    <!-- Video container -->
    <div class="w-full flex flex-col items-center">
        <div class="video-container scanning-border">
            <video id="video" width="600" height="450" autoplay muted playsinline></video>
            <div id="status"></div>
        </div>
        <div class="flex justify-center mt-4 relative" style="position: relative; z-index: 10;">
            <button id="captureBtn" class="bg-[#4CAF50] hover:bg-[#45a049] text-white px-8 py-4 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-camera"></i>
                <span>Take Photo</span>
            </button>
        </div>
        
        <!-- Status indicator -->
        <div id="statusIndicator" class="status-indicator mt-6 px-6 py-3 rounded-lg bg-blue-100 text-blue-700 font-semibold text-center border border-blue-200">
            <div class="flex items-center justify-center space-x-2">
                <div class="w-2 h-2 bg-blue-500 rounded-full pulse"></div>
                <span>Camera ready</span>
            </div>
        </div>
    </div>

    <!-- Info section -->
    <div class="w-full text-center space-y-4">
        <p class="text-gray-600 text-sm pulse">Simple Camera Capture System</p>
        
        <!-- Back button -->
        <a href="<?= ROOT ?>student?page=StudentProfile" class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2 mx-auto w-fit">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>

</div>

<script>
    // Pass student ID to JavaScript
    window.studentID = '<?= $studentID ?>';
    
    const video = document.getElementById('video');
    const captureBtn = document.getElementById('captureBtn');
    const statusDiv = document.getElementById('status');
    const statusIndicator = document.getElementById('statusIndicator');
    
    let isCapturing = false;
    
    // iOS-optimized video constraints
    const videoConstraints = {
        video: {
            width: { ideal: 640, max: 1280 },
            height: { ideal: 480, max: 720 },
            frameRate: { ideal: 15, max: 30 },
            facingMode: "user"
        }
    };
    
    // Start camera
    function startCamera() {
        updateStatusIndicator('Starting camera...', 'detecting');
        
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia(videoConstraints)
                .then(stream => {
                    video.srcObject = stream;
                    video.play();
                    updateStatusIndicator('Camera ready - Click to capture', 'success');
                })
                .catch(err => {
                    console.error("Camera access error:", err);
                    updateStatusIndicator('Camera access denied. Please allow camera permissions.', 'error');
                });
        } else {
            // Fallback for older browsers
            console.warn('getUserMedia not supported, trying legacy API');
            if (navigator.getUserMedia) {
                navigator.getUserMedia(
                    { video: videoConstraints.video },
                    stream => {
                        video.srcObject = stream;
                        updateStatusIndicator('Camera ready - Click to capture', 'success');
                    },
                    err => {
                        console.error(err);
                        updateStatusIndicator('Camera access denied', 'error');
                    }
                );
            } else {
                updateStatusIndicator('Camera not supported on this device', 'error');
            }
        }
    }
    
    // Capture button event listener
    captureBtn.addEventListener('click', capturePhoto);
    
    function capturePhoto() {
        if (isCapturing) return;
        
        isCapturing = true;
        captureBtn.disabled = true;
        captureBtn.classList.add('capturing');
        captureBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Capturing...</span>';
        
        // Create a canvas to capture the video frame
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Draw the current video frame to canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert canvas to base64 image data
        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        
        // Send the image data to server
        const formData = new FormData();
        formData.append('image_data', imageData);
        
        updateStatusIndicator('Uploading photo...', 'detecting');
        
        fetch('?action=capture&id=' + window.studentID, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus(data.message, 'success');
                updateStatusIndicator('Photo captured successfully!', 'success');
                
                // Redirect back to student profile after successful capture
                setTimeout(() => {
                    window.location.href = '../public/student';
                }, 2000);
            } else {
                showStatus(data.message, 'error');
                updateStatusIndicator('Failed to capture photo', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStatus('Failed to capture photo. Please try again.', 'error');
            updateStatusIndicator('Network error occurred', 'error');
        })
        .finally(() => {
            isCapturing = false;
            captureBtn.disabled = false;
            captureBtn.classList.remove('capturing');
            captureBtn.innerHTML = '<i class="fas fa-camera"></i><span>Take Photo</span>';
        });
    }
    
    function showStatus(message, type) {
        statusDiv.textContent = message;
        statusDiv.className = type;
        statusDiv.style.display = 'block';
        
        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(hideStatus, 3000);
        }
    }
    
    function hideStatus() {
        statusDiv.style.display = 'none';
    }
    
    function updateStatusIndicator(message, type) {
        const statusText = statusIndicator.querySelector('span');
        const statusDot = statusIndicator.querySelector('.w-2');
        
        statusText.textContent = message;
        
        // Update colors based on type
        switch(type) {
            case 'success':
                statusIndicator.className = 'status-indicator mt-6 px-6 py-3 rounded-lg bg-green-100 text-green-700 font-semibold text-center border border-green-200';
                statusDot.className = 'w-2 h-2 bg-green-500 rounded-full pulse';
                break;
            case 'error':
                statusIndicator.className = 'status-indicator mt-6 px-6 py-3 rounded-lg bg-red-100 text-red-700 font-semibold text-center border border-red-200';
                statusDot.className = 'w-2 h-2 bg-red-500 rounded-full pulse';
                break;
            case 'detecting':
            default:
                statusIndicator.className = 'status-indicator mt-6 px-6 py-3 rounded-lg bg-blue-100 text-blue-700 font-semibold text-center border border-blue-200';
                statusDot.className = 'w-2 h-2 bg-blue-500 rounded-full pulse';
                break;
        }
    }
    
    // Start camera when page loads
    window.addEventListener('load', startCamera);
</script>

</body>
</html>