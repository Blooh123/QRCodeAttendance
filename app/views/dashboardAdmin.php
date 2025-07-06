<?php
global $numberOfStudents, $numberOfAttendance, $numberOfFaci, $listOfAttendance, $listOfFaci;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard • USep Attendance System</title>
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
<body class="p-4 md:p-6">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-chart-line text-[#a31d1d] text-3xl"></i>
        <h1 class="text-4xl font-extrabold text-[#a31d1d] tracking-tight">Admin Dashboard</h1>
    </div>
</header>

<!-- Overview Cards -->
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="?page=Students" class="glass-card rounded-2xl p-6 flex flex-col items-center hover-card shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <h2 class="text-2xl font-bold text-[#a31d1d] mb-2">Total Students</h2>
        <p class="text-5xl font-extrabold text-[#a31d1d]"><?php echo $data['numberOfStudents']?></p>
    </a>
    <a href="?page=Attendance" class="glass-card rounded-2xl p-6 flex flex-col items-center hover-card shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <h2 class="text-2xl font-bold text-[#d62828] mb-2">Total Attendance</h2>
        <p class="text-5xl font-extrabold text-[#d62828]"><?php echo $data['numberOfAttendance']?></p>
    </a>
    <a href="?page=Users" class="glass-card rounded-2xl p-6 flex flex-col items-center hover-card shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <h2 class="text-2xl font-bold text-[#f77f00] mb-2">Total Facilitators</h2>
        <p class="text-5xl font-extrabold text-[#f77f00]"><?php echo $data['numberOfFaci']?></p>
    </a>
</div>

<!-- Details Section -->
<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Facilitators Status -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8">
        <h2 class="text-3xl font-bold text-[#a31d1d] mb-4">Facilitators Status</h2>
        <div class="space-y-4 max-h-64 overflow-y-auto hide-scrollbar">
            <?php foreach ($data['listOfFaci'] as $faci): ?>
                <div class="bg-gray-100 p-4 rounded-lg shadow flex justify-between items-center">
                    <span class="text-lg font-semibold text-[#515050]"><?php echo htmlspecialchars($faci[1])?></span>
                    <span class="text-lg font-bold <?php echo ($faci[4] === 'login' || $faci[4] === 'scanning') ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo ucfirst($faci[4]) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8">
        <h2 class="text-3xl font-bold text-[#a31d1d] mb-4">Recent Attendance</h2>
        <div class="space-y-4 max-h-64 overflow-y-auto hide-scrollbar">
            <?php foreach ($data['listOfAttendance'] as $attendance):?>
                <div class="bg-gray-100 p-4 rounded-lg shadow flex justify-between items-center">
                    <span class="text-lg font-semibold text-[#515050]"><?php echo htmlspecialchars($attendance[1])?></span>
                    <span class="text-lg font-semibold text-[#515050]"><?php echo htmlspecialchars($attendance[5])?></span>
                </div>
            <?php endforeach?>
        </div>
        <div class="flex justify-center mt-4">
            <a href="?page=Attendance" class="bg-[#a31d1d] text-white px-6 py-3 rounded-xl text-lg font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#8a1818] transition-all duration-200">
                View More
            </a>
        </div>
    </div>
</div>
</body>
</html>
