<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details • USep Attendance System</title>
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
        .status-badge {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 2px solid #f59e0b;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }
        .document-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .document-card:hover {
            border-color: #a31d1d;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .action-btn {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .btn-approve {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
        }
        .btn-reject {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            color: white;
        }
        .description-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            border-left: 4px solid #a31d1d;
        }
        .remarks-box {
            background: #fef3c7;
            border-radius: 10px;
            padding: 1.5rem;
            border-left: 4px solid #f59e0b;
        }
        .document-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .back-btn {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            border: none;
            color: white;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
        }
        .back-btn:hover {
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-7xl mx-auto glass-card">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <i class="fas fa-file-alt text-[#a31d1d] text-3xl"></i>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Application Details</h1>
        </div>
        <a href="<?= ROOT ?>adminHome?page=StudentApplication" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>Back to Applications
        </a>
    </div>
</header>

<div class="max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 mb-6 bg-green-50 border-l-4 border-green-400">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                <p class="text-green-800 font-medium"><?= $_SESSION['success'] ?></p>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 mb-6 bg-red-50 border-l-4 border-red-400">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3"></i>
                <p class="text-red-800 font-medium"><?= $_SESSION['error'] ?></p>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Student Information -->
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 mb-8 bg-gradient-to-r from-[#a31d1d] to-[#8a1818] text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h2 class="text-2xl md:text-3xl font-bold mb-2">
                    <i class="fas fa-user-circle mr-3"></i>
                    <?= htmlspecialchars($application['name']) ?>
                </h2>
                <div class="space-y-1">
                    <p class="text-lg">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        <?= htmlspecialchars($application['program']) ?> - <?= htmlspecialchars($application['acad_year']) ?>
                    </p>
                    <p class="text-lg">
                        <i class="fas fa-calendar mr-2"></i>
                        Application submitted on <?= date('F d, Y \a\t g:i A', strtotime($application['date_submitted'])) ?>
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0">
                <?php
                $statusClass = '';
                $statusText = '';
                $statusIcon = '';
                
                switch ($application['application_status']) {
                    case 0:
                        $statusClass = 'status-pending';
                        $statusText = 'Pending Review';
                        $statusIcon = 'fas fa-clock';
                        break;
                    case 1:
                        $statusClass = 'status-approved';
                        $statusText = 'Approved';
                        $statusIcon = 'fas fa-check';
                        break;
                    case 2:
                        $statusClass = 'status-rejected';
                        $statusText = 'Rejected';
                        $statusIcon = 'fas fa-times';
                        break;
                }
                ?>
                <span class="status-badge <?= $statusClass ?> bg-white">
                    <i class="<?= $statusIcon ?> mr-2"></i>
                    <?= $statusText ?>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Application Details -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
                <h3 class="text-xl font-bold text-[#a31d1d] mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Application Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Event Name</h4>
                        <p class="text-lg font-medium text-gray-900"><?= htmlspecialchars($application['event_name']) ?></p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Event Date</h4>
                        <p class="text-lg font-medium text-gray-900">
                            <i class="fas fa-calendar mr-2 text-[#a31d1d]"></i>
                            <?= date('F d, Y', strtotime($application['event_date'])) ?>
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Application Description</h4>
                    <div class="description-box">
                        <p class="text-gray-800 leading-relaxed"><?= ($application['application_description']) ?></p>
                    </div>
                </div>

                <?php if ($application['admin_remarks']): ?>
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3">Admin Remarks</h4>
                        <div class="remarks-box">
                            <p class="text-gray-800 leading-relaxed"><?= (($application['admin_remarks'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons (only show for pending applications) -->
                <?php if ($application['application_status'] == 0): ?>
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-[#a31d1d] mb-4">Take Action</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button type="button" class="action-btn btn-approve w-full shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black" onclick="openApproveModal()">
                                <i class="fas fa-check mr-2"></i>Approve Application
                            </button>
                            <button type="button" class="action-btn btn-reject w-full shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black" onclick="openRejectModal()">
                                <i class="fas fa-times mr-2"></i>Reject Application
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="lg:col-span-1">
            <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 hover-card">
                <h3 class="text-xl font-bold text-[#a31d1d] mb-6 flex items-center">
                    <i class="fas fa-paperclip mr-3"></i>
                    Supporting Documents
                </h3>

                <?php if ($document1Info || $document2Info): ?>
                    <?php if ($document1Info): ?>
                        <div class="document-card">
                            <div class="text-center mb-4">
                                <i class="<?= $document1Info['icon'] ?> document-icon text-[#a31d1d]"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-2">Document 1</h4>
                            <p class="text-sm text-gray-600 mb-3">
                                Type: <span class="font-medium"><?= strtoupper($document1Info['extension']) ?></span><br>
                                Size: <span class="font-medium"><?= $document1Info['size_formatted'] ?></span>
                            </p>
                            <div class="flex flex-col gap-2">
                                <?php if (strpos($document1Info['mime_type'], 'image/') === 0): ?>
                                    <button onclick="viewImage(<?= $application['id'] ?>, 1, '<?= $document1Info['mime_type'] ?>')" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fas fa-eye"></i>View Image
                                    </button>
                                <?php else: ?>
                                                                         <a href="<?= ROOT ?>view_application?action=viewDocument&id=<?= $application['id'] ?>&doc=1" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2" target="_blank">
                                         <i class="fas fa-eye"></i>View
                                     </a>
                                     <a href="<?= ROOT ?>view_application?action=downloadDocument&id=<?= $application['id'] ?>&doc=1" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                                         <i class="fas fa-download"></i>Download
                                     </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($document2Info): ?>
                        <div class="document-card">
                            <div class="text-center mb-4">
                                <i class="<?= $document2Info['icon'] ?> document-icon text-[#a31d1d]"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800 mb-2">Document 2</h4>
                            <p class="text-sm text-gray-600 mb-3">
                                Type: <span class="font-medium"><?= strtoupper($document2Info['extension']) ?></span><br>
                                Size: <span class="font-medium"><?= $document2Info['size_formatted'] ?></span>
                            </p>
                            <div class="flex flex-col gap-2">
                                <?php if (strpos($document2Info['mime_type'], 'image/') === 0): ?>
                                    <button onclick="viewImage(<?= $application['id'] ?>, 2, '<?= $document2Info['mime_type'] ?>')" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                                        <i class="fas fa-eye"></i>View Image
                                    </button>
                                <?php else: ?>
                                                                         <a href="<?= ROOT ?>view_application?action=viewDocument&id=<?= $application['id'] ?>&doc=2" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2" target="_blank">
                                         <i class="fas fa-eye"></i>View
                                     </a>
                                     <a href="<?= ROOT ?>view_application?action=downloadDocument&id=<?= $application['id'] ?>&doc=2" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center justify-center gap-2">
                                         <i class="fas fa-download"></i>Download
                                     </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">No supporting documents attached</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 max-w-md w-full">
        <div class="flex items-center mb-4">
            <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
            <h3 class="text-xl font-bold text-[#a31d1d]">Approve Application</h3>
        </div>
        <form method="POST">
            <p class="text-gray-700 mb-4">Are you sure you want to approve this application?</p>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Remarks (Optional)</label>
                <textarea name="remarks" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                          placeholder="Add any additional remarks..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('approveModal')" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                    Cancel
                </button>
                <button type="submit" name="status" value="1" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                    <i class="fas fa-check mr-2"></i>Approve
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 max-w-md w-full">
        <div class="flex items-center mb-4">
            <i class="fas fa-times-circle text-red-600 text-2xl mr-3"></i>
            <h3 class="text-xl font-bold text-[#a31d1d]">Reject Application</h3>
        </div>
        <form method="POST">
            <p class="text-gray-700 mb-4">Are you sure you want to reject this application?</p>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Remarks <span class="text-red-500">*</span></label>
                <textarea name="remarks" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                          placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('rejectModal')" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                    Cancel
                </button>
                <button type="submit" name="status" value="2" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0px_2px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>Reject
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Viewer Modal -->
<div id="imageViewerModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4">
    <div class="relative w-full h-full flex items-center justify-center">
        <!-- Close button -->
        <button onclick="closeImageViewer()" 
                class="absolute top-4 right-4 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-3 rounded-full transition-all duration-200 z-10">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <!-- Image container -->
        <div class="max-w-full max-h-full flex items-center justify-center">
            <img id="fullscreenImage" src="" alt="Document Image" 
                 class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        </div>
        
        <!-- Loading spinner -->
        <div id="imageLoading" class="hidden">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                <span class="ml-3 text-white text-lg">Loading image...</span>
            </div>
        </div>
    </div>
</div>

<script>
    function openApproveModal() {
        document.getElementById('approveModal').classList.remove('hidden');
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function viewImage(applicationId, documentNumber, mimeType) {
        // Show loading spinner
        document.getElementById('imageLoading').classList.remove('hidden');
        document.getElementById('fullscreenImage').classList.add('hidden');
        document.getElementById('imageViewerModal').classList.remove('hidden');

        // Fetch image data
        fetch(`<?= ROOT ?>view_application?action=viewDocument&id=${applicationId}&doc=${documentNumber}`)
            .then(response => response.json())
            .then(data => {
                if (data.type === 'image') {
                    // Create base64 image source
                    const imageSrc = `data:${data.mime_type};base64,${data.data}`;
                    document.getElementById('fullscreenImage').src = imageSrc;
                    
                    // Hide loading and show image
                    document.getElementById('imageLoading').classList.add('hidden');
                    document.getElementById('fullscreenImage').classList.remove('hidden');
                } else {
                    // Handle non-image documents
                    window.open(`<?= ROOT ?>view_application?action=viewDocument&id=${applicationId}&doc=${documentNumber}`, '_blank');
                    closeImageViewer();
                }
            })
            .catch(error => {
                console.error('Error loading image:', error);
                alert('Error loading image. Please try again.');
                closeImageViewer();
            });
    }

    function closeImageViewer() {
        document.getElementById('imageViewerModal').classList.add('hidden');
        document.getElementById('imageLoading').classList.add('hidden');
        document.getElementById('fullscreenImage').classList.add('hidden');
        // Clear the image source
        document.getElementById('fullscreenImage').src = '';
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('fixed')) {
            if (event.target.id === 'imageViewerModal') {
                closeImageViewer();
            } else {
                event.target.classList.add('hidden');
            }
        }
    });

    // Close image viewer with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageViewer();
        }
    });
</script>
</body>
</html>