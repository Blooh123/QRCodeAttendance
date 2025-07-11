
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo ROOT ?>assets/css/students.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Attendance System • Add Student</title>
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
</head>
<body>

<!-- Main modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="false" class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-md max-h-full overflow-y-auto">
        <!-- Modal content -->
        <div class="relative bg-gray-50 rounded-lg shadow"> <!-- Changed to bg-gray-50 -->
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200"> <!-- Changed border color -->
                <h3 class="text-lg font-semibold text-gray-800"> <!-- Changed text color -->
                    Add New Student
                </h3>
                <a href="<?php echo ROOT ?>adminHome?page=Students" type="button" class="text-gray-500 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-close="crud-modal"> <!-- Changed text and hover colors -->
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </a>
            </div>
            <!-- Modal body -->
            <form method="POST" class="p-4 md:p-5" action="<?php echo ROOT ?>add_student" enctype="multipart/form-data">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Name" >
                        <label for="StudentID" class="block mb-2 text-sm font-medium text-gray-700">Student ID</label>
                        <input type="text" name="student_id" id="StudentID" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Student ID" >
                        <label for="Email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="Email" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Email" >
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="program" class="block mb-2 text-sm font-medium text-gray-700">Program</label>
                        <!-- Program Dropdown -->
                        <select name="program" id="program" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Select program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?php echo htmlspecialchars($program['program']); ?>">
                                    <?php echo htmlspecialchars($program['program']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="year" class="block mb-2 text-sm font-medium text-gray-700">Year</label>
                        <!-- Year Dropdown -->
                        <select name="year" id="year" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Select year</option>
                            <?php foreach ($years as $year): ?>
                                <option value="<?php echo htmlspecialchars($year['acad_year']); ?>">
                                    <?php echo htmlspecialchars($year['acad_year']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Add Student
                </button>
                <label for="excelFile" class="block mb-2 text-sm font-medium text-gray-700">Import Excel File</label>
                <a href="<?php echo ROOT?>upload_file" type="submit" class="mt-2 text-white inline-flex items-center bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-200 ease-in-out" >
                    <svg class="me-2 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 16a1 1 0 011-1h12a1 1 0 011 1v1a1 1 0 11-2 0v-1H5v1a1 1 0 11-2 0v-1zM10 3a1 1 0 011 1v8.586l1.707-1.707a1 1 0 011.414 1.414l-3.5 3.5a1 1 0 01-1.414 0l-3.5-3.5a1 1 0 111.414-1.414L9 12.586V4a1 1 0 011-1z"></path>
                    </svg>
                    Import Students
                </a>

            </form>
        </div>
    </div>
</div>

<!-- Backdrop -->
<div id="crud-modal-backdrop" class="fixed inset-0 z-40 bg-black bg-opacity-50"></div>
<script>
</script>

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