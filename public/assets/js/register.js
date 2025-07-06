function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => console.error(err));
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
  // Capture 5 images
  for (let i = 1; i <= 3; i++) {
    // Wait for a short delay between captures
    await new Promise(res => setTimeout(res, 500));
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/jpeg');
    // Send to server
    await fetch('../app/Controller/register.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        username: username,
        imgData: dataUrl,
        imgNum: i
      })
    });
  }
  registerStatus.textContent = "Registration complete!";
});