    <?php
    global $imageSource, $buttonLabel, $buttonAction, $buttonClass, $year, $programList, $requiredAttendees, $attendanceDetails,$activityListLog;
    require "../app/core/imageConfig.php";

    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script src="https://cdn.tailwindcss.com"></script>

        <title>Attendance System • Create Attendance</title>
        <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            /* Custom Maroon Dark Red */
            .bg-maroon { background-color: #800000; }
            .hover\:bg-maroon-hover:hover { background-color: #660000; }
            .text-maroon { color: #800000; }
            .border-maroon { border-color: #800000; }
            .focus\:ring-maroon:focus { --tw-ring-color: #800000; }
            /* Hide scrollbar but keep scroll functionality */
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .hide-scrollbar {
                -ms-overflow-style: none;  /* IE and Edge */
                scrollbar-width: none;     /* Firefox */
            }
        </style>

    </head>
    <body>

    <!-- Main modal -->
    <div id="crud-modal" tabindex="-1" aria-hidden="false" class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-lg max-h-full overflow-y-auto">
            <!-- Modal content -->
            <div class="relative bg-gray-50 rounded-lg shadow"> <!-- Changed to bg-gray-50 -->
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200"> <!-- Changed border color -->
                    <h3 class="text-lg font-semibold text-gray-800"> <!-- Changed text color -->
                        Edit Attendance
                    </h3>
                    <a href="<?php echo ROOT?>adminHome?page=Attendance" type="button" class="text-gray-500 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-close="crud-modal"> <!-- Changed text and hover colors -->
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </a>
                </div>
                
                <!-- Modal body -->
                <form method="POST" class="p-4 md:p-5" action="<?php echo ROOT?>update_attendance" id="attendanceForm">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="eventName" class="block mb-2 text-sm font-medium text-gray-700">Event Name</label>
                            <input type="text" name="eventName" id="eventName" value="<?php echo htmlspecialchars($attendanceDetails['event_name']); ?>" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <label for="atten_id" class="block mb-2 text-sm font-medium text-gray-700">Attendance Status</label>
                            <input type="hidden" name="atten_id" id="atten_id" value="<?php echo htmlspecialchars($attendanceDetails['atten_id']); ?>" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <input type="hidden" name="atten_status" id="atten_status" value="<?php echo $attendanceDetails['atten_status']?>">
                            <?php echo ''.$attendanceDetails['atten_status']?>
                        </div>

                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Required Attendees</label>
                            <ul class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full">
                                <?php
                                // Check if required attendees data exists
                                if (!empty($requiredAttendees) && is_array($requiredAttendees)):
                                    foreach ($requiredAttendees as $index => $program):
                                        $year = isset($acad_year[$index]) ? $acad_year[$index] : '';
                                        
                                        // Display "All years" if year is empty, null, or empty string
                                        $yearDisplay = (!empty($year) && $year !== '' && $year !== null) ? htmlspecialchars($year) : 'All years';
                                        ?>
                                        <li class="flex justify-between items-center p-2 border-b border-gray-200 last:border-b-0">
                                            <span class="font-medium"><?php echo htmlspecialchars($program); ?></span>
                                            <span class="text-gray-600">(<?php echo $yearDisplay; ?>)</span>
                                        </li>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <li class="text-gray-500 p-2">No required attendees listed</li>
                                <?php endif; ?>
                            </ul>
                        </div>



                        <div class="col-span-2">
                            <label for="sanction" class="block mb-2 text-sm font-medium text-gray-700">Sanction</label>
                            <input type="number" name="sanction" id="sanction" value="<?php echo htmlspecialchars($attendanceDetails['sanction']); ?>" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                    </div>

                    <!-- Hidden input to differentiate actions -->
                    <input type="hidden" name="action" id="action" value="">

                    <!-- Button Container for Better Layout -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-4">
                        <!-- Done Button -->
                        <button type="submit" onclick="setAction('save changes of',event)"
                                class="w-full sm:w-auto text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            <i class="fas fa-check me-2"></i>
                            Done
                        </button>

                        <!-- Start/Stop Attendance Button -->
                        <button type="submit"
                                onclick="setAction('<?php echo $buttonAction ?>', event)"
                                class="w-full sm:w-auto text-white inline-flex items-center <?php echo $buttonClass; ?> font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                            <?php echo ($buttonClass === 'hidden') ? 'hidden disabled style="pointer-events: none;"' : ''; ?>>
                            <i class="fas <?php echo ($buttonAction === 'start') ? 'fa-play' : 'fa-stop'; ?> me-2"></i>
                            <?php echo $buttonLabel; ?>
                        </button>


                        <!-- Finished Attendance Button -->
                        <button type="submit" onclick="setAction('finished',event)"
                                class="w-full sm:w-auto text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                <?php echo ($buttonClass === 'hidden') ? 'hidden disabled style="pointer-events: none;"' : ''; ?>>
                            <i class="fas fa-flag-checkered me-2"></i>
                            Finished Attendance
                        </button>
                        <script src="<?php echo ROOT?>assets/js/editingAttendance.js"></script>
                    </div>
                </form>

                <!-- Pass PHP array to JS -->
                <script>
                    const fullActivityLog = <?php echo json_encode($activityListLog); ?>;
                </script>

                <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                    <!-- Toggle -->
                    <button onclick="toggleLogs()" class="bg-maroon hover:bg-maroon-hover text-white px-4 py-2 rounded-lg flex items-center gap-2 mb-4">
                        <i class="fas fa-clock"></i> View Activity Log
                    </button>

                    <!-- Log List -->
                    <div id="activity-log" class="mt-4">
                        <h3 class="text-xl font-bold mb-2 text-maroon">Activity Log</h3>

                        <!-- Search -->
                        <div class="flex flex-col md:flex-row md:items-center gap-2 mb-1">
                            <input type="text" id="search-input"
                                   placeholder="Search..."
                                   class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon">

                            <button type="button" id="search-btn"
                                    class="bg-maroon hover:bg-maroon-hover text-white px-4 py-2 rounded-lg flex items-center gap-4">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>

                        <!-- Scrollable container -->
                        <div class="h-60 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 hide-scrollbar">
                            <ul class="space-y-2" id="activity-log-list">
                                <!-- Logs will be rendered here by JS -->
                            </ul>
                        </div>
                    </div>
                </div>

                <script>
                    // Render logs
                    function renderLogs(logs) {
                        const list = document.getElementById("activity-log-list");
                        list.innerHTML = ''; // Clear
                        if (logs.length === 0) {
                            list.innerHTML = '<li class="text-gray-500 text-sm">No logs found.</li>';
                        } else {
                            logs.forEach(log => {
                                const item = document.createElement("li");
                                item.className = "border border-gray-200 rounded-lg p-3 text-gray-700 bg-white";
                                item.innerHTML = `
                    <span class="font-semibold">${log.activity}</span><br>
                    <span class="text-sm text-gray-500">${log.time_created}</span>
                `;
                                list.appendChild(item);
                            });
                        }
                    }

                    // Search functionality
                    document.getElementById("search-btn").addEventListener("click", () => {
                        const keyword = document.getElementById("search-input").value.toLowerCase();
                        const filtered = fullActivityLog.filter(log =>
                            log.activity.toLowerCase().includes(keyword) ||
                            log.time_created.toLowerCase().includes(keyword)
                        );
                        renderLogs(filtered);
                    });

                    // Optional: Enter key triggers search
                    document.getElementById("search-input").addEventListener("keypress", function (e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            document.getElementById("search-btn").click();
                        }
                    });

                    // Optional toggle (if you want to show/hide the log section)
                    function toggleLogs() {
                        const logDiv = document.getElementById("activity-log");
                        logDiv.classList.toggle("hidden");
                        if (!logDiv.classList.contains("hidden")) {
                            renderLogs(fullActivityLog);
                        }
                    }

                    // ✅ Render logs by default on page load
                    document.addEventListener("DOMContentLoaded", () => {
                        renderLogs(fullActivityLog);
                    });
                </script>




                <script>
                    function toggleLogs() {
                        const logSection = document.getElementById("activity-log");
                        logSection.classList.toggle("hidden");
                    }
                </script>

            </div>
        </div>


    </div>

    <!-- Backdrop -->
    <div id="crud-modal-backdrop" class="fixed inset-0 z-40 bg-black bg-opacity-50"></div>


    </body>
    <script>
        //// Track if the user is navigating away or submitting a form
        //let isNavigating = false;
        //
        //// Detect clicks on links or buttons that lead to navigation
        //document.addEventListener('click', function(event) {
        //    const target = event.target.closest('a, button[type="submit"], input[type="submit"]');
        //    if (target) {
        //        isNavigating = true;
        //    }
        //});
        //
        //// Detect form submissions
        //document.addEventListener('submit', function() {
        //    isNavigating = true;
        //});
        //
        //// Use beforeunload to detect when the user is leaving the page
        //window.addEventListener('beforeunload', function(event) {
        //    // If the user is NOT navigating or submitting a form, they are closing the tab/browser
        //    if (!isNavigating) {
        //        navigator.sendBeacon('<?php //echo ROOT; ?>//logout', new URLSearchParams({
        //            action: 'logOutOnClose'
        //        }));
        //    }
        //});

    </script>
    </html>