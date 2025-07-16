<?php
global $imageSource;
require "../app/core/imageConfig.php";
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
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm p-6 mb-6">
        <div class="max-w-[95%] mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-user-graduate text-red-600 text-3xl"></i>
                <h1 class="text-3xl font-bold text-gray-900">Student Attendance Summary</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo htmlspecialchars(ROOT); ?>sanction_summary?>"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Student Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
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

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Records -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-check text-red-600 mr-2"></i>
                        Attendance Records
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
                                        <?php echo$record['time_out'] ?? 'N/A'; ?>
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
                        Sanctions
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