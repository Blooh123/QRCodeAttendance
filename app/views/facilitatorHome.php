<?php
global $imageSource, $imageSource2, $imageSource3, $programList, $selectedProgram, $EventName, $EventDate, $EventTime, $EventLocation, $attendanceRecordList, $EventID, $facilitatorPermissions, $username;
require_once "../app/core/imageConfig.php";

// Page routing logic similar to adminHome
$page = $_GET['page'] ?? 'Dashboard';
$allowed_pages = ['Dashboard', 'Students', 'Attendance', 'Users', 'ProfileFacilitator', 'StudentApplication'];
if (!in_array($page, $allowed_pages)) {
    $page = 'Dashboard';
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
    <title>Facilitator Home • USep Attendance System</title>
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
        
        /* Sidebar animations */
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .sidebar.open {
            transform: translateX(0);
        }
        
        .sidebar-overlay {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }
        
        .sidebar-overlay.open {
            opacity: 1;
            visibility: visible;
        }
        
        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }
        
        .main-content.with-sidebar {
            margin-left: 0;
        }
        
        .main-content.with-sidebar.open {
            margin-left: 280px;
        }
        
        @media (max-width: 768px) {
            .main-content.with-sidebar,
            .main-content.with-sidebar.open {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">
<div class="flex">
    <!-- Sidebar -->
    <?php if (!empty($facilitatorPermissions)): ?>
    <div id="sidebar" class="sidebar fixed top-0 left-0 h-full w-70 bg-white shadow-2xl z-50 overflow-y-auto">
        <div class="p-6">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo $imageSource; ?>" alt="OSAS Logo" class="w-10 h-10 rounded-lg">
                    <h2 class="text-xl font-bold text-[#a31d1d]">Menu</h2>
                </div>
                <button id="closeSidebar" class="text-gray-500 hover:text-[#a31d1d] transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="space-y-2">
                <!-- Dashboard -->
                <a href="?page=Dashboard" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#a31d1d] hover:text-white transition-all duration-200 group <?php echo $page === 'Dashboard' ? 'bg-[#a31d1d] text-white' : ''; ?>">
                    <i class="fas fa-tachometer-alt text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <?php if (in_array('manage students', $facilitatorPermissions)): ?>
                <!-- Manage Students -->
                <div class="space-y-1">
                    <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 text-gray-700">
                        <i class="fas fa-users text-lg"></i>
                        <span class="font-medium">Manage Students</span>
                    </div>
                    <div class="ml-6 space-y-1">
                        <?php if (in_array('add student', $facilitatorPermissions)): ?>
                            <a href="<?php echo ROOT ?>add_student" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-[#a31d1d] hover:text-white transition-all duration-200 group">
                                <i class="fas fa-user-plus text-sm group-hover:scale-110 transition-transform"></i>
                                <span class="text-sm">Add Student</span>
                            </a>
                        <?php endif; ?>
                        <a href="?page=Students" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-[#a31d1d] hover:text-white transition-all duration-200 group <?php echo $page === 'Students' ? 'bg-[#a31d1d] text-white' : ''; ?>">
                            <i class="fas fa-list text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">View Students</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('manage attendance', $facilitatorPermissions)): ?>
                <!-- Manage Attendance -->
                <div class="space-y-1">
                    <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 text-gray-700">
                        <i class="fas fa-calendar-check text-lg"></i>
                        <span class="font-medium">Manage Attendance</span>
                    </div>
                    <div class="ml-6 space-y-1">
                        <a href="<?php echo ROOT ?>add_attendance" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-[#a31d1d] hover:text-white transition-all duration-200 group">
                            <i class="fas fa-plus text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">Create Event</span>
                        </a>
                        <a href="?page=Attendance" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-[#a31d1d] hover:text-white transition-all duration-200 group <?php echo $page === 'Attendance' ? 'bg-[#a31d1d] text-white' : ''; ?>">
                            <i class="fas fa-list text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">View Events</span>
                        </a>

                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('manage users', $facilitatorPermissions)): ?>
                <!-- Manage Users -->
                <div class="space-y-1">
                    <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 text-gray-700">
                        <i class="fas fa-user-cog text-lg"></i>
                        <span class="font-medium">Manage Users</span>
                    </div>
                    <div class="ml-6 space-y-1">
                        <!-- check if have the permission to add new user -->
                        <?php if (in_array('add user', $facilitatorPermissions)): ?>
                            <a href="<?php echo ROOT ?>add_user" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-[#a31d1d] hover:text-white transition-all duration-200 group">
                                <i class="fas fa-user-plus text-sm group-hover:scale-110 transition-transform"></i>
                                <span class="text-sm">Add User</span>
                            </a>
                        <?php endif; ?>
                        <a href="?page=Users" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-[#a31d1d] hover:text-white transition-all duration-200 group <?php echo $page === 'Users' ? 'bg-[#a31d1d] text-white' : ''; ?>">
                            <i class="fas fa-list text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">View Users</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Program Management (if has any program permissions) -->
                <?php 
                $programPermissions = array_filter($facilitatorPermissions, function($permission) {
                    return in_array($permission, ['BEED', 'BSNED', 'BSEED', 'BSED_MATH', 'BSED_ENGLISH', 'BSED_FILIPINO', 'BSIT', 'BSTVE', 'BSABE']);
                });
                if (!empty($programPermissions)): 
                ?>
                <div class="space-y-1">
                    <div class="flex items-center space-x-3 p-3 rounded-lg bg-gray-50 text-gray-700">
                        <i class="fas fa-graduation-cap text-lg"></i>
                        <span class="font-medium">Programs</span>
                    </div>
                    <div class="ml-6 space-y-1">
                        <?php foreach ($programPermissions as $program): ?>
                        <div class="flex items-center space-x-3 p-2 rounded-lg bg-blue-50 text-blue-700">
                            <i class="fas fa-check-circle text-sm"></i>
                            <span class="text-sm"><?php echo htmlspecialchars($program); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            
            </nav>
        </div>
    </div>
    
    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-40"></div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div id="mainContent" class="main-content flex-1 <?php echo !empty($facilitatorPermissions) ? 'with-sidebar' : ''; ?>">
        <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-4 md:p-6 mb-8 glass-card">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <?php if (!empty($facilitatorPermissions)): ?>
                <button id="menuButton" class="text-[#a31d1d] hover:text-[#8a1818] transition-colors p-2">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <?php endif; ?>
                <img src="<?php echo $imageSource; ?>" alt="OSAS Logo" class="w-12 h-12 md:w-16 md:h-16 rounded-lg">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Facilitator Dashboard</h1>
            </div>
            <div class="flex items-center gap-3">
                <!-- Profile Card (Desktop) -->
                <div class="hidden lg:flex items-center gap-4 bg-gradient-to-r from-[#a31d1d] to-red-900 px-4 py-2 rounded-xl text-white shadow-lg" style="min-width:220px;">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm"><?php echo htmlspecialchars($username ?? 'Facilitator'); ?></p>
                        <p class="text-xs opacity-90">Facilitator</p>
                    </div>
                </div>
                
                <?php if (!empty($facilitatorPermissions)): ?>
                <button id="sidebarToggle" class="hidden md:flex bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg font-semibold shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 items-center gap-2">
                    <i class="fas fa-bars text-sm"></i>
                    <span class="text-sm">Menu</span>
                </button>
                <?php endif; ?>
                <button onclick="logout('<?php echo ROOT; ?>')" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 md:px-6 py-2 md:py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2 w-full md:w-auto">
                    <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Logout</span>
                </button>
            </div>
        </div>
    </header>
    <script>
        function logout(root) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of the system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#a31d1d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = root + "logout";
                }
            });
        }
    </script>

    <!-- Activity Log Toggle -->
    <script>
        const fullActivityLog = <?php echo json_encode($activityLogList); ?>;
    </script>





    <script>
        const dropdownButton = document.getElementById('attendanceDropdownButton');
        const dropdownMenu = document.getElementById('attendanceDropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');

        dropdownButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
            dropdownIcon.classList.toggle('rotate-180');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
                dropdownIcon.classList.remove('rotate-180');
            }
        });

        // Close dropdown when an item is clicked
        dropdownMenu.querySelectorAll('a').forEach(item => {
            item.addEventListener('click', () => {
                dropdownMenu.classList.add('hidden');
                dropdownIcon.classList.remove('rotate-180');
            });
        });

        // Add hover effect for better UX (only on desktop)
        if (window.innerWidth > 768) {
            dropdownMenu.querySelectorAll('a').forEach(item => {
                item.addEventListener('mouseenter', () => {
                    item.style.transform = 'translateX(4px)';
                });
                
                item.addEventListener('mouseleave', () => {
                    item.style.transform = 'translateX(0)';
                });
            });
        }
    </script>
    <!-- Main Content Area -->
    <div class="facilitator-container w-full bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg">
        <?php if ($page === 'Dashboard'): ?>
            <!-- Dashboard Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-8">
                <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 md:p-6 hover-card">
                    <a href="<?php echo ROOT ?>scanner" class="block text-center">
                        <img src="<?php echo $imageSource3; ?>" alt="Scan QR Code" class="mx-auto w-32 h-32 md:w-48 md:h-48 object-cover rounded-xl mb-4 shadow-lg">
                        <h3 class="text-xl md:text-2xl font-bold text-[#a31d1d] mb-2">Scan QR Code</h3>
                        <p class="text-gray-600 text-sm md:text-base">Start scanning attendance for the current event</p>
                    </a>
                </div>
                <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 md:p-6 hover-card">
                    <h2 class="text-xl md:text-2xl font-bold text-[#a31d1d] mb-4 text-center">Current Event</h2>
                    <div class="space-y-2 md:space-y-3">
                        <div class="bg-blue-50 p-3 md:p-4 rounded-xl border border-blue-200">
                            <div class="text-blue-600 text-base md:text-lg mb-1">📅 Event</div>
                            <div class="text-blue-700 font-semibold text-sm md:text-base"><?php echo htmlspecialchars($EventName)?></div>
                        </div>
                        <div class="bg-green-50 p-3 md:p-4 rounded-xl border border-green-200">
                            <div class="text-green-600 text-base md:text-lg mb-1">📆 Date</div>
                            <div class="text-green-700 font-semibold text-sm md:text-base"><?php echo htmlspecialchars($EventDate)?></div>
                        </div>
                        <div class="bg-purple-50 p-3 md:p-4 rounded-xl border border-purple-200">
                            <div class="text-purple-600 text-base md:text-lg mb-1">⏰ Time</div>
                            <div class="text-purple-700 font-semibold text-sm md:text-base"><?php echo htmlspecialchars($EventTime)?></div>
                        </div>
                        <div class="bg-orange-50 p-3 md:p-4 rounded-xl border border-orange-200">
                            <div class="text-orange-600 text-base md:text-lg mb-1">📍 Location</div>
                            <div class="text-orange-700 font-semibold text-sm md:text-base"><?php echo htmlspecialchars($EventLocation)?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log Section -->
            <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 md:p-6 mb-8">
                <button onclick="toggleLogs()" class="w-full sm:w-auto bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 md:px-6 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2 mb-4">
                    <i class="fas fa-clock"></i> View Activity Log
                </button>
                <div id="activity-log" class="mt-4 hidden">
                    <h3 class="text-xl md:text-2xl font-bold text-[#a31d1d] mb-4">Activity Log</h3>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 md:gap-4 mb-4">
                        <input type="text" id="search-input" placeholder="Search activity logs..."
                               class="flex-1 px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d] text-sm md:text-base">
                        <button type="button" id="search-btn"
                                class="w-full sm:w-auto bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 md:px-6 py-2 md:py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                    <div class="h-60 md:h-80 overflow-y-auto border border-gray-200 rounded-xl p-3 md:p-4 bg-gray-50 hide-scrollbar">
                        <ul class="space-y-2 md:space-y-3" id="activity-log-list">
                            <!-- Logs will be rendered here by JS -->
                        </ul>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Load other pages -->
            <div>
                <?php require "../app/Controller/{$page}.php"; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function renderLogs(logs) {
            const list = document.getElementById("activity-log-list");
            list.innerHTML = '';
            if (logs.length === 0) {
                list.innerHTML = '<li class="text-gray-500 text-center py-8">No activity logs found.</li>';
            } else {
                logs.forEach(log => {
                    const item = document.createElement("li");
                    item.className = "glass-card rounded-xl p-4 text-gray-700 hover-card";
                    item.innerHTML = `
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <span class="font-semibold text-[#a31d1d]">${log.activity}</span>
                                <div class="text-sm text-gray-500 mt-1">${log.time_created}</div>
                            </div>
                            <i class="fas fa-clock text-[#a31d1d] text-lg ml-3"></i>
                        </div>
                    `;
                    list.appendChild(item);
                });
            }
        }

        document.getElementById("search-btn").addEventListener("click", () => {
            const keyword = document.getElementById("search-input").value.toLowerCase();
            const filtered = fullActivityLog.filter(log =>
                log.activity.toLowerCase().includes(keyword) ||
                log.time_created.toLowerCase().includes(keyword)
            );
            renderLogs(filtered);
        });

        document.getElementById("search-input").addEventListener("keypress", function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById("search-btn").click();
            }
        });

        document.addEventListener("DOMContentLoaded", () => {
            renderLogs(fullActivityLog);
        });

        function toggleLogs() {
            const logSection = document.getElementById("activity-log");
            logSection.classList.toggle("hidden");
        }
    </script>
    
    <!-- Sidebar JavaScript -->
    <?php if (!empty($facilitatorPermissions)): ?>
    <script>
        // Sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const menuButton = document.getElementById('menuButton');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const closeSidebarButton = document.getElementById('closeSidebar');
        const mainContent = document.getElementById('mainContent');
        
        let sidebarOpen = false;
        
        // Toggle sidebar
        function toggleSidebar() {
            if (sidebarOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }
        
        // Open sidebar
        function openSidebar() {
            sidebar.classList.add('open');
            sidebarOverlay.classList.add('open');
            mainContent.classList.add('open');
            document.body.style.overflow = 'hidden';
            sidebarOpen = true;
            
            // Update toggle button text
            if (sidebarToggle) {
                sidebarToggle.innerHTML = '<i class="fas fa-times text-sm"></i><span class="text-sm">Close</span>';
            }
        }
        
        // Close sidebar
        function closeSidebar() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
            mainContent.classList.remove('open');
            document.body.style.overflow = 'auto';
            sidebarOpen = false;
            
            // Update toggle button text
            if (sidebarToggle) {
                sidebarToggle.innerHTML = '<i class="fas fa-bars text-sm"></i><span class="text-sm">Menu</span>';
            }
        }
        
        // Event listeners
        if (menuButton) {
            menuButton.addEventListener('click', toggleSidebar);
        }
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
        
        if (closeSidebarButton) {
            closeSidebarButton.addEventListener('click', closeSidebar);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        
        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
        
        // Initialize sidebar state based on screen size
        function initializeSidebar() {
            if (window.innerWidth > 768) {
                // Desktop: Start with sidebar closed, let user toggle
                sidebarOpen = false;
                if (sidebarToggle) {
                    sidebarToggle.innerHTML = '<i class="fas fa-bars text-sm"></i><span class="text-sm">Menu</span>';
                }
            } else {
                // Mobile: Start with sidebar closed
                sidebarOpen = false;
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializeSidebar);
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768 && sidebarOpen) {
                closeSidebar();
            }
        });
    </script>
    <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>