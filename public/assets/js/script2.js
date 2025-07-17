const video = document.getElementById('video')
const captureBtn = document.getElementById('captureBtn')
const statusDiv = document.getElementById('status')
const statusIndicator = document.getElementById('statusIndicator')

let faceDetected = false
let isCapturing = false

// Hide capture button by default
captureBtn.style.display = 'none'

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('../public/assets/js/models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('../public/assets/js/models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('../public/assets/js/models'),
  faceapi.nets.faceExpressionNet.loadFromUri('../public/assets/js/models')
]).then(startVideo)

function startVideo() {
  navigator.getUserMedia(
    { video: {} },
    stream => video.srcObject = stream,
    err => {
      console.error(err)
      updateStatusIndicator('Camera access denied', 'error')
    }
  )
}

video.addEventListener('play', () => {
  const canvas = faceapi.createCanvasFromMedia(video)
  const videoContainer = document.querySelector('.video-container')
  videoContainer.appendChild(canvas)
  
  const displaySize = { width: video.width, height: video.height }
  faceapi.matchDimensions(canvas, displaySize)
  
  // Position canvas absolutely over the video
  canvas.style.position = 'absolute'
  canvas.style.top = '0'
  canvas.style.left = '0'
  canvas.style.width = '100%'
  canvas.style.height = '100%'
  
  setInterval(async () => {
    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions()
    const resizedDetections = faceapi.resizeResults(detections, displaySize)
    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
    faceapi.draw.drawDetections(canvas, resizedDetections)
    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections)
    faceapi.draw.drawFaceExpressions(canvas, resizedDetections)
    
    // Check if face is detected
    if (detections.length > 0 && !faceDetected) {
      faceDetected = true
      captureBtn.style.display = 'flex'
      updateStatusIndicator('Face detected! You can now take a photo.', 'success')
    } else if (detections.length === 0 && faceDetected) {
      faceDetected = false
      captureBtn.style.display = 'none'
      updateStatusIndicator('Detecting face…', 'detecting')
    }
  }, 100)
})

// Capture button event listener
captureBtn.addEventListener('click', capturePhoto)

function capturePhoto() {
  if (isCapturing) return
  
  isCapturing = true
  captureBtn.disabled = true
  captureBtn.classList.add('capturing')
  captureBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Capturing...</span>'
  
  // Create a canvas to capture the video frame
  const canvas = document.createElement('canvas')
  const context = canvas.getContext('2d')
  canvas.width = video.videoWidth
  canvas.height = video.videoHeight
  
  // Draw the current video frame to canvas
  context.drawImage(video, 0, 0, canvas.width, canvas.height)
  
  // Convert canvas to base64 image data
  const imageData = canvas.toDataURL('image/jpeg', 0.8)
  
  // Send the image data to server
  const formData = new FormData()
  formData.append('image_data', imageData)
  
  fetch('?action=capture&id=' + window.studentID, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showStatus(data.message, 'success')
      updateStatusIndicator('Photo captured successfully!', 'success')
      // Hide the capture button after successful capture
      setTimeout(() => {
        captureBtn.style.display = 'none'
        faceDetected = false
      }, 2000)
      
      // Redirect back to student profile after successful capture
      setTimeout(() => {
        window.location.href = '../public/student'
      }, 3000)
    } else {
      showStatus(data.message, 'error')
      updateStatusIndicator('Failed to capture photo', 'error')
    }
  })
  .catch(error => {
    console.error('Error:', error)
    showStatus('Failed to capture photo. Please try again.', 'error')
    updateStatusIndicator('Network error occurred', 'error')
  })
  .finally(() => {
    isCapturing = false
    captureBtn.disabled = false
    captureBtn.classList.remove('capturing')
    captureBtn.innerHTML = '<i class="fas fa-camera"></i><span>Take Photo</span>'
  })
}

function showStatus(message, type) {
  statusDiv.textContent = message
  statusDiv.className = type
  statusDiv.style.display = 'block'
  
  // Auto-hide success messages after 3 seconds
  if (type === 'success') {
    setTimeout(hideStatus, 3000)
  }
}

function hideStatus() {
  statusDiv.style.display = 'none'
}

function updateStatusIndicator(message, type) {
  const statusText = statusIndicator.querySelector('span')
  const statusDot = statusIndicator.querySelector('.w-2')
  
  statusText.textContent = message
  
  // Update colors based on type
  switch(type) {
    case 'success':
      statusIndicator.className = 'status-indicator mt-6 px-6 py-3 rounded-lg bg-green-100 text-green-700 font-semibold text-center border border-green-200'
      statusDot.className = 'w-2 h-2 bg-green-500 rounded-full pulse'
      break
    case 'error':
      statusIndicator.className = 'status-indicator mt-6 px-6 py-3 rounded-lg bg-red-100 text-red-700 font-semibold text-center border border-red-200'
      statusDot.className = 'w-2 h-2 bg-red-500 rounded-full pulse'
      break
    case 'detecting':
    default:
      statusIndicator.className = 'status-indicator mt-6 px-6 py-3 rounded-lg bg-blue-100 text-blue-700 font-semibold text-center border border-blue-200'
      statusDot.className = 'w-2 h-2 bg-blue-500 rounded-full pulse'
      break
  }
}