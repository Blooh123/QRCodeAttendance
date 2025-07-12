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
            <button id="searchBtn" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg flex items-center gap-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200" type="submit">
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

    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 mb-8">
        <div class="text-2xl font-bold text-[#a31d1d] mb-4">Attendance Records</div>



        <?php if (empty($attendanceList)): ?>
            <div class="mt-6 text-center text-gray-600 text-lg">
                <i class="fas fa-inbox text-4xl mb-4 text-gray-400"></i>
                <p>No attendance records found.</p>
                <p class="text-sm text-gray-500 mt-2">Try adding some attendance records first.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($attendanceList as $attendance): ?>
                    <div class="glass-card w-full rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 flex flex-col space-y-3 hover-card">
                        <h2 class="text-lg font-semibold text-[#a31d1d]"><?php echo htmlspecialchars($attendance['event_name'] ?? 'No Event Name'); ?></h2>
                        <p class="text-gray-700"><strong>Date Created:</strong> <?php echo htmlspecialchars($attendance['date_created'] ?? 'No Date'); ?></p>
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
                            <span class="ml-2 px-3 py-1 text-sm font-medium rounded-full <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </p>
                        <div class="flex mt-4 gap-4">
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
        <?php endif; ?>
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
</script>
</body>
</html>
