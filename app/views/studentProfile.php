<?php
    global $imageSource5, $imageSource6;

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

        .dev-panel {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.25s ease, padding 0.25s ease;
            pointer-events: none;
        }

        .dev-panel.is-open {
            max-height: 700px;
            opacity: 1;
            pointer-events: auto;
        }

        .dev-toggle .chevron {
            transition: transform 0.25s ease;
        }

        .dev-toggle[aria-expanded="true"] .chevron {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">
    <div class="max-w-6xl mx-auto space-y-8">
        <header class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#a31d1d]/80">Account</p>
                    <h1 class="text-2xl md:text-4xl font-extrabold text-[#a31d1d] mt-2">Student Profile</h1>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 xl:grid-cols-[320px_minmax(0,1fr)] gap-8">
            <aside class="glass-card rounded-3xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 xl:sticky xl:top-6 self-start">
                <div class="flex flex-col items-center text-center">
                    <div class="relative mb-5">
                        <div class="absolute inset-0 rounded-full bg-[#a31d1d]/20 blur-2xl scale-125"></div>
                        <div class="relative w-40 h-40 rounded-full overflow-hidden border-4 border-[#a31d1d] shadow-xl ring-4 ring-white">
                            <?php if (!empty($studentInfo['studentProfile'])): ?>
                                <img id="profile-img"
                                     src="data:image/jpeg;base64,<?= base64_encode($studentInfo['studentProfile']) ?>"
                                     class="w-full h-full object-cover"
                                     alt="Profile Picture">
                            <?php else: ?>
                                <img id="profile-img"
                                     src="<?php echo $imageSource5 ?>"
                                     class="w-full h-full object-cover"
                                     alt="Default Profile">
                            <?php endif; ?>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($studentInfo['name'] ?? 'Student'); ?></h2>
                    <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($studentInfo['student_id'] ?? 'N/A'); ?></p>

                    <div class="mt-5 flex flex-wrap justify-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            <?php echo htmlspecialchars($studentInfo['program'] ?? 'N/A'); ?>
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                            <i class="fas fa-calendar mr-1"></i>
                            <?php echo htmlspecialchars($studentInfo['acad_year'] ?? 'N/A'); ?>
                        </span>
                    </div>

                    <a href="<?= ROOT ?>take-photo?id=<?php echo $studentInfo['student_id']?>"
                       class="mt-6 w-full bg-[#a31d1d] text-white px-4 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#8a1818] transition-all duration-200 text-sm md:text-base flex items-center justify-center gap-2">
                        <i class="fas fa-camera"></i>
                        Take a Photo
                    </a>
                </div>
            </aside>

            <main class="space-y-8">
                <?php if (!empty($studentInfo)): ?>
                    <section class="glass-card p-6 md:p-8 rounded-3xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                        <div class="flex items-center justify-between gap-3 mb-6">
                            <h3 class="text-xl md:text-2xl font-bold text-[#a31d1d]">Personal Information</h3>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#f5e5e5] text-[#a31d1d] text-xs font-semibold">
                                <i class="fas fa-user-check mr-1"></i>
                                Active Student
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-[0.15em] text-gray-500 font-semibold">Full Name</p>
                                <p class="mt-2 text-base md:text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($studentInfo['name'] ?? 'N/A'); ?></p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-[0.15em] text-gray-500 font-semibold">Email</p>
                                <p class="mt-2 text-base md:text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($studentInfo['email'] ?? 'N/A'); ?></p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-[0.15em] text-gray-500 font-semibold">Student ID</p>
                                <p class="mt-2 text-base md:text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($studentInfo['student_id'] ?? 'N/A'); ?></p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                                <p class="text-xs uppercase tracking-[0.15em] text-gray-500 font-semibold">Program</p>
                                <p class="mt-2 text-base md:text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($studentInfo['program'] ?? 'N/A'); ?></p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4 md:col-span-2">
                                <p class="text-xs uppercase tracking-[0.15em] text-gray-500 font-semibold">Academic Year</p>
                                <p class="mt-2 text-base md:text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($studentInfo['acad_year'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <section class="glass-card p-6 md:p-8 rounded-3xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <h3 class="text-xl md:text-2xl font-bold text-[#a31d1d]">Security</h3>
                        <span class="inline-flex items-center text-xs font-semibold text-gray-500 uppercase tracking-[0.15em]">
                            <i class="fas fa-shield-alt mr-2 text-[#a31d1d]"></i>
                            Password
                        </span>
                    </div>
                    <form method="POST" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-xs md:text-sm text-gray-500 font-medium uppercase tracking-[0.12em] mb-2">Current Password</label>
                                <input type="password" name="current_password" required
                                       class="w-full p-3 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm text-gray-500 font-medium uppercase tracking-[0.12em] mb-2">New Password</label>
                                <input type="password" name="new_password" required
                                       class="w-full p-3 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm text-gray-500 font-medium uppercase tracking-[0.12em] mb-2">Confirm New Password</label>
                                <input type="password" name="confirm_password" required
                                       class="w-full p-3 rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" name="change_password"
                                    class="bg-[#a31d1d] text-white px-6 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#8a1818] transition-all duration-200 text-sm md:text-base">
                                Update Password
                            </button>
                        </div>
                    </form>
                </section>

                <div class="glass-card rounded-3xl shadow-[0px_6px_0px_2px_rgba(163,29,29,0.15)] outline outline-2 outline-[#a31d1d] overflow-hidden">
                    <button type="button" id="devInfoToggle" aria-expanded="false" class="dev-toggle w-full flex items-center justify-between px-6 md:px-8 py-5 text-lg md:text-xl font-bold text-[#a31d1d] bg-[#f8f9fa] rounded-t-3xl focus:outline-none transition-colors hover:bg-[#f3eaea]">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-info-circle text-[#a31d1d]"></i>
                            Developer Info
                        </span>
                        <svg id="devInfoChevron" class="chevron h-6 w-6 text-[#a31d1d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="devInfoPanel" class="dev-panel px-6 md:px-8 pb-8 pt-0 bg-gradient-to-br from-[#fff] via-[#f8f9fa] to-[#ffeaea] rounded-b-3xl">
                        <div class="flex flex-col md:flex-row items-center gap-8 mt-6">
                            <div class="relative group">
                                <img src="<?php echo $imageSource6 ?>" alt="Developer Picture" class="w-28 h-28 md:w-32 md:h-32 rounded-full border-4 border-[#a31d1d] shadow-xl object-cover transition-transform duration-300 group-hover:scale-105">
                                <span class="absolute bottom-2 right-2 bg-[#a31d1d] text-white text-[10px] px-2 py-0.5 rounded-full shadow-md font-semibold">Dev</span>
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <div class="text-2xl font-extrabold text-[#a31d1d] mb-2 tracking-wide">Dave D. Tiongson</div>
                                <div class="space-y-2 text-sm md:text-base">
                                    <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                                        <span class="text-gray-700 font-medium">Program:</span>
                                        <span class="font-semibold text-[#a31d1d]">Bachelor of Science in Information Technology</span>
                                    </div>
                                    <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                                        <span class="text-gray-700 font-medium">Skills:</span>
                                        <span class="font-semibold text-[#a31d1d]">Fullstack Developer, UI/UX Designer, Database Administrator</span>
                                    </div>
                                    <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                                        <span class="text-gray-600 italic">Takbong pogi</span>
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-5">
                                    <a href="https://web.facebook.com/debbytrades" class="inline-flex items-center gap-2 text-[#a31d1d] hover:underline font-medium transition-colors">
                                        <i class="fab fa-facebook"></i>
                                        Follow
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
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
            let resizedBlob = null;

            if (fileInput && uploadButton && fileNameDisplay) {
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
            }

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

            function showPopup(message) {
                const popup = document.getElementById('popup');
                if (!popup) return;
                popup.textContent = message;
                popup.style.display = 'block';
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 3000);
            }

            const devInfoToggle = document.getElementById('devInfoToggle');
            const devInfoPanel = document.getElementById('devInfoPanel');
            const devInfoChevron = document.getElementById('devInfoChevron');
            if (devInfoToggle && devInfoPanel && devInfoChevron) {
                devInfoToggle.addEventListener('click', function () {
                    const nextOpen = devInfoToggle.getAttribute('aria-expanded') !== 'true';
                    devInfoToggle.setAttribute('aria-expanded', String(nextOpen));
                    devInfoPanel.classList.toggle('is-open', nextOpen);
                });
            }
        });
    </script>
</body>
</html>