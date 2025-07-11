
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Attendance System • Add Student</title>
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
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(163, 29, 29, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #a31d1d 0%, #c53030 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(163, 29, 29, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(163, 29, 29, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        }
        .section-divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(163, 29, 29, 0.3), transparent);
            margin: 2rem 0;
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-2xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-user-plus text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Add New Student</h1>
    </div>
</header>

<div class="max-w-2xl mx-auto">
    <div class="glass-card rounded-2xl p-8 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <form method="POST" action="<?php echo ROOT ?>add_student" enctype="multipart/form-data" class="space-y-6">
            
            <!-- Personal Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-circle text-[#a31d1d] mr-2"></i>
                    Personal Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-user text-[#a31d1d] mr-1"></i>Full Name
                        </label>
                        <input type="text" name="name" id="name" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" 
                               placeholder="Enter student's full name" required>
                    </div>
                    
                    <div>
                        <label for="StudentID" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-id-card text-[#a31d1d] mr-1"></i>Student ID
                        </label>
                        <input type="text" name="student_id" id="StudentID" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" 
                               placeholder="Enter student ID" required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="Email" class="block mb-2 text-sm font-medium text-gray-700">
                        <i class="fas fa-envelope text-[#a31d1d] mr-1"></i>Email Address
                    </label>
                    <input type="email" name="email" id="Email" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" 
                           placeholder="Enter email address" required>
                    <div class="mt-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        This email will be used for attendance notifications and system communications.
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- Academic Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-graduation-cap text-[#a31d1d] mr-2"></i>
                    Academic Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="program" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-book text-[#a31d1d] mr-1"></i>Program
                        </label>
                        <select name="program" id="program" 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" required>
                            <option value="">Select program</option>
                            <?php foreach ($programs as $program): ?>
                                <option value="<?php echo htmlspecialchars($program['program']); ?>">
                                    <?php echo htmlspecialchars($program['program']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="year" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-calendar text-[#a31d1d] mr-1"></i>Academic Year
                        </label>
                        <select name="year" id="year" 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] focus:border-[#a31d1d]" required>
                            <option value="">Select year</option>
                            <?php foreach ($years as $year): ?>
                                <option value="<?php echo htmlspecialchars($year['acad_year']); ?>">
                                    <?php echo htmlspecialchars($year['acad_year']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    This information will be used to determine which attendance events the student is required to attend.
                </div>
            </div>

            <hr class="section-divider">

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button type="submit" 
                        class="btn-primary text-white font-semibold rounded-xl px-6 py-3 flex items-center justify-center space-x-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Student</span>
                </button>
                
                <a href="<?php echo ROOT?>upload_file" 
                   class="btn-secondary text-white font-semibold rounded-xl px-6 py-3 flex items-center justify-center space-x-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <i class="fas fa-file-excel"></i>
                    <span>Import from Excel</span>
                </a>
                
                <a href="<?php echo ROOT ?>adminHome?page=Students"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-xl px-6 py-3 flex items-center justify-center space-x-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Cancel</span>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Add interactive effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus effects to inputs
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });

        // Add loading state to submit button
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding Student...';
            submitBtn.disabled = true;
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const studentId = document.getElementById('StudentID').value.trim();
            const email = document.getElementById('email').value.trim();
            const program = document.getElementById('program').value;
            const year = document.getElementById('year').value;

            if (!name || !studentId || !email || !program || !year) {
                e.preventDefault();
                Swal.fire({
                    title: 'Required Fields Missing',
                    text: 'Please fill in all required fields.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                Swal.fire({
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                document.getElementById('email').focus();
                return false;
            }
        });
    });
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

    <?php if (isset($_SESSION['error_message'])): ?>
    Swal.fire({
        title: 'Error!',
        text: '<?php echo $_SESSION['error_message']; ?>',
        icon: 'error',
        confirmButtonText: 'OK'
    });
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
</script>

</body>
</html>