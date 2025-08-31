<?php

global $isFiltered;
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (\Random\RandomException $e) {

    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students • USep Attendance System</title>
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
        .search-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }
        .search-loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .search-loading-spinner {
            position: relative;
            width: 60px;
            height: 60px;
        }
        .search-loading-spinner:before,
        .search-loading-spinner:after {
            content: '';
            position: absolute;
            border-radius: 50%;
            animation: searchPulse 1.5s linear infinite;
        }
        .search-loading-spinner:before {
            width: 100%;
            height: 100%;
            background: rgba(220, 38, 38, 0.2);
            animation-delay: -0.5s;
        }
        .search-loading-spinner:after {
            width: 75%;
            height: 75%;
            background: #dc2626;
            top: 12.5%;
            left: 12.5%;
            animation-delay: -1s;
        }
        .search-loading-text {
            color: #dc2626;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            animation: searchFadeInOut 1.5s ease-in-out infinite;
        }
        @keyframes searchPulse {
            0% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            50% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
        }
        @keyframes searchFadeInOut {
            0%, 100% {
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
        }
        .search-animated-logo {
            position: relative;
            width: 70px;
            height: 70px;
            margin-bottom: 10px;
        }
        .logo-circle {
            width: 100%;
            height: 100%;
            border: 6px solid #a31d1d;
            border-top: 6px solid #f8fafc;
            border-radius: 50%;
            animation: spin 1.2s cubic-bezier(.68,-0.55,.27,1.55) infinite;
            box-shadow: 0 0 30px 0 #a31d1d33;
        }
        .logo-dot {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 18px;
            height: 18px;
            background: #a31d1d;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: bounce 1.2s infinite alternate;
            box-shadow: 0 0 10px 2px #a31d1d44;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        @keyframes bounce {
            0% { transform: translate(-50%, -50%) scale(1);}
            60% { transform: translate(-50%, -60%) scale(1.15);}
            100% { transform: translate(-50%, -50%) scale(1);}
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Search Loading Overlay -->
<div id="searchLoadingOverlay" class="search-loading-overlay">
    <div class="search-loading-container">
        <div class="search-animated-logo">
            <div class="logo-circle"></div>
            <div class="logo-dot"></div>
        </div>
        <div class="search-loading-text" id="searchLoadingText">Hang on tight...</div>
    </div>
</div>

<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-4 md:p-6 mb-6 md:mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <i class="fas fa-user-graduate text-[#a31d1d] text-2xl md:text-3xl"></i>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Students</h1>
        </div>
        
        <!-- Permission Indicator -->
        <?php if (isset($userRole) && $userRole === 'Facilitator' && !empty($facilitatorCoursePermissions)): ?>
            <div class="flex items-center space-x-2 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2 text-xs md:text-sm">
                <i class="fas fa-shield-alt text-blue-600"></i>
                <span class="text-blue-800 font-medium">
                    Access: <?php echo implode(', ', $facilitatorCoursePermissions); ?>
                </span>
            </div>
        <?php elseif (isset($userRole) && $userRole === 'admin'): ?>
            <div class="flex items-center space-x-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2 text-xs md:text-sm">
                <i class="fas fa-crown text-green-600"></i>
                <span class="text-green-800 font-medium">Full Access</span>
            </div>
        <?php endif; ?>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Search and Filter Section -->
    <div class="glass-card rounded-2xl p-4 md:p-6 mb-6 md:mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <form action="<?php echo ROOT ?>adminHome" method="GET" class="space-y-4">
            <input type="hidden" name="page" value="Students">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="flex-1 flex items-center gap-2">
                    <input type="text" name="search" id="search-input" placeholder="Search students..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                           class="flex-1 px-3 md:px-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                    <button type="submit" id="search-btn" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-3 md:px-4 py-2 md:py-2.5 rounded-lg flex items-center gap-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 text-sm md:text-base">
                        <i class="fas fa-search"></i>
                        <span class="hidden sm:inline">Search</span>
                    </button>
                </div>
                <div class="text-gray-600 text-sm md:text-base text-center sm:text-left">
                    Students: <span class="font-bold"><?php echo $numOfStudent ?></span>
                </div>
            </div>
        </form>

        <form action="<?php echo ROOT ?>adminHome" method="GET" class="space-y-4 mt-6 filter-container">
            <input type="hidden" name="page" value="Students">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <select name="program" id="program-filter" class="w-full px-3 md:px-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                    <option value="">Select Program</option>
                    <?php if (isset($userRole) && $userRole === 'Facilitator' && !empty($facilitatorCoursePermissions)): ?>
                        <!-- Show only permitted programs for facilitators -->
                        <?php foreach ($programList as $program): ?>
                            <?php if (in_array($program['program'], $facilitatorCoursePermissions)): ?>
                                <option value="<?php echo htmlspecialchars($program['program']); ?>"
                                    <?php echo (isset($_GET['program']) && $_GET['program'] === $program['program']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($program['program']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Show all programs for admins or facilitators without specific course permissions -->
                        <?php foreach ($programList as $program): ?>
                            <option value="<?php echo htmlspecialchars($program['program']); ?>"
                                <?php echo (isset($_GET['program']) && $_GET['program'] === $program['program']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($program['program']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <select name="year" id="year-filter" class="w-full px-3 md:px-4 py-2 md:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                    <option value="">Select Year</option>
                    <?php foreach ($yearList as $year): ?>
                        <option value="<?php echo htmlspecialchars($year['acad_year']); ?>"
                            <?php echo (isset($_GET['year']) && $_GET['year'] === $year['acad_year']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($year['acad_year']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="w-full bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 md:py-2.5 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2 text-sm md:text-base">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
            </div>
        </form>
        
        <!-- check for permission to add student -->
        <?php if (isset($userRole) && $userRole === 'Facilitator' && in_array('add student', $facilitatorPermissions)): ?>
            <div class="mt-6 pt-4 border-t border-gray-200">
                <a href="<?php echo ROOT ?>add_student"
                class="w-full sm:w-auto inline-block bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 md:px-6 py-2 md:py-2.5 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 text-center">
                    <i class="fas fa-user-graduate"></i> Add Student
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Students Grid -->
    <?php if (!empty($studentsList)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mt-6">
            <?php foreach ($studentsList as $student): ?>
                <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 md:p-6 flex flex-col space-y-3 hover-card">
                    <div class="flex items-center space-x-2 md:space-x-3 mb-2">
                        <i class="fas fa-user-graduate text-[#a31d1d] text-xl md:text-2xl"></i>
                        <h2 class="text-lg md:text-xl font-semibold text-[#a31d1d] truncate">
                            <?php echo htmlspecialchars($student['name']); ?>
                        </h2>
                    </div>
                    <div class="space-y-2 text-sm md:text-base">
                        <p class="text-gray-700"><strong>ID:</strong> <span class="break-all"><?php echo htmlspecialchars($student['student_id']); ?></span></p>
                        <p class="text-gray-700"><strong>Program:</strong> <span class="break-words"><?php echo htmlspecialchars($student['program']); ?></span></p>
                        <p class="text-gray-700"><strong>Year:</strong> <?php echo htmlspecialchars($student['acad_year']); ?></p>
                        <p class="text-gray-700"><strong>Email:</strong> <span class="break-all text-xs md:text-sm"><?php echo htmlspecialchars($student['email']); ?></span></p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 mt-4">
                        <a href="<?php echo ROOT?>edit_student?id=<?php echo htmlspecialchars($student['student_id']); ?>"
                           class="flex-1 bg-blue-600 hover:bg-blue-800 text-white px-3 md:px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-1 text-sm md:text-base">
                            <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span>
                        </a>
                        <?php if (isset($userRole) && $userRole === 'Facilitator' && in_array('delete student', $facilitatorPermissions)): ?>
                            <a href="<?php echo ROOT?>delete_student?id=<?php echo htmlspecialchars($student['student_id']); ?>"
                            onclick="return confirmDelete(event, this.href);"
                            class="flex-1 bg-red-600 hover:bg-red-800 text-white px-3 md:px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-1 text-sm md:text-base">
                                <i class="fas fa-trash"></i> <span class="hidden sm:inline">Delete</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($isFiltered): ?>
        <div class="text-center py-12">
            <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-600 text-lg">No students found for the selected filters.</p>
            <p class="text-gray-500 text-sm mt-2">Try adjusting your search criteria.</p>
        </div>
    <?php elseif(!$isFiltered):?>
        <div class="text-center py-12">
            <i class="fas fa-user-graduate text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-600 text-lg">Student Information will be displayed here.</p>
            <p class="text-gray-500 text-sm mt-2">Use the search and filter options above to find students.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    function changePage(pageNumber) {
        document.getElementById('pageInput').value = pageNumber;
        document.getElementById('paginationForm').submit();
    }
    function confirmDelete(event, url) {
        event.preventDefault(); // Prevents immediate navigation

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url; // Redirects to delete URL
            }
        });
    }

    // Add loading screen functionality for search and filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchLoadingOverlay = document.getElementById('searchLoadingOverlay');
        const searchLoadingText = document.getElementById('searchLoadingText');
        let loadingInterval = null;
        let loadingMessages = [
            "Hang on tight...",
            "Crunching the numbers...",
            "Fetching student data...",
            "Almost there...",
            "Just a moment more..."
        ];
        let msgIndex = 0;

        function startLoading() {
            msgIndex = 0;
            searchLoadingText.textContent = loadingMessages[msgIndex];
            searchLoadingOverlay.style.display = 'flex';
            loadingInterval = setInterval(() => {
                msgIndex = (msgIndex + 1) % loadingMessages.length;
                searchLoadingText.textContent = loadingMessages[msgIndex];
            }, 1200);
        }

        function stopLoading() {
            searchLoadingOverlay.style.display = 'none';
            clearInterval(loadingInterval);
        }

        // Show loading for search form
        document.querySelector('form[action*="adminHome"]').addEventListener('submit', function() {
            startLoading();
        });

        // Show loading for filter form
        document.querySelector('.filter-container').addEventListener('submit', function() {
            startLoading();
        });

        // Hide loading when page is fully loaded
        window.addEventListener('load', function() {
            stopLoading();
        });
    });
</script>
</body>
</html>