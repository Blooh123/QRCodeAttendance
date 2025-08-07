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
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-3xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-user-edit text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Edit User</h1>
    </div>
</header>

<div class="max-w-3xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- User Edit Card -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 flex flex-col">
        <form id="userForm" action="edit_user?id=<?php echo $_GET['id']; ?>" method="POST" class="space-y-4">
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
                <a href="<?php echo ROOT ?>face-register?id=<?php echo urlencode($userData[0]['username']); ?>" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex items-center gap-2 justify-center transition-all duration-200">
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
