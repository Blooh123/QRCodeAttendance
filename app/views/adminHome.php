<?php
global $imageSource, $OSASLogo, $username;
require_once "../app/core/imageConfig.php";

$page = $_GET['page'] ?? 'Dashboard';
$allowed_pages = ['Dashboard', 'Students', 'Attendance', 'Users', 'ProfileAdmin', 'StudentApplication'];
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

<!-- Responsive Header -->
<header class="w-full shadow-lg sticky top-0 z-50 glass-header">
    <div class="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">
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
                    'ProfileAdmin' => ['Profile', 'fas fa-user-circle']
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
    <div class="admin-container w-full max-w-5xl bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg mt-8">

        <div>
            <?php require "../app/Controller/{$page}.php"; ?>
        </div>
    </div>
</main>

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
</script>
</body>
</html>