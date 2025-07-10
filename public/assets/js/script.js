const video = document.getElementById("video");

// Loading state management
function updateLoadingStep(stepNumber, status = 'loading') {
  const step = document.getElementById(`step-${stepNumber}`);
  if (!step) return;
  
  const icon = step.querySelector('i');
  const text = step.querySelector('span');
  
  if (status === 'completed') {
    icon.className = 'fas fa-check text-green-500';
    text.className = 'text-green-600 font-medium';
  } else if (status === 'error') {
    icon.className = 'fas fa-times text-red-500';
    text.className = 'text-red-600 font-medium';
  }
}

function hideLoadingScreen() {
  const loadingScreen = document.getElementById('loading-screen');
  const mainContent = document.getElementById('main-content');
  
  if (loadingScreen && mainContent) {
    loadingScreen.style.opacity = '0';
    loadingScreen.style.transition = 'opacity 0.5s ease-out';
    
    setTimeout(() => {
      loadingScreen.style.display = 'none';
      mainContent.classList.remove('hidden');
    }, 500);
  }
}

// Load models with progress tracking
Promise.all([
  faceapi.nets.ssdMobilenetv1.loadFromUri("../public/assets/js/models")
    .then(() => {
      updateLoadingStep(1, 'completed');
      console.log('Face detection model loaded');
    })
    .catch(error => {
      updateLoadingStep(1, 'error');
      console.error('Error loading face detection model:', error);
    }),
  faceapi.nets.faceRecognitionNet.loadFromUri("../public/assets/js/models")
    .then(() => {
      updateLoadingStep(2, 'completed');
      console.log('Face recognition model loaded');
    })
    .catch(error => {
      updateLoadingStep(2, 'error');
      console.error('Error loading face recognition model:', error);
    }),
  faceapi.nets.faceLandmark68Net.loadFromUri("../public/assets/js/models")
    .then(() => {
      updateLoadingStep(3, 'completed');
      console.log('Face landmarks model loaded');
    })
    .catch(error => {
      updateLoadingStep(3, 'error');
      console.error('Error loading face landmarks model:', error);
    })
]).then(() => {
  // All models loaded, now start video and process faces
  startVideo();
}).catch(error => {
  console.error('Error loading models:', error);
  alert('Failed to load AI models. Please refresh the page.');
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
    // Use absolute path
    const res = await fetch('../public/assets/js/labels.php');
    const labels = await res.json();

    console.log(`Processing ${labels.length} registered faces...`);

    const labeledFaceDescriptors = await Promise.all(
      labels.map(async (label, index) => {
        const descriptions = [];
        for (let i = 1; i <= 3; i++) {
          const extensions = ['jpg', 'jpeg', 'png'];
          let found = false;
          for (const ext of extensions) {
            try {
              const img = await faceapi.fetchImage(`../public/assets/js/labels/${label}/${i}.${ext}`);
              const detections = await faceapi
                .detectSingleFace(img)
                .withFaceLandmarks()
                .withFaceDescriptor();
              if (detections) {
                descriptions.push(detections.descriptor);
                found = true;
                break;
              }
            } catch (e) {
              // Image does not exist, try next extension
            }
          }
          if (!found) {
            console.warn(`No valid image found for ${label}/${i} (jpg, jpeg, png)`);
          }
        }
        if (descriptions.length === 0) {
          console.warn(`No valid images for label: ${label}`);
          return null;
        }
        return new faceapi.LabeledFaceDescriptors(label, descriptions);
      })
    );

    // Update loading step 4 to completed
    updateLoadingStep(4, 'completed');
    
    // Hide loading screen after a short delay
    setTimeout(() => {
      hideLoadingScreen();
    }, 1000);

    return labeledFaceDescriptors;
  } catch (error) {
    console.error('Error processing labeled face descriptions:', error);
    updateLoadingStep(4, 'error');
    throw error;
  }
}

// Fetch the username stored in PHP session
async function getUsernameFromSession() {
  try {
    const res = await fetch('../public/assets/js/session_user.php');
    const data = await res.json();
    return data.username;
  } catch (e) {
    console.error('Failed to fetch session username', e);
    return null;
  }
}


video.addEventListener("play", async () => {
  let labeledFaceDescriptors = await getLabeledFaceDescriptions();
  labeledFaceDescriptors = labeledFaceDescriptors.filter(d => d); // Remove nulls
  
  if (labeledFaceDescriptors.length === 0) {
    console.warn('No valid face descriptors found');
    setStatus("⚠️ No registered faces found", "status-warning");
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

  const username = await getUsernameFromSession();
  console.log("Session username:", username);

  if (!username) {
    alert("No user session found.");
    return;
  }

  let redirected = false; // 🔷 flag to ensure redirect only happens once

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
      const matched = results.some(r => r.label === username);
      if (matched && !redirected) {  // 🔷 only if not already redirected
        redirected = true;          // 🔷 set flag
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





