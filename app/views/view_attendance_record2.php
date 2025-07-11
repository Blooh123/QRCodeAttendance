<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Sanctioned Students</title>
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
    <script src="https://cdn.tailwindcss.com"></script>
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
        
        @media print {
            .no-print, .no-print * { 
                display: none !important; 
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                font-size: 12px; 
            }
            th, td { 
                font-size: 12px; 
                border: 1px solid black !important; 
                padding: 8px; 
                text-align: left; 
            }
            th { 
                background-color: #f0f0f0 !important; 
                color: black !important; 
                font-weight: bold; 
            }
            body { 
                background: none; 
                padding: 20px; 
            }
            .text-center { 
                text-align: center; 
            }
            .text-4xl { 
                font-size: 20px !important; 
                font-weight: bold; 
            }
            .text-2xl { 
                font-size: 16px !important; 
                font-weight: bold; 
            }
            .text-gray-600 { 
                color: black !important; 
            }
            tr:nth-child(20n) { 
                page-break-after: always; 
            }
        }
        
        a[href]:after { 
            content: none !important; 
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 mb-8 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <i class="fas fa-exclamation-triangle text-[#a31d1d] text-3xl"></i>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#a31d1d] tracking-tight">Sanctioned Students</h1>
                <p class="text-gray-600 font-medium"><?php echo htmlspecialchars($_GET['eventName'] ?? 'Event Details'); ?></p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <button onclick="history.back()" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 no-print">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <button onclick="window.print()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 no-print">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 no-print">
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Sanctioned</p>
                    <p class="text-3xl font-bold text-red-600"><?= count($sanctioned ?? []) ?></p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Hours</p>
                    <p class="text-3xl font-bold text-orange-600">
                        <?= array_sum(array_column($sanctioned ?? [], 'sanction_hours')) ?>
                    </p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Average Hours</p>
                    <p class="text-3xl font-bold text-yellow-600">
                        <?= count($sanctioned ?? []) > 0 ? round(array_sum(array_column($sanctioned, 'sanction_hours')) / count($sanctioned), 1) : 0 ?>
                    </p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-calculator text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 no-print">
        <h2 class="text-xl font-bold text-[#a31d1d] mb-4 flex items-center gap-2">
            <i class="fas fa-filter"></i> Filter Records
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       id="searchInput" 
                       placeholder="Search by name or student ID..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d] outline-none transition-all duration-200 bg-white shadow-sm"
                       onkeyup="filterTable()">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                <select id="programFilter" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d] outline-none transition-all duration-200 bg-white shadow-sm"
                        onchange="filterTable()">
                    <option value="">All Programs</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                <select id="yearFilter" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d] outline-none transition-all duration-200 bg-white shadow-sm"
                        onchange="filterTable()">
                    <option value="">All Academic Years</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Sanctioned Students Table -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#a31d1d] flex items-center gap-2">
                <i class="fas fa-table"></i> Sanctioned Students
                <span class="text-sm font-normal text-gray-600">(<?= count($sanctioned ?? []) ?> students)</span>
            </h2>
        </div>
        
        <div class="overflow-x-auto hide-scrollbar">
            <table class="w-full">
                <thead class="bg-[#a31d1d] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Student ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Academic Year</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Program</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Sanction Hours</th>
                    </tr>
                </thead>
                <tbody id="sanctionedTable" class="divide-y divide-gray-200">
                    <?php if (!empty($sanctioned)): ?>
                        <?php foreach ($sanctioned as $student): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($student['student_id']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-medium"><?php echo htmlspecialchars($student['name']); ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($student['acad_year']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($student['program']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <?php echo htmlspecialchars($student['sanction_hours']); ?> hours
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-4">
                                    <i class="fas fa-exclamation-triangle text-4xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-600 mb-2">No Sanctioned Students</h3>
                                <p class="text-gray-500">No students have been sanctioned for this event.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function filterTable() {
        const search = document.getElementById("searchInput").value.toLowerCase();
        const selectedProgram = document.getElementById("programFilter").value.toLowerCase();
        const selectedYear = document.getElementById("yearFilter").value.toLowerCase();
        const rows = document.querySelectorAll("#sanctionedTable tr");

        rows.forEach(row => {
            // Skip if it's the "no data" row
            if (row.cells.length === 1) {
                row.style.display = "none";
                return;
            }

            const studentId = row.cells[0]?.innerText.toLowerCase() || "";
            const name = row.cells[1]?.innerText.toLowerCase() || "";
            const year = row.cells[2]?.innerText.toLowerCase() || "";
            const program = row.cells[3]?.innerText.toLowerCase() || "";
            const sanctionHours = row.cells[4]?.innerText.toLowerCase() || "";

            // Combine all text for search
            const allText = `${studentId} ${name} ${year} ${program} ${sanctionHours}`;
            
            const matchesSearch = !search || allText.includes(search);
            const matchesProgram = !selectedProgram || program === selectedProgram;
            const matchesYear = !selectedYear || year === selectedYear;

            row.style.display = (matchesSearch && matchesProgram && matchesYear) ? "" : "none";
        });
    }

    // Populate filter dropdowns
    window.addEventListener("DOMContentLoaded", () => {
        const programSet = new Set();
        const yearSet = new Set();
        const rows = document.querySelectorAll("#sanctionedTable tr");

        rows.forEach(row => {
            // Skip if it's the "no data" row
            if (row.cells.length === 1) {
                return;
            }

            const program = row.cells[3]?.innerText.trim(); // Program is in column 3
            const year = row.cells[2]?.innerText.trim(); // Academic Year is in column 2
            
            if (program) programSet.add(program);
            if (year) yearSet.add(year);
        });

        const programFilter = document.getElementById("programFilter");
        [...programSet].sort().forEach(program => {
            const option = document.createElement("option");
            option.value = option.text = program;
            programFilter.add(option);
        });

        const yearFilter = document.getElementById("yearFilter");
        [...yearSet].sort().forEach(year => {
            const option = document.createElement("option");
            option.value = option.text = year;
            yearFilter.add(option);
        });
    });
</script>

</body>
</html>
