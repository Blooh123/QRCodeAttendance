<?php
global $imageSource, $OSASLogo, $username;
require_once "../app/core/imageConfig.php";


$page = $_GET['page'] ?? 'Dashboard';
$allowed_pages = ['Dashboard', 'Students', 'Attendance', 'Users', 'ProfileAdmin', 'StudentApplication', 'ActivityLogs', 'BackupPage'];
if (!in_array($page, $allowed_pages)) {
    $page = 'Dashboard';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: 
                radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
                linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        
        .glass-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(163, 29, 29, 0.1);
        }
        
        .nav-link {
            position: relative;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            white-space: nowrap;
            line-height: 1.2;
            padding: 0.5rem 0.75rem;
        }
        
        .nav-link:hover {
            background: rgba(163, 29, 29, 0.1);
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            background: #a31d1d;
            color: white !important;
            box-shadow: 0 4px 12px rgba(163, 29, 29, 0.3);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: #a31d1d;
            border-radius: 50%;
        }
        
        .profile-card {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            border-radius: 1rem;
            padding: 0.5rem;
            box-shadow: 0 4px 12px rgba(163, 29, 29, 0.2);
            line-height: 1.2;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 4px 0px 1px rgba(0,0,0,1);
            outline: 1px solid #000;
            transition: all 0.2s ease;
        }
        
        .logout-btn:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
            transform: translateY(-1px);
        }
        
        .mobile-menu-btn {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            box-shadow: 0 4px 0px 1px rgba(0,0,0,1);
            outline: 1px solid #000;
            transition: all 0.2s ease;
        }
        
        .mobile-menu-btn:hover {
            background: linear-gradient(135deg, #8a1818 0%, #7c1515 100%);
            transform: translateY(-1px);
        }
        
        @media (max-width: 1024px) {
            .nav-link {
                padding: 0.75rem 1rem;
                margin-bottom: 0.5rem;
                text-align: center;
                border-radius: 0.75rem;
                box-shadow: 0 4px 0px 1px rgba(0,0,0,1);
                outline: 1px solid #000;
            }
            
            .nav-link.active {
                background: #a31d1d;
                color: white !important;
            }
        }
    </style>
    <title>Attendance System • Admin</title>
</head>
<body class="bg-[#f8f9fa] font-['Poppins']">
    <!-- Countdown Timer -->
<div id="lockCountdown" class="text-xl font-bold text-[#800000] mb-4"></div>

<!-- Responsive Header -->
<header class="w-full shadow-lg sticky top-0 z-50 glass-header">
    <div class="max-w-8xl mx-auto px-6 h-24 flex items-center justify-between">
        <!-- Left Section: Logo & Brand -->
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3">
                <img 
                    src="<?php echo $imageSource ?>" 
                    alt="Logo" 
                    style="height: 100px; width: auto; max-width: 100%; object-fit: contain; display: block;"
                    class="block"
                />
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-1">
                <?php
                $navPages = [
                    'Dashboard' => ['Dashboard', 'fas fa-tachometer-alt'],
                    'Students' => ['Students', 'fas fa-user-graduate'],
                    'Attendance' => ['Attendance', 'fas fa-clipboard-check'],
                    'Users' => ['Accounts', 'fas fa-users-cog'],
                    'StudentApplication' => ['Excuse Applications', 'fas fa-file-medical'],
                    'ProfileAdmin' => ['Profile', 'fas fa-user-circle'],
                    'ActivityLogs' => ['Activity Log', 'fas fa-history']
                ];
                foreach ($navPages as $key => $navItem): ?>
                    <a href="?page=<?php echo $key; ?>"
                       class="nav-link text-sm font-semibold transition-all duration-300 flex items-center gap-2 px-3 py-2 <?php echo $page === $key ? 'active' : 'text-[#515050] hover:text-[#a31d1d]'; ?>">
                        <i class="<?php echo $navItem[1]; ?> text-sm"></i>
                        <span class="whitespace-nowrap"><?php echo $navItem[0]; ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
        
        <!-- Right Section: Profile & Actions -->
        <div class="flex items-center gap-4 ml-4">
            <!-- Profile Card (Desktop) -->
            <div class="hidden lg:flex items-center gap-4 profile-card px-4 py-2" style="min-width:220px;">
                <img src="<?php echo $OSASLogo ?>" alt="Profile" class="h-12 w-12 rounded-full border-2 border-white object-cover">
                <div class="text-white">
                    <p class="font-semibold text-base"><?php echo $username ?></p>
                    <p class="text-sm opacity-90">Administrator</p>
                </div>
            </div>
            
            <!-- Logout Button -->
            <a href="<?php echo ROOT ?>logout"
               class="logout-btn px-5 py-3 rounded-xl text-sm font-semibold text-white flex items-center gap-2">
                <i class="fas fa-sign-out-alt text-sm"></i>
                <span class="hidden lg:inline whitespace-nowrap">Logout</span>
            </a>
            
            <!-- Mobile Menu Button -->
            <button id="openSidebarBtn" class="mobile-menu-btn lg:hidden p-4 rounded-xl text-white">
                <i class="fas fa-bars text-lg"></i>
            </button>
        </div>
    </div>
    <!-- Mobile Sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden backdrop-blur-sm"></div>
    <aside id="sidebarMenu" class="fixed z-50 left-0 top-0 h-full w-72 bg-white/95 backdrop-blur-lg shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex items-center gap-4 px-6 py-6 border-b border-gray-200 bg-gradient-to-r from-[#a31d1d] to-red-900">
            <img class="h-12 w-auto" src="<?php echo $imageSource ?>" alt="Logo" />
            <div class="text-white">
                <h2 class="font-bold text-lg">Admin Panel</h2>
                <p class="text-sm opacity-90">Navigation Menu</p>
            </div>
        </div>
        <nav class="flex-1 flex flex-col gap-3 px-4 py-6 bg-white">
            <?php foreach ($navPages as $key => $navItem): ?>
                <a href="?page=<?php echo $key; ?>"
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-base font-semibold transition-all duration-200
                   <?php echo $page === $key
                       ? 'bg-[#a31d1d] text-white shadow-lg'
                       : 'bg-gray-50 text-[#515050] hover:bg-[#a31d1d] hover:text-white hover:shadow-md'; ?>">
                    <i class="<?php echo $navItem[1]; ?> text-lg"></i>
                    <?php echo $navItem[0]; ?>
                </a>
            <?php endforeach; ?>
            <div class="mt-6 pt-4 border-t border-gray-200">
                <a href="<?php echo ROOT ?>logout"
                   class="logout-btn w-full px-4 py-3 rounded-xl text-base font-semibold text-white flex items-center gap-3 justify-center">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </nav>
    </aside>
</header>

<!-- Main Content -->
<main class="flex flex-col items-center justify-start p-4 min-h-screen">
    <div class="admin-container w-full max-w-7xl bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg mt-8">

        <div>
            <?php require "../app/Controller/{$page}.php"; ?>
        </div>
    </div>
</main>



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

<script>
    // Sidebar toggle logic for mobile
    const sidebar = document.getElementById('sidebarMenu');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const openSidebarBtn = document.getElementById('openSidebarBtn');

    function openSidebar() {
        sidebar.style.transform = 'translateX(0)';
        sidebarOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.style.transform = 'translateX(-100%)';
        sidebarOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    openSidebarBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        openSidebar();
    });

    sidebarOverlay.addEventListener('click', closeSidebar);

    // Close sidebar when clicking menu items (for mobile)
    const mobileMenuItems = sidebar.querySelectorAll('a');
    mobileMenuItems.forEach(item => {
        item.addEventListener('click', closeSidebar);
    });

    // Ensure sidebar and button states are correct on resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.style.transform = 'translateX(0)';
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        } else {
            sidebar.style.transform = 'translateX(-100%)';
        }
    });

    // Database backup function
