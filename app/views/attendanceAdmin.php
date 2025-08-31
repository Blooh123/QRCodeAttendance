<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Record • USep Attendance System</title>
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
        .section-header {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 1.5rem 1.5rem 0 0;
            margin: 0 -2rem 0.5rem -2rem;
            position: relative;
            overflow: hidden;
        }
        .section-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }
        .scrollable-container {
            max-height: 700px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #a31d1d #f1f5f9;
            padding-right: 8px;
        }
        .scrollable-container::-webkit-scrollbar {
            width: 8px;
        }
        .scrollable-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        .scrollable-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            border-radius: 10px;
        }
        .scrollable-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #8a1818 0%, #6b1414 100%);
        }
        .attendance-card {
            min-height: 250px;
            display: flex;
            flex-direction: column;
            border-radius: 1.5rem;
            overflow: hidden;
        }
        .attendance-card .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .attendance-card .card-actions {
            margin-top: auto;
            padding-top: 1rem;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-action {
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        .btn-action:hover::before {
            left: 100%;
        }
        .btn-view {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.6);
        }
        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.6);
        }
        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.6);
        }
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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

<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-file-alt text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Attendance Record</h1>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Search and Actions -->
    <div class="glass-card rounded-2xl p-6 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <form id="searchForm" onsubmit="return false;" class="flex flex-col md:flex-row items-center gap-4">
            <input type="hidden" name="page" value="Attendance">
            <div class="flex items-center w-full md:w-auto gap-2">
                <input type="text" id="searchInput" placeholder="Search attendance records..."
                       class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                <button id="searchBtn" onclick="searchAttendance()" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="flex gap-4 flex-wrap mt-4">
            <a href="<?php echo ROOT ?>add_attendance" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                <i class="fas fa-plus"></i> Add Attendance
            </a>
            <a href="<?php echo ROOT ?>sanctions_summary" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                <i class="fas fa-exclamation-triangle"></i> View Sanctions
            </a>
        </div>
    </div>

    <?php
    // Separate attendance records by date
    $beforeJune2025 = [];
    $fromJune2025 = [];
    $june2025Date = '2025-06-01';
    
    if (!empty($attendanceList)) {
        foreach ($attendanceList as $attendance) {
            $dateCreated = $attendance['date_created'] ?? '';
            if ($dateCreated && $dateCreated < $june2025Date) {
                $beforeJune2025[] = $attendance;
            } else {
                $fromJune2025[] = $attendance;
            }
        }
    }
    ?>

    <!-- Grid Layout for Both Sections -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        <!-- Records Before June 2025 -->
        <div class="attendance-card glass-card p-6 min-w-0 fade-in">
            <div class="section-header">
                <div class="flex items-center gap-4 relative z-10">
                    <div class="bg-white/20 p-3 rounded-full">
                        <i class="fas fa-history text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Historical Records</h2>
                        <p class="text-sm opacity-90">Records created before June 2025</p>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <?php if (empty($beforeJune2025)): ?>
                    <div class="empty-state">
                        <i class="fas fa-archive"></i>
                        <h3 class="text-xl font-semibold mb-2">No Historical Records</h3>
                        <p class="text-gray-500">Records created before June 2025 will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="scrollable-container">
                        <div class="grid grid-cols-1 gap-6">
                            <?php foreach ($beforeJune2025 as $index => $attendance): ?>
                                <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 flex flex-col space-y-2 hover-card" style="max-width: 95%; margin: 10px;">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <i class="fas fa-file-alt text-[#a31d1d] text-2xl"></i>
                                        <h3 class="text-xl font-semibold text-[#a31d1d] truncate flex-1">
                                            <?php echo htmlspecialchars($attendance['event_name'] ?? 'No Event Name'); ?>
                                        </h3>
                                        <span class="status-badge bg-gradient-to-r from-orange-400 to-orange-500 text-white">
                                            <i class="fas fa-clock mr-1"></i>Historical
                                        </span>
                                    </div>
                                    <p class="text-gray-700"><strong>Date:</strong> <?php echo htmlspecialchars($attendance['date_created'] ?? 'No Date'); ?></p>
                                    <p class="text-gray-700"><strong>Status:</strong> 
                                        <?php
                                        $status = $attendance['atten_status'] ?? 'unknown';
                                        $statusClass = '';
                                        $statusIcon = '';
                                        switch ($status) {
                                            case 'on going': 
                                                $statusClass = 'bg-gradient-to-r from-blue-400 to-blue-500 text-white';
                                                $statusIcon = 'fas fa-play-circle';
                                                break;
                                            case 'stopped': 
                                                $statusClass = 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white';
                                                $statusIcon = 'fas fa-pause-circle';
                                                break;
                                            case 'finished': 
                                                $statusClass = 'bg-gradient-to-r from-green-400 to-green-500 text-white';
                                                $statusIcon = 'fas fa-check-circle';
                                                break;
                                            case 'closed': 
                                                $statusClass = 'bg-gradient-to-r from-red-400 to-red-500 text-white';
                                                $statusIcon = 'fas fa-times-circle';
                                                break;
                                            default: 
                                                $statusClass = 'bg-gradient-to-r from-gray-400 to-gray-500 text-white';
                                                $statusIcon = 'fas fa-question-circle';
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <i class="<?php echo $statusIcon; ?> mr-1"></i><?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </p>
                                    <div class="flex justify-between mt-4">
                                        <a href="<?php echo ROOT ?>view_records?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                           class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="<?php echo ROOT ?>edit_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <a href="<?php echo ROOT ?>delete_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>"
                                           onclick="return confirmDelete(event, this.href);"
                                           class="bg-red-600 hover:bg-red-800 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Records From June 2025 Onwards -->
        <div class="attendance-card glass-card p-6 min-w-0 fade-in" style="animation-delay: 0.2s;">
            <div class="section-header">
                <div class="flex items-center gap-4 relative z-10">
                    <div class="bg-white/20 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Current Records</h2>
                        <p class="text-sm opacity-90">Records from June 2025 onwards</p>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <?php if (empty($fromJune2025)): ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-plus"></i>
                        <h3 class="text-xl font-semibold mb-2">No Current Records</h3>
                        <p class="text-gray-500">Records from June 2025 onwards will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="scrollable-container">
                        <div class="grid grid-cols-1 gap-6">
                            <?php foreach ($fromJune2025 as $index => $attendance): ?>
                                <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 flex flex-col space-y-2 hover-card" style="max-width: 95%; margin: 10px;">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <i class="fas fa-file-alt text-[#a31d1d] text-2xl"></i>
                                        <h3 class="text-xl font-semibold text-[#a31d1d] truncate flex-1">
                                            <?php echo htmlspecialchars($attendance['event_name'] ?? 'No Event Name'); ?>
                                        </h3>
                                        <span class="status-badge bg-gradient-to-r from-green-400 to-green-500 text-white">
                                            <i class="fas fa-check-circle mr-1"></i>Current
                                        </span>
                                    </div>
                                    <p class="text-gray-700"><strong>Date:</strong> <?php echo htmlspecialchars($attendance['date_created'] ?? 'No Date'); ?></p>
                                    <p class="text-gray-700"><strong>Status:</strong> 
                                        <?php
                                        $status = $attendance['atten_status'] ?? 'unknown';
                                        $statusClass = '';
                                        $statusIcon = '';
                                        switch ($status) {
                                            case 'on going': 
                                                $statusClass = 'bg-gradient-to-r from-blue-400 to-blue-500 text-white';
                                                $statusIcon = 'fas fa-play-circle';
                                                break;
                                            case 'stopped': 
                                                $statusClass = 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white';
                                                $statusIcon = 'fas fa-pause-circle';
                                                break;
                                            case 'finished': 
                                                $statusClass = 'bg-gradient-to-r from-green-400 to-green-500 text-white';
                                                $statusIcon = 'fas fa-check-circle';
                                                break;
                                            case 'closed': 
                                                $statusClass = 'bg-gradient-to-r from-red-400 to-red-500 text-white';
                                                $statusIcon = 'fas fa-times-circle';
                                                break;
                                            default: 
                                                $statusClass = 'bg-gradient-to-r from-gray-400 to-gray-500 text-white';
                                                $statusIcon = 'fas fa-question-circle';
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <i class="<?php echo $statusIcon; ?> mr-1"></i><?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </p>
                                    <div class="flex justify-between mt-4">
                                        <a href="<?php echo ROOT ?>view_records?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                           class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <?php if (isset($userRole) && $userRole === 'Facilitator' && in_array('edit attendance', $facilitatorPermissions)): ?>
                                            <a href="<?php echo ROOT ?>edit_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                        <?php endif; ?>
                                        <!-- check if admin -->
                                        <?php if (isset($userRole) && $userRole === 'admin'): ?>
                                            <a href="<?php echo ROOT ?>edit_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                        <?php endif; ?>
                                        <?php if (isset($userRole) && $userRole === 'Facilitator' && in_array('delete attendance', $facilitatorPermissions)): ?>
                                            <a href="<?php echo ROOT ?>delete_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>"
                                            onclick="return confirmDelete(event, this.href);"
                                            class="bg-red-600 hover:bg-red-800 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        <?php endif; ?>
                                        <!-- check if admin -->
                                        <?php if (isset($userRole) && $userRole === 'admin'): ?>
                                            <a href="<?php echo ROOT ?>delete_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>"
                                            onclick="return confirmDelete(event, this.href);"
                                            class="bg-red-600 hover:bg-red-800 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Confirm delete function
    function confirmDelete(event, url) {
        event.preventDefault();
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
                window.location.href = url;
            }
        });
        return false;
    }

    function searchAttendance() {
        const searchInput = document.getElementById('searchInput');
        const searchValue = searchInput.value.trim();
        if (searchValue) {
            window.location.href = `<?php echo ROOT ?>adminHome?page=Attendance&search=${searchValue}`;
        }
    }

    // Add enter key support for search
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchAttendance();
        }
    });

    // Add loading screen functionality for search
    document.addEventListener('DOMContentLoaded', function() {
        const searchLoadingOverlay = document.getElementById('searchLoadingOverlay');
        const searchLoadingText = document.getElementById('searchLoadingText');
        let loadingInterval = null;
        let loadingMessages = [
            "Hang on tight...",
            "Crunching the numbers...",
            "Fetching attendance data...",
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
        document.getElementById('searchForm').addEventListener('submit', function() {
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
