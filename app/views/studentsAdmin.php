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
    <link rel="stylesheet" href="<?php echo ROOT ?>assets/css/students.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Students</title>
    <style>
        /* Search Loading Overlay */
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
<body class="bg-[#f8f9fa]">
<!-- Search Loading Overlay -->
<div id="searchLoadingOverlay" class="search-loading-overlay">
    <div class="search-loading-container">
        <div class="search-loading-spinner"></div>
        <div class="search-loading-text">Hang on tight...</div>
    </div>
</div>

<header class="bg-white shadow-sm p-6">
    <div class="max-w-7xl mx-auto flex items-center space-x-2">
        <i class="fas fa-user-graduate text-gray-900 text-2xl"></i>
        <h1 class="text-3xl font-bold text-gray-900">Students</h1>
    </div>
</header>

<div class="container">
    <!-- Search and Filter Section -->
    <div class="search-bar">
        <!-- Search Input -->
        <form action="<?php echo ROOT ?>adminHome" method="GET" class="flex flex-col md:flex-row items-center gap-4 bg-white p-4 rounded-lg shadow-md w-full">
            <input type="hidden" name="page" value="Students">
            <div class="flex items-center w-full md:w-auto gap-2">
                <input type="text" name="search" id="search-input" placeholder="Search..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                       class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon">
                <button type="submit" id="search-btn" class="bg-maroon hover:bg-maroon-hover text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-search" style="color: black"></i>
                </button>
            </div>
            <div class="text-gray-600 text-sm">
                Number of Students: <span class="font-bold"><?php echo $numOfStudent ?></span>
            </div>
        </form>


        <!-- Filter Dropdowns -->
        <form action="<?php echo ROOT ?>adminHome" method="GET" class="filter-container">
            <input type="hidden" name="page" value="Students">
            <select name="program" id="program-filter">
                <option value="">Select Program</option>
                <?php foreach ($programList as $program): ?>
                    <option value="<?php echo htmlspecialchars($program['program']); ?>"
                        <?php echo (isset($_GET['program']) && $_GET['program'] === $program['program']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($program['program']); ?>
                    </option>
                <?php endforeach ?>
            </select>

            <select name="year" id="year-filter">
                <option value="">Select Year</option>
                <?php foreach ($yearList as $year): ?>
                    <option value="<?php echo htmlspecialchars($year['acad_year']); ?>"
                        <?php echo (isset($_GET['year']) && $_GET['year'] === $year['acad_year']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($year['acad_year']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="px-4 py-2 bg-red-800 text-white rounded-lg shadow hover:bg-red-700 transition duration-200 ease-in-out">
                <i class="fas fa-filter"></i> Apply Filter
            </button>
        </form>

        <!-- Add Student Button -->
        <a href="<?php echo ROOT ?>add_student"
           class="block text-white bg-red-800 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
            <i class="fas fa-user-graduate"></i> Add Student
        </a>
    </div>


    <!-- Students Table -->
    <div class="overflow-x-auto mt-6">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-100">
            <tr>

            </tr>
            </thead>
            <tbody>
            <?php if (!empty($studentsList)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    <?php foreach ($studentsList as $student): ?>
                        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col space-y-3 border border-gray-200
                            transition-transform transform hover:scale-105 hover:shadow-lg duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user-graduate text-red-800 text-2xl"></i>
                                <h2 class="text-xl font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($student['name']); ?>
                                </h2>
                            </div>

                            <p class="text-gray-700"><strong>ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                            <p class="text-gray-700"><strong>Program:</strong> <?php echo htmlspecialchars($student['program']); ?></p>
                            <p class="text-gray-700"><strong>Year:</strong> <?php echo htmlspecialchars($student['acad_year']); ?></p>
                            <p class="text-gray-700"><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                            <!-- <p class="text-gray-700"><strong>Contact No.:</strong> <?php echo htmlspecialchars($student['contact_num']); ?></p> -->

                            <div class="flex justify-between mt-4">
                                <a href="<?php echo ROOT?>edit_student?id=<?php echo htmlspecialchars($student['student_id']); ?>"
                                   class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?php echo ROOT?>delete_student?id=<?php echo htmlspecialchars($student['student_id']); ?>"
                                   onclick="return confirmDelete(event, this.href);"
                                   class="text-red-600 hover:text-red-800 flex items-center gap-1">
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

            </tbody>
        </table>
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
</div>
</body>
</html>