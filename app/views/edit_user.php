<?php global $userData; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User • USep Attendance System</title>
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT; ?>assets/images/LOGO_QRCODE_v2.png">
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .hover-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .facial-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            transition: transform 0.2s, border-color 0.2s;
        }
        .facial-image:hover {
            transform: scale(1.05);
            border-color: #a31d1d;
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-6xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-user-edit text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Edit User</h1>
    </div>
</header>

<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- User Edit Card -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 flex flex-col">
        <form id="userForm" action="edit_user?user_id=<?php echo $_GET['user_id']; ?>" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                <input name="username" id="username" type="text" value="<?php echo htmlspecialchars($userData['username']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" required>
            </div>
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Full Name</label>
                <input name="name" id="name" type="text" value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" required>
            </div>
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input name="email" id="email" type="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" required>
            </div>
            <div>
                <label for="newPassword" class="block mb-2 text-sm font-medium text-gray-700">New Password</label>
                <input name="newPassword" id="newPassword" type="password" placeholder="New Password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
            </div>
            <div>
                <label for="confirmPassword" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                <input name="confirmPassword" id="confirmPassword" type="password" placeholder="Confirm Password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
            </div>
            <div class="flex flex-col gap-3 mt-6">
                <button type="button" onclick="confirmAction('saveChanges')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex items-center gap-2 justify-center transition-all duration-200">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <button type="button" onclick="confirmAction('changePassword')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex items-center gap-2 justify-center transition-all duration-200">
                    <i class="fas fa-key"></i> Change Password
                </button>
                <button type="button" onclick="confirmAction('deleteUser')" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex items-center gap-2 justify-center transition-all duration-200">
                    <i class="fas fa-trash"></i> Delete User
                </button>
                <a href="<?php echo ROOT ?>face-register?id=<?php echo htmlspecialchars($userData['username']); ?>" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex items-center gap-2 justify-center transition-all duration-200">
                    <i class="fas fa-user-circle"></i> Face Registration
                </a>
                <a href="<?php echo ROOT ?>adminHome?page=Users" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex items-center gap-2 justify-center transition-all duration-200">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
            <input type="hidden" id="actionType" name="actionType">
        </form>
    </div>

    <!-- Session Details Card -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 flex flex-col">
        <h3 class="text-2xl font-bold text-[#a31d1d] mb-4 flex items-center gap-2">
            <i class="fas fa-desktop text-[#a31d1d]"></i> Session Details
        </h3>
        <div class="max-h-96 overflow-y-auto space-y-4 pr-2">
            <?php foreach ($userSession as $session): ?>
                <?php
                    $ip = $session['ip_address'] ?? null;
                    $device = $session['deviceInfo'] ?? null;
                    $login = $session['created_at'] ?? null;
                ?>
                <?php if ($ip !== null || $device !== null || $login !== null): ?>
                <div class="bg-gradient-to-r from-[#f8fafc] to-[#f1f5f9] p-4 rounded-lg shadow flex flex-col border border-gray-200 mb-2">
                    <?php if ($ip !== null): ?>
                        <p class="text-gray-700"><strong>IP Address:</strong> <?php echo htmlspecialchars($ip); ?></p>
                    <?php endif; ?>
                    <?php if ($device !== null): ?>
                        <p class="text-gray-700"><strong>Device Info:</strong> <?php echo htmlspecialchars($device); ?></p>
                    <?php endif; ?>
                    <?php if ($login !== null): ?>
                        <p class="text-gray-700"><strong>Last Login:</strong> <?php echo htmlspecialchars($login); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo ROOT ?>logout2?sessionID=<?php echo urlencode($session['id']) ?>&user_id=<?php echo urlencode($session['id']) ?>"
                       class="mt-3 inline-block bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Facial Images Card -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 flex flex-col">
        <h3 class="text-2xl font-bold text-[#a31d1d] mb-4 flex items-center gap-2">
            <i class="fas fa-user-circle text-[#a31d1d]"></i> Facial Images
        </h3>
        <div class="flex-1">
            <?php if (!empty($facialImages)): ?>
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach ($facialImages as $index => $image): ?>
                        <div class="relative group">
                            <img 
                                src="data:image/jpeg;base64,<?php echo base64_encode($image['img']); ?>" 
                                alt="Facial Image <?php echo $index + 1; ?>"
                                class="facial-image w-full h-32 object-cover"
                                onclick="openImageModal(this.src, 'Facial Image <?php echo $index + 1; ?>')"
                            />
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex gap-2">
                                    <button 
                                        onclick="openImageModal(this.parentElement.parentElement.previousElementSibling.src, 'Facial Image <?php echo $index + 1; ?>')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full transition-all duration-200"
                                        title="View Image"
                                    >
                                        <i class="fas fa-search-plus text-sm"></i>
                                    </button>
                                    <button 
                                        onclick="deleteFacialImage(<?php echo $image['id']; ?>, <?php echo $_GET['user_id']; ?>, <?php echo $index + 1; ?>)"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-all duration-200"
                                        title="Delete Image"
                                    >
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <span class="text-sm text-gray-600">Image <?php echo $index + 1; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-500">Total Images: <?php echo count($facialImages); ?></span>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-user-slash text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">No facial images registered</p>
                    <p class="text-sm text-gray-400 mt-2">This user hasn't completed facial registration yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="Modal Image" class="max-w-full max-h-full object-contain rounded-lg">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 transition-all duration-200">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="absolute bottom-4 left-4 text-white bg-black bg-opacity-50 px-3 py-1 rounded-lg">
            <span id="modalTitle"></span>
        </div>
    </div>
</div>

<script>
    function confirmAction(action) {
        let messages = {
            saveChanges: "Are you sure you want to update the user information?",
            changePassword: "Are you sure you want to change the password?",
            deleteUser: "This action is irreversible. Are you sure you want to delete this user?"
        };

        let confirmButtonColor = action === 'deleteUser' ? '#d33' : '#3085d6';

        Swal.fire({
            title: "Confirm Action",
            text: messages[action],
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: "#aaa",
            confirmButtonText: "Yes, proceed!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('actionType').value = action;
                document.getElementById('userForm').submit();
            }
        });
    }

    function openImageModal(src, title) {
        document.getElementById('modalImage').src = src;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function deleteFacialImage(imageId, userId, imageNumber) {
        Swal.fire({
            title: "Delete Facial Image",
            text: `Are you sure you want to delete Image ${imageNumber}? This action cannot be undone.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#aaa",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: "Deleting...",
                    text: "Please wait while we delete the image.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request to delete the image
                fetch('<?php echo ROOT ?>delete_facial_image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        image_id: imageId,
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "The facial image has been deleted successfully.",
                            icon: "success",
                            confirmButtonColor: "#3085d6"
                        }).then(() => {
                            // Reload the page to refresh the images
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: data.error || "Failed to delete the image. Please try again.",
                            icon: "error",
                            confirmButtonColor: "#d33"
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred while deleting the image. Please try again.",
                        icon: "error",
                        confirmButtonColor: "#d33"
                    });
                });
            }
        });
    }

    // Close modal when clicking outside
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    // Show alerts if there's a success message
    <?php if (isset($_GET['success'])): ?>
    Swal.fire({
        title: "Success",
        text: "Changes saved successfully!",
        icon: "success",
        confirmButtonColor: "#3085d6"
    });
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
    Swal.fire({
        title: "Error",
        text: "Something went wrong. Please try again.",
        icon: "error",
        confirmButtonColor: "#d33"
    });
    <?php endif; ?>
</script>

</body>
</html>
