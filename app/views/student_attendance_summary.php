<?php
global $imageSource;
require "../app/core/imageConfig.php";

// determine year label for headers
$selectedYear = $data['selectedYear'] ?? null;
if ($selectedYear && is_numeric($selectedYear)) {
    $yearLabel = "A. Y. " . htmlspecialchars($selectedYear) . "-" . htmlspecialchars($selectedYear + 1);
} else {
    $yearLabel = "All Years";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource?>">
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

        /* Loading overlay styles */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 50;
            backdrop-filter: blur(5px);
        }

        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .loading-spinner {
            position: relative;
            width: 60px;
            height: 60px;
        }

        .loading-spinner:before,
        .loading-spinner:after {
            content: '';
            position: absolute;
            border-radius: 50%;
            animation: pulse 1.5s linear infinite;
        }

        .loading-spinner:before {
            width: 100%;
            height: 100%;
            background: rgba(163, 29, 29, 0.2);
            animation-delay: -0.5s;
        }

        .loading-spinner:after {
            width: 75%;
            height: 75%;
            background: #a31d1d;
            top: 12.5%;
            left: 12.5%;
            animation-delay: -1s;
        }

        .loading-text {
            color: #a31d1d;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            animation: fadeInOut 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1); opacity: 1; }
            100% { transform: scale(0.8); opacity: 0.5; }
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body class="p-4 md:p-6">

<!-- Header -->
<header class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 mb-6 max-w-screen-2xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <i class="fas fa-user-graduate text-[#a31d1d] text-3xl"></i>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#a31d1d] tracking-tight">Student Attendance Summary</h1>
                <p class="text-gray-600 font-medium">Individual attendance and sanctions</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3 items-center">
            <a href="<?php echo htmlspecialchars(ROOT); ?>sanctions_summary"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <!-- Year filter (kept functionality) -->
            <form method="GET" class="flex items-center space-x-2">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($_GET['student_id']); ?>">
                <label for="year" class="text-sm text-gray-700">Academic Year:</label>
                <select id="year" name="year" class="rounded-md border px-2 py-1 text-sm">
                    <option value="">All</option>
                    <?php
                        $current = (int)date('Y');
                        for ($y = $current; $y >= $current - 6; $y--) {
                            $label = $y . '-' . ($y + 1);
                            $sel = (isset($data['selectedYear']) && $data['selectedYear'] == $y) ? 'selected' : '';
                            echo "<option value=\"{$y}\" {$sel}>{$label}</option>";
                        }
                    ?>
                </select>
                <button type="submit" class="inline-flex items-center px-3 py-1 bg-[#a31d1d] text-white rounded-md text-sm">Filter</button>
            </form>
        </div>
    </div>
</header>

<div class="max-w-screen-2xl mx-auto space-y-6">
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6">
        <div class="flex items-center space-x-4">
            <div class="bg-red-100 p-3 rounded-full">
                <i class="fas fa-user-graduate text-red-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    <?php echo htmlspecialchars($data['studentInfo']['name']); ?>
                </h2>
                <p class="text-gray-600">Student ID: <?php echo htmlspecialchars($data['studentInfo']['student_id']); ?></p>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black overflow-hidden relative">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#a31d1d] flex items-center gap-2">
                <i class="fas fa-table"></i> Attendance Overview
                <span class="text-sm font-normal text-gray-600"> (<?php echo htmlspecialchars($yearLabel); ?>)</span>
            </h2>
        </div>

        <div class="overflow-x-auto hide-scrollbar relative p-6">
            <!-- Loading Overlay (keeps available but hidden) -->
            <div id="loadingOverlay" class="loading-overlay">
                <div class="loading-container">
                    <div class="loading-spinner"></div>
                    <div class="loading-text">Loading...</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Attendance Records -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-calendar-check text-red-600 mr-2"></i>
                            Attendance Records (<?php echo $yearLabel; ?>)
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data['attendanceRecord'] as $record): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($record['event_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($record['atten_started']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($record['time_in']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo $record['time_out'] ?? 'N/A'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Not attended -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-calendar-check text-red-600 mr-2"></i>
                            Not attended (<?php echo $yearLabel; ?>)
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data['notAttended'] as $record): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($record['event_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($record['date_created']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sanctions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                            Sanctions (<?php echo $yearLabel; ?>)
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Applied</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data['sanctionList'] as $sanction): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($sanction['date_applied']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <?php echo htmlspecialchars($sanction['sanction_reason']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                                <?php echo htmlspecialchars($sanction['sanction_hours']); ?> hours
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Are you sure you want to remove this sanction?');">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($sanction['sanction_id']); ?>">
                                                <input type="hidden" name="studentID" value="<?php echo htmlspecialchars($_GET['student_id']); ?>">
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors duration-200" style="background:none; border:none; padding:0; cursor:pointer;" title="Remove sanction">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function confirmDelete(event, href) {
        event.preventDefault();
        if (confirm('Are you sure you want to remove this sanction?')) {
            window.location.href = href;
        }
        return false;
    }

    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>