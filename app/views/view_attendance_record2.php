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
        @media print {
            .no-print, .no-print * { display: none !important; }
            table { width: 100%; border-collapse: collapse; font-size: 14px; }
            th, td { font-size: 12px; border: 1px solid black !important; padding: 10px; text-align: left; }
            th { background-color: #f0f0f0 !important; color: black !important; font-size: 14px; font-weight: bold; }
            body { background: none; padding: 20px; }
            .text-center { text-align: center; }
            .text-4xl { font-size: 24px !important; font-weight: bold; }
            .text-2xl { font-size: 18px !important; font-weight: bold; }
            .text-gray-600 { color: black !important; }
            tr:nth-child(20n) { page-break-after: always; }
        }
        a[href]:after { content: none !important; }
    </style>
</head>
<body class="bg-gray-100 p-6">
<div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
    <h1 class="text-2xl sm:text-3xl font-bold text-[var(--maroon)] text-center sm:text-left">
        Sanctioned Students for Event:
        <span class="text-gray-700"><?php echo htmlspecialchars($_GET['eventName']); ?></span>
    </h1>
    <div class="flex flex-wrap justify-center sm:justify-end space-x-2 sm:space-x-3 no-print">
        <button onclick="history.back()" class="px-3 py-2 sm:px-4 sm:py-2 bg-gray-500 text-white text-sm sm:text-base rounded-lg hover:bg-gray-600">
            Back
        </button>
        <button onclick="window.print()" class="px-3 py-2 sm:px-4 sm:py-2 bg-blue-600 text-white text-sm sm:text-base rounded-lg hover:bg-blue-700">
            Print
        </button>
    </div>
</div>

<div class="flex flex-col md:flex-row gap-4 mb-4 no-print">
    <input type="text" id="searchInput" placeholder="Search by name or student ID..." class="w-full md:w-1/3 px-4 py-2 border rounded-lg shadow-sm" onkeyup="filterTable()">
    <select id="programFilter" class="px-4 py-2 border rounded-lg shadow-sm" onchange="filterTable()">
        <option value="">All Programs</option>
    </select>
    <select id="yearFilter" class="px-4 py-2 border rounded-lg shadow-sm" onchange="filterTable()">
        <option value="">All Academic Years</option>
    </select>
</div>


<div class="overflow-x-auto bg-white rounded-xl shadow-md">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Student ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Last Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Academic Year</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Program</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Sanction Hours</th>
        </tr>
        </thead>
        <tbody id="sanctionedTable" class="bg-white divide-y divide-gray-200">
        <?php if (!empty($sanctioned)): ?>
            <?php foreach ($sanctioned as $student): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['student_id']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['acad_year']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['program']); ?></td>


                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['sanction_hours']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No sanctioned students found for this event.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
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
            const lastName = row.cells[1]?.innerText.toLowerCase() || "";
            const year = row.cells[2]?.innerText.toLowerCase() || "";
            const program = row.cells[3]?.innerText.toLowerCase() || "";
            const sanctionHours = row.cells[4]?.innerText.toLowerCase() || "";

            // Combine all text for search
            const allText = `${studentId} ${lastName} ${year} ${program} ${sanctionHours}`;
            
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
