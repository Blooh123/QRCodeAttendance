<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Excuse Applications - Admin Review</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 15px md:20px;
            border-radius: 15px;
            width: 95%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-4 md:p-6 mb-6 md:mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <i class="fas fa-file-medical text-[#a31d1d] text-2xl md:text-3xl"></i>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Student Excuse Applications</h1>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Search and Filter Section -->
    <div class="glass-card rounded-2xl p-4 md:p-6 mb-6 md:mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <!-- Search Bar -->
        <div class="mb-4">
            <form method="POST" action="">
                <input type="hidden" name="action" value="search">
                <div class="flex gap-2">
                    <input type="text" name="search_query" 
                           placeholder="Search by student name, event, or program..."
                           value="<?php echo htmlspecialchars($searchQuery ?? ''); ?>"
                           class="flex-1 px-3 md:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d] text-sm md:text-base">
                    <button type="submit" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-3 md:px-4 py-2 rounded-lg font-medium transition-all duration-200 text-sm md:text-base">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Filter and Stats Row -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Status Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <form method="POST" action="" class="w-full">
                    <input type="hidden" name="action" value="filter">
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 w-full">
                        <button type="submit" name="filter" value="all" 
                                class="filter-btn <?php echo ($currentFilter === 'all') ? 'active bg-[#a31d1d] text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?> px-4 py-2 rounded-xl font-semibold transition-all duration-200 text-sm md:text-base min-w-[70px] focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                            <i class="fas fa-list mr-1"></i> All
                        </button>
                        <button type="submit" name="filter" value="0" 
                                class="filter-btn <?php echo ($currentFilter === '0') ? 'active bg-yellow-500 text-white shadow-lg' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'; ?> px-4 py-2 rounded-xl font-semibold transition-all duration-200 text-sm md:text-base min-w-[90px] focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <i class="fas fa-clock mr-1"></i> Pending
                        </button>
                        <button type="submit" name="filter" value="1" 
                                class="filter-btn <?php echo ($currentFilter === '1') ? 'active bg-green-600 text-white shadow-lg' : 'bg-green-100 text-green-800 hover:bg-green-200'; ?> px-4 py-2 rounded-xl font-semibold transition-all duration-200 text-sm md:text-base min-w-[100px] focus:outline-none focus:ring-2 focus:ring-green-400">
                            <i class="fas fa-check mr-1"></i> Approved
                        </button>
                        <button type="submit" name="filter" value="2" 
                                class="filter-btn <?php echo ($currentFilter === '2') ? 'active bg-red-600 text-white shadow-lg' : 'bg-red-100 text-red-800 hover:bg-red-200'; ?> px-4 py-2 rounded-xl font-semibold transition-all duration-200 text-sm md:text-base min-w-[90px] focus:outline-none focus:ring-2 focus:ring-red-400">
                            <i class="fas fa-times mr-1"></i> Rejected
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Stats -->
            <div class="flex flex-wrap gap-2 md:gap-4 text-xs md:text-sm">
                <div class="bg-yellow-100 text-yellow-800 px-2 md:px-3 py-1 rounded-full font-medium">
                    <i class="fas fa-clock mr-1"></i>
                    <span><?php echo $pendingCount ?? 0; ?></span> Pending
                </div>
                <div class="bg-green-100 text-green-800 px-2 md:px-3 py-1 rounded-full font-medium">
                    <i class="fas fa-check mr-1"></i>
                    <span><?php echo $approvedCount ?? 0; ?></span> Approved
                </div>
                <div class="bg-red-100 text-red-800 px-2 md:px-3 py-1 rounded-full font-medium">
                    <i class="fas fa-times mr-1"></i>
                    <span><?php echo $rejectedCount ?? 0; ?></span> Rejected
                </div>
            </div>
        </div>
    </div>

    <!-- Applications List -->
    <div id="applicationsContainer" class="space-y-6">
        <?php
        if (empty($applications)):
        ?>
            <div class="glass-card rounded-2xl p-8 text-center">
                <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Applications Found</h3>
                <p class="text-gray-500">
                    <?php if (!empty($searchQuery)): ?>
                        No applications match your search criteria.
                    <?php elseif ($currentFilter !== 'all'): ?>
                        No <?php echo $currentFilter == '0' ? 'pending' : ($currentFilter == '1' ? 'approved' : 'rejected'); ?> applications found.
                    <?php else: ?>
                        There are no student excuse applications to review.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($applications as $app): ?>
                <div class="application-card glass-card rounded-2xl p-6 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover-card">
                    
                    <!-- Header with Status -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-[#a31d1d]"><?php echo htmlspecialchars($app['event_name']); ?></h3>
                            <p class="text-gray-600 text-sm md:text-base"><?php echo date('M d, Y', strtotime($app['event_date'])); ?></p>
                        </div>
                        <div class="text-left sm:text-right">
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
                            <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium <?php echo $statusClass; ?>">
                                <i class="<?php echo $statusIcon; ?> mr-1"></i>
                                <?php echo $statusText; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-4">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2 text-sm md:text-base">Student Information</h4>
                            <div class="space-y-1 text-xs md:text-sm">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($app['name']); ?></p>
                                <p><strong>Program:</strong> <?php echo htmlspecialchars($app['program']); ?></p>
                                <p><strong>Academic Year:</strong> <?php echo htmlspecialchars($app['acad_year']); ?></p>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2 text-sm md:text-base">Application Details</h4>
                            <div class="space-y-1 text-xs md:text-sm">
                                <p><strong>Submitted:</strong> <?php echo date('M d, Y g:i A', strtotime($app['date_submitted'])); ?></p>
                                <p><strong>Application ID:</strong> #<?php echo $app['id']; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2 text-sm md:text-base">Excuse Description</h4>
                        <div class="bg-gray-50 rounded-lg p-3 md:p-4 text-xs md:text-sm">
                            <?php echo $app['application_description']; ?>
                        </div>
                    </div>

                    <!-- Supporting Images -->
                    <?php if ($app['document1'] || $app['document2']): ?>
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2 flex items-center text-sm md:text-base">
                                <i class="fas fa-image text-[#a31d1d] mr-2"></i>
                                Supporting Images
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 md:gap-4">
                                <?php if ($app['document1']): ?>
                                    <div class="bg-gray-50 rounded-lg p-3 md:p-4 border">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                            <h5 class="font-medium text-gray-800 text-sm md:text-base">Image 1</h5>
                                            <button onclick="downloadImage('<?php echo base64_encode($app['document1']); ?>', 'image1.jpg')" 
                                                    class="bg-green-500 hover:bg-green-600 text-white px-2 md:px-3 py-1 rounded text-xs md:text-sm font-medium transition-colors duration-200 flex items-center gap-1 self-start sm:self-auto">
                                                <i class="fas fa-download"></i> Download
                                            </button>
                                        </div>
                                        <div class="flex justify-center">
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($app['document1']); ?>" 
                                                 alt="Supporting Image 1" 
                                                 class="max-w-full h-auto max-h-48 md:max-h-64 rounded-lg shadow-md cursor-pointer hover:scale-105 transition-transform duration-200"
                                                 onclick="openImageModal('data:image/jpeg;base64,<?php echo base64_encode($app['document1']); ?>', 'Image 1')">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($app['document2']): ?>
                                    <div class="bg-gray-50 rounded-lg p-3 md:p-4 border">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                            <h5 class="font-medium text-gray-800 text-sm md:text-base">Image 2</h5>
                                            <button onclick="downloadImage('<?php echo base64_encode($app['document2']); ?>', 'image2.jpg')" 
                                                    class="bg-green-500 hover:bg-green-600 text-white px-2 md:px-3 py-1 rounded text-xs md:text-sm font-medium transition-colors duration-200 flex items-center gap-1 self-start sm:self-auto">
                                                <i class="fas fa-download"></i> Download
                                            </button>
                                        </div>
                                        <div class="flex justify-center">
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($app['document2']); ?>" 
                                                 alt="Supporting Image 2" 
                                                 class="max-w-full h-auto max-h-48 md:max-h-64 rounded-lg shadow-md cursor-pointer hover:scale-105 transition-transform duration-200"
                                                 onclick="openImageModal('data:image/jpeg;base64,<?php echo base64_encode($app['document2']); ?>', 'Image 2')">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Admin Remarks -->
                    <?php if (!empty($app['admin_remarks'])): ?>
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2 text-sm md:text-base">
                                <i class="fas fa-comment mr-1"></i>Admin Remarks
                            </h4>
                            <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-3 md:p-4 text-xs md:text-sm">
                                <?php echo htmlspecialchars($app['admin_remarks']); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <?php if ($app['application_status'] == 0): ?>
                        <div class="flex flex-col lg:flex-row gap-3 pt-4 border-t border-gray-200">
                            <!-- Approve Form -->
                            <form method="POST" action="" class="flex-1" onsubmit="return confirmApprove()">
                                <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                <input type="hidden" name="status" value="1">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input type="text" name="remarks" placeholder="Optional remarks..." 
                                           class="flex-1 px-3 md:px-4 py-2 border border-gray-300 rounded-lg text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <button type="submit" 
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 md:px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-xs md:text-sm">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Reject Form -->
                            <form method="POST" action="" class="flex-1" onsubmit="return confirmReject()">
                                <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                <input type="hidden" name="status" value="2">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input type="text" name="remarks" placeholder="Reason for rejection..." required
                                           class="flex-1 px-3 md:px-4 py-2 border border-gray-300 rounded-lg text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 md:px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-xs md:text-sm">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs md:text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                This application has been <?php echo $statusText; ?>.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-base md:text-lg font-semibold text-gray-800"></h3>
            <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700 text-xl md:text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="flex justify-center">
            <img id="modalImage" src="" alt="Full size image" class="max-w-full h-auto max-h-64 md:max-h-96 rounded-lg">
        </div>
    </div>
</div>

<script>
    // Form confirmation functions
    function confirmApprove() {
        return confirm('Are you sure you want to approve this excuse application?');
    }
    
    function confirmReject() {
        const remarks = event.target.closest('form').querySelector('input[name="remarks"]').value.trim();
        if (!remarks) {
            alert('Please provide a reason for rejection.');
            return false;
        }
        return confirm('Are you sure you want to reject this excuse application?');
    }

    // Image handling functions
    function openImageModal(imageSrc, title) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        
        modalImage.src = imageSrc;
        modalTitle.textContent = title;
        modal.style.display = 'block';
        
        // Close modal when clicking outside
        modal.onclick = function(event) {
            if (event.target === modal) {
                closeImageModal();
            }
        };
    }
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
    }
    
    function downloadImage(base64Data, filename) {
        console.log('Downloading image:', filename);
        try {
            // Convert base64 to blob
            const byteCharacters = atob(base64Data);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], { type: 'image/jpeg' });
            
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
            console.log('Download completed successfully');
        } catch (error) {
            console.error('Error downloading image:', error);
            alert('Error downloading image. Please try again.');
        }
    }
</script>
</body>
</html>