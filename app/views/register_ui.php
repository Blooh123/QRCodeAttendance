<?php
require_once '../app/core/config.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Registration • USep Attendance System</title>
    <!-- <script defer src="<?= ROOT ?>assets/js/register.js"></script> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-2xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-user-circle text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Face Registration</h1>
    </div>
</header>

<div class="max-w-2xl mx-auto glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 flex flex-col items-center space-y-6">
    <div class="w-full flex flex-col md:flex-row gap-8 items-center">
        <div class="flex-1 flex flex-col items-center">
            <video id="video" width="320" height="240" autoplay class="rounded-xl border-2 border-[#a31d1d] shadow"></video>
            <button
                id="flipCameraBtn"
                type="button"
                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow outline outline-1 outline-black transition-all duration-200 flex items-center gap-2"
            >
                <i class="fas fa-sync-alt"></i> Flip Camera
            </button>
            <span id="registerStatus" class="block mt-4 text-center text-lg font-semibold text-[#a31d1d]"></span>
        </div>
        <form class="flex-1 w-full flex flex-col gap-4 items-center" onsubmit="return false;">
            <div class="w-full">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-700">User ID</label>
                <input
                    type="text"
                    value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : 'n/a'; ?>"
                    id="username"
                    readonly
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#a31d1d] focus:border-[#a31d1d] block w-full p-2.5"
                />
            </div>
            <button id="registerBtn" class="w-full bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i> Register Face
            </button>
            <!-- <a href="<?php echo ROOT ?>adminHome?page=Users" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a> -->
        </form>
    </div>
</div>
<script>
let currentStream = null;
let usingFrontCamera = true;

async function startCamera() {
    if (currentStream) {
        currentStream.getTracks().forEach(track => track.stop());
    }
    const constraints = {
        video: {
            facingMode: usingFrontCamera ? "user" : "environment"
        }
    };
    try {
        currentStream = await navigator.mediaDevices.getUserMedia(constraints);
        const video = document.getElementById('video');
        video.srcObject = currentStream;
        // Clear any error messages if camera starts successfully
        document.getElementById('registerStatus').textContent = "";
    } catch (err) {
        console.error('Camera access error:', err);
        document.getElementById('registerStatus').textContent = "Unable to access camera: " + err.message;
    }
}

document.getElementById('flipCameraBtn').addEventListener('click', () => {
    usingFrontCamera = !usingFrontCamera;
    startCamera();
});

// Initialize camera when page loads
window.addEventListener('DOMContentLoaded', startCamera);

const registerBtn = document.getElementById('registerBtn');
const usernameInput = document.getElementById('username');
const registerStatus = document.getElementById('registerStatus');

registerBtn.addEventListener('click', async () => {
  const username = usernameInput.value.trim().replace(/\s+/g, '_');
  if (!username) {
    registerStatus.textContent = "Please enter your name.";
    return;
  }
  
  registerStatus.textContent = "Registering...";
  registerBtn.disabled = true;
  
  try {
    // Get video element
    const video = document.getElementById('video');
    
    // Capture 3 images
    for (let i = 1; i <= 3; i++) {
      registerStatus.textContent = `Capturing image ${i}/3...`;
      
      // Wait for a short delay between captures
      await new Promise(res => setTimeout(res, 500));
      
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
      const dataUrl = canvas.toDataURL('image/jpeg');
      
      // Send to server
      const response = await fetch('../public/assets/js/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          username: username,
          imgData: dataUrl,
          imgNum: i
        })
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.text();
      console.log(`Image ${i} response:`, result);
      
      // Check if the response contains an error
      if (result.includes('error') || result.includes('failed') || result.includes('User not found')) {
        throw new Error(result);
      }
    }
    
    registerStatus.textContent = "Registration complete!";
    // redirect to login page
    window.location.href = '<?= ROOT ?>login';
  } catch (error) {
    console.error('Registration error:', error);
    registerStatus.textContent = `Registration failed: ${error.message}`;
  } finally {
    registerBtn.disabled = false;
  }
});
</script>
</body>
</html>