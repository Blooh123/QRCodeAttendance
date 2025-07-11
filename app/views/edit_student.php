<?php
global $studentData, $imageSource5;
require "../app/core/imageConfig.php";
// Calculate total sanction hours
$totalSanctionHours = array_sum(array_column($sanctionList, 'sanction_hours'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details</title>
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome CDN (for back button icon) -->
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
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?php echo htmlspecialchars(ROOT); ?>adminHome?page=Students"
           class="inline-flex items-center px-4 py-2 bg-[#a31d1d] hover:bg-[#8a1818] text-white rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 gap-2">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>

    <!-- Student Profile Card -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 mb-8 flex flex-col items-center">
        <?php if (!empty($studentData['studentProfile'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($studentData['studentProfile']) ?>"
                 class="w-32 h-32 object-cover rounded-full border-4 border-gray-300 shadow-md mb-3"
                 alt="Profile Picture">
        <?php else: ?>
            <img src="<?php echo $imageSource5 ?>"
                 class="w-32 h-32 object-cover rounded-full border-4 border-gray-300 shadow-md mb-3"
                 alt="Default Profile">
        <?php endif; ?>
        <h2 class="text-xl font-bold text-[#a31d1d]"><?php echo htmlspecialchars($studentData['name']) ?></h2>
        <p class="text-gray-700"><?php echo htmlspecialchars($studentData['program']) ?></p>
    </div>

    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 mb-8">
        <h1 class="text-2xl font-bold text-[#a31d1d] text-center mb-6">Edit Student Details</h1>
        <form id="studentForm" method="post" action="<?php echo htmlspecialchars(ROOT); ?>edit_student?id=<?php echo htmlspecialchars($_GET['id']) ?>">
            <input type="hidden" name="form_type" value="update_student">
            <div class="mb-4">
                <label class="block text-[#a31d1d] font-semibold">Student ID</label>
                <input type="text" name="id" id="id" class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-200 cursor-not-allowed"
                       value="<?php echo htmlspecialchars($studentData['student_id']) ?>">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[#a31d1d] font-semibold">Name</label>
                    <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-lg p-2.5"
                           value="<?php echo htmlspecialchars($studentData['name']) ?>">
                </div>
                <div>
                    <label class="block text-[#a31d1d] font-semibold">Email</label>
                    <input type="email" name="email" id="email" class="w-full border-gray-300 rounded-lg p-2.5"
                           value="<?php echo htmlspecialchars($studentData['email']) ?>">
                </div>
                <div>
                    <label class="block text-[#a31d1d] font-semibold">Program</label>
                    <input class="w-full border-gray-300 rounded-lg p-2.5" name="program" id="program"
                           value="<?php echo htmlspecialchars($studentData['program']) ?>">
                </div>
                <div>
                    <label class="block text-[#a31d1d] font-semibold">Year Level</label>
                    <input class="w-full border-gray-300 rounded-lg p-2.5" type="text" name="acad_year" id="acad_year"
                           value="<?php echo htmlspecialchars($studentData['acad_year']) ?>" min="1">
                </div>
            </div>
            <div class="mt-6 bg-white/90 p-4 rounded-xl border border-gray-200 shadow hover-card">
                <h2 class="text-lg font-semibold text-[#a31d1d]">Student Account</h2>
                <p class="text-gray-700 mt-2"><strong>Username:</strong> <?php echo htmlspecialchars($studentData['email']); ?></p>
            </div>
            <div class="mt-6 text-right">
                <button type="submit"
                        class="px-6 py-2 bg-[#a31d1d] hover:bg-[#8a1818] text-white rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Sanction Form -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 mb-8">
        <form id="sanctionForm" method="post" action="" class="">
            <h2 class="text-lg font-semibold text-[#a31d1d] mb-3">Apply Sanction</h2>
            <input type="hidden" name="form_type" value="apply_sanction">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($studentData['student_id']); ?>">
            <label for="sanctionHours" class="block text-[#a31d1d] mb-1">Sanction Hours</label>
            <input type="number" name="sanctionH" id="sanctionHours"
                   placeholder="Enter hours" min="1"
                   class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-red-500 focus:border-red-500 transition">
            <div id="reasonContainer" class="mt-3 hidden">
                <label for="sanctionReason" class="block text-[#a31d1d] mb-1">Reason for Sanction</label>
                <textarea name="reason" id="sanctionReason"
                          placeholder="Enter reason..."
                          class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-red-500 focus:border-red-500 transition"></textarea>
            </div>
            <button type="submit" id="applySanction"
                    class="mt-4 w-full px-4 py-2 bg-red-600 hover:bg-red-800 text-white rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                <i class="fas fa-gavel"></i> Apply Sanction
            </button>
        </form>
    </div>

    <!-- Sanctions Section -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 mt-8">
        <h2 class="text-xl font-bold text-[#a31d1d] mb-4">Student Sanctions</h2>
        <div class="space-y-4">
            <?php foreach ($sanctionList as $sanction): ?>
                <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 flex justify-between items-center hover-card">
                    <div>
                        <p class="text-gray-500 text-sm"><?= htmlspecialchars($sanction['date_applied']); ?></p>
                        <h3 class="text-lg font-semibold text-gray-700"><?= htmlspecialchars($sanction['sanction_reason']); ?></h3>
                        <p class="text-gray-800 font-medium">Sanction Hours:
                            <span class="text-red-500"><?= htmlspecialchars($sanction['sanction_hours']); ?></span>
                        </p>
                    </div>
                    <a href="<?php echo htmlspecialchars(ROOT); ?>remove_sanction?id=<?php echo htmlspecialchars($sanction['sanction_id']); ?>&studentID=<?php echo htmlspecialchars($_GET['id']); ?>"
                       class="text-red-600 hover:text-red-800 text-xl"
                       onclick="return confirmDelete(event, this.href);"
                       title="Remove Sanction">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-6 bg-blue-600 text-white text-lg font-semibold p-4 rounded-lg shadow-md text-center">
            Total Sanction Hours: <span><?= htmlspecialchars($totalSanctionHours); ?></span>
        </div>
    </div>
</div>

<script>
    document.getElementById('sanctionHours').addEventListener('input', function () {
        let hours = this.value;
        let reasonContainer = document.getElementById('reasonContainer');
        let applyButton = document.getElementById('applySanction');
        if (hours > 0) {
            reasonContainer.classList.remove('hidden');
        } else {
            reasonContainer.classList.add('hidden');
            document.getElementById('sanctionReason').value = '';
        }
        checkFormValidity();
    });

    document.getElementById('sanctionReason').addEventListener('input', checkFormValidity);

    function checkFormValidity() {
        let hours = document.getElementById('sanctionHours').value;
        let reason = document.getElementById('sanctionReason').value.trim();
        let applyButton = document.getElementById('applySanction');
        applyButton.disabled = !(hours > 0 && reason.length > 0);
    }

    function confirmDelete(event, url) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>

<?php if (isset($_GET['removed']) && $_GET['removed'] == 1): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Sanction removed successfully.',
            confirmButtonColor: '#d33'
        });
    </script>
<?php endif; ?>

<script>
    document.getElementById('studentForm').addEventListener('submit', function (event) {
        event.preventDefault();
        let email = document.getElementById("email").value.trim();
        let acadYear = document.getElementById("acad_year").value.trim();
        let requiredFields = ["email", "program", "acad_year"];
        let allFilled = true;
        requiredFields.forEach(field => {
            let input = document.getElementById(field);
            if (input && input.value.trim() === "") {
                allFilled = false;
                input.classList.add("border-red-500");
            } else if (input) {
                input.classList.remove("border-red-500");
            }
        });
        if (!email.endsWith("@usep.edu.ph")) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: 'Email must end with "@usep.edu.ph"',
                confirmButtonColor: '#d33'
            });
            return;
        }
        if (!allFilled) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Fields',
                text: 'All fields are required!',
                confirmButtonColor: '#d33'
            });
            return;
        }
        Swal.fire({
            title: 'Confirm Update',
            text: "Are you sure you want to update this student’s details?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
</body>
</html>