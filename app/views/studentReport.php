<?php
$totalHours = array_sum(array_column($sanctionList, 'sanction_hours'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sanctions & Attendance • USep Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* Custom Maroon Dark Red */
        .bg-maroon { background-color: #800000; }
        .hover\:bg-maroon-hover:hover { background-color: #660000; }
        .text-maroon { color: #800000; }
        .border-maroon { border-color: #800000; }
        .focus\:ring-maroon:focus { --tw-ring-color: #800000; }
    </style>
</head>
<body class="p-4 md:p-6">
    <div class="max-w-5xl mx-auto space-y-8">
        <!-- Sanctions Section -->
        <div>
            <h3 class="text-2xl font-bold text-[#a31d1d] mb-6 [text-shadow:_0px_1px_0px_rgb(0_0_0_/_0.1)]">
                Sanctions Overview
            </h3>
            <!-- Download Report Button -->
            <div class="mt-6 flex justify-end">
                <a href="<?php echo ROOT ?>download-report" 
                   class="bg-[#a31d1d] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#8a1818] transition-all duration-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download Report PDF
                </a>
            </div>
            <div class="space-y-4">
                <?php if (empty($sanctionList)): ?>
                    <div class="glass-card p-6 rounded-2xl text-center text-gray-500 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                        No sanctions found.
                    </div>
                <?php else: ?>
                    <?php foreach ($sanctionList as $sanction): ?>
                        <div class="glass-card p-6 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:shadow-lg transition-all duration-300">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-sm font-medium text-[#a31d1d]">
                                    <?= date('F j, Y', strtotime($sanction['date_applied'])); ?>
                                </p>
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                    <?= htmlspecialchars($sanction['sanction_hours']); ?> Hours
                                </span>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                <?= htmlspecialchars($sanction['sanction_reason']); ?>
                            </h4>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Total Hours Card -->
            <div class="mt-6 glass-card bg-[#a31d1d] text-lg font-bold p-6 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-center floating">
                Total Sanction Hours: <span class="text-2xl ml-2"><?= htmlspecialchars($totalHours); ?></span>
            </div>
        </div>

        <!-- Recent Attended Activities -->
        <div>
            <h3 class="text-2xl font-bold text-[#a31d1d] mb-6 [text-shadow:_0px_1px_0px_rgb(0_0_0_/_0.1)]">
                Recent Attended Activities
            </h3>
            
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-hidden rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                <div class="overflow-x-auto glass-card">
                    <table class="min-w-full">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="py-4 px-6 text-left text-sm font-bold text-[#a31d1d]">Event Name</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-[#a31d1d]">Date</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-[#a31d1d]">Time In</th>
                                <th class="py-4 px-6 text-left text-sm font-bold text-[#a31d1d]">Time Out</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($attendanceRecord)): ?>
                                <tr>
                                    <td colspan="4" class="py-4 px-6 text-center text-gray-500">No attendance records found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($attendanceRecord as $record): ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 px-6 text-sm text-gray-800 font-medium"><?= htmlspecialchars($record['event_name'] ?? 'N/A'); ?></td>
                                        <td class="py-4 px-6 text-sm text-gray-600"><?= date('M j, Y', strtotime($record['atten_started'] ?? 'N/A')); ?></td>
                                        <td class="py-4 px-6 text-sm text-gray-600"><?= date('g:i A', strtotime($record['time_in'] ?? 'N/A')); ?></td>
                                        <td class="py-4 px-6 text-sm text-gray-600">
                                            <p class="text-gray-800">
                                                <?= !empty($record['time_out']) 
                                                    ? date('g:i A', strtotime($record['time_out'])) 
                                                    : '<span class="text-gray-400 italic">Not yet recorded</span>'; ?>
                                            </p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                <?php if (empty($attendanceRecord)): ?>
                    <div class="glass-card p-6 rounded-2xl text-center text-gray-500 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                        No attendance records found.
                    </div>
                <?php else: ?>
                    <?php foreach ($attendanceRecord as $record): ?>
                        <div class="glass-card p-6 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black space-y-3">
                            <h4 class="text-lg font-bold text-[#a31d1d]">
                                <?= htmlspecialchars($record['event_name'] ?? 'N/A'); ?>
                            </h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 font-medium">Date</p>
                                    <p class="text-gray-800"><?= date('F j, Y', strtotime($record['atten_started'] ?? 'N/A')); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-500 font-medium">Status</p>
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        Present
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium">Time In</p>
                                    <p class="text-gray-800"><?= date('g:i A', strtotime($record['time_in'] ?? 'N/A')); ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium">Time Out</p>
                                    <p class="text-gray-800"><?= date('g:i A', strtotime($record['time_out'] ?? 'N/A')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            
        </div>
    </div>
</body>
</html>