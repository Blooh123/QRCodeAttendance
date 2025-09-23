<?php
require_once '../app/core/config.php';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo ROOT ?>assets/css/students.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Attendance System • Add Student</title>
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
</head>
<body>

<!-- Main modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="false" class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-md max-h-full overflow-y-auto">
        <!-- Modal content -->
        <div class="relative bg-gray-50 rounded-lg shadow"> <!-- Changed to bg-gray-50 -->
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200"> <!-- Changed border color -->
                <h3 class="text-lg font-semibold text-gray-800"> <!-- Changed text color -->
                    Add New User
                </h3>
                <a href="<?php echo ROOT ?>adminHome?page=Users" type="button" class="text-gray-500 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-close="crud-modal"> <!-- Changed text and hover colors -->
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </a>
            </div>
            <!-- Modal body -->
            <form method="POST" class="p-4 md:p-5" action="<?php echo ROOT ?>add_user">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="fullname" class="block mb-2 text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="fullname" id="fullname" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Full name" required>

                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Email address" required>

                        <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Username" required>

                        <label for="id" class="block mb-2 text-sm font-medium text-gray-700">ID</label>
                        <input type="text" name="id" id="id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Enter unique ID" required>

                        <label for="Password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="Password" id="Password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Password" required>

                        <label for="ConfirmPassword" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="ConfirmPassword" id="ConfirmPassword" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Confirm Password" required>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select role</option>
                            <option value="Admin">admin</option>
                            <option value="Facilitator">Facilitator</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Add User
                </button>
            </form>
        </div>
    </div>
</div>


<div id="systemLockOverlay" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.25);">
    <div class="flex items-center justify-center min-h-screen">
        <div class="min-h-screen flex flex-col items-center justify-center p-4">
            <!-- Header Logo -->
            <div class="w-full max-w-4xl flex items-center justify-center mb-8">
                <div class="floating">
                    <i class="fas fa-lock text-7xl text-[#800000]"></i>
                </div>
            </div>

            <!-- Main Content Container -->
            <div class="w-full max-w-md flex flex-col items-center justify-center relative bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-[0_8px_32px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_12px_48px_-8px_rgba(0,0,0,0.2)] transition-all duration-300 floating">
                <div class="text-[#515050] text-3xl md:text-5xl font-extrabold mb-8 text-center w-full md:w-auto rounded-xl [text-shadow:_0px_2px_0px_rgb(0_0_0_/_0.1)]">
                    SYSTEM LOCKED
                </div>
                <div class="w-full mb-4 text-center">
                    <span class="text-lg text-gray-700">Welcome, <span class="font-semibold"><?php echo htmlspecialchars($username); ?></span></span>
                </div>
                <div class="w-full mb-6 text-base text-gray-600 text-center">
                    Your session has been locked due to inactivity.<br>
                    Please re-enter your password to unlock and continue.
                </div>
                <form id="unlockForm" class="w-full flex flex-col items-center space-y-6" action="<?php echo ROOT; ?>system-lock">
                    <div class="w-full mb-4 relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Re-enter your password"
                            class="w-full h-12 md:h-14 pl-11 pr-12 bg-white rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-normal text-neutral-600 focus:outline-[#800000] focus:ring-2 focus:ring-[#800000] transition-all duration-200"
                            autocomplete="current-password"
                        />
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7C3.732 7.943 7.523 5 12 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <!-- Eye Icon -->
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#800000] focus:outline-none z-20">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542-7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <button type="submit"
                        class="w-full h-12 md:h-14 bg-[#800000] hover:bg-[#660000] rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-bold text-white transition-all duration-200 focus:outline-[#800000] focus:ring-2 focus:ring-[#800000]">
                        Unlock & Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Backdrop -->
<div id="crud-modal-backdrop" class="fixed inset-0 z-40 bg-black bg-opacity-50"></div>


</body>

<script>
    
    // Inactivity logout based on mouse movement
let inactivityTimer;

function showSystemLockOverlay() {
    // AJAX request to set system lock cookies
    fetch('<?php echo ROOT; ?>adminHome?action=lock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ lock: true })
    });

    document.getElementById('systemLockOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
        showSystemLockOverlay();
    }, 60000); // 1 minute = 60,000 ms
}
// check if isset cookie system_lock
function checkIfSystemLocked2() {
    if (document.cookie.split(';').some((item) => item.trim().startsWith('system_lock=true'))) {
        showSystemLockOverlay();
    }
}
checkIfSystemLocked2();

function checkIfSystemLocked() {
    fetch('<?php echo ROOT; ?>adminHome', {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.locked) {
            showSystemLockOverlay();
        }
    })
    .catch(error => {
        console.error('Error checking system lock status:', error);
    });
}
   function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268-2.943 9.543-7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542-7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
        }
    }  
checkIfSystemLocked();

// Ajax for unlocking
document.getElementById('unlockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const password = document.getElementById('password').value;

    fetch('<?php echo ROOT; ?>system-lock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Unlock successful, hide overlay and restore scroll
            document.getElementById('systemLockOverlay').style.display = 'none';
            document.body.style.overflow = '';
            // Optionally reset inactivity timer
            resetInactivityTimer();
        } else {
            // Show error (customize as needed)
            alert('Incorrect password. Please try again.');
        }
    })
    .catch(error => {
        alert('Error unlocking system.');
    });
});

// Monitor mouse movement
document.addEventListener('mousemove', resetInactivityTimer);
document.addEventListener('keydown', resetInactivityTimer);
document.addEventListener('mousedown', resetInactivityTimer);
document.addEventListener('touchstart', resetInactivityTimer);

// Start timer on page load
resetInactivityTimer();

    // Disable right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Disable F12, Ctrl+Shift+I, Ctrl+U
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' ||
            (e.ctrlKey && e.shiftKey && e.key === 'I') ||
            (e.ctrlKey && e.key === 'u')) {
            e.preventDefault();
        }
    });
</script>
</html>