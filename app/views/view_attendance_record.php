<?php
global $EventID;
require_once "../app/core/imageConfig.php";

$selectedProgram = $_POST['program'] ?? $_GET['program'] ?? '';
$selectedYear = $_POST['year'] ?? $_GET['year'] ?? '';
$viewNotAttended = $_GET['view'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Event Attendance</title>
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
            <i class="fas fa-file-alt text-[#a31d1d] text-3xl"></i>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#a31d1d] tracking-tight">Attendance Record</h1>
                <p class="text-gray-600 font-medium"><?php echo htmlspecialchars($EventName ?? 'Event Details'); ?></p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo ROOT ?>adminHome?page=Attendance" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 no-print">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="<?php echo ROOT ?>view_record2?id=<?php echo htmlspecialchars($_GET['id']) ?>&eventName=<?php echo htmlspecialchars($_GET['eventName']); ?>" 
               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 no-print">
                <i class="fas fa-exclamation-triangle"></i> View Sanctioned
            </a>
            <button onclick="window.print()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 no-print">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Filter Section -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6">
        <h2 class="text-xl font-bold text-[#a31d1d] mb-4 flex items-center gap-2">
            <i class="fas fa-filter"></i> Filter Records
        </h2>
        <form action="" method="post" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                    <select name="program" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-3 focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" required>
                        <option value="">Select program</option>
                        <?php foreach ($programList as $program): ?>
                            <option value="<?= htmlspecialchars($program['program']); ?>" <?= (isset($_POST['program']) && $_POST['program'] === $program['program']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($program['program']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                    <select name="year" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-3 focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" required>
                        <option value="">Select year</option>
                        <?php foreach ($year as $yr): ?>
                            <option value="<?= htmlspecialchars($yr['acad_year']); ?>" <?= (isset($_POST['year']) && $_POST['year'] === $yr['acad_year']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($yr['acad_year']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i> View Records
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 no-print">
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Students</p>
                    <p class="text-3xl font-bold text-blue-600"><?= number_format($totalStudents ?? 0) ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Attended</p>
                    <p class="text-3xl font-bold text-green-600"><?= number_format($attendedCount["COUNT(student_id)"] ?? 0) ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Not Attended</p>
                    <p class="text-3xl font-bold text-yellow-600"><?= number_format(($totalStudents ?? 0) - ($attendedCount["COUNT(student_id)"] ?? 0)) ?></p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6">
        <h2 class="text-xl font-bold text-[#a31d1d] mb-4 flex items-center gap-2">
            <i class="fas fa-search"></i> Search Records
        </h2>
        <form action="" method="post" class="flex gap-4">
            <input type="text" name="search" placeholder="Search by student name or ID..." 
                   value="<?= htmlspecialchars($_POST['search'] ?? ''); ?>"
                   class="flex-grow bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-3 focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d] shadow-sm" required>
            <button type="submit" name="searchBtn" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <!-- Attendance Records Table -->
    <?php if (!empty($attendanceList)): ?>
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-[#a31d1d] flex items-center gap-2">
                    <i class="fas fa-table"></i> Attendance Records
                    <span class="text-sm font-normal text-gray-600">(<?= count($attendanceList) ?> records)</span>
                </h2>
            </div>
            
            <div class="overflow-x-auto hide-scrollbar">
                <table class="w-full">
                    <thead class="bg-[#a31d1d] text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Student ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Program</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Year</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                            <?php if ($viewNotAttended !== 'not_attended'): ?>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Time In</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Time Out</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold no-print">Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($attendanceList as $record): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($record['student_id']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium"><?= htmlspecialchars($record['f_name'] . ' ' . $record['l_name']); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($record['program']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($record['acad_year']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <a href="mailto:<?= htmlspecialchars($record['email']); ?>" class="text-blue-600 hover:text-blue-800">
                                        <?= htmlspecialchars($record['email']); ?>
                                    </a>
                                </td>
                                <?php if ($viewNotAttended !== 'not_attended'): ?>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-sign-in-alt mr-1"></i>
                                            <?= htmlspecialchars($record['time_in'] ?? 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?php if (!empty($record['time_out'])): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-sign-out-alt mr-1"></i>
                                                <?= htmlspecialchars($record['time_out']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Not recorded</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 no-print">
                                        <button onclick="deleteRecord('<?php echo ROOT?>delete_attendance_record?atten_id=<?php echo $EventID; ?>&student_id=<?php echo $record['student_id'];?>')"
                                                class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                title="Delete Record">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-12 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-file-alt text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Records Found</h3>
            <p class="text-gray-500">No attendance records match your current filters.</p>
        </div>
    <?php endif; ?>

</div>

<script>
function deleteRecord(deleteUrl) {
    Swal.fire({
        title: "Delete Attendance Record?",
        text: "This action cannot be undone. The record will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(deleteUrl, { method: "GET" })
                .then(response => response.text())
                .then(data => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "The attendance record has been successfully deleted.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2100);
                })
                .catch(error => {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong while deleting the record.",
                        icon: "error"
                    });
                });
        }
    });
}

// Add smooth scrolling for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                submitBtn.disabled = true;
            }
        });
    });
});
</script>

</body>
</html>
