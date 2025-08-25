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
        .section-header {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 1rem 1rem 0 0;
            margin: 0 -1.5rem 1.5rem -1.5rem;
        }
        .scrollable-container {
            max-height: 600px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #a31d1d #f1f5f9;
        }
        .scrollable-container::-webkit-scrollbar {
            width: 8px;
        }
        .scrollable-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .scrollable-container::-webkit-scrollbar-thumb {
            background: #a31d1d;
            border-radius: 4px;
        }
        .scrollable-container::-webkit-scrollbar-thumb:hover {
            background: #8a1818;
        }
        .attendance-card {
            min-height: 200px;
            display: flex;
            flex-direction: column;
        }
        .attendance-card .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .attendance-card .card-actions {
            margin-top: auto;
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-file-alt text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Attendance Record</h1>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Search and Actions -->
    <div class="glass-card rounded-2xl p-6 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <form id="searchForm" onsubmit="return false;" class="flex items-center gap-2 w-full md:w-auto">
            <input type="hidden" name="page" value="Attendance">
            <input type="text" id="searchInput" placeholder="Search"
                   class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
            <button id="searchBtn" onclick="searchAttendance()" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
        <div class="flex gap-4">
            <a class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2"
               id="add-attendance" href="<?php echo ROOT ?>add_attendance">
                <i class="fas fa-plus"></i> Add Attendance
            </a>
            <a class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2"
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Records Before June 2025 -->
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 attendance-card">
            <div class="section-header">
                <div class="flex items-center gap-3">
                    <i class="fas fa-history text-2xl"></i>
                    <div>
                        <h2 class="text-2xl font-bold">Historical Records</h2>
                        <p class="text-sm opacity-90">Records created before June 2025</p>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <?php if (empty($beforeJune2025)): ?>
                    <div class="mt-6 text-center text-gray-600 text-lg">
                        <i class="fas fa-archive text-4xl mb-4 text-gray-400"></i>
                        <p>No historical attendance records found.</p>
                        <p class="text-sm text-gray-500 mt-2">Records before June 2025 will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="scrollable-container">
                        <div class="grid grid-cols-1 gap-4">
                            <?php foreach ($beforeJune2025 as $attendance): ?>
                                <div class="glass-card w-full rounded-xl shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 hover-card">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-md font-semibold text-[#a31d1d] truncate"><?php echo htmlspecialchars($attendance['event_name'] ?? 'No Event Name'); ?></h3>
                                        <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-1 rounded-full flex-shrink-0">
                                            <i class="fas fa-clock mr-1"></i>Historical
                                        </span>
                                    </div>
                                    <div class="space-y-2 text-sm">
                                        <p class="text-gray-700"><strong>Date:</strong> <?php echo htmlspecialchars($attendance['date_created'] ?? 'No Date'); ?></p>
                                        <p class="text-gray-700 flex items-center">
                                            <strong>Status:</strong>
                                            <?php
                                            $status = $attendance['atten_status'] ?? 'unknown';
                                            $statusClass = '';
                                            switch ($status) {
                                                case 'on going': $statusClass = 'bg-blue-500 text-white'; break;
                                                case 'stopped': $statusClass = 'bg-yellow-500 text-white'; break;
                                                case 'finished': $statusClass = 'bg-green-500 text-white'; break;
                                                case 'closed': $statusClass = 'bg-red-500 text-white'; break;
                                                default: $statusClass = 'bg-gray-500 text-white';
                                            }
                                            ?>
                                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full <?php echo $statusClass; ?>">
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="card-actions mt-4">
                                        <div class="flex gap-2">
                                            <a href="<?php echo ROOT ?>view_records?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                               class="bg-blue-600 hover:bg-blue-800 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="<?php echo ROOT ?>edit_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <a href="<?php echo ROOT ?>delete_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>"
                                               onclick="return confirmDelete(event, this.href);"
                                               class="bg-red-600 hover:bg-red-800 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
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
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 attendance-card">
            <div class="section-header">
                <div class="flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                    <div>
                        <h2 class="text-2xl font-bold">Current Records</h2>
                        <p class="text-sm opacity-90">Records from June 2025 onwards</p>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <?php if (empty($fromJune2025)): ?>
                    <div class="mt-6 text-center text-gray-600 text-lg">
                        <i class="fas fa-calendar-plus text-4xl mb-4 text-gray-400"></i>
                        <p>No current attendance records found.</p>
                        <p class="text-sm text-gray-500 mt-2">Records from June 2025 onwards will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="scrollable-container">
                        <div class="grid grid-cols-1 gap-4">
                            <?php foreach ($fromJune2025 as $attendance): ?>
                                <div class="glass-card w-full rounded-xl shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 hover-card">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-md font-semibold text-[#a31d1d] truncate"><?php echo htmlspecialchars($attendance['event_name'] ?? 'No Event Name'); ?></h3>
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full flex-shrink-0">
                                            <i class="fas fa-check-circle mr-1"></i>Current
                                        </span>
                                    </div>
                                    <div class="space-y-2 text-sm">
                                        <p class="text-gray-700"><strong>Date:</strong> <?php echo htmlspecialchars($attendance['date_created'] ?? 'No Date'); ?></p>
                                        <p class="text-gray-700 flex items-center">
                                            <strong>Status:</strong>
                                            <?php
                                            $status = $attendance['atten_status'] ?? 'unknown';
                                            $statusClass = '';
                                            switch ($status) {
                                                case 'on going': $statusClass = 'bg-blue-500 text-white'; break;
                                                case 'stopped': $statusClass = 'bg-yellow-500 text-white'; break;
                                                case 'finished': $statusClass = 'bg-green-500 text-white'; break;
                                                case 'closed': $statusClass = 'bg-red-500 text-white'; break;
                                                default: $statusClass = 'bg-gray-500 text-white';
                                            }
                                            ?>
                                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full <?php echo $statusClass; ?>">
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="card-actions mt-4">
                                        <div class="flex gap-2">
                                            <a href="<?php echo ROOT ?>view_records?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                               class="bg-blue-600 hover:bg-blue-800 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="<?php echo ROOT ?>edit_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>&eventName=<?php echo urlencode($attendance['event_name'] ?? ''); ?>"
                                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <a href="<?php echo ROOT ?>delete_attendance?id=<?php echo urlencode($attendance['atten_id'] ?? ''); ?>"
                                               onclick="return confirmDelete(event, this.href);"
                                               class="bg-red-600 hover:bg-red-800 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-1">
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
        window.location.href = `<?php echo ROOT ?>adminHome?page=Attendance&search=${searchValue}`;
    }
</script>
</body>
</html>
