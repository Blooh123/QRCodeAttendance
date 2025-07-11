<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        <h1 class="text-3xl font-bold text-[#a31d1d] mb-8 event-title">Events</h1>

        <?php
        $allEvents = $allEvents ?? [];
        $today = date('Y-m-d');
        $upcoming = [];
        $recent = [];

        foreach ($allEvents as $event) {
            $eventDate = $event['date_created'] ?? '';
            if ($eventDate >= $today) {
                $upcoming[] = $event;
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
                                <!-- <a href="?page=EventDetails&id=<?php echo $event['atten_id']; ?>" class="event-btn mt-4 inline-block">Apply for excuse</a> -->
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
                                <!-- <a href="?page=EventDetails&id=<?php echo $event['atten_id']; ?>" class="event-btn mt-4 inline-block">Apply for Excuse</a> -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-gray-500 italic">No recent activities.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>