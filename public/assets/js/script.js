const video = document.getElementById("video");

Promise.all([
  faceapi.nets.ssdMobilenetv1.loadFromUri("../public/assets/js/models"),
  faceapi.nets.faceRecognitionNet.loadFromUri("../public/assets/js/models"),
  faceapi.nets.faceLandmark68Net.loadFromUri("../public/assets/js/models"),
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
    const res = await fetch('../public/assets/js/get_facial_images.php');
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
    const res = await fetch('../public/assets/js/get_facial_images.php');
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

        fetch('../public/assets/js/redirect.php', {
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
setStatus("❌ Face not recognized", "status-failed");
video.classList.remove("scanning-border");





