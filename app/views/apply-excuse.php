<?php
require_once '../app/core/config.php';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance System • Apply for Excuse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Check if SweetAlert is loaded
        window.addEventListener('load', function() {
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 failed to load!');
            } else {
                console.log('SweetAlert2 loaded successfully!');
            }
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Quill.js Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
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
        
        /* Quill.js Custom Styling */
        .ql-toolbar {
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            border-bottom: 1px solid #d1d5db !important;
            background-color: #f9fafb !important;
            border-radius: 8px 8px 0 0 !important;
        }
        
        .ql-container {
            border: none !important;
            border-radius: 0 0 8px 8px !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 14px !important;
        }
        
        .ql-editor {
            min-height: 150px !important;
            padding: 12px 15px !important;
        }
        
        .ql-editor.ql-blank::before {
            color: #9ca3af !important;
            font-style: italic !important;
            font-family: 'Poppins', sans-serif !important;
        }
        
        .ql-snow .ql-tooltip {
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }
        
        /* File upload styling */
        .file-upload-zone {
            transition: all 0.3s ease;
        }
        
        .file-upload-zone:hover {
            border-color: #a31d1d !important;
            background-color: #fef2f2 !important;
        }
        
        .file-upload-zone.drag-over {
            border-color: #a31d1d !important;
            background-color: #fef2f2 !important;
            transform: scale(1.02);
        }
        
        .file-preview {
            transition: all 0.3s ease;
        }
        
        .application-card {
            transition: all 0.3s ease;
        }
        
        .application-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-6xl mx-auto glass-card">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <i class="fas fa-file-medical text-[#a31d1d] text-3xl"></i>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Apply for Excuse</h1>
        </div>
        <!-- <div class="flex items-center space-x-4">
            <span class="text-gray-600">
                <i class="fas fa-user mr-2"></i>
                <?php echo htmlspecialchars($userData['username'] ?? 'Student'); ?>
            </span>
            <a href="<?php echo ROOT?>student" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-home"></i>
                Home
            </a>
        </div> -->
    </div>
</header>

<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Apply for Excuse Form -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-2xl p-8 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                <?php if (isset($selectedEvent) && $selectedEvent): ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-check text-blue-600 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-medium text-blue-800">Selected Event</h3>
                                <p class="text-sm text-blue-700 mt-1 font-semibold">
                                    <?php echo htmlspecialchars($selectedEvent['event_name']); ?>
                                    <span class="text-blue-600 ml-2">
                                        (<?php echo date('M d, Y', strtotime($selectedEvent['date_created'])); ?>)
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($existingApplication) && $existingApplication): ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">Notice</h3>
                                <p class="text-sm text-yellow-700 mt-1">You have already submitted an excuse application for this event.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form id="excuseForm" enctype="multipart/form-data" class="space-y-6">
                    <div>
                        <label for="atten_id" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-calendar-alt mr-2 text-[#a31d1d]"></i>
                            <?php if (isset($selectedEvent) && $selectedEvent): ?>
                                Event <span class="text-red-500">*</span>
                            <?php else: ?>
                                Select Event <span class="text-red-500">*</span>
                            <?php endif; ?>
                        </label>
                        <?php if (isset($selectedEvent) && $selectedEvent): ?>
                            <!-- Show selected event as a disabled input with option to change -->
                            <div class="relative">
                                <input type="text" 
                                       value="<?php echo htmlspecialchars($selectedEvent['event_name']); ?> (<?php echo date('M d, Y', strtotime($selectedEvent['date_created'])); ?>)" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" 
                                       readonly>
                                <button type="button" 
                                        onclick="toggleEventSelection()" 
                                        class="absolute right-2 top-2 text-[#a31d1d] hover:text-[#8a1818] transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <div id="eventSelectionDropdown" class="hidden mt-2">
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" 
                                        id="atten_id" name="atten_id" required>
                                    <option value="">Choose a different event...</option>
                                    <?php if (is_array($events)): ?>
                                        <?php foreach ($events as $event): ?>
                                            <option value="<?php echo htmlspecialchars($event['atten_id']); ?>" 
                                                    <?php echo ($selectedEventId == $event['atten_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($event['event_name']); ?> 
                                                (<?php echo date('M d, Y', strtotime($event['date_created'])); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <!-- Show regular dropdown when no event is pre-selected -->
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" 
                                    id="atten_id" name="atten_id" required <?php echo (isset($existingApplication) && $existingApplication) ? 'disabled' : ''; ?>>
                                <option value="">Choose an event...</option>
                                <?php if (is_array($events)): ?>
                                    <?php foreach ($events as $event): ?>
                                        <option value="<?php echo htmlspecialchars($event['atten_id']); ?>" 
                                                <?php echo ($selectedEventId == $event['atten_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($event['event_name']); ?> 
                                            (<?php echo date('M d, Y', strtotime($event['date_created'])); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php endif; ?>
                        
                        <?php if (isset($existingApplication) && $existingApplication): ?>
                            <input type="hidden" name="atten_id" value="<?php echo htmlspecialchars($selectedEventId); ?>">
                        <?php endif; ?>
                        
                        <?php if (isset($selectedEvent) && $selectedEvent): ?>
                            <div class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Click the edit icon to select a different event if needed.
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">
                            <i class="fas fa-edit mr-2 text-[#a31d1d]"></i>
                            Excuse Description <span class="text-red-500">*</span>
                        </label>
                        <div id="editor-container" class="border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-[#a31d1d]">
                            <div id="editor" style="height: 200px;"></div>
                        </div>
                        <input type="hidden" name="description" id="description">
                        <div class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Provide a detailed explanation for your absence including any relevant circumstances.
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-image text-[#a31d1d] mr-2"></i>
                            Supporting Images
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Document 1 -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-image mr-1"></i>Image 1 (Optional)
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="document1" class="file-upload-zone flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                            <p class="mb-2 text-sm text-gray-500">
                                                <span class="font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500">JPG, PNG, GIF up to 10MB</p>
                                        </div>
                                        <input id="document1" name="document1" type="file" class="hidden" accept=".jpg,.jpeg,.png,.gif" />
                                    </label>
                                </div>
                                <div id="filePreview1" class="file-preview mt-3 hidden"></div>
                            </div>

                            <!-- Document 2 -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-image mr-1"></i>Image 2 (Optional)
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="document2" class="file-upload-zone flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                            <p class="mb-2 text-sm text-gray-500">
                                                <span class="font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500">JPG, PNG, GIF up to 10MB</p>
                                        </div>
                                        <input id="document2" name="document2" type="file" class="hidden" accept=".jpg,.jpeg,.png,.gif" />
                                    </label>
                                </div>
                                <div id="filePreview2" class="file-preview mt-3 hidden"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6">
                        <a href="<?php echo ROOT?>student" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" 
                                class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2"
                                <?php echo (isset($existingApplication) && $existingApplication) ? 'disabled' : ''; ?>>
                            <i class="fas fa-paper-plane"></i>
                            <?php echo (isset($existingApplication) && $existingApplication) ? 'Application Already Submitted' : 'Submit Excuse Application'; ?>
                        </button>
                    </div>
                            </form>
                        </div>
                    </div>
                </div>

        <!-- My Applications -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-2xl p-6 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                <h3 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-list text-[#a31d1d]"></i>
                    My Applications
                </h3>
                
                <?php if (empty($studentApplications)): ?>
                    <div class="flex flex-col items-center justify-center py-12">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-lg text-gray-500 font-medium">No applications yet</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-96 overflow-y-auto hide-scrollbar">
                        <?php foreach ($studentApplications as $app): ?>
                            <div class="application-card bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 border border-gray-200 shadow hover:shadow-lg transition-shadow duration-200 flex flex-col h-full">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-base truncate max-w-[12rem]">
                                            <?php echo htmlspecialchars($app['event_name']); ?>
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            <?php echo date('M d, Y', strtotime($app['event_date'])); ?>
                                        </p>
                                    </div>
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    $statusIcon = '';
                                    switch ($app['application_status']) {
                                        case 0:
                                            $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                            $statusText = 'Pending';
                                            $statusIcon = 'fas fa-clock';
                                            break;
                                        case 1:
                                            $statusClass = 'bg-green-100 text-green-800 border-green-300';
                                            $statusText = 'Approved';
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 2:
                                            $statusClass = 'bg-red-100 text-red-800 border-red-300';
                                            $statusText = 'Rejected';
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border <?php echo $statusClass; ?>">
                                        <i class="<?php echo $statusIcon; ?> mr-1"></i>
                                        <?php echo $statusText; ?>
                                    </span> 
                                </div>
                                <div class="flex-1">
                                    <?php if ($app['document1'] || $app['document2']): ?>
                                        <div class="flex gap-2 mt-2">
                                            <?php if ($app['document1']): ?>
                                                <a href="?action=viewDocument&id=<?php echo $app['id']; ?>&doc=1" 
                                                   class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                                    <i class="fas fa-file-image mr-1 text-[#a31d1d]"></i>Doc 1
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($app['document2']): ?>
                                                <a href="?action=viewDocument&id=<?php echo $app['id']; ?>&doc=2" 
                                                   class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                                    <i class="fas fa-file-image mr-1 text-[#a31d1d]"></i>Doc 2
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Quill.js Rich Text Editor
        let quill;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Show notice popup on page load
            showNoticePopup();
            
            // Initialize Quill editor
            quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Please provide a detailed explanation for your excuse...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link'],
                        ['clean']
                    ]
                },
                formats: [
                    'header', 'bold', 'italic', 'underline', 'color', 'background',
                    'list', 'bullet', 'align', 'link'
                ]
            });
            
            // Update hidden textarea when editor content changes
            quill.on('text-change', function() {
                const content = quill.root.innerHTML;
                document.getElementById('description').value = content;
            });

            // Setup file uploads
            setupFileUpload('document1', 'filePreview1');
            setupFileUpload('document2', 'filePreview2');

            // Auto-save on content change
            quill.on('text-change', autoSaveDraft);
            const attenSelect = document.getElementById('atten_id');
            if (attenSelect) {
                attenSelect.addEventListener('change', autoSaveDraft);
            }

            // Handle event selection change
            if (attenSelect) {
                attenSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    if (selectedValue && selectedValue !== '<?php echo $selectedEventId; ?>') {
                        // If a different event is selected, redirect to that event's excuse page
                        window.location.href = '<?php echo ROOT?>apply_excuse?id=' + selectedValue;
                    }
                });
            }
        });

        // File upload handling
        function setupFileUpload(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const dropZone = input.closest('label');

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    showFilePreview(file, preview, input);
                }
            });

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            dropZone.addEventListener('drop', handleDrop, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                dropZone.classList.add('drag-over');
            }

            function unhighlight(e) {
                dropZone.classList.remove('drag-over');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    input.files = files;
                    showFilePreview(files[0], preview, input);
                }
            }
        }

        function showFilePreview(file, previewElement, input) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    title: 'Invalid File Type',
                    text: 'Please upload a valid image file (JPG, PNG, or GIF).',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = '';
                return;
            }
            
            // Validate file size (10MB)
            const maxSize = 10 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    title: 'File Too Large',
                    text: 'Please upload a file smaller than 10MB.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = '';
                return;
            }

            previewElement.innerHTML = `
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-image text-blue-500 text-lg"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${file.name}</p>
                                <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</p>
                            </div>
                        </div>
                        <button type="button" onclick="removeFile('${input.id}')" 
                                class="text-red-500 hover:text-red-700 transition-colors duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            previewElement.classList.remove('hidden');
        }

        function removeFile(inputId) {
            const input = document.getElementById(inputId);
            const preview = input.closest('div').nextElementSibling;
            input.value = '';
            preview.classList.add('hidden');
        }

        // Form submission
        document.getElementById('excuseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check if form is disabled (existing application)
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn.disabled) {
                Swal.fire({
                    title: 'Already Submitted',
                    text: 'You have already submitted an application for this event',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Get content from Quill editor
            const description = quill.root.innerHTML;
            document.getElementById('description').value = description;
            
            // Validate form
            const attenId = document.getElementById('atten_id').value;
            if (!attenId) {
                Swal.fire({
                    title: 'Event Required',
                    text: 'Please select an event',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            if (quill.getText().trim().length < 10) {
                Swal.fire({
                    title: 'Description Required',
                    text: 'Please provide a detailed description (at least 10 characters)',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                quill.focus();
                return;
            }
            
            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
            submitBtn.disabled = true;
            
            // Create FormData object
            const formData = new FormData(this);
            
            // Submit form via AJAX
            fetch('?action=submit', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data); // Debug log
                if (data.success) {
                    console.log('Showing success popup...'); // Debug log
                    // Simple popup first to test
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your excuse application has been submitted successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reset form
                        document.getElementById('excuseForm').reset();
                        quill.setText('');
                        document.getElementById('filePreview1').classList.add('hidden');
                        document.getElementById('filePreview2').classList.add('hidden');
                        // Clear draft
                        clearDraft();
                        // Reload page to show updated applications
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '❌ Submission Failed',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'Try Again',
                        confirmButtonColor: '#a31d1d',
                        customClass: {
                            popup: 'rounded-2xl',
                            title: 'text-xl font-bold',
                            content: 'text-gray-600'
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // Auto-save draft functionality
        let autoSaveTimer;
        function autoSaveDraft() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                const description = quill.root.innerHTML;
                const attenId = document.getElementById('atten_id').value;
                
                if (description && attenId) {
                    localStorage.setItem('excuse_draft_description', description);
                    localStorage.setItem('excuse_draft_event', attenId);
                }
            }, 2000); // Save after 2 seconds of inactivity
        }

        // Load draft on page load
        window.addEventListener('load', function() {
            const savedDescription = localStorage.getItem('excuse_draft_description');
            const savedEvent = localStorage.getItem('excuse_draft_event');
            const selectedEventId = '<?php echo $selectedEventId; ?>';
            
            if (savedDescription && !selectedEventId) {
                quill.root.innerHTML = savedDescription;
            }
            
            if (savedEvent && !selectedEventId) {
                document.getElementById('atten_id').value = savedEvent;
            }
            
            // If there's a pre-selected event, clear any saved draft for other events
            if (selectedEventId) {
                const currentSavedEvent = localStorage.getItem('excuse_draft_event');
                if (currentSavedEvent && currentSavedEvent !== selectedEventId) {
                    localStorage.removeItem('excuse_draft_description');
                    localStorage.removeItem('excuse_draft_event');
                }
            }
        });

        // Clear draft when form is successfully submitted
        function clearDraft() {
            localStorage.removeItem('excuse_draft_description');
            localStorage.removeItem('excuse_draft_event');
        }

        // Toggle event selection dropdown
        function toggleEventSelection() {
            const dropdown = document.getElementById('eventSelectionDropdown');
            const isHidden = dropdown.classList.contains('hidden');
            
            if (isHidden) {
                dropdown.classList.remove('hidden');
                dropdown.querySelector('select').focus();
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Show notice popup function
        function showNoticePopup() {
            Swal.fire({
                title: '<div class="flex items-center justify-center"><i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mr-3"></i><span class="text-xl font-bold text-gray-800">Important Notice</span></div>',
                html: `
                    <div class="text-left space-y-4">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">One-Time Submission Policy</h3>
                                    <ul class="text-sm text-yellow-700 space-y-2">
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle text-yellow-600 mt-1 mr-2 text-xs"></i>
                                            <span>You can only submit <strong>ONE</strong> excuse application per event</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle text-yellow-600 mt-1 mr-2 text-xs"></i>
                                            <span><strong>No re-submission</strong> is allowed once submitted</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle text-yellow-600 mt-1 mr-2 text-xs"></i>
                                            <span>Please ensure all information is <strong>accurate and complete</strong></span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle text-yellow-600 mt-1 mr-2 text-xs"></i>
                                            <span>Only submit applications for <strong>valid and legitimate</strong> excuses</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-lightbulb text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Before You Submit</h3>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• Double-check all information for accuracy</li>
                                        <li>• Ensure supporting documents are clear and relevant</li>
                                        <li>• Make sure your excuse is legitimate and well-documented</li>
                                        <li>• Review your application thoroughly before submission</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-red-800 mb-2">Important Reminder</h3>
                                    <p class="text-sm text-red-700">
                                        This feature is designed to help students with legitimate absences. 
                                        <strong>Misuse of this system may result in disciplinary action.</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                `,
                icon: 'warning',
                confirmButtonText: '<i class="fas fa-check mr-2"></i>I Understand',
                confirmButtonColor: '#a31d1d',
                showCancelButton: true,
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                cancelButtonColor: '#6b7280',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'rounded-2xl',
                    title: 'text-xl font-bold',
                    content: 'text-gray-600',
                    confirmButton: 'rounded-xl font-semibold',
                    cancelButton: 'rounded-xl font-semibold'
                },
                width: '600px'
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    // If user clicks cancel, redirect back to student home
                    window.location.href = '<?php echo ROOT?>student';
                }
            });
        }


    </script>
</body>
</html>