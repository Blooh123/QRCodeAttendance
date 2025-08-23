<?php
  require_once '../app/core/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="format-detection" content="telephone=no">
<title>Face Detection</title>

<script defer src="<?= ROOT ?>assets/js/face-api.min.js"></script>
<!-- <script defer src="<?= ROOT ?>assets/js/script.js"></script> -->

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

<script>
    const video = document.getElementById("video");

Promise.all([
  faceapi.nets.ssdMobilenetv1.loadFromUri("<?= ROOT ?>assets/js/models"),
  faceapi.nets.faceRecognitionNet.loadFromUri("<?= ROOT ?>assets/js/models"),
  faceapi.nets.faceLandmark68Net.loadFromUri("<?= ROOT ?>assets/js/models"),
]).then(() => {
  console.log('Face-api.js models loaded successfully');
  startVideo();
}).catch(error => {
  console.error('Failed to load face-api.js models:', error);
});

function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => console.error(err));
}

async function getLabeledFaceDescriptions() {
  try {
    // Fetch facial images from database
    const res = await fetch('<?= ROOT ?>assets/js/get_facial_images.php');
    const data = await res.json();
    
    if (data.error) {
      console.error('Error fetching facial images:', data.error);
      return [];
    }
    
    if (!data.success || !data.images || data.images.length === 0) {
      console.warn('No facial images found in database');
      return [];
    }
    
    console.log('Fetched images:', data.images.length, 'images');
    
    const username = data.username;
    const descriptions = [];
    
    // Process each image from the database
    for (const imageData of data.images) {
      try {
        console.log(`Processing image ${imageData.id}...`);
        
        // Create an image element from the data URL
        const img = await faceapi.fetchImage(imageData.dataUrl);
        console.log(`Image ${imageData.id} loaded, dimensions:`, img.width, 'x', img.height);
        
        // Check if face-api models are loaded
        if (!faceapi.nets.ssdMobilenetv1.isLoaded) {
          console.error('Face detection model not loaded');
          return [];
        }
        
        const detections = await faceapi
          .detectSingleFace(img)
          .withFaceLandmarks()
          .withFaceDescriptor();
        
        if (detections) {
          console.log(`Face detected in image ${imageData.id}`);
          descriptions.push(detections.descriptor);
        } else {
          console.warn(`No face detected in image ${imageData.id} - trying with different settings`);
          
          // Try with different detection settings
          const altDetections = await faceapi
            .detectSingleFace(img, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.1 }))
            .withFaceLandmarks()
            .withFaceDescriptor();
            
          if (altDetections) {
            console.log(`Face detected in image ${imageData.id} with lower confidence`);
            descriptions.push(altDetections.descriptor);
          } else {
            console.warn(`No face detected in image ${imageData.id} even with lower confidence`);
          }
        }
      } catch (e) {
        console.error(`Error processing image ${imageData.id}:`, e);
      }
    }
    
    if (descriptions.length === 0) {
      console.warn('No valid face descriptors found');
      return [];
    }
    
    console.log('Successfully processed', descriptions.length, 'face descriptors');
    
    // Return labeled face descriptors for the current user
    return [new faceapi.LabeledFaceDescriptors(username, descriptions)];
    
  } catch (error) {
    console.error('Failed to get labeled face descriptions:', error);
    return [];
  }
}

// Test function to verify images are loading correctly
async function testImageLoading() {
  try {
    const res = await fetch('<?= ROOT ?>assets/js/get_facial_images.php');
    const data = await res.json();
    
    if (data.success && data.images) {
      console.log('Testing image loading...');
      for (const imageData of data.images) {
        const img = new Image();
        img.onload = () => {
          console.log(`Image ${imageData.id} loaded successfully:`, img.width, 'x', img.height);
        };
        img.onerror = () => {
          console.error(`Failed to load image ${imageData.id}`);
        };
        img.src = imageData.dataUrl;
      }
    }
  } catch (error) {
    console.error('Error testing image loading:', error);
  }
}

video.addEventListener("play", async () => {
  // Test image loading first
  await testImageLoading();
  
  let labeledFaceDescriptors = await getLabeledFaceDescriptions();
  
  if (labeledFaceDescriptors.length === 0) {
    console.error('No facial descriptors available for recognition');
    setStatus("❌ No facial data available", "status-failed");
    return;
  }
  
  const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5);

  const canvas = faceapi.createCanvasFromMedia(video);
  const container = document.getElementById("video-container");
  canvas.id = "overlay";
  canvas.classList.add("absolute", "top-0", "left-0");
  container.appendChild(canvas);

  const displaySize = {
    width: video.videoWidth,
    height: video.videoHeight
  };

  canvas.width = displaySize.width;
  canvas.height = displaySize.height;

  faceapi.matchDimensions(canvas, displaySize);

  let redirected = false; // flag to ensure redirect only happens once

  setInterval(async () => {
    const detections = await faceapi
      .detectAllFaces(video)
      .withFaceLandmarks()
      .withFaceDescriptors();

    const resizedDetections = faceapi.resizeResults(detections, displaySize);

    canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

    const results = resizedDetections.map((d) => {
      return faceMatcher.findBestMatch(d.descriptor);
    });

    results.forEach((result, i) => {
      const box = resizedDetections[i].detection.box;
      const drawBox = new faceapi.draw.DrawBox(box, {
        label: result.toString(),
      });
      drawBox.draw(canvas);
    });

    if (results.length > 0) {
      // Check if any result matches the expected user (not "unknown")
      const matched = results.some(r => r.label !== "unknown" && r.distance < 0.5);
      
      if (matched && !redirected) {
        redirected = true;
        setStatus("✅ Face recognized!", "status-success");
        video.classList.remove("scanning-border");

        fetch('<?= ROOT ?>assets/js/redirect.php', {
          method: 'POST',
          credentials: 'include'
        })
          .then(res => res.json())
          .then(data => {
            if (data.redirect) {
              window.location.href = data.redirect;
            } else {
              console.error('No redirect URL provided.');
            }
          })
          .catch(err => {
            console.error('Failed to set cookie and redirect:', err);
          });
      } else if (results.some(r => r.label !== "unknown") && !matched) {
        setStatus("❌ Face not recognized", "status-failed");
        video.classList.remove("scanning-border");
        alert("Invalid Face");
        window.location.reload();
      } else {
        setStatus("Detecting face…", "status-detecting");
        video.classList.add("scanning-border");
      }
    }

  }, 100);
});


const statusEl = document.getElementById('status');


function setStatus(message, statusClass) {
  statusEl.textContent = message;
  statusEl.className = `px-4 py-2 mb-4 rounded-lg font-medium bg-green-100 text-green-600 shadow`;
}

// When starting scanning
setStatus("Detecting face…", "status-detecting");
video.classList.add("scanning-border");

// When a match is found
setStatus("✅ Face recognized!", "status-success");
video.classList.remove("scanning-border");

// When no match or failed
setStatus("Recognizing Face...", "status-failed");
video.classList.remove("scanning-border");






</script>

</body>
</html>
