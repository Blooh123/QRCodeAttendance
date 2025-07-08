const video = document.getElementById("video");

Promise.all([
  faceapi.nets.ssdMobilenetv1.loadFromUri("../public/assets/js/models"),
  faceapi.nets.faceRecognitionNet.loadFromUri("../public/assets/js/models"),
  faceapi.nets.faceLandmark68Net.loadFromUri("../public/assets/js/models"),
]).then(startVideo);

function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => console.error(err));
}

async function getLabeledFaceDescriptions() {
  // Use absolute path
  const res = await fetch('../public/assets/js/labels.php');
  const labels = await res.json();

  return Promise.all(
    labels.map(async (label) => {
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
  const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.5);

  const canvas = faceapi.createCanvasFromMedia(video);
  document.body.append(canvas);

  const displaySize = { width: video.width, height: video.height };
  faceapi.matchDimensions(canvas, displaySize);

  const username = await getUsernameFromSession();
  console.log("Session username:", username);

  if (!username) {
    alert("No user session found.");
    return;
  }

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
      if (matched) {
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
      }else if (results.some(r => r.label !== "unknown")) {
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