function downloadBackup() {
    // Show confirmation dialog
    Swal.fire({
        title: 'Download Database Backup?',
        text: 'This will download a complete SQL backup of the database. The file may be large.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#800000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Download Backup',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin text-4xl mb-2"></i><h2 class="text-xl font-bold mb-2">Creating Backup...</h2><p class="text-sm text-center">Please wait</p>';
            button.disabled = true;
            
            // Create a temporary link to download the backup
            const link = document.createElement('a');
            link.href = '<?php echo ROOT; ?>database-backup?action=download';
            link.download = 'qrcode_attendance_backup_' + new Date().toISOString().slice(0,19).replace(/:/g, '-') + '.sql';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success message
            Swal.fire({
                title: 'Backup Downloaded!',
                text: 'Your database backup has been downloaded successfully.',
                icon: 'success',
                confirmButtonColor: '#800000'
            });
            
            // Reset button after a delay
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
            }, 2000);
        }
    });
}


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
    clearInterval(countdownTimer);
    document.getElementById('lockCountdown').style.display = 'none';
    inactivityTimer = setTimeout(() => {
        startLockCountdown(); // <-- Start the countdown after inactivity
    }, 60000); // 1 minute = 60,000 ms
}

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
</script>

<script>
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


let countdownTimer;
let countdownValue = 10; // seconds

function startLockCountdown() {
    countdownValue = 10;
    document.getElementById('lockCountdown').textContent = `System will lock in ${countdownValue} seconds...`;
    document.getElementById('lockCountdown').style.display = 'block';

    countdownTimer = setInterval(() => {
        countdownValue--;
        document.getElementById('lockCountdown').textContent = `System will lock in ${countdownValue} seconds...`;
        if (countdownValue <= 0) {
            clearInterval(countdownTimer);
            document.getElementById('lockCountdown').style.display = 'none';
            showSystemLockOverlay();
        }
    }, 1000);
}
  
</script>
</body>
</html>