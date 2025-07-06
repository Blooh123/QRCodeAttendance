<?php
global $imageSource, $imageSource2, $imageSource4;
    require "../app/core/imageConfig.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanctions Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Loading overlay styles */
        .loading-overlay {
            position: fixed;
            top: 160px; /* Adjusted to start below the header and search bar */
            left: 0;
            width: 100%;
            height: calc(100% - 120px); /* Adjusted height to account for the top offset */
            background: rgba(255, 255, 255, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 40; /* Lower z-index to stay below search bar */
            backdrop-filter: blur(5px);
        }
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .loading-spinner {
            position: relative;
            width: 60px;
            height: 60px;
        }
        .loading-spinner:before,
        .loading-spinner:after {
            content: '';
            position: absolute;
            border-radius: 50%;
            animation: pulse 1.5s linear infinite;
        }
        .loading-spinner:before {
            width: 100%;
            height: 100%;
            background: rgba(220, 38, 38, 0.2);
            animation-delay: -0.5s;
        }
        .loading-spinner:after {
            width: 75%;
            height: 75%;
            background: #dc2626;
            top: 12.5%;
            left: 12.5%;
            animation-delay: -1s;
        }
        .loading-text {
            color: #dc2626;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            animation: fadeInOut 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1); opacity: 1; }
            100% { transform: scale(0.8); opacity: 0.5; }
        }
        @keyframes fadeInOut {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading...</div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-sm p-6 mb-6">
        <div class="max-w-[95%] mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                <h1 class="text-3xl font-bold text-gray-900">Sanctions Summary</h1>
            </div>
            <a href="<?php echo ROOT ?>adminHome?page=Attendance" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Attendance
            </a>
        </div>
    </header>

    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Search by student ID, name, or program..." 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all duration-200"
                >
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <!-- Sanctions Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                            <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                            <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sanction Hours</th>
                            <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="sanctionsTableBody">
                        <?php 
                        $itemsPerPage = 10;
                        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $searchTerm = isset($_GET['search']) ? strtolower($_GET['search']) : '';
                        
                        // Filter data based on search term
                        $filteredData = $data['sanctionSummary'];
                        if ($searchTerm) {
                            $filteredData = array_filter($data['sanctionSummary'], function($item) use ($searchTerm) {
                                return strpos(strtolower($item['student_id']), $searchTerm) !== false ||
                                       strpos(strtolower($item['name']), $searchTerm) !== false ||
                                       strpos(strtolower($item['program']), $searchTerm) !== false;
                            });
                        }
                        
                        $totalItems = count($filteredData);
                        $totalPages = ceil($totalItems / $itemsPerPage);
                        $startIndex = ($currentPage - 1) * $itemsPerPage;
                        $endIndex = min($startIndex + $itemsPerPage, $totalItems);
                        
                        $displayData = array_slice($filteredData, $startIndex, $itemsPerPage);
                        
                        foreach ($displayData as $sanction): 
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-8 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($sanction['student_id']); ?>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($sanction['name']); ?>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($sanction['program']); ?>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($sanction['acad_year']); ?>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo $sanction['hours'] > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                        <?php echo htmlspecialchars($sanction['hours']); ?> hours
                                    </span>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="<?php echo ROOT ?>student_attendance_summary?student_id=<?php echo htmlspecialchars($sanction['student_id']); ?>" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        View Attendance Record
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    <?php endif; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                           class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium"><?php echo $startIndex + 1; ?></span> to 
                            <span class="font-medium"><?php echo $endIndex; ?></span> of 
                            <span class="font-medium"><?php echo $totalItems; ?></span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?php echo $currentPage - 1; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            <?php endif; ?>

                            <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);

                            for ($i = $startPage; $i <= $endPage; $i++):
                            ?>
                                <a href="?page=<?php echo $i; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $currentPage ? 'text-red-600 bg-red-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?php echo $currentPage + 1; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('sanctionsTableBody');
            const rows = tableBody.getElementsByTagName('tr');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const paginationLinks = document.querySelectorAll('nav a');

            // Get current search term from URL if it exists
            const urlParams = new URLSearchParams(window.location.search);
            const currentSearch = urlParams.get('search') || '';
            searchInput.value = currentSearch;

            // Function to update URL with search term
            function updateURL(searchTerm) {
                const url = new URL(window.location.href);
                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }
                url.searchParams.set('page', '1'); // Reset to first page on new search
                window.location.href = url.toString();
            }

            // Add search parameter to all pagination links
            paginationLinks.forEach(link => {
                const href = new URL(link.href);
                if (currentSearch) {
                    href.searchParams.set('search', currentSearch);
                    link.href = href.toString();
                }
            });

            // Handle search input
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.toLowerCase();
                loadingOverlay.style.display = 'flex';

                searchTimeout = setTimeout(() => {
                    updateURL(searchTerm);
                }, 500);
            });

            // Initial search if there's a search term
            if (currentSearch) {
                Array.from(rows).forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(currentSearch.toLowerCase()) ? '' : 'none';
                });
            }
        });
    </script>
</body>
</html>