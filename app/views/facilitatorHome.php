<?php
global $imageSource, $imageSource2, $imageSource3, $programList, $selectedProgram, $EventName, $EventDate, $EventTime, $EventLocation, $attendanceRecordList, $EventID;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
    <title>Facilitator Home • USep Attendance System</title>
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
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-4 md:p-6 mb-8 glass-card">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <img src="<?php echo $imageSource; ?>" alt="OSAS Logo" class="w-12 h-12 md:w-16 md:h-16 rounded-lg">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Facilitator Dashboard</h1>
            </div>
            <button onclick="logout('<?php echo ROOT; ?>')" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 md:px-6 py-2 md:py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2 w-full md:w-auto">
                <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Logout</span>
            </button>
        </div>
    </header>
    <script>
        function logout(root) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of the system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#a31d1d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = root + "logout";
                }
            });
        }
    </script>

    <!-- Activity Log Toggle -->
    <script>
        const fullActivityLog = <?php echo json_encode($activityLogList); ?>;
    </script>



    <!-- Attendance Dropdown -->
    <div class="glass-card rounded-2xl p-6 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black relative">
        <button id="attendanceDropdownButton"
                class="w-full sm:w-auto bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-between gap-2">
            <span>View Recent Events</span>
            <i class="fas fa-chevron-down" id="dropdownIcon"></i>
        </button>
        <div id="attendanceDropdownMenu"
             class="hidden absolute z-50 w-full sm:w-80 mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
            <?php if (empty($attendanceList2)) { ?>
                <div class="p-4 text-center text-gray-500">
                    No attendance records found.
                </div>
            <?php } else { ?>
                <?php foreach ($attendanceList2 as $attendance) { ?>
                    <a href="<?php echo ROOT ?>view_records?id=<?php echo htmlspecialchars($attendance['atten_id']) ?>&eventName=<?php echo htmlspecialchars($attendance['event_name']); ?>"
                       class="flex items-center justify-between p-3 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                        <div>
                            <p class="text-sm font-semibold text-[#a31d1d]"><?php echo htmlspecialchars($attendance['event_name']); ?></p>
                            <p class="text-xs text-gray-600"><?php echo htmlspecialchars($attendance['date_created']); ?></p>
                        </div>
                        <i class="fas fa-eye text-[#a31d1d] hover:text-[#8a1818]" title="View Details"></i>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>

    <script>
        const dropdownButton = document.getElementById('attendanceDropdownButton');
        const dropdownMenu = document.getElementById('attendanceDropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');

        dropdownButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
            dropdownIcon.classList.toggle('fa-chevron-down');
            dropdownIcon.classList.toggle('fa-chevron-up');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
                dropdownIcon.classList.add('fa-chevron-down');
                dropdownIcon.classList.remove('fa-chevron-up');
            }
        });

        // Close dropdown when an item is clicked
        dropdownMenu.querySelectorAll('a').forEach(item => {
            item.addEventListener('click', () => {
                dropdownMenu.classList.add('hidden');
                dropdownIcon.classList.add('fa-chevron-down');
                dropdownIcon.classList.remove('fa-chevron-up');
            });
        });
    </script>
    <!-- Event Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <a href="<?php echo ROOT ?>scanner" class="block text-center">
                <img src="<?php echo $imageSource3; ?>" alt="Scan QR Code" class="mx-auto w-48 h-48 object-cover rounded-xl mb-4 shadow-lg">
                <h3 class="text-2xl font-bold text-[#a31d1d] mb-2">Scan QR Code</h3>
                <p class="text-gray-600">Start scanning attendance for the current event</p>
            </a>
        </div>
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <h2 class="text-2xl font-bold text-[#a31d1d] mb-4 text-center">Current Event</h2>
            <div class="space-y-3">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                    <div class="text-blue-600 text-lg mb-1">📅 Event</div>
                    <div class="text-blue-700 font-semibold"><?php echo htmlspecialchars($EventName)?></div>
                </div>
                <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                    <div class="text-green-600 text-lg mb-1">📆 Date</div>
                    <div class="text-green-700 font-semibold"><?php echo htmlspecialchars($EventDate)?></div>
                </div>
                <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                    <div class="text-purple-600 text-lg mb-1">⏰ Time</div>
                    <div class="text-purple-700 font-semibold"><?php echo htmlspecialchars($EventTime)?></div>
                </div>
                <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
                    <div class="text-orange-600 text-lg mb-1">📍 Location</div>
                    <div class="text-orange-700 font-semibold"><?php echo htmlspecialchars($EventLocation)?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log Section -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 mb-8">
        <button onclick="toggleLogs()" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 mb-4">
            <i class="fas fa-clock"></i> View Activity Log
        </button>
        <div id="activity-log" class="mt-4 hidden">
            <h3 class="text-2xl font-bold text-[#a31d1d] mb-4">Activity Log</h3>
            <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                <input type="text" id="search-input" placeholder="Search activity logs..."
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]">
                <button type="button" id="search-btn"
                        class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            <div class="h-80 overflow-y-auto border border-gray-200 rounded-xl p-4 bg-gray-50 hide-scrollbar">
                <ul class="space-y-3" id="activity-log-list">
                    <!-- Logs will be rendered here by JS -->
                </ul>
            </div>
        </div>
    </div>

    <script>
        function renderLogs(logs) {
            const list = document.getElementById("activity-log-list");
            list.innerHTML = '';
            if (logs.length === 0) {
                list.innerHTML = '<li class="text-gray-500 text-center py-8">No activity logs found.</li>';
            } else {
                logs.forEach(log => {
                    const item = document.createElement("li");
                    item.className = "glass-card rounded-xl p-4 text-gray-700 hover-card";
                    item.innerHTML = `
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <span class="font-semibold text-[#a31d1d]">${log.activity}</span>
                                <div class="text-sm text-gray-500 mt-1">${log.time_created}</div>
                            </div>
                            <i class="fas fa-clock text-[#a31d1d] text-lg ml-3"></i>
                        </div>
                    `;
                    list.appendChild(item);
                });
            }
        }

        document.getElementById("search-btn").addEventListener("click", () => {
            const keyword = document.getElementById("search-input").value.toLowerCase();
            const filtered = fullActivityLog.filter(log =>
                log.activity.toLowerCase().includes(keyword) ||
                log.time_created.toLowerCase().includes(keyword)
            );
            renderLogs(filtered);
        });

        document.getElementById("search-input").addEventListener("keypress", function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById("search-btn").click();
            }
        });

        document.addEventListener("DOMContentLoaded", () => {
            renderLogs(fullActivityLog);
        });

        function toggleLogs() {
            const logSection = document.getElementById("activity-log");
            logSection.classList.toggle("hidden");
        }
    </script>
</div>
</body>
</html>