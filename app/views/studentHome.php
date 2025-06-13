<?php


global $imageSource;
require_once '../app/core/imageConfig.php'; // Include your configuration file

$page = $_GET['page'] ?? 'studentProfile'; // Default to 'studentProfile'
$allowed_pages = ['StudentProfile', 'StudentQRCode', 'StudentReport'];

// Prevent loading invalid files
if (!in_array($page, $allowed_pages)) {
    $page = 'StudentProfile';
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
    </style>
</head>
<body class="bg-[#f8f9fa]">

<!-- Container -->
<div class="min-h-screen flex flex-col">
    <!-- Header Section with QR CODE text -->
    <div class="sticky top-0 z-50">
        <header class="bg-white/80 backdrop-blur-sm shadow-lg w-full">
            <div class="max-w-7xl mx-auto p-4">
                <div class="w-full flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <img class="h-16 w-auto" src="<?php echo $imageSource4 ?>" alt="Logo" />
                        <!-- <div class="hidden md:flex items-center gap-1">
                            <span class="text-white text-4xl font-extrabold font-['Poppins'] text-stroke-2 [text-shadow:_0px_4px_0px_rgb(0_0_0_/_1.00)]">CONGRATS SA WALAY</span>
                            <span class="text-red-800 text-4xl font-extrabold font-['Poppins'] text-stroke-2 [text-shadow:_0px_4px_0px_rgb(0_0_0_/_1.00)]">SANCTIONS! 🥳🍆</span>
                        </div> -->
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button id="menuBtn" class="md:hidden text-[#515050] hover:text-[#a31d1d] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Navigation Section -->
        <nav id="navMenu" class="w-full bg-white/80 backdrop-blur-sm shadow-lg transition-all duration-300 ease-in-out">
            <div class="max-w-7xl mx-auto p-4">
                <div class="flex flex-col md:flex-row justify-center items-center gap-4">
                    <a href="?page=studentProfile"
                       class="w-full md:w-auto px-6 py-2 rounded-xl text-lg font-semibold text-center transition-all duration-200
                          <?php echo $page === 'studentProfile' 
                            ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' 
                            : 'bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black'; ?>">
                    Profile
                    </a>
                    <a href="?page=StudentQRCode"
                       class="w-full md:w-auto px-6 py-2 rounded-xl text-lg font-semibold text-center transition-all duration-200
                          <?php echo $page === 'StudentQRCode' 
                            ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' 
                            : 'bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black'; ?>">
                    QR Code
                    </a>
                    <a href="?page=StudentReport"
                       class="w-full md:w-auto px-6 py-2 rounded-xl text-lg font-semibold text-center transition-all duration-200
                          <?php echo $page === 'StudentReport' 
                            ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' 
                            : 'bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black'; ?>">
                    Reports
                    </a>
                    <a href="<?php echo ROOT ?>logout"
                       class="w-full md:w-auto px-6 py-2 rounded-xl text-lg font-semibold text-center transition-all duration-200 bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    Logout
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 mb-8">
        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-[0_8px_32px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_12px_48px_-8px_rgba(0,0,0,0.2)] transition-all duration-300 relative overflow-hidden">
            <!-- Background Illustration -->
            <img 
                class="absolute inset-0 w-full h-full object-contain opacity-20 pointer-events-none select-none z-0 scale-125"
                src="<?php echo $imageSource2?>" 
                alt="Illustration" 
                style="filter: blur(0.5px);" 
            />
            
            <!-- Content -->
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

    const menuBtn = document.getElementById('menuBtn');
    const navMenu = document.getElementById('navMenu');
    let isMenuOpen = false;

    // Initially hide menu on mobile
    if (window.innerWidth < 768) {
        navMenu.style.maxHeight = '0';
        navMenu.style.overflow = 'hidden';
    }

    menuBtn.addEventListener('click', () => {
        isMenuOpen = !isMenuOpen;
        if (isMenuOpen) {
            navMenu.style.maxHeight = navMenu.scrollHeight + 'px';
            navMenu.style.overflow = 'visible';
        } else {
            navMenu.style.maxHeight = '0';
            navMenu.style.overflow = 'hidden';
        }
    });

    // Handle resize events
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            navMenu.style.maxHeight = 'none';
            navMenu.style.overflow = 'visible';
        } else if (!isMenuOpen) {
            navMenu.style.maxHeight = '0';
            navMenu.style.overflow = 'hidden';
        }
    });
</script>

</body>
</html>
