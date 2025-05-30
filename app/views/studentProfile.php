<?php

require_once '../app/core/imageConfig.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
    /* Custom Popup Notification */
    .popup {
        position: fixed;
        top: 20px;
        right: 20px;
        background: green;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        display: none;
        font-weight: bold;
    }
</style>
<body class="bg-gray-100">
<div class="container mx-auto p-4">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">

        <!-- Profile Picture Section -->
        <div class="flex flex-col items-center">
            <h1 class="text-2xl font-semibold text-gray-700 mb-4">Your Profile</h1>

            <!-- Display Profile Picture -->
            <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-300">
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


            <!-- Profile Picture Upload Form -->
            <form id="profile-form" method="post" enctype="multipart/form-data" class="mt-4 text-center">
                <!-- File Input -->
                <label for="file-upload"
                       class="cursor-pointer bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg shadow-md inline-block">
                    Choose File
                </label>
                <input type="file" id="file-upload" accept="image/*" class="hidden">

                <!-- Upload Button -->
                <button type="button" id="upload-button" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md ml-2 hover:bg-blue-600 hidden">
                    Upload
                </button>

                <!-- Display File Name -->
                <p id="file-name" class="text-gray-500 mt-2 hidden"></p>
            </form>



            <!-- Success Popup -->
            <div id="popup" class="popup">✅ Profile picture uploaded successfully!</div>

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
                });
            </script>
        </div>

        <div class="p-6">
            <?php if (!empty($studentInfo)): ?>
                <h1 class="text-2xl font-semibold text-gray-700 mb-6">Personal Information</h1>

                <!-- Grid Container -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-1">Name</label>
                        <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['f_name'].' '.$studentInfo['l_name'] ?? 'N/A'); ?></p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-1">Email</label>
                        <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['email'] ?? 'N/A'); ?></p>
                    </div>

                    <!-- Student ID -->
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-1">Student ID</label>
                        <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['student_id'] ?? 'N/A'); ?></p>
                    </div>

                    <!-- Contact -->
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-1">Contact</label>
                        <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['contact_num'] ?? 'N/A'); ?></p>
                    </div>

                    <!-- Program -->
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-1">Program</label>
                        <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['program'] ?? 'N/A'); ?></p>
                    </div>

                    <!-- Section -->
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-1">Section</label>
                        <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['section'] ?? 'N/A'); ?></p>
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-gray-600 text-sm font-medium mb-1">Year</label>
                        <p class="text-gray-800 text-lg"><?php echo htmlspecialchars($studentInfo['acad_year'] ?? 'N/A'); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <h1 class="text-2xl font-semibold text-gray-700 mb-6">No student information found.</h1>
            <?php endif; ?>

        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden p-6">
            <h1 class="text-2xl font-semibold text-gray-700 mb-4">Change Password</h1>

            <?php if (!empty($Message)): ?>
                <div id="messageBox" class="fixed top-10 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg opacity-100 transition-opacity duration-1000">
                    <?php echo htmlspecialchars($Message); ?>
                </div>

                <script>
                    // Make the message fade out after 3 seconds
                    setTimeout(() => {
                        let messageBox = document.getElementById("messageBox");
                        messageBox.classList.add("opacity-0");
                        setTimeout(() => messageBox.remove(), 1000); // Remove after fading out
                    }, 3000);
                </script>
            <?php endif; ?>


            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">Current Password</label>
                    <input type="password" name="current_password" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">New Password</label>
                    <input type="password" name="new_password" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" name="change_password" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Update Password
                </button>
            </form>


        </div>
        <script>
            document.getElementById("changePasswordForm").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent form submission

                let formData = new FormData(this);

                fetch("", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        let messageBox = document.getElementById("passwordMessage");
                        messageBox.textContent = data.message;
                        messageBox.classList.remove("hidden");
                        messageBox.classList.add(data.success ? "bg-green-500" : "bg-red-500");

                        if (data.success) {
                            // Clear the input fields on success
                            document.getElementById("current_password").value = "";
                            document.getElementById("new_password").value = "";
                            document.getElementById("confirm_password").value = "";
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        </script>

    </div>
</div>
</body>
</html>