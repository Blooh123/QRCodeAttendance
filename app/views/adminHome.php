<?php
global $imageSource, $OSASLogo, $username;
require_once "../app/core/imageConfig.php";

$page = $_GET['page'] ?? 'Dashboard';
$allowed_pages = ['Dashboard', 'Students', 'Attendance', 'Users', 'ProfileAdmin'];
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;800&display=swap">
    <style>
        body {
            background-image: 
                radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
                linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
            font-family: 'Poppins', Arial, Helvetica, sans-serif !important;
        }
    </style>
    <title>Attendance System • Admin</title>
</head>
<body class="bg-[#f8f9fa] font-['Poppins']">

<!-- Responsive Header -->
<header class="w-full shadow-lg sticky top-0 z-50 bg-white/90 backdrop-blur-lg">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <img class="h-14 w-auto" src="<?php echo $imageSource ?>" alt="Logo" />
            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-6">
                <?php
                $navPages = [
                    'Dashboard' => 'Dashboard',
                    'Students' => 'Students',
                    'Attendance' => 'Attendance',
                    'Users' => 'Accounts',
                    'ProfileAdmin' => 'Profile'
                ];
                foreach ($navPages as $key => $label): ?>
                    <a href="?page=<?php echo $key; ?>"
                       class="nav-link text-lg font-semibold transition-colors <?php echo $page === $key ? 'text-[#a31d1d] active' : 'text-[#515050] hover:text-[#a31d1d]'; ?>">
                        <?php echo $label; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
        <div class="flex items-center gap-4">
            <img src="<?php echo $OSASLogo ?>" alt="Profile" class="h-12 w-12 rounded-full border-2 border-[#a31d1d] object-cover">
            <span class="font-bold text-[#515050] hidden md:inline"><?php echo $username ?></span>
            <a href="<?php echo ROOT ?>logout"
               class="px-6 py-2 rounded-xl text-lg font-semibold transition-all duration-200 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black bg-[#a31d1d] text-white hover:bg-[#8a1818] ml-2">
                Logout
            </a>
            <!-- Mobile menu button -->
            <button id="openSidebarBtn" class="md:hidden p-2 rounded-lg bg-[#a31d1d] text-white hover:bg-[#8a1818]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </div>
    <!-- Mobile Sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden"></div>
    <aside id="sidebarMenu" class="fixed z-50 left-0 top-0 h-full w-64 bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out rounded-r-3xl">
        <div class="flex items-center gap-4 px-6 py-6 border-b border-gray-200 bg-white">
            <img class="h-14 w-auto" src="<?php echo $imageSource ?>" alt="Logo" />
        </div>
        <nav class="flex-1 flex flex-col gap-2 px-4 py-6 bg-white">
            <?php foreach ($navPages as $key => $label): ?>
                <a href="?page=<?php echo $key; ?>"
                   class="mb-1 px-6 py-2 rounded-xl text-lg font-semibold transition-all duration-200 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black
                   <?php echo $page === $key
                       ? 'bg-[#a31d1d] text-white hover:bg-[#8a1818]'
                       : 'bg-white text-[#515050] hover:bg-[#a31d1d] hover:text-white'; ?>">
                    <?php echo $label; ?>
                </a>
            <?php endforeach; ?>
            <a href="<?php echo ROOT ?>logout"
               class="mt-4 px-6 py-2 rounded-xl text-lg font-semibold transition-all duration-200 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black bg-[#a31d1d] text-white hover:bg-[#8a1818]">
                Logout
            </a>
        </nav>
    </aside>
</header>

<!-- Main Content -->
<main class="flex flex-col items-center justify-start p-4 min-h-screen">
    <div class="admin-container w-full max-w-5xl bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg mt-8">
        <div class="admin-header text-3xl font-extrabold text-[#a31d1d] mb-6 text-center">
            <?php echo htmlspecialchars($page); ?>
        </div>
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