const video = document.getElementById("video");

Promise.all([
  faceapi.nets.ssdMobilenetv1.loadFromUri("../app/models"),
  faceapi.nets.faceRecognitionNet.loadFromUri("../app/models"),
  faceapi.nets.faceLandmark68Net.loadFromUri("../app/models"),
]).then(startVideo);

function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => console.error(err));
}

async function getLabeledFaceDescriptions() {
  const res = await fetch('/public/assets/js/labels.php');
  const labels = await res.json();

  return Promise.all(
    labels.map(async (label) => {
      const descriptions = [];
      for (let i = 1; i <= 10; i++) {
        const extensions = ['jpg', 'jpeg', 'png'];
        let found = false;
        for (const ext of extensions) {
          try {
            const img = await faceapi.fetchImage(`/public/assets/js/labels/${label}/${i}.${ext}`);
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

video.addEventListener("play", async () => {
  

  let labeledFaceDescriptors = await getLabeledFaceDescriptions();
  labeledFaceDescriptors = labeledFaceDescriptors.filter(d => d); // Remove nulls
  const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors,0.5);

  const canvas = faceapi.createCanvasFromMedia(video);
  document.body.append(canvas);

  const displaySize = { width: video.width, height: video.height };
  faceapi.matchDimensions(canvas, displaySize);

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
  }, 100);
});



