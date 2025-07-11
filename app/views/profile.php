<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile • USep Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(163, 29, 29, 0.08);
        }
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .hover-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 40px -10px rgba(163,29,29,0.12);
        }
        .profile-avatar {
            box-shadow: 0 4px 24px 0 rgba(163,29,29,0.10);
            border: 4px solid #a31d1d;
        }
        .section-title {
            text-shadow: 0px 1px 0px rgb(0 0 0 / 0.08);
        }
        .popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10B981;
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            display: none;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 16px -1px rgba(0, 0, 0, 0.12);
            z-index: 50;
        }
    </style>
</head>

<body class="bg-[#f8f9fa] p-4 md:p-6">
<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-3xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-user text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight section-title">Profile</h1>
    </div>
</header>

<div class="container mx-auto max-w-3xl p-0">
    <div class="glass-card p-8 shadow-lg rounded-2xl">
        <h2 class="text-2xl font-bold text-[#a31d1d] mb-8 text-center section-title">Profile Settings</h2>
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <!-- Profile Picture Card -->
        
            <!-- Username & Password Section -->
            <div class="flex-1 flex flex-col gap-8">
                <!-- Username Section -->
                <div class="bg-white/90 p-6 rounded-xl border border-gray-200 shadow hover-card">
                    <label class="block text-[#a31d1d] font-semibold mb-2">Username</label>
                    <form action="" method="POST">
                        <input type="text" id="username" name="username"
                               class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#a31d1d] focus:outline-none"
                               value="<?php echo htmlspecialchars($username); ?>">
                        <button type="submit"
                                class="mt-4 w-full bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </form>
                </div>
                <!-- Password Change Section -->
                <div class="bg-white/90 p-6 rounded-xl border border-gray-200 shadow hover-card">
                    <label class="block text-[#a31d1d] font-semibold mb-2">Change Password</label>
                    <form id="passwordForm" action="" method="POST">
                        <input type="password" id="newPassword" name="password"
                               class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#059669] focus:outline-none"
                               placeholder="New Password">
                        <input type="password" id="confirmPassword"
                               class="w-full border border-gray-300 rounded-lg p-3 mt-3 focus:ring-2 focus:ring-[#059669] focus:outline-none"
                               placeholder="Confirm Password">
                        <button type="submit"
                                class="mt-4 w-full bg-[#059669] hover:bg-[#047857] text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Details -->
    <div class="glass-card p-8 shadow-lg rounded-2xl mt-10">
        <h3 class="text-xl font-semibold text-[#a31d1d] mb-6 text-center section-title">Session Details</h3>
        <?php foreach ($userData as $userSession): ?>
            <div class="bg-white/90 p-5 rounded-xl border border-gray-200 shadow-md mb-6 hover-card">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-gray-700"><strong>IP Address:</strong> <?php echo htmlspecialchars($userSession['ip_address']); ?></p>
                        <p class="text-gray-700"><strong>Device Info:</strong> <?php echo htmlspecialchars($userSession['deviceInfo']); ?></p>
                        <p class="text-gray-700"><strong>Last Login:</strong> <?php echo htmlspecialchars($userSession['created_at']); ?></p>
                    </div>
                    <div>
                        <a href="<?php echo ROOT?>logout2?sessionID=<?php echo urlencode($userSession['SessionID'])?>&user_id=<?php echo urlencode($userSession['id'])?>"
                           class="inline-block px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition mt-2 md:mt-0">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="popup" class="popup"></div>

<script>
    // Password validation
    document.getElementById('passwordForm').addEventListener('submit', function (event) {
        let newPassword = document.getElementById('newPassword').value;
        let confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword.length < 8) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password must be at least 8 characters long!'
            });
            event.preventDefault();
            return;
        }

        if (newPassword !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Passwords do not match!'
            });
            event.preventDefault();
        }
    });

    // Profile picture preview and upload
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('file-upload');
        const uploadButton = document.getElementById('upload-button');
        const fileNameDisplay = document.getElementById('file-name');
        const previewImg = document.getElementById('profile-img');
        let resizedBlob = null;

        fileInput.addEventListener("change", function (event) {
            const file = event.target.files[0];

            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File too large',
                        text: 'Maximum allowed size is 2MB.'
                    });
                    fileInput.value = "";
                    uploadButton.classList.add("hidden");
                    fileNameDisplay.classList.add("hidden");
                    return;
                }

                fileNameDisplay.textContent = "Selected: " + file.name;
                fileNameDisplay.classList.remove("hidden");
                uploadButton.classList.remove("hidden");

                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.onload = function () {
                        const canvas = document.createElement("canvas");
                        const ctx = canvas.getContext("2d");

                        const maxWidth = 300;
                        const maxHeight = 300;
                        let width = img.width;
                        let height = img.height;

                        if (width > height) {
                            if (width > maxWidth) {
                                height *= maxWidth / width;
                                width = maxWidth;
                            }
                        } else {
                            if (height > maxHeight) {
                                width *= maxHeight / height;
                                height = maxHeight;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);

                        const compressedBase64 = canvas.toDataURL("image/jpeg", 0.7);

                        if (previewImg) {
                            previewImg.src = compressedBase64;
                        }

                        resizedBlob = dataURLtoBlob(compressedBase64);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                uploadButton.classList.add("hidden");
                fileNameDisplay.classList.add("hidden");
            }
        });

        function dataURLtoBlob(dataurl) {
            const arr = dataurl.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);

            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }

            return new Blob([u8arr], { type: mime });
        }

        uploadButton.addEventListener("click", function () {
            if (!resizedBlob) {
                Swal.fire({
                    icon: 'error',
                    title: 'No image',
                    text: 'No resized image available!'
                });
                return;
            }

            const formData = new FormData();
            formData.append("profile_picture", resizedBlob, "profile.jpg");

            fetch("<?php echo ROOT ?>student", {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    showPopup("✅ Profile picture uploaded successfully!");
                    uploadButton.classList.add("hidden");
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload failed',
                        text: 'There was an error uploading your profile picture.'
                    });
                });
        });

        function showPopup(message) {
            const popup = document.getElementById('popup');
            popup.textContent = message;
            popup.style.display = 'block';
            setTimeout(() => {
                popup.style.display = 'none';
            }, 3000);
        }
    });
</script>
</body>
</html>
