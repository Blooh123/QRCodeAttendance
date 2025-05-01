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
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-[var(--maroon)]">Sanctioned Students for Event: <span class="text-gray-700"><?php echo htmlspecialchars($_GET['eventName']); ?></span></h1>
    <div class="space-x-3 no-print">
        <button onclick="history.back()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
            Back
        </button>

        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Print</button>
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
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">First Name</th>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['f_name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['l_name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['acad_year']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['program']); ?></td>


                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['sanction_hours']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['sanction_reason']); ?></td>
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
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#sanctionedTable tr");

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? "" : "none";
        });
    }
</script>
<script>
    function filterTable() {
        const search = document.getElementById("searchInput").value.toLowerCase();
        const selectedProgram = document.getElementById("programFilter").value.toLowerCase();
        const selectedYear = document.getElementById("yearFilter").value.toLowerCase();
        const rows = document.querySelectorAll("#sanctionedTable tr");

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const matchesSearch = text.includes(search);
            const programCell = row.cells[4]?.innerText.toLowerCase() || ""; // adjust index if needed
            const yearCell = row.cells[5]?.innerText.toLowerCase() || "";

            const matchesProgram = !selectedProgram || programCell === selectedProgram;
            const matchesYear = !selectedYear || yearCell === selectedYear;

            row.style.display = (matchesSearch && matchesProgram && matchesYear) ? "" : "none";
        });
    }

    // Populate filter dropdowns
    window.addEventListener("DOMContentLoaded", () => {
        const programSet = new Set();
        const yearSet = new Set();
        const rows = document.querySelectorAll("#sanctionedTable tr");

        rows.forEach(row => {
            const program = row.cells[4]?.innerText.trim();
            const year = row.cells[5]?.innerText.trim();
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
