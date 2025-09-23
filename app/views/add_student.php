
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Attendance System • Add Student</title>
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
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
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(163, 29, 29, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #a31d1d 0%, #c53030 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(163, 29, 29, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(163, 29, 29, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        }
        .section-divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(163, 29, 29, 0.3), transparent);
            margin: 2rem 0;
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-2xl mx-auto glass-card">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <i class="fas fa-user-plus text-[#a31d1d] text-3xl"></i>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Add New Student</h1>
        </div>
        
        <!-- Permission Indicator -->
        <?php 
        // Get user session data to show permissions
        $user = new \Model\User();
        $userData = $user->checkSession('add_student');
        if ($userData && $userData['role'] === 'Facilitator') {
            $facilitatorPermissions = $user->getUserPermissions($userData['user_id']);
            $facilitatorCoursePermissions = [];
            foreach ($facilitatorPermissions as $permission) {
                // Handle both formats: "course:BSIT" and direct course names like "Bachelor of Science in Information Technology"
                if (strpos($permission, 'course:') === 0) {
                    $course = str_replace('course:', '', $permission);
                    $facilitatorCoursePermissions[] = $course;
                } elseif (!in_array($permission, ['manage students', 'manage attendance', 'manage users'])) {
                    // If it's not a management permission, assume it's a course name
                    $facilitatorCoursePermissions[] = $permission;
                }
            }
            if (!empty($facilitatorCoursePermissions)) { ?>
                <div class="flex items-center space-x-2 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
                    <i class="fas fa-shield-alt text-blue-600"></i>
                    <span class="text-sm text-blue-800 font-medium">
                        Can add to: <?php echo implode(', ', $facilitatorCoursePermissions); ?>
                    </span>
                </div>
            <?php }
        } elseif ($userData && $userData['role'] === 'admin') { ?>
            <div class="flex items-center space-x-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                <i class="fas fa-crown text-green-600"></i>
                <span class="text-sm text-green-800 font-medium">Can add to all programs</span>
            </div>
        <?php } ?>
    </div>
</header>

<div class="max-w-2xl mx-auto">
    <div class="glass-card rounded-2xl p-8 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <form method="POST" action="<?php echo ROOT ?>add_student" enctype="multipart/form-data" class="space-y-6">
            
            <!-- Personal Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-circle text-[#a31d1d] mr-2"></i>
                    Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-user text-[#a31d1d] mr-1"></i>Full Name
                        </label>
                        <input type="text" name="name" id="name" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" 
                               placeholder="Enter student's full name" required>
                    </div>
                    
                    <div>
                        <label for="StudentID" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-id-card text-[#a31d1d] mr-1"></i>Student ID
                        </label>
                        <input type="text" name="student_id" id="StudentID" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" 
                               placeholder="Enter student ID" required>
                    </div>
            </div>
                
                <div class="mt-4">
                    <label for="Email" class="block mb-2 text-sm font-medium text-gray-700">
                        <i class="fas fa-envelope text-[#a31d1d] mr-1"></i>Email Address
                    </label>
                    <input type="email" name="email" id="Email" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" 
                           placeholder="Enter email address" required>
                    <div class="mt-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        This email will be used for attendance notifications and system communications.
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- Academic Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-graduation-cap text-[#a31d1d] mr-2"></i>
                    Academic Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="program" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-book text-[#a31d1d] mr-1"></i>Program
                        </label>
                        <select name="program" id="program" 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" required>
                            <option value="">Select program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?php echo htmlspecialchars($program['program']); ?>">
                                    <?php echo htmlspecialchars($program['program']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>  
                    
                    <div>
                        <label for="year" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-calendar text-[#a31d1d] mr-1"></i>Academic Year
                        </label>
                        <select name="year" id="year" 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" required>
                            <option value="">Select year</option>
                            <?php foreach ($years as $year): ?>
                                <option value="<?php echo htmlspecialchars($year['acad_year']); ?>">
                                    <?php echo htmlspecialchars($year['acad_year']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    This information will be used to determine which attendance events the student is required to attend.
                </div>
            </div>

            <hr class="section-divider">

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button type="submit" 
                        class="btn-primary text-white font-semibold rounded-xl px-6 py-3 flex items-center justify-center space-x-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Student</span>
                </button>
                
                <a href="<?php echo ROOT?>upload_file" 
                   class="btn-secondary text-white font-semibold rounded-xl px-6 py-3 flex items-center justify-center space-x-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <i class="fas fa-file-excel"></i>
                    <span>Import from Excel</span>
                </a>
                
                <a href="<?php echo ROOT ?>adminHome?page=Students"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-xl px-6 py-3 flex items-center justify-center space-x-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Cancel</span>
                </a>
            </div>
            </form>
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

<script>
    // Add interactive effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus effects to inputs
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });

        // Add loading state to submit button
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding Student...';
            submitBtn.disabled = true;
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const studentId = document.getElementById('StudentID').value.trim();
            const email = document.getElementById('email').value.trim();
            const program = document.getElementById('program').value;
            const year = document.getElementById('year').value;

            if (!name || !studentId || !email || !program || !year) {
                e.preventDefault();
                Swal.fire({
                    title: 'Required Fields Missing',
                    text: 'Please fill in all required fields.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                Swal.fire({
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                document.getElementById('email').focus();
                return false;
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if (isset($_SESSION['success_message'])): ?>
    Swal.fire({
        title: 'Success!',
        text: '<?php echo $_SESSION['success_message']; ?>',
        icon: 'success',
        confirmButtonText: 'OK'
    });
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
    Swal.fire({
        title: 'Error!',
        text: '<?php echo $_SESSION['error_message']; ?>',
        icon: 'error',
        confirmButtonText: 'OK'
    });
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>



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

</body>
</html>