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

<div class="max-w-6xl mx-auto space-y-8">
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
     <!-- Facilitator-specific sections in single column -->
    <?php if ($userData['roles'] == 'Facilitator'): ?>
     <div class="max-w-6xl mx-auto mt-8 space-y-8">
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

         <!-- Manage Facilitator Permission  -->
         <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 flex flex-col">
             <h3 class="text-2xl font-bold text-[#a31d1d] mb-4 flex items-center gap-2">
                 <i class="fas fa-shield-alt text-[#a31d1d]"></i> Manage Facilitator Permission
             </h3>
             
             <!-- Debug info (remove this after testing) -->
             <div class="mb-4 p-3 bg-gray-100 rounded-lg text-sm">
                 <strong>Debug - Current Permissions:</strong> 
                 <?php echo htmlspecialchars(json_encode($userPermissions)); ?>
             </div>
             
             <!-- Permissions Section -->
             <div class="space-y-6">
                 <!-- Manage Students Permission -->
                 <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                     <div class="flex items-center gap-3 mb-3">
                         <input type="checkbox" id="manageStudents" name="permissions[manageStudents]" 
                             <?php echo in_array('manage students', $userPermissions) ? 'checked' : ''; ?>
                             class="w-5 h-5 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                         <label for="manageStudents" class="text-lg font-semibold text-gray-700">Manage Students</label>
                     </div>
                     <!-- permission to add student -->
                     <div class="flex items-center gap-3 mb-3">
                         <input type="checkbox" id="addStudent" name="permissions[addStudent]" 
                             <?php echo in_array('add student', $userPermissions) ? 'checked' : ''; ?>
                             class="w-5 h-5 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                         <label for="addStudent" class="text-lg font-semibold text-gray-700">Add Student</label>
                     </div>
                     <!-- Program Selection (only visible when Manage Students is checked) -->
                     <div id="programSelection" class="ml-8 space-y-3 <?php echo in_array('manage students', $userPermissions) ? '' : 'hidden'; ?>">
                        
                         <p class="text-sm text-gray-600 mb-2">Select programs this user can manage:</p>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                             <!-- Education Programs -->
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_beed" name="permissions[programs][]" value="Bachelor of Elementary Education" 
                                     <?php echo in_array('Bachelor of Elementary Education', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_beed" class="text-sm text-gray-700">Bachelor of Elementary Education</label>
                             </div>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_bsned" name="permissions[programs][]" value="Bachelor of Special Needs Education" 
                                     <?php echo in_array('Bachelor of Special Needs Education', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_bsned" class="text-sm text-gray-700">Bachelor of Special Needs Education</label>
                             </div>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_bseed" name="permissions[programs][]" value="Bachelor of Early Childhood Education" 
                                     <?php echo in_array('Bachelor of Early Childhood Education', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_bseed" class="text-sm text-gray-700">Bachelor of Early Childhood Education</label>
                             </div>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_bsed_math" name="permissions[programs][]" value="Bachelor of Secondary Education - Major in Mathematics" 
                                     <?php echo in_array('Bachelor of Secondary Education - Major in Mathematics', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_bsed_math" class="text-sm text-gray-700">Bachelor of Secondary Education - Major in Mathematics</label>
                             </div>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_bsed_english" name="permissions[programs][]" value="Bachelor of Secondary Education - Major in English" 
                                     <?php echo in_array('Bachelor of Secondary Education - Major in English', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_bsed_english" class="text-sm text-gray-700">Bachelor of Secondary Education - Major in English</label>
                             </div>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_bsed_filipino" name="permissions[programs][]" value="Bachelor of Secondary Education - Major in Filipino" 
                                     <?php echo in_array('Bachelor of Secondary Education - Major in Filipino', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_bsed_filipino" class="text-sm text-gray-700">Bachelor of Secondary Education - Major in Filipino</label>
                             </div>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_bsit" name="permissions[programs][]" value="Bachelor of Science in Information Technology" 
                                     <?php echo in_array('Bachelor of Science in Information Technology', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_bsit" class="text-sm text-gray-700">Bachelor of Science in Information Technology</label>
                             </div>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_bstve" name="permissions[programs][]" value="Bachelor of Technical-Vocational Teacher Education" 
                                     <?php echo in_array('Bachelor of Technical-Vocational Teacher Education', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_bstve" class="text-sm text-gray-700">Bachelor of Technical-Vocational Teacher Education</label>
                             </div>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="program_bsabe" name="permissions[programs][]" value="Bachelor of Science in Agricultural and Biosystems Engineering" 
                                     <?php echo in_array('Bachelor of Science in Agricultural and Biosystems Engineering', $userPermissions) ? 'checked' : ''; ?>
                                     class="w-4 h-4 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                                 <label for="program_bsabe" class="text-sm text-gray-700">Bachelor of Science in Agricultural and Biosystems Engineering</label>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Manage Attendance Permission -->
                 <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                     <div class="flex items-center gap-3">
                         <input type="checkbox" id="manageAttendance" name="permissions[manageAttendance]" 
                             <?php echo in_array('manage attendance', $userPermissions) ? 'checked' : ''; ?>
                             class="w-5 h-5 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                         <label for="manageAttendance" class="text-lg font-semibold text-gray-700">Manage Attendance</label>
                     </div>
                 </div>

                 <!-- Manage Users Permission -->
                 <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                     <div class="flex items-center gap-3">
                         <input type="checkbox" id="manageUsers" name="permissions[manageUsers]" 
                             <?php echo in_array('manage users', $userPermissions) ? 'checked' : ''; ?>
                             class="w-5 h-5 text-[#a31d1d] bg-gray-100 border-gray-300 rounded focus:ring-[#a31d1d] focus:ring-2">
                         <label for="manageUsers" class="text-lg font-semibold text-gray-700">Manage Users</label>
                     </div>
                 </div>
             </div>

             <!-- Save Permissions Button -->
             <div class="mt-6 pt-4 border-t border-gray-200">
                 <button type="button" onclick="savePermissions()" class="w-full bg-[#a31d1d] hover:bg-[#8a1a1a] text-white font-semibold px-6 py-3 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex items-center gap-2 justify-center transition-all duration-200">
                     <i class="fas fa-save"></i> Save Permissions
                 </button>
             </div>
         </div>
     </div>
    <?php endif; ?>

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
                <?php if ($ip !== null || $device !== null || $login !== null){ ?>
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
                <?php }else{ ?>
                    <div class="text-center py-8">
                        <i class="fas fa-user-slash text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No session details found</p>
                        <p class="text-sm text-gray-400 mt-2">This user hasn't logged in yet.</p>
                    </div>
                <?php } ?>
            <?php endforeach; ?>
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

     // Permission management functionality
     document.addEventListener('DOMContentLoaded', function() {
         const manageStudentsCheckbox = document.getElementById('manageStudents');
         const programSelection = document.getElementById('programSelection');
         
         if (manageStudentsCheckbox && programSelection) {
             // Show/hide program selection based on Manage Students checkbox
             manageStudentsCheckbox.addEventListener('change', function() {
                 if (this.checked) {
                     programSelection.classList.remove('hidden');
                 } else {
                     programSelection.classList.add('hidden');
                     // Uncheck all program checkboxes when Manage Students is unchecked
                     const programCheckboxes = programSelection.querySelectorAll('input[type="checkbox"]');
                     programCheckboxes.forEach(checkbox => checkbox.checked = false);
                 }
             });
         }
     });

     function savePermissions() {
         // Collect permission data
         const permissions = {
             manageStudents: document.getElementById('manageStudents')?.checked || false,
             manageAttendance: document.getElementById('manageAttendance')?.checked || false,
             manageUsers: document.getElementById('manageUsers')?.checked || false,
             addStudent: document.getElementById('addStudent')?.checked || false,
             programs: []
         };

         // Collect selected programs if Manage Students is checked
         if (permissions.manageStudents) {
             const programCheckboxes = document.querySelectorAll('input[name="permissions[programs][]"]:checked');
             programCheckboxes.forEach(checkbox => {
                 permissions.programs.push(checkbox.value);
             });
         }

         // Validate that at least one permission is selected
         if (!permissions.manageStudents && !permissions.manageAttendance && !permissions.manageUsers && !permissions.addStudent) {
             Swal.fire({
                 title: "Warning",
                 text: "Please select at least one permission.",
                 icon: "warning",
                 confirmButtonColor: "#a31d1d"
             });
             return;
         }

         // Validate that programs are selected if Manage Students is checked
         if (permissions.manageStudents && permissions.programs.length === 0) {
             Swal.fire({
                 title: "Warning",
                 text: "Please select at least one program for student management.",
                 icon: "warning",
                 confirmButtonColor: "#a31d1d"
             });
             return;
         }

         // Show confirmation dialog
         Swal.fire({
             title: "Save Permissions",
             text: "Are you sure you want to save these permissions?",
             icon: "question",
             showCancelButton: true,
             confirmButtonColor: "#a31d1d",
             cancelButtonColor: "#6b7280",
             confirmButtonText: "Yes, save!",
             cancelButtonText: "Cancel"
         }).then((result) => {
             if (result.isConfirmed) {
                 // Show loading state
                 Swal.fire({
                     title: "Saving...",
                     text: "Please wait while we save the permissions.",
                     allowOutsideClick: false,
                     didOpen: () => {
                         Swal.showLoading();
                     }
                 });

                 // Send AJAX request to save permissions
                 fetch('<?php echo ROOT ?>save_facilitator_permissions', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                     },
                     body: JSON.stringify({
                         user_id: <?php echo $_GET['user_id']; ?>,
                         permissions: permissions
                     })
                 })
                 .then(response => response.json())
                 .then(data => {
                     if (data.success) {
                         Swal.fire({
                             title: "Success!",
                             text: "Permissions have been saved successfully.",
                             icon: "success",
                             confirmButtonColor: "#a31d1d"
                         });
                     } else {
                         Swal.fire({
                             title: "Error!",
                             text: data.error || "Failed to save permissions. Please try again.",
                             icon: "error",
                             confirmButtonColor: "#d33"
                         });
                     }
                 })
                 .catch(error => {
                     console.error('Error:', error);
                     Swal.fire({
                         title: "Error!",
                         text: "An error occurred while saving permissions. Please try again.",
                         icon: "error",
                         confirmButtonColor: "#d33"
                     });
                 });
             }
         });
     }
</script>

</body>
</html>
