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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: -1;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .hover-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        
        .hover-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .hover-card:hover::before {
            left: 100%;
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
            border: 2px solid rgba(255,255,255,0.3);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
            transform: rotate(45deg) translateX(100px);
            transition: transform 0.6s;
        }
        
        .stat-card:hover::after {
            transform: rotate(45deg) translateX(-100px);
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        .animate-bounce-in {
            animation: bounceIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .status-indicator {
            position: relative;
            display: inline-block;
        }
        
        .status-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -8px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transform: translateY(-50%);
            animation: pulse 2s infinite;
        }
        
        .status-online::before {
            background-color: #10b981;
        }
        
        .status-offline::before {
            background-color: #ef4444;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: translateY(-50%) scale(1);
            }
            50% {
                opacity: 0.5;
                transform: translateY(-50%) scale(1.2);
            }
        }
        
        .floating-action {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .btn-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }
    </style>
</head>
<body class="p-4 md:p-6">

<!-- Header -->
<header class="glass-card rounded-3xl p-8 mb-8 max-w-7xl mx-auto animate-fade-in">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-3 rounded-2xl">
                <i class="fas fa-chart-line text-white text-3xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-black gradient-text tracking-tight">Admin Dashboard</h1>
                <p class="text-gray-600 font-medium">Welcome back! Here's your system overview</p>
            </div>
        </div>
        <div class="hidden md:flex items-center space-x-4">
            <div class="text-right">
                <p class="text-sm text-gray-500">Last Updated</p>
                <p class="text-sm font-semibold text-gray-700" id="lastUpdated"></p>
            </div>
        </div>
    </div>
</header>

<!-- Overview Cards -->
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    <div class="stat-card rounded-3xl p-8 hover-card animate-slide-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 rounded-2xl">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 font-medium">Total</p>
                <p class="text-2xl font-bold text-blue-600"><?php echo $data['numberOfStudents']?></p>
            </div>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Students</h3>
        <p class="text-gray-600 text-sm">Registered in the system</p>
        <a href="?page=Students" class="inline-block mt-4 text-blue-600 font-semibold hover:text-blue-700 transition-colors">
            View Details <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="stat-card rounded-3xl p-8 hover-card animate-slide-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 rounded-2xl">
                <i class="fas fa-clipboard-check text-white text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 font-medium">Total</p>
                <p class="text-2xl font-bold text-green-600"><?php echo $data['numberOfAttendance']?></p>
            </div>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Attendance</h3>
        <p class="text-gray-600 text-sm">Records captured today</p>
        <a href="?page=Attendance" class="inline-block mt-4 text-green-600 font-semibold hover:text-green-700 transition-colors">
            View Details <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="stat-card rounded-3xl p-8 hover-card animate-slide-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4 rounded-2xl">
                <i class="fas fa-user-tie text-white text-2xl"></i>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 font-medium">Total</p>
                <p class="text-2xl font-bold text-orange-600"><?php echo $data['numberOfFaci']?></p>
            </div>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Facilitators</h3>
        <p class="text-gray-600 text-sm">Active in the system</p>
        <a href="?page=Users" class="inline-block mt-4 text-orange-600 font-semibold hover:text-orange-700 transition-colors">
            View Details <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>

<!-- Details Section -->
<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Facilitators Status -->
    <div class="glass-card rounded-3xl p-8 animate-slide-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold gradient-text flex items-center gap-3">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-2 rounded-xl">
                    <i class="fas fa-users-cog text-white"></i>
                </div>
                Facilitators Status
            </h2>
            <div class="text-sm text-gray-500">
                <span class="font-semibold"><?php echo count($data['listOfFaci']); ?></span> total
            </div>
        </div>
        
        <div class="space-y-4 max-h-80 overflow-y-auto hide-scrollbar" id="faciStatusList">
            <?php foreach ($data['listOfFaci'] as $faci): ?>
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200 hover:border-purple-300 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 text-lg"><?php echo htmlspecialchars($faci[1])?></h3>
                                <p class="text-sm text-gray-500">Facilitator</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="status-indicator <?php echo ($faci[4] === 'login' || $faci[4] === 'scanning') ? 'status-online' : 'status-offline'; ?> font-semibold text-lg">
                                <?php echo ucfirst($faci[4]) ?>
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                <?php echo ($faci[4] === 'login' || $faci[4] === 'scanning') ? 'Active' : 'Inactive'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Facilitators Pagination -->
        <div class="flex justify-center mt-6" id="faciPagination"></div>
    </div>

    <!-- Recent Attendance -->
    <div class="glass-card rounded-3xl p-8 animate-slide-up" style="animation-delay: 0.5s;">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold gradient-text flex items-center gap-3">
                <div class="bg-gradient-to-r from-green-500 to-blue-500 p-2 rounded-xl">
                    <i class="fas fa-clock text-white"></i>
                </div>
                Recent Attendance
            </h2>
            <div class="text-sm text-gray-500">
                <span class="font-semibold"><?php echo count($data['listOfAttendance']); ?></span> records
            </div>
        </div>
        
        <div class="space-y-4 max-h-80 overflow-y-auto hide-scrollbar" id="attendanceList">
            <?php foreach ($data['listOfAttendance'] as $attendance):?>
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200 hover:border-green-300 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-check text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 text-lg"><?php echo htmlspecialchars($attendance[1])?></h3>
                                <p class="text-sm text-gray-500">Student</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-calendar-check mr-1"></i>
                                <?php echo htmlspecialchars($attendance[5])?>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Attendance recorded</p>
                        </div>
                    </div>
                </div>
            <?php endforeach?>
        </div>
        
        <!-- Attendance Pagination -->
        <div class="flex justify-center mt-6" id="attendancePagination"></div>
        
        <div class="flex justify-center mt-6">
            <a href="?page=Attendance" class="btn-modern">
                <i class="fas fa-eye mr-2"></i>
                View All Attendance
            </a>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<div class="floating-action">
    <button onclick="refreshDashboard()" class="btn-modern rounded-full w-14 h-14 flex items-center justify-center">
        <i class="fas fa-sync-alt"></i>
    </button>
</div>

<script>
/**
 * Enhanced client-side pagination with modern UI and smooth animations
 */
document.addEventListener('DOMContentLoaded', function () {
    // Update last updated time
    function updateLastUpdated() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: true, 
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit'
        });
        const dateString = now.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        document.getElementById('lastUpdated').textContent = `${timeString} • ${dateString}`;
    }
    
    updateLastUpdated();
    setInterval(updateLastUpdated, 1000);

    // Facilitators Pagination
    const faciItems = Array.from(document.querySelectorAll('#faciStatusList > div'));
    const faciPerPage = 4;
    let faciCurrentPage = 1;
    const faciPagination = document.getElementById('faciPagination');

    function renderFaciPage(page) {
        const totalPages = Math.ceil(faciItems.length / faciPerPage);
        faciCurrentPage = page;
        
        faciItems.forEach((item, idx) => {
            const shouldShow = idx >= (page - 1) * faciPerPage && idx < page * faciPerPage;
            if (shouldShow) {
                item.style.display = '';
                item.style.animation = 'slideUp 0.3s ease-out';
            } else {
                item.style.display = 'none';
            }
        });
        
        renderFaciPagination(page, totalPages);
    }
    
    function renderFaciPagination(page, totalPages) {
        faciPagination.innerHTML = '';
        if (totalPages <= 1) return;

        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'flex items-center space-x-2 bg-white rounded-full p-2 shadow-lg';

        // Prev arrow
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevBtn.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-200 ' +
            (page === 1 
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                : 'bg-gradient-to-r from-purple-500 to-pink-500 text-white hover:shadow-lg hover:scale-105');
        prevBtn.disabled = page === 1;
        prevBtn.onclick = () => renderFaciPage(page - 1);
        paginationContainer.appendChild(prevBtn);

        // Page numbers
        const pageNumbers = document.createElement('div');
        pageNumbers.className = 'flex items-center space-x-1';
        
        if (page > 2) {
            addPageBtn(1, page, pageNumbers);
            if (page > 3) {
                addEllipsis(pageNumbers);
            }
        }
        
        for (let i = Math.max(1, page - 1); i <= Math.min(totalPages, page + 1); i++) {
            addPageBtn(i, page, pageNumbers);
        }
        
        if (page < totalPages - 1) {
            if (page < totalPages - 2) {
                addEllipsis(pageNumbers);
            }
            addPageBtn(totalPages, page, pageNumbers);
        }
        
        paginationContainer.appendChild(pageNumbers);

        // Next arrow
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextBtn.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-200 ' +
            (page === totalPages 
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                : 'bg-gradient-to-r from-purple-500 to-pink-500 text-white hover:shadow-lg hover:scale-105');
        nextBtn.disabled = page === totalPages;
        nextBtn.onclick = () => renderFaciPage(page + 1);
        paginationContainer.appendChild(nextBtn);

        faciPagination.appendChild(paginationContainer);

        function addPageBtn(i, current, container) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-200 ' +
                (i === current
                    ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg'
                    : 'bg-white text-gray-600 hover:bg-gray-100 hover:scale-105');
            btn.onclick = () => renderFaciPage(i);
            container.appendChild(btn);
        }
        
        function addEllipsis(container) {
            const span = document.createElement('span');
            span.textContent = '...';
            span.className = 'px-2 text-gray-400 font-bold';
            container.appendChild(span);
        }
    }
    
    renderFaciPage(faciCurrentPage);

    // Attendance Pagination
    const attItems = Array.from(document.querySelectorAll('#attendanceList > div'));
    const attPerPage = 4;
    let attCurrentPage = 1;
    const attPagination = document.getElementById('attendancePagination');

    function renderAttPage(page) {
        const totalPages = Math.ceil(attItems.length / attPerPage);
        attCurrentPage = page;
        
        attItems.forEach((item, idx) => {
            const shouldShow = idx >= (page - 1) * attPerPage && idx < page * attPerPage;
            if (shouldShow) {
                item.style.display = '';
                item.style.animation = 'slideUp 0.3s ease-out';
            } else {
                item.style.display = 'none';
            }
        });
        
        renderAttPagination(page, totalPages);
    }
    
    function renderAttPagination(page, totalPages) {
        attPagination.innerHTML = '';
        if (totalPages <= 1) return;

        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'flex items-center space-x-2 bg-white rounded-full p-2 shadow-lg';

        // Prev arrow
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevBtn.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-200 ' +
            (page === 1 
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                : 'bg-gradient-to-r from-green-500 to-blue-500 text-white hover:shadow-lg hover:scale-105');
        prevBtn.disabled = page === 1;
        prevBtn.onclick = () => renderAttPage(page - 1);
        paginationContainer.appendChild(prevBtn);

        // Page numbers
        const pageNumbers = document.createElement('div');
        pageNumbers.className = 'flex items-center space-x-1';
        
        if (page > 2) {
            addPageBtn(1, page, pageNumbers);
            if (page > 3) {
                addEllipsis(pageNumbers);
            }
        }
        
        for (let i = Math.max(1, page - 1); i <= Math.min(totalPages, page + 1); i++) {
            addPageBtn(i, page, pageNumbers);
        }
        
        if (page < totalPages - 1) {
            if (page < totalPages - 2) {
                addEllipsis(pageNumbers);
            }
            addPageBtn(totalPages, page, pageNumbers);
        }
        
        paginationContainer.appendChild(pageNumbers);

        // Next arrow
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextBtn.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-200 ' +
            (page === totalPages 
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                : 'bg-gradient-to-r from-green-500 to-blue-500 text-white hover:shadow-lg hover:scale-105');
        nextBtn.disabled = page === totalPages;
        nextBtn.onclick = () => renderAttPage(page + 1);
        paginationContainer.appendChild(nextBtn);

        attPagination.appendChild(paginationContainer);

        function addPageBtn(i, current, container) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = 'w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-200 ' +
                (i === current
                    ? 'bg-gradient-to-r from-green-500 to-blue-500 text-white shadow-lg'
                    : 'bg-white text-gray-600 hover:bg-gray-100 hover:scale-105');
            btn.onclick = () => renderAttPage(i);
            container.appendChild(btn);
        }
        
        function addEllipsis(container) {
            const span = document.createElement('span');
            span.textContent = '...';
            span.className = 'px-2 text-gray-400 font-bold';
            container.appendChild(span);
        }
    }
    
    renderAttPage(attCurrentPage);
});

// Refresh dashboard function
function refreshDashboard() {
    const refreshBtn = document.querySelector('.floating-action button');
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Add smooth scroll behavior
document.documentElement.style.scrollBehavior = 'smooth';
</script>
</body>
</html>
