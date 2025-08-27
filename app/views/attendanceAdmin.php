<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Record</title>
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
            border: 2px solid rgba(163, 29, 29, 0.1);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            position: relative;
        }
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 1rem;
            padding: 2px;
            background: linear-gradient(135deg, rgba(163, 29, 29, 0.2), rgba(138, 24, 24, 0.2));
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            pointer-events: none;
        }
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .hover-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }
        .hover-card:hover::before {
            left: 100%;
        }
        .hover-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(163, 29, 29, 0.3);
            border-color: rgba(163, 29, 29, 0.4);
        }
        .section-header {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 1.5rem 1.5rem 0 0;
            margin: 0 -2rem 2rem -2rem;
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
            border: 2px solid rgba(163, 29, 29, 0.15);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .historical-card {
            border-left: 4px solid #f59e0b;
            border-top: 4px solid #f59e0b;
        }
        .current-card {
            border-left: 4px solid #10b981;
            border-top: 4px solid #10b981;
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
        .search-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-radius: 2rem;
        }
        .search-input {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .search-input:focus {
            border-color: #a31d1d;
            box-shadow: 0 0 0 3px rgba(163, 29, 29, 0.1);
            background: white;
        }
        .search-btn {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            color: white;
            border-radius: 1rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(163, 29, 29, 0.4);
        }
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(163, 29, 29, 0.6);
        }
        .action-btn {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            color: white;
            border-radius: 1rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(163, 29, 29, 0.4);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(163, 29, 29, 0.6);
            color: white;
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
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="p-4 md:p-6">

<!-- Header -->
<header class="glass-card rounded-3xl p-8 mb-8 max-w-7xl mx-auto text-center">
    <div class="flex items-center justify-center space-x-4 mb-4">
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-4 rounded-full">
            <i class="fas fa-file-alt text-white text-4xl"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-red-600 to-red-700 bg-clip-text text-transparent">
            Attendance Record
        </h1>
    </div>
    <p class="text-gray-600 text-lg">Manage and monitor attendance records efficiently</p>
</header>

<div class="w-full px-4 md:px-6 lg:px-8 mx-auto">
    <!-- Search and Actions -->
    <div class="search-container p-6 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <form id="searchForm" onsubmit="return false;" class="flex items-center gap-3 w-full md:w-auto">
            <input type="hidden" name="page" value="Attendance">
            <div class="relative flex-1 md:flex-none">
                <input type="text" id="searchInput" placeholder="Search attendance records..."
                       class="search-input w-full md:w-96 px-6 py-3 rounded-2xl focus:outline-none">
                <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <button id="searchBtn" onclick="searchAttendance()" class="search-btn flex items-center gap-2" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
        <div class="flex gap-4 flex-wrap">
            <a class="action-btn" id="add-attendance" href="<?php echo ROOT ?>add_attendance">
                <i class="fas fa-plus"></i> Add Attendance
            </a>
            <a class="action-btn bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700" 
               href="<?php echo ROOT ?>sanctions_summary">
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
                                <div class="glass-card rounded-2xl p-6 hover-card historical-card" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-bold text-[#a31d1d] truncate flex-1 mr-4">
                                            <?php echo htmlspecialchars($attendance['event_name'] ?? 'No Event Name'); ?>
                                        </h3>
                                        <span class="status-badge bg-gradient-to-r from-orange-400 to-orange-500 text-white">
                                            <i class="fas fa-clock mr-1"></i>Historical
                                        </span>
                                    </div>
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-calendar-alt w-5 text-[#a31d1d] mr-3"></i>
                                            <span><strong>Date:</strong> <?php echo htmlspecialchars($attendance['date_created'] ?? 'No Date'); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-info-circle w-5 text-[#a31d1d] mr-3"></i>
                                            <span class="mr-3"><strong>Status:</strong></span>
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
                                        </div>
                                    </div>
                                    <div class="card-actions">
                                        <div class="flex gap-3 flex-wrap">
                                            <a href="<?php echo ROOT ?>view_records?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                               class="btn-action btn-view">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="<?php echo ROOT ?>edit_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                               class="btn-action btn-edit">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <a href="<?php echo ROOT ?>delete_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>"
                                               onclick="return confirmDelete(event, this.href);"
                                               class="btn-action btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
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
                                <div class="glass-card rounded-2xl p-6 hover-card current-card" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-bold text-[#a31d1d] truncate flex-1 mr-4">
                                            <?php echo htmlspecialchars($attendance['event_name'] ?? 'No Event Name'); ?>
                                        </h3>
                                        <span class="status-badge bg-gradient-to-r from-green-400 to-green-500 text-white">
                                            <i class="fas fa-check-circle mr-1"></i>Current
                                        </span>
                                    </div>
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-calendar-alt w-5 text-[#a31d1d] mr-3"></i>
                                            <span><strong>Date:</strong> <?php echo htmlspecialchars($attendance['date_created'] ?? 'No Date'); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-info-circle w-5 text-[#a31d1d] mr-3"></i>
                                            <span class="mr-3"><strong>Status:</strong></span>
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
                                        </div>
                                    </div>
                                    <div class="card-actions">
                                        <div class="flex gap-3 flex-wrap">
                                            <a href="<?php echo ROOT ?>view_records?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                               class="btn-action btn-view">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="<?php echo ROOT ?>edit_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                               class="btn-action btn-edit">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <a href="<?php echo ROOT ?>delete_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>"
                                               onclick="return confirmDelete(event, this.href);"
                                               class="btn-action btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
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
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            background: 'rgba(255, 255, 255, 0.95)',
            backdrop: 'rgba(0, 0, 0, 0.4)',
            customClass: {
                popup: 'rounded-2xl'
            }
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

    // Add loading animation
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.hover-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
</body>
</html>
