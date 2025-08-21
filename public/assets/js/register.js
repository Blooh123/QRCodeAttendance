function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => {
      console.error('Error accessing camera:', err);
      registerStatus.textContent = "Error: Cannot access camera. Please check permissions.";
    });
}

startVideo();

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
      const response = await fetch('../app/Controller/register.php', {
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
  } catch (error) {
    console.error('Registration error:', error);
    registerStatus.textContent = `Registration failed: ${error.message}`;
  } finally {
    registerBtn.disabled = false;
  }
});