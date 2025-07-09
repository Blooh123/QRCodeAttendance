<?php
require_once '../app/core/config.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System • Create Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
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
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-2xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-calendar-plus text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Create New Attendance</h1>
    </div>
</header>

<div class="max-w-2xl mx-auto">
    <div class="glass-card rounded-2xl p-8 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <form method="POST" action="<?php echo ROOT?>add_attendance" class="space-y-6">
            <div>
                <label for="eventName" class="block mb-2 text-sm font-medium text-gray-700">Event Name</label>
                <input type="text" name="eventName" id="eventName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" placeholder="Event name" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="program" class="block mb-2 text-sm font-medium text-gray-700">Program</label>
                    <select name="program[]" class="program-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" required>
                        <option value="">Select program</option>
                        <option value="AllStudents">All Students</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?php echo htmlspecialchars($program['program']); ?>">
                                <?php echo htmlspecialchars($program['program']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="year" class="block mb-2 text-sm font-medium text-gray-700">Year</label>
                    <select name="year[]" class="year-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                        <option value="">Select year</option>
                        <?php foreach ($years as $year): ?>
                            <option value="<?php echo htmlspecialchars($year['acad_year']); ?>">
                                <?php echo htmlspecialchars($year['acad_year']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="space-y-1 mt-2">
                <div id="additional-fields"></div>
                <button type="button"
                        onclick="addFieldSet()"
                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Required Attendance Record</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="required_attendance[]" value="time_in" checked required
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">Time In (Default)</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="required_attendance[]" value="time_out"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">Time Out</span>
                    </label>
                </div>
            </div>
            <div>
                <label for="sanction" class="block mb-2 text-sm font-medium text-gray-700">Sanction (in hours)</label>
                <input type="number" name="sanction" id="sanction"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                       placeholder="Sanction" required>
            </div>
            <div class="flex justify-end gap-4">
                <a href="<?php echo ROOT?>adminHome?page=Attendance"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit"
                        class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-check"></i> Done
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    // Fetch programs and years from PHP
    let programs = <?php echo json_encode($programs); ?>;
    let years = <?php echo json_encode($years); ?>;
    function addFieldSet() {
        let container = document.getElementById("additional-fields");
        let fieldSet = document.createElement("div");
        fieldSet.className = "relative bg-gray-100 p-4 rounded-lg shadow-md border border-gray-300 mt-2";
        let programDiv = document.createElement("div");
        programDiv.className = "mb-2";
        let programLabel = document.createElement("label");
        programLabel.className = "block mb-2 text-sm font-medium text-gray-700";
        programLabel.textContent = "Program";
        let programSelect = document.createElement("select");
        programSelect.name = "program[]";
        programSelect.className = "program-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]";
        let programOptions = `<option value="">Select program</option>
                              <option value="AllStudents">All Students</option>`;
        programs.forEach(program => {
            programOptions += `<option value="${program.program}">${program.program}</option>`;
        });
        programSelect.innerHTML = programOptions;
        programDiv.appendChild(programLabel);
        programDiv.appendChild(programSelect);
        let yearDiv = document.createElement("div");
        let yearLabel = document.createElement("label");
        yearLabel.className = "block mb-2 text-sm font-medium text-gray-700";
        yearLabel.textContent = "Year";
        let yearSelect = document.createElement("select");
        yearSelect.name = "year[]";
        yearSelect.className = "year-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]";
        let yearOptions = `<option value="">Select year</option>`;
        years.forEach(year => {
            yearOptions += `<option value="${year.acad_year}">${year.acad_year}</option>`;
        });
        yearSelect.innerHTML = yearOptions;
        yearDiv.appendChild(yearLabel);
        yearDiv.appendChild(yearSelect);
        let removeBtn = document.createElement("button");
        removeBtn.className = "absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-lg hover:bg-red-600";
        removeBtn.textContent = "Remove";
        removeBtn.onclick = function () {
            container.removeChild(fieldSet);
        };
        fieldSet.appendChild(removeBtn);
        fieldSet.appendChild(programDiv);
        fieldSet.appendChild(yearDiv);
        container.appendChild(fieldSet);
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if (isset($_SESSION['success_message'])): ?>
    Swal.fire({
        title: 'Success!',
        text: '<?php echo $_SESSION['success_message']; ?>',
        icon: 'success',
        confirmButtonText: 'OK'
    });
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
</script>
</body>
</html>