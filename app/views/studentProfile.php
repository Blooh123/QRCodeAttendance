<?php
global $imageSource, $imageSource4, $imageSource2, $imageSource5,$imageSource6;
require_once '../app/core/imageConfig.php'; // Include your configuration file


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile • USep Attendance System</title>
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10B981;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            display: none;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="p-4 md:p-6">
    <div class="max-w-5xl mx-auto space-y-8">
        <!-- Profile Section -->
        <div>
            <h3 class="text-xl md:text-2xl font-bold text-[#a31d1d] mb-6 [text-shadow:_0px_1px_0px_rgb(0_0_0_/_0.1)]">
                Student Profile
            </h3>

            <!-- Profile Picture Card -->
            <div class="glass-card p-8 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black space-y-6">
                <div class="flex flex-col items-center">
                    <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-[#a31d1d] shadow-lg">
                        <?php if (!empty($studentInfo['studentProfile'])): ?>
                            <img id="profile-img"
                                 src="data:image/jpeg;base64,<?= base64_encode($studentInfo['studentProfile']) ?>"
                                 class="w-full h-full object-cover"
                                 alt="Profile Picture">
                        <?php else: ?>
                            <img id="profile-img"
                                 src="<?php echo ROOT ?>assets/images/Default.png"
                                 class="w-full h-full object-cover"
                                 alt="Default Profile">
                        <?php endif; ?>
                    </div>

                    <!-- take a photo -->
                    <a href="<?= ROOT ?>take-photo?id=<?php echo $studentInfo['student_id']?>" class="bg-[#a31d1d] text-white mt-2 px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#8a1818] transition-all duration-200 text-sm md:text-base">
                        Take a Photo
                    </a>

                    <!-- Upload Form -->
                    <!-- <form id="profile-form" class="mt-6 flex flex-col items-center gap-4">
                        <label for="file-upload"
                               class="cursor-pointer bg-white text-[#515050] px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#a31d1d] hover:text-white transition-all duration-200">
                            Choose File
                        </label>
                        <input type="file" id="file-upload" accept="image/*" class="hidden">
                        <button type="button" id="upload-button" 
                                class="bg-[#a31d1d] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#8a1818] transition-all duration-200 hidden">
                            Upload
                        </button>
                        <p id="file-name" class="text-gray-500 text-sm hidden"></p>
                    </form> -->
                </div>
            </div>

            <!-- Personal Information Card -->
            <?php if (!empty($studentInfo)): ?>
                <div class="mt-8 glass-card p-8 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <h4 class="text-lg md:text-xl font-bold text-[#a31d1d] mb-6">Personal Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <p class="text-xs md:text-sm text-gray-500 font-medium">Full Name</p>
                            <p class="text-sm md:text-lg text-gray-800"><?php echo htmlspecialchars($studentInfo['name'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs md:text-sm text-gray-500 font-medium">Email</p>
                            <p class="text-sm md:text-lg text-gray-800"><?php echo htmlspecialchars($studentInfo['email'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs md:text-sm text-gray-500 font-medium">Student ID</p>
                            <p class="text-sm md:text-lg text-gray-800"><?php echo htmlspecialchars($studentInfo['student_id'] ?? 'N/A'); ?></p>
                        </div>
                        <!-- <div class="space-y-2">
                            <p class="text-gray-500 font-medium">Contact</p>
                            <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['contact_num'] ?? 'N/A'); ?></p>
                        </div> -->
                        <div class="space-y-2">
                            <p class="text-xs md:text-sm text-gray-500 font-medium">Program</p>
                            <p class="text-sm md:text-lg text-gray-800"><?php echo htmlspecialchars($studentInfo['program'] ?? 'N/A'); ?></p>
                        </div>
                        <!-- <div class="space-y-2">
                            <p class="text-gray-500 font-medium">Section</p>
                            <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['section'] ?? 'N/A'); ?></p>
                        </div> -->
                        <div class="space-y-2">
                            <p class="text-xs md:text-sm text-gray-500 font-medium">Year</p>
                            <p class="text-sm md:text-lg text-gray-800"><?php echo htmlspecialchars($studentInfo['acad_year'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Change Password Card -->
            <!-- Security Header and Developer Info Dropdown -->
            <div class="mt-8">
                <h4 class="text-2xl font-bold text-[#a31d1d] mb-4">Security</h4>

            </div>
            <div class="glass-card p-8 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                <h4 class="text-lg md:text-xl font-bold text-[#a31d1d] mb-6">Change Password</h4>
                <form method="POST" class="space-y-4">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs md:text-sm text-gray-500 font-medium">Current Password</label>
                            <input type="password" name="current_password" required 
                                   class="w-full mt-1 p-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                        </div>
                        <div>
                            <label class="text-xs md:text-sm text-gray-500 font-medium">New Password</label>
                            <input type="password" name="new_password" required 
                                   class="w-full mt-1 p-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                        </div>
                        <div>
                            <label class="text-xs md:text-sm text-gray-500 font-medium">Confirm New Password</label>
                            <input type="password" name="confirm_password" required 
                                   class="w-full mt-1 p-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                        </div>
                    </div>
                    <button type="submit" name="change_password" 
                            class="bg-[#a31d1d] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#8a1818] transition-all duration-200 text-sm md:text-base">
                        Update Password
                    </button>
                </form>
            </div>

                <!-- Developer Info Dropdown -->
                <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black mt-6">
                    <button type="button" id="devInfoToggle" class="w-full flex items-center justify-between px-6 py-4 text-lg font-semibold text-[#a31d1d] focus:outline-none">
                        <span>Developer Info</span>
                        <svg id="devInfoChevron" class="h-6 w-6 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="devInfoPanel" class="px-6 pb-6 pt-0 max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-in-out pointer-events-none">
                        <div class="flex flex-col md:flex-row items-center gap-6 mt-4">
                            <img src="<?php echo $imageSource6 ?>" alt="Developer Picture" class="w-28 h-28 rounded-full border-4 border-[#a31d1d] shadow-lg object-cover">
                            <div class="flex-1 text-center">
                                <div class="text-xl font-bold text-[#a31d1d]">Dave D. Tiongson</div>
                                <div class="text-gray-700 font-medium mb-1">Program: <span class="font-semibold">BSIT</span></div>
                                <div class="text-gray-700 font-medium mb-1">Skills: <span class="font-semibold">Fullstack Developer</span></div>
                                <div class="text-gray-600 text-base">Takbong pogi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const devInfoToggle = document.getElementById('devInfoToggle');
                    const devInfoPanel = document.getElementById('devInfoPanel');
                    const devInfoChevron = document.getElementById('devInfoChevron');

                    devInfoToggle.addEventListener('click', function () {
                        const isOpen = devInfoPanel.classList.contains('pointer-events-auto');
                        if (!isOpen) {
                            devInfoPanel.classList.remove('max-h-0', 'opacity-0', 'pointer-events-none');
                            devInfoPanel.classList.add('max-h-[500px]', 'opacity-100', 'pointer-events-auto');
                            devInfoChevron.style.transform = 'rotate(180deg)';
                        } else {
                            devInfoPanel.classList.remove('max-h-[500px]', 'opacity-100', 'pointer-events-auto');
                            devInfoPanel.classList.add('max-h-0', 'opacity-0', 'pointer-events-none');
                            devInfoChevron.style.transform = '';
                        }
                    });
                });
                </script>
                <style>
                    /* For smooth max-height transition */
                    #devInfoPanel.max-h-\[500px\] {
                        max-height: 500px !important;
                    }
                    #devInfoPanel.opacity-100 {
                        opacity: 1 !important;
                    }
                    #devInfoPanel.pointer-events-auto {
                        pointer-events: auto !important;
                    }
                </style>
        </div>
    </div>

    <div id="popup" class="popup"></div>

    <!-- JavaScript: Show Preview & Success Popup -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('file-upload');
            const uploadButton = document.getElementById('upload-button');
            const fileNameDisplay = document.getElementById('file-name');
            const previewImg = document.getElementById('profile-img');
            let resizedBlob = null; // To store resized Blob

            fileInput.addEventListener("change", function (event) {
                const file = event.target.files[0];

                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert("❌ File is too large. Maximum allowed size is 2MB.");
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

            // Convert Base64 to Blob
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

            // Handle Upload Button Click
            uploadButton.addEventListener("click", function () {
                if (!resizedBlob) {
                    alert("No resized image available!");
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
                        console.log(data);
                        showPopup("✅ Profile picture uploaded successfully!");
                        uploadButton.classList.add("hidden");
                    })
                    .catch(error => {
                        console.error(error);
                        alert("Upload failed.");
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

            // Developer Info Dropdown
            const devInfoToggle = document.getElementById('devInfoToggle');
            const devInfoPanel = document.getElementById('devInfoPanel');
            const devInfoChevron = document.getElementById('devInfoChevron');
            if (devInfoToggle && devInfoPanel && devInfoChevron) {
                devInfoToggle.addEventListener('click', function () {
                    const isOpen = devInfoPanel.classList.contains('opacity-100');
                    if (!isOpen) {
                        devInfoPanel.classList.remove('max-h-0', 'opacity-0', 'pointer-events-none');
                        devInfoPanel.classList.add('max-h-[500px]', 'opacity-100', 'pointer-events-auto');
                        devInfoChevron.classList.add('rotate-180');
                    } else {
                        devInfoPanel.classList.remove('max-h-[500px]', 'opacity-100', 'pointer-events-auto');
                        devInfoPanel.classList.add('max-h-0', 'opacity-0', 'pointer-events-none');
                        devInfoChevron.classList.remove('rotate-180');
                    }
                });
            }
        });
    </script>
</body>
</html>