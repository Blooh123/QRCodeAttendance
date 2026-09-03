<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Applications • USep Attendance System</title>
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
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 2px solid #f59e0b;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
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
            background: rgba(163, 29, 29, 0.2);
            animation-delay: -0.5s;
        }
        .search-loading-spinner:after {
            width: 75%;
            height: 75%;
            background: #a31d1d;
            top: 12.5%;
            left: 12.5%;
            animation-delay: -1s;
        }
        .search-loading-text {
            color: #a31d1d;
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
        <div class="search-loading-text" id="searchLoadingText">Processing applications...</div>
    </div>
</div>

<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <i class="fas fa-file-alt text-[#a31d1d] text-3xl"></i>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Student Applications</h1>
        </div>
        <a href="<?= ROOT ?>view_application" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-user-check"></i>Excuse a Student
        </a>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center space-x-4">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-[#a31d1d]"><?= $pendingCount ?></h3>
                    <p class="text-gray-600 font-medium">Pending</p>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-[#a31d1d]"><?= $approvedCount ?></h3>
                    <p class="text-gray-600 font-medium">Approved</p>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center space-x-4">
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-[#a31d1d]"><?= $rejectedCount ?></h3>
                    <p class="text-gray-600 font-medium">Rejected</p>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-[#a31d1d]"><?= $pendingCount + $approvedCount + $rejectedCount ?></h3>
                    <p class="text-gray-600 font-medium">Total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="glass-card rounded-2xl p-6 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <form method="POST" action="<?= ROOT ?>adminHome?page=StudentApplication" class="space-y-4">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="flex items-center w-full md:w-auto gap-2">
                    <input type="text" 
                           name="search_query" 
                           placeholder="Search by student name, event, or description..."
                           value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                           class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                    <input type="hidden" name="action" id="actionValue" value="filter">
                    <button type="submit" onclick="setAction('search')" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="text-gray-600 text-sm">
                    Total Applications: <span class="font-bold"><?= $pendingCount + $approvedCount + $rejectedCount ?></span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-4">
                <label class="text-gray-700 font-medium">Filter by Status:</label>
                <div class="flex flex-wrap gap-2">
                    <input type="hidden" name="filter" id="filterValue" value="<?= $currentFilter ?? '0' ?>">
                    <button type="button" class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 <?= ($currentFilter ?? '0') === '0' ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>" onclick="setFilter('0')">
                        <i class="fas fa-clock me-1"></i>Pending
                    </button>
                    <button type="button" class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 <?= ($currentFilter ?? '0') === '1' ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>" onclick="setFilter('1')">
                        <i class="fas fa-check me-1"></i>Approved
                    </button>
                    <button type="button" class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 <?= ($currentFilter ?? '0') === '2' ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>" onclick="setFilter('2')">
                        <i class="fas fa-times me-1"></i>Rejected
                    </button>
                    <button type="button" class="px-4 py-2 rounded-lg font-semibold transition-all duration-200 <?= ($currentFilter ?? '0') === 'all' ? 'bg-[#a31d1d] text-white shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>" onclick="setFilter('all')">
                        <i class="fas fa-list me-1"></i>All
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Applications List -->
    <?php if (empty($applications)): ?>
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-12 text-center">
            <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No applications found</h3>
            <p class="text-gray-500">There are no applications matching your current filter criteria.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php foreach ($applications as $app): ?>
                <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-[#a31d1d]/10 p-3 rounded-full">
                                <i class="fas fa-user-graduate text-[#a31d1d] text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-[#a31d1d]">
                                    <?= htmlspecialchars($app['name']) ?>
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    <?= htmlspecialchars($app['program']) ?> - <?= htmlspecialchars($app['acad_year']) ?>
                                </p>
                            </div>
                        </div>
                        <?php
                        $statusClass = '';
                        $statusText = '';
                        $statusIcon = '';
                        
                        switch ($app['application_status']) {
                            case 0:
                                $statusClass = 'status-pending';
                                $statusText = 'Pending';
                                $statusIcon = 'fas fa-clock';
                                break;
                            case 1:
                                $statusClass = 'status-approved';
                                $statusText = 'Approved';
                                $statusIcon = 'fas fa-check';
                                break;
                            case 2:
                                $statusClass = 'status-rejected';
                                $statusText = 'Rejected';
                                $statusIcon = 'fas fa-times';
                                break;
                        }
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                            <i class="<?= $statusIcon ?> me-1"></i>
                            <?= $statusText ?>
                        </span>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1">Event</h4>
                            <p class="text-gray-700"><?= htmlspecialchars($app['event_name']) ?></p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1">Event Date</h4>
                            <p class="text-gray-700">
                                <i class="fas fa-calendar me-1"></i>
                                <?= date('M d, Y', strtotime($app['event_date'])) ?>
                            </p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1">Submitted</h4>
                            <p class="text-gray-700">
                                <i class="fas fa-calendar-plus me-1"></i>
                                <?= date('M d, Y', strtotime($app['date_submitted'])) ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="<?= ROOT ?>view_application?id=<?= $app['id'] ?>" 
                           class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-eye"></i>View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function setFilter(value) {
        document.getElementById('filterValue').value = value;
        document.getElementById('actionValue').value = 'filter';
        // Submit the form
        document.querySelector('form').submit();
    }
    
    function setAction(action) {
        document.getElementById('actionValue').value = action;
    }

    // Add loading screen functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchLoadingOverlay = document.getElementById('searchLoadingOverlay');
        const searchLoadingText = document.getElementById('searchLoadingText');
        let loadingInterval = null;
        let loadingMessages = [
            "Processing applications...",
            "Fetching application data...",
            "Filtering results...",
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

        // Show loading for form submissions
        document.querySelector('form').addEventListener('submit', function() {
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