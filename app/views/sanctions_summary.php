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
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Loading overlay styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 50;
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
            background: rgba(163, 29, 29, 0.2);
            animation-delay: -0.5s;
        }
        
        .loading-spinner:after {
            width: 75%;
            height: 75%;
            background: #a31d1d;
            top: 12.5%;
            left: 12.5%;
            animation-delay: -1s;
        }
        
        .loading-text {
            color: #a31d1d;
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
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-container">
        <div class="loading-spinner"></div>
        <div class="loading-text">Loading...</div>
    </div>
</div>

<!-- Header -->
<header class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 mb-8 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <i class="fas fa-exclamation-triangle text-[#a31d1d] text-3xl"></i>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#a31d1d] tracking-tight">Sanctions Summary</h1>
                <p class="text-gray-600 font-medium">Student Sanction Records</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo ROOT ?>adminHome?page=Attendance" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Attendance
            </a>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Search Section -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6">
        <h2 class="text-xl font-bold text-[#a31d1d] mb-4 flex items-center gap-2">
            <i class="fas fa-search"></i> Search Sanctions
        </h2>
        <div class="relative">
            <input type="text" 
                   id="searchInput" 
                   placeholder="Search by student ID, name, or program..." 
                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d] outline-none transition-all duration-200 bg-white shadow-sm"
            >
            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Students</p>
                    <p class="text-3xl font-bold text-blue-600"><?= count($data['sanctionSummary']) ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div> -->
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Student With Sanctions</p>
                    <p class="text-3xl font-bold text-red-600">
                        <?= count(array_filter($data['sanctionSummary'], function($item) { return $item['hours'] > 0; })) ?>
                    </p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Clean Record</p>
                    <p class="text-3xl font-bold text-green-600">
                        <?= count(array_filter($data['sanctionSummary'], function($item) { return $item['hours'] == 0; })) ?>
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Sanctions Table -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#a31d1d] flex items-center gap-2">
                <i class="fas fa-table"></i> Sanctions Records
                <span class="text-sm font-normal text-gray-600">
                    (<?= count($data['sanctionSummary']) ?> students)
                </span>
            </h2>
        </div>
        
        <div class="overflow-x-auto hide-scrollbar">
            <table class="w-full">
                <thead class="bg-[#a31d1d] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Student ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Program</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Year</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Sanction Hours</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="sanctionsTableBody">
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
                    
                    if (empty($displayData)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-4">
                                    <i class="fas fa-exclamation-triangle text-4xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-600 mb-2">No Sanctions Found</h3>
                                <p class="text-gray-500">No sanction records match your search criteria.</p>
                            </td>
                        </tr>
                    <?php else:
                        foreach ($displayData as $sanction): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($sanction['student_id']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-medium"><?php echo htmlspecialchars($sanction['name']); ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($sanction['program']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($sanction['acad_year']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php if ($sanction['hours'] > 0): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            <?php echo htmlspecialchars($sanction['hours']); ?> hours
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Clean Record
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <a href="<?php echo ROOT ?>student_attendance_summary?student_id=<?php echo htmlspecialchars($sanction['student_id']); ?>" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-[#a31d1d] hover:bg-[#8a1818] shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        View Record
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;
                    endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?php echo $currentPage - 1; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        Previous
                    </a>
                <?php endif; ?>
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?php echo $currentPage + 1; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                       class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
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
                    <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px" aria-label="Pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                               class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-200">
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
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium transition-all duration-200 <?php echo $i === $currentPage ? 'text-[#a31d1d] bg-red-50 border-[#a31d1d]' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" 
                               class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-200">
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