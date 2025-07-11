<?php


global $imageSource, $imageSource4, $imageSource2;
require_once '../app/core/imageConfig.php'; // Include your configuration file

$page = $_GET['page'] ?? 'StudentProfile'; // Default to 'studentProfile'
$allowed_pages = ['StudentProfile', 'StudentQRCode', 'StudentReport', 'Event'];

// Prevent loading invalid files
if (!in_array($page, $allowed_pages)) {
    $page = 'studentProfile';
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource ?>">
    <title>Student Home • QRCode Attendance System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        
        body {
            background-image: 
                radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
                linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
            font-family: 'Poppins', Arial, Helvetica, sans-serif !important;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .text-stroke-2 {
            -webkit-text-stroke: 2px black;
            text-stroke: 2px black;
        }
        .text-stroke-1 {
            -webkit-text-stroke: 1px black;
            text-stroke: 1px black;
        }
        .sidebar-overlay {
            background: rgba(0,0,0,0.35);
            z-index: 40;
        }

        /* Add these new styles */
        .sidebar-height {
            height: 100vh;
            overflow: hidden;
        }
        
        .nav-container {
            height: calc(100vh - 5rem); /* Subtract header height */
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
        }
        
        .nav-container::-webkit-scrollbar {
            display: none; /* Chrome/Safari/Opera */
        }

        @media (min-width: 768px) {
            .desktop-nav {
                height: 80px;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
            }
            
            .nav-link {
                position: relative;
            }
            
            .nav-link::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 0;
                height: 2px;
                background: #a31d1d;
                transition: width 0.3s ease;
            }
            
            .nav-link:hover::after {
                width: 100%;
            }
            
            .nav-link.active::after {
                width: 100%;
            }
        }
    </style>
</head>
<body class="bg-[#f8f9fa]">

<!-- Container -->
<div class="min-h-screen flex flex-col">
    <!-- Desktop Navigation - Visible only on md and above -->
    <header class="hidden md:block desktop-nav shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-full">
            <div class="flex items-center justify-between h-full">
                <div class="flex items-center gap-4">
                    <img class="h-14 w-auto floating" src="<?php echo $imageSource4 ?>" alt="Logo" />
                </div>
                
                <nav class="flex items-center gap-8">
                    <a href="?page=StudentProfile" 
                       class="nav-link text-lg font-semibold transition-colors <?php echo $page === 'StudentProfile' ? 'text-[#a31d1d] active' : 'text-[#515050] hover:text-[#a31d1d]'; ?>">
                        Profile
                    </a>
                    <a href="?page=StudentQRCode" 
                       class="nav-link text-lg font-semibold transition-colors <?php echo $page === 'StudentQRCode' ? 'text-[#a31d1d] active' : 'text-[#515050] hover:text-[#a31d1d]'; ?>">
                        QR Code
                    </a>
                    <a href="?page=Event" 
                       class="nav-link text-lg font-semibold transition-colors <?php echo $page === 'Event' ? 'text-[#a31d1d] active' : 'text-[#515050] hover:text-[#a31d1d]'; ?>">
                        Events
                    </a>
                    <a href="?page=StudentReport" 
                       class="nav-link text-lg font-semibold transition-colors <?php echo $page === 'StudentReport' ? 'text-[#a31d1d] active' : 'text-[#515050] hover:text-[#a31d1d]'; ?>">
                        Reports
                    </a>
                    <a href="<?php echo ROOT ?>logout" 
                       class="ml-4 px-6 py-2 rounded-xl text-lg font-semibold transition-all duration-200 bg-[#a31d1d] text-white hover:bg-[#8a1818] shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                        Logout
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar - Only visible on small screens -->
    <div class="md:hidden">
        <!-- Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 sidebar-overlay hidden"></div>
        
        <!-- Mobile Navigation -->
        <aside id="sidebarMenu" class="fixed z-50 left-0 top-0 h-full w-64 bg-white/90 backdrop-blur-lg shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out rounded-r-3xl">
            <!-- Sidebar Header - Fixed -->
            <div class="flex items-center gap-4 px-6 py-6 border-b border-gray-200 bg-white/90 backdrop-blur-lg">
                <img class="h-14 w-auto floating" src="<?php echo $imageSource4 ?>" alt="Logo" />
            </div>
            
            <!-- Navigation Links - Scrollable if needed -->
            <div class="nav-container">
                <nav class="flex-1 flex flex-col gap-2 px-4 py-6">
                    <a href="?page=StudentProfile"
                       class="mb-1 px-5 py-3 rounded-xl text-lg font-semibold transition-all duration-200
                          <?php echo $page === 'StudentProfile' 
                            ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' 
                            : 'bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black'; ?>">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profile</span>
                        </div>
                    </a>
                    <a href="?page=StudentQRCode"
                       class="mb-1 px-5 py-3 rounded-xl text-lg font-semibold transition-all duration-200
                          <?php echo $page === 'StudentQRCode' 
                            ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' 
                            : 'bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black'; ?>">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span>QR Code</span>
                        </div>
                    </a>
                    <a href="?page=Event"
                       class="mb-1 px-5 py-3 rounded-xl text-lg font-semibold transition-all duration-200
                          <?php echo $page === 'Event' 
                            ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' 
                            : 'bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black'; ?>">
                        <div class="flex items-center gap-3">
                            <!-- SVG Calendar Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="<?php echo $page === 'Event' ? '#fff' : '#a31d1d'; ?>"
                                 stroke-width="2">
                              <rect x="3" y="8" width="18" height="13" rx="2" />
                              <path d="M16 2v4M8 2v4M3 10h18" />
                            </svg>
                            <span>Events</span>
                        </div>
                    </a>
                    <a href="?page=StudentReport"
                       class="mb-1 px-5 py-3 rounded-xl text-lg font-semibold transition-all duration-200
                          <?php echo $page === 'StudentReport' 
                            ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' 
                            : 'bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black'; ?>">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Reports</span>
                        </div>
                    </a>
                    
                    <!-- Logout at the bottom -->
                    <a href="<?php echo ROOT ?>logout"
                       class="mt-auto px-5 py-3 rounded-xl text-lg font-semibold transition-all duration-200 bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </div>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Mobile Toggle Button -->
        <button id="openSidebarBtn" 
            class="fixed md:hidden top-4 right-4 z-50 bg-[#a31d1d] hover:bg-[#7c1818] 
                   rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black 
                   text-white p-3 transition-all duration-200 focus:outline-[#a31d1d] focus:ring-2 focus:ring-[#a31d1d]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>

    <!-- Main Content - Updated padding for desktop -->
    <div class="flex-1 flex flex-col min-h-screen">
        <main class="flex-1 container mx-auto px-4 mb-8 pt-8">
            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-[0_8px_32px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_12px_48px_-8px_rgba(0,0,0,0.2)] transition-all duration-300 relative overflow-hidden">
                <img 
                    class="absolute inset-0 w-full h-full object-contain opacity-20 pointer-events-none select-none z-0 scale-125"
                    src="<?php echo $imageSource2?>" 
                    alt="Illustration" 
                    style="filter: blur(0.5px);" 
                />
                <div class="relative z-10">
                    <?php require "../app/Controller/{$page}.php"; ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/80 backdrop-blur-sm shadow-lg w-full py-6">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <div class="text-[#515050] text-lg font-normal">Copyright © <?php echo date('Y'); ?>. All Rights Reserved.</div>
                <div class="flex justify-center space-x-4 mt-2">
                    <a href="#" class="text-[#515050] text-lg font-bold hover:text-[#a31d1d] transition-colors">Terms of Service</a>
                    <div class="w-px h-6 bg-[#515050]"></div>
                    <a href="https://www.usep.edu.ph/usep-data-privacy-statement/" target="_blank" class="text-[#515050] text-lg font-bold hover:text-[#a31d1d] transition-colors">Privacy Policy</a>
                </div>
            </div>
        </footer>
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
        openSidebarBtn.style.opacity = '0'; // Hide menu button
        openSidebarBtn.style.pointerEvents = 'none'; // Disable interactions
    }

    function closeSidebar() {
        sidebar.style.transform = 'translateX(-100%)';
        sidebarOverlay.classList.add('hidden');
        document.body.style.overflow = '';
        openSidebarBtn.style.opacity = '1'; // Show menu button
        openSidebarBtn.style.pointerEvents = 'auto'; // Enable interactions
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
            openSidebarBtn.style.opacity = '0';
        } else {
            sidebar.style.transform = 'translateX(-100%)';
            openSidebarBtn.style.opacity = '1';
            openSidebarBtn.style.pointerEvents = 'auto';
        }
    });

    // Prevent context menu and dev tools
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
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
