<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
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
            box-shadow: 0 4px 16px 0 rgba(0,0,0,0.10), 0 1.5px 0px 1px #000;
            outline: 1px solid #000;
        }
        .event-banner {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 1.5rem 1.5rem 0 0;
            background: #f3f4f6;
            box-shadow: 0 2px 8px 0 rgba(0,0,0,0.10);
        }
        .event-description {
            color: #374151;
            font-size: 1rem;
            margin-top: 0.5rem;
        }
        .event-title {
            text-shadow: 0px 1px 0px rgb(0 0 0 / 0.1);
        }
        .event-btn {
            background: #a31d1d;
            color: #fff;
            padding: 0.5rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            outline: 1px solid #000;
            box-shadow: 0 4px 0px 1px rgba(0,0,0,1);
            transition: background 0.2s;
        }
        .event-btn:hover {
            background: #8a1818;
        }
        @media (max-width: 640px) {
            .event-banner {
                height: 140px;
            }
            .glass-card {
                padding: 1rem !important;
            }
        }
    </style>
</head>
<body class="p-4 md:p-6">
    <div class="max-w-4xl mx-auto space-y-10">
        <!-- Recent Applications Dropdown -->
        <div class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 glass-card">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-[#a31d1d] flex items-center gap-2">
                    <i class="fas fa-file-medical"></i>
                    My Recent Applications
                </h2>
                <button onclick="toggleApplicationsDropdown()" class="text-[#a31d1d] hover:text-[#8a1818] transition-colors duration-200">
                    <i class="fas fa-chevron-down" id="applicationsDropdownIcon"></i>
                </button>
            </div>
            
            <div id="applicationsDropdown" class="hidden">
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    <?php
                    // Get student's recent applications
                    require_once '../app/Model/ExcuseApplication.php';
                    $excuseApp = new \Model\ExcuseApplication();
                    
                    // Get user data from session/cookie
                    if (isset($_COOKIE['user_data'])) {
                        $userSessions = json_decode($_COOKIE['user_data'], true);
                        if (is_array($userSessions) && !empty($userSessions)) {
                            $studentId = $userSessions[0]['user_id'] ?? null;
                            if ($studentId) {
                                $studentApplications = $excuseApp->getExcuseApplicationsByStudent($studentId);
                                
                                if (empty($studentApplications)): ?>
                                    <div class="text-center py-6 text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-2"></i>
                                        <p>No applications yet</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach (array_slice($studentApplications, 0, 5) as $app): ?>
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-800 text-sm">
                                                        <?php echo htmlspecialchars($app['event_name']); ?>
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        <?php echo date('M d, Y', strtotime($app['date_submitted'])); ?>
                                                    </p>
                                                    <?php if (!empty($app['admin_remarks'])): ?>
                                                        <div class="mt-2 p-2 bg-blue-50 border-l-4 border-blue-400 rounded">
                                                            <p class="text-xs text-blue-800 font-medium mb-1">
                                                                <i class="fas fa-comment mr-1"></i>Admin Remarks:
                                                            </p>
                                                            <p class="text-xs text-blue-700">
                                                                <?php echo htmlspecialchars($app['admin_remarks']); ?>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="ml-4">
                                                    <?php
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    $statusIcon = '';
                                                    switch ($app['application_status']) {
                                                        case 0:
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                            $statusText = 'Pending';
                                                            $statusIcon = 'fas fa-clock';
                                                            break;
                                                        case 1:
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusText = 'Approved';
                                                            $statusIcon = 'fas fa-check-circle';
                                                            break;
                                                        case 2:
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            $statusText = 'Rejected';
                                                            $statusIcon = 'fas fa-times-circle';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusClass; ?>">
                                                        <i class="<?php echo $statusIcon; ?> mr-1"></i>
                                                        <?php echo $statusText; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <?php if (count($studentApplications) > 5): ?>
                                        <div class="text-center pt-2">
                                            <a href="<?php echo ROOT ?>apply_excuse" class="text-[#a31d1d] hover:text-[#8a1818] text-sm font-medium">
                                                View all <?php echo count($studentApplications); ?> applications →
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php endif;
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-[#a31d1d] mb-8 event-title">Events</h1>

        <?php
        $allEvents = $allEvents ?? [];
        $today = date('Y-m-d');
        $upcoming = [];
        $ongoing = [];
        $recent = [];

        foreach ($allEvents as $event) {
            if ($event['atten_status'] === 'not started') {
                $upcoming[] = $event;
            } elseif ($event['atten_status'] === 'on going') {
                $ongoing[] = $event;
            } else {
                $recent[] = $event;
            }
        }

        // Robust base64 handler for banners
        function getBannerBase64($event) {
            if (!empty($event['banner'])) {
                $mime = 'image/jpeg';
                // Try to detect PNG
                if (isset($event['banner_mime'])) {
                    $mime = $event['banner_mime'];
                } elseif (function_exists('finfo_buffer')) {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->buffer($event['banner']);
                }
                // If already base64, just return it
                if (strpos($event['banner'], 'data:image') === 0) {
                    return $event['banner'];
                } else {
                    return 'data:' . $mime . ';base64,' . base64_encode($event['banner']);
                }
            }
            return null;
        }
        ?>

                <!-- Ongoing Events -->
        <div>
            <h2 class="text-2xl font-bold text-yellow-700 mb-6 flex items-center gap-2">
                <svg class="h-7 w-7 text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <rect x="3" y="4" width="18" height="18" rx="4" stroke="currentColor" stroke-width="2" fill="none"/>
                  <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="2"/>
                  <circle cx="12" cy="16" r="2" fill="currentColor"/>
                </svg>
                Ongoing Activities
            </h2>
            <?php if (count($ongoing)): ?>
                <div class="space-y-8">
                    <?php foreach ($ongoing as $event): ?>
                        <div class="glass-card rounded-2xl overflow-hidden p-0">
                            <?php $banner = getBannerBase64($event); ?>
                            <?php if ($banner): ?>
                                <img src="<?php echo htmlspecialchars($banner); ?>" alt="Event Banner" class="event-banner">
                            <?php endif; ?>
                            <div class="p-8">
                                <div class="text-xl font-bold text-[#a31d1d] mb-2 event-title"><?php echo htmlspecialchars($event['event_name']); ?></div>
                                <div class="text-gray-600 text-base mb-1 font-medium">Date: <?php echo htmlspecialchars($event['date_created']); ?></div>
                                <div class="text-yellow-700 text-sm mb-2 font-semibold">Status: Ongoing</div>
                                <?php if (!empty($event['description'])): ?>
                                    <div class="event-description mb-2"><?php echo $event['description']; ?></div>
                                <?php endif; ?>
                                <div class="mt-4 text-sm text-yellow-700 italic">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    This event is currently ongoing.
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-gray-500 italic">No ongoing activities.</div>
            <?php endif; ?>
        </div>

        <!-- Upcoming Events -->
        <div>
            <h2 class="text-2xl font-bold text-green-700 mb-6 flex items-center gap-2">
                <svg class="h-7 w-7 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <rect x="3" y="4" width="18" height="18" rx="4" stroke="currentColor" stroke-width="2" fill="none"/>
                  <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="2"/>
                  <path d="M9.5 16l2 2 4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Upcoming Activities
            </h2>
            <?php if (count($upcoming)): ?>
                <div class="space-y-8">
                    <?php foreach ($upcoming as $event): ?>
                        <div class="glass-card rounded-2xl overflow-hidden p-0">
                            <?php $banner = getBannerBase64($event); ?>
                            <?php if ($banner): ?>
                                <img src="<?php echo htmlspecialchars($banner); ?>" alt="Event Banner" class="event-banner">
                            <?php endif; ?>
                            <div class="p-8">
                                <div class="text-xl font-bold text-[#a31d1d] mb-2 event-title"><?php echo htmlspecialchars($event['event_name']); ?></div>
                                <div class="text-gray-600 text-base mb-1 font-medium">Date: <?php echo htmlspecialchars($event['date_created']); ?></div>
                                <div class="text-gray-500 text-sm mb-2">Status: <?php echo htmlspecialchars($event['atten_status']); ?></div>
                                <?php if (!empty($event['description'])): ?>
                                    <div class="event-description mb-2"><?php echo $event['description']; ?></div>
                                <?php endif; ?>
                                <?php if ($event['atten_status'] === 'not started'): ?>
                                    <a href="<?php echo ROOT ?>apply_excuse?id=<?php echo $event['atten_id']; ?>" class="event-btn mt-4 inline-block">Apply for Excuse</a>
                                <?php else: ?>
                                    <div class="mt-4 text-sm text-gray-500 italic">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Excuse applications are only available for events that haven't started yet.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-gray-500 italic">No upcoming activities.</div>
            <?php endif; ?>
        </div>



        <!-- Recent Events -->
        <div>
            <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center gap-2">
                <svg class="h-7 w-7 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/>
                  <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Recent Activities
            </h2>
            <?php if (count($recent)): ?>
                <div class="space-y-8">
                    <?php foreach ($recent as $event): ?>
                        <div class="glass-card rounded-2xl overflow-hidden p-0">
                            <?php $banner = getBannerBase64($event); ?>
                            <?php if ($banner): ?>
                                <img src="<?php echo htmlspecialchars($banner); ?>" alt="Event Banner" class="event-banner">
                            <?php endif; ?>
                            <div class="p-8">
                                <div class="text-xl font-bold text-[#a31d1d] mb-2 event-title"><?php echo htmlspecialchars($event['event_name']); ?></div>
                                <div class="text-gray-600 text-base mb-1 font-medium">Date: <?php echo htmlspecialchars($event['date_created']); ?></div>
                                <div class="text-gray-500 text-sm mb-2">Status: <?php echo htmlspecialchars($event['atten_status']); ?></div>
                                <?php if (!empty($event['description'])): ?>
                                    <div class="event-description mb-2"><?php echo $event['description']; ?></div>
                                <?php endif; ?>
                                <?php if ($event['atten_status'] === 'not started'): ?>
                                    <a href="<?php echo ROOT ?>apply_excuse?id=<?php echo $event['atten_id']; ?>" class="event-btn mt-4 inline-block">Apply for Excuse</a>
                                <?php else: ?>
                                    <div class="mt-4 text-sm text-gray-500 italic">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Excuse applications are only available for events that haven't started yet.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-gray-500 italic">No recent activities.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Toggle applications dropdown
        function toggleApplicationsDropdown() {
            const dropdown = document.getElementById('applicationsDropdown');
            const icon = document.getElementById('applicationsDropdownIcon');
            const isHidden = dropdown.classList.contains('hidden');
            
            if (isHidden) {
                dropdown.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                dropdown.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    </script>
</body>
</html>