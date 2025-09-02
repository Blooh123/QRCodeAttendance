<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs • USep Attendance System</title>
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
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
        }
        
        .event-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.625rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .event-add {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .event-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .event-update {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .event-login {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }
        
        .role-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-weight: 500;
            font-size: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .role-admin {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }
        
        .role-facilitator {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
        }
        
        .role-student {
            background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%);
            color: white;
        }
        
        .time-ago {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .scrollable-container {
            max-height: 600px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #a31d1d #f1f5f9;
            padding-right: 8px;
        }
        
        .scrollable-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .scrollable-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .scrollable-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #a31d1d 0%, #8a1818 100%);
            border-radius: 10px;
        }
        
        .scrollable-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #8a1818 0%, #6b1414 100%);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stats-card.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .stats-card.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .stats-card.info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
    </style>
</head>
<body class="p-2 md:p-4 lg:p-6 bg-[#f8f9fa]">

<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-4 md:p-6 mb-6 md:mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex items-center space-x-2 md:space-x-3">
        <i class="fas fa-history text-[#a31d1d] text-2xl md:text-3xl"></i>
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Activity Logs</h1>
    </div>
    <p class="text-gray-600 mt-2">Track all system activities and user actions</p>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <?php
        $totalLogs = count($activityLogs);
        $addCount = 0;
        $deleteCount = 0;
        $updateCount = 0;
        $loginCount = 0;
        
        foreach ($activityLogs as $log) {
            $evnt = strtolower($log['evnt'] ?? '');
            if (strpos($evnt, 'add') !== false) $addCount++;
            elseif (strpos($evnt, 'delete') !== false) $deleteCount++;
            elseif (strpos($evnt, 'update') !== false) $updateCount++;
            elseif (strpos($evnt, 'logged') !== false) $loginCount++;
        }
        ?>
        
        <div class="stats-card rounded-2xl p-4 md:p-6 text-center hover-card">
            <i class="fas fa-list-alt text-3xl md:text-4xl mb-2 opacity-80"></i>
            <h3 class="text-lg md:text-xl font-bold">Total Activities</h3>
            <p class="text-2xl md:text-3xl font-extrabold"><?php echo $totalLogs; ?></p>
        </div>
        
        <div class="stats-card success rounded-2xl p-4 md:p-6 text-center hover-card">
            <i class="fas fa-plus text-3xl md:text-4xl mb-2 opacity-80"></i>
            <h3 class="text-lg md:text-xl font-bold">Add Operations</h3>
            <p class="text-2xl md:text-3xl font-extrabold"><?php echo $addCount; ?></p>
        </div>
        
        <div class="stats-card warning rounded-2xl p-4 md:p-6 text-center hover-card">
            <i class="fas fa-edit text-3xl md:text-4xl mb-2 opacity-80"></i>
            <h3 class="text-lg md:text-xl font-bold">Update Operations</h3>
            <p class="text-2xl md:text-3xl font-extrabold"><?php echo $updateCount; ?></p>
        </div>
        
        <div class="stats-card danger rounded-2xl p-4 md:p-6 text-center hover-card">
            <i class="fas fa-trash text-3xl md:text-4xl mb-2 opacity-80"></i>
            <h3 class="text-lg md:text-xl font-bold">Delete Operations</h3>
            <p class="text-2xl md:text-3xl font-extrabold"><?php echo $deleteCount; ?></p>
        </div>
    </div>

    <!-- Activity Logs List -->
    <div class="glass-card rounded-2xl p-4 md:p-6 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <i class="fas fa-clipboard-list text-[#a31d1d] text-xl md:text-2xl"></i>
                <h2 class="text-xl md:text-2xl font-bold text-[#a31d1d]">Recent Activities</h2>
            </div>
            <div class="text-sm text-gray-500">
                Showing activities with: add, delete, update, and login events
            </div>
        </div>

        <?php if (empty($activityLogs)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3 class="text-xl md:text-2xl font-semibold mb-2">No Activity Logs</h3>
                <p class="text-gray-500">No activities have been recorded yet.</p>
            </div>
        <?php else: ?>
            <div class="scrollable-container">
                <div class="space-y-4">
                    <?php foreach ($activityLogs as $log): ?>
                        <div class="glass-card rounded-2xl p-4 md:p-6 hover-card fade-in">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                                <!-- Left side: Event info -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <!-- Event Badge -->
                                        <?php
                                        $evnt = strtolower($log['evnt'] ?? '');
                                        $eventClass = '';
                                        $eventIcon = '';
                                        
                                        if (strpos($evnt, 'add') !== false) {
                                            $eventClass = 'event-add';
                                            $eventIcon = 'fas fa-plus';
                                        } elseif (strpos($evnt, 'delete') !== false) {
                                            $eventClass = 'event-delete';
                                            $eventIcon = 'fas fa-trash';
                                        } elseif (strpos($evnt, 'update') !== false) {
                                            $eventClass = 'event-update';
                                            $eventIcon = 'fas fa-edit';
                                        } elseif (strpos($evnt, 'logged') !== false) {
                                            $eventClass = 'event-login';
                                            $eventIcon = 'fas fa-sign-in-alt';
                                        } else {
                                            $eventClass = 'event-update';
                                            $eventIcon = 'fas fa-info-circle';
                                        }
                                        ?>
                                        <span class="event-badge <?php echo $eventClass; ?>">
                                            <i class="<?php echo $eventIcon; ?> mr-1"></i>
                                            <?php echo htmlspecialchars($log['evnt'] ?? 'Unknown Event'); ?>
                                        </span>
                                        
                                        <!-- Role Badge -->
                                        <?php
                                        $role = strtolower($log['role'] ?? '');
                                        $roleClass = '';
                                        
                                        if ($role === 'admin') {
                                            $roleClass = 'role-admin';
                                        } elseif ($role === 'facilitator') {
                                            $roleClass = 'role-facilitator';
                                        } elseif ($role === 'student') {
                                            $roleClass = 'role-student';
                                        } else {
                                            $roleClass = 'role-admin';
                                        }
                                        ?>
                                        <span class="role-badge <?php echo $roleClass; ?>">
                                            <?php echo htmlspecialchars(ucfirst($log['role'] ?? 'Unknown')); ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Activity Description -->
                                    <div class="mb-3">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                            <?php echo htmlspecialchars($log['username'] ?? 'Unknown User'); ?>
                                        </h3>
                                        <p class="text-gray-600 text-sm md:text-base">
                                            <?php echo ($log['activity'] ?? 'No description available'); ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Time Information -->
                                    <div class="flex items-center space-x-4 text-sm">
                                        <span class="time-ago">
                                            <i class="fas fa-clock mr-1"></i>
                                            <?php echo htmlspecialchars($log['time_ago'] ?? 'Unknown time'); ?>
                                        </span>
                                        <span class="time-ago">
                                            <i class="fas fa-calendar mr-1"></i>
                                            <?php echo htmlspecialchars($log['time_created'] ?? 'Unknown date'); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Right side: Additional info -->
                                <div class="flex flex-col items-end space-y-2">
                                    <!-- User ID -->
                                    <div class="text-xs text-gray-500">
                                        User ID: <?php echo htmlspecialchars($log['user_id'] ?? 'N/A'); ?>
                                    </div>
                                    
                                    <!-- Event Type Indicator -->
                                    <div class="w-3 h-3 rounded-full <?php echo $eventClass; ?> opacity-60"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Add fade-in animation delay for each card
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.fade-in');
        cards.forEach((card, index) => {
            card.style.animationDelay = (index * 0.1) + 's';
        });
    });
</script>

</body>
</html>