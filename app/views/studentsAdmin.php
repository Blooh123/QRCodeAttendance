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
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Search Loading Overlay -->
<div id="searchLoadingOverlay" class="search-loading-overlay">
    <div class="search-loading-container">
        <div class="search-loading-spinner"></div>
        <div class="search-loading-text">Hang on tight...</div>
    </div>
</div>

<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-user-graduate text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Students</h1>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Search and Filter Section -->
    <div class="glass-card rounded-2xl p-6 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <form action="<?php echo ROOT ?>adminHome" method="GET" class="flex flex-col md:flex-row items-center gap-4">
            <input type="hidden" name="page" value="Students">
            <div class="flex items-center w-full md:w-auto gap-2">
                <input type="text" name="search" id="search-input" placeholder="Search..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                       class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                <button type="submit" id="search-btn" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="text-gray-600 text-sm">
                Number of Students: <span class="font-bold"><?php echo $numOfStudent ?></span>
            </div>
        </form>

        <form action="<?php echo ROOT ?>adminHome" method="GET" class="flex flex-col md:flex-row items-center gap-4 mt-4 filter-container">
            <input type="hidden" name="page" value="Students">
            <select name="program" id="program-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                <option value="">Select Program</option>
                <?php foreach ($programList as $program): ?>
                    <option value="<?php echo htmlspecialchars($program['program']); ?>"
                        <?php echo (isset($_GET['program']) && $_GET['program'] === $program['program']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($program['program']); ?>
                    </option>
                <?php endforeach ?>
            </select>

            <select name="year" id="year-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                <option value="">Select Year</option>
                <?php foreach ($yearList as $year): ?>
                    <option value="<?php echo htmlspecialchars($year['acad_year']); ?>"
                        <?php echo (isset($_GET['year']) && $_GET['year'] === $year['acad_year']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($year['acad_year']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-filter"></i> Apply Filter
            </button>
        </form>

        <a href="<?php echo ROOT ?>add_student"
           class="mt-4 inline-block bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
            <i class="fas fa-user-graduate"></i> Add Student
        </a>
    </div>

    <!-- Students Grid -->
    <?php if (!empty($studentsList)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <?php foreach ($studentsList as $student): ?>
                <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 flex flex-col space-y-3 hover-card">
                    <div class="flex items-center space-x-3 mb-2">
                        <i class="fas fa-user-graduate text-[#a31d1d] text-2xl"></i>
                        <h2 class="text-xl font-semibold text-[#a31d1d]">
                            <?php echo htmlspecialchars($student['name']); ?>
                        </h2>
                    </div>
                    <p class="text-gray-700"><strong>ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                    <p class="text-gray-700"><strong>Program:</strong> <?php echo htmlspecialchars($student['program']); ?></p>
                    <p class="text-gray-700"><strong>Year:</strong> <?php echo htmlspecialchars($student['acad_year']); ?></p>
                    <p class="text-gray-700"><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                    <div class="flex justify-between mt-4">
                        <a href="<?php echo ROOT?>edit_student?id=<?php echo htmlspecialchars($student['student_id']); ?>"
                           class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?php echo ROOT?>delete_student?id=<?php echo htmlspecialchars($student['student_id']); ?>"
                           onclick="return confirmDelete(event, this.href);"
                           class="bg-red-600 hover:bg-red-800 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($isFiltered): ?>
        <p class="text-center text-gray-600 mt-6">No students found for the selected filters.</p>
    <?php elseif(!$isFiltered):?>
        <p class="text-center text-gray-600 mt-6">Student Information will be displayed here.</p>
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
        
        // Show loading for search form
        document.querySelector('form[action*="adminHome"]').addEventListener('submit', function() {
            searchLoadingOverlay.style.display = 'flex';
        });

        // Show loading for filter form
        document.querySelector('.filter-container').addEventListener('submit', function() {
            searchLoadingOverlay.style.display = 'flex';
        });

        // Hide loading when page is fully loaded
        window.addEventListener('load', function() {
            searchLoadingOverlay.style.display = 'none';
        });
    });
</script>
</body>
</html>