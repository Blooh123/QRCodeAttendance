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
        
        /* Optimized CSS with reduced redundancy */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            background-image: radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0);
            background-size: 24px 24px;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            inset: 0;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 15px;
            width: 95%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Virtual scrolling container */
        .virtual-scroll-container {
            height: 70vh;
            overflow-y: auto;
            position: relative;
        }
        
        .virtual-scroll-content {
            position: relative;
        }
        
        .virtual-scroll-item {
            position: absolute;
            width: 100%;
            left: 0;
        }
        
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Optimized button styles */
        .btn-primary {
            @apply bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200;
        }
        
        .btn-secondary {
            @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200;
        }
        
        .btn-success {
            @apply bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200;
        }
        
        .btn-danger {
            @apply bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200;
        }
        
        /* Status badges */
        .status-pending { @apply bg-yellow-100 text-yellow-800; }
        .status-approved { @apply bg-green-100 text-green-800; }
        .status-rejected { @apply bg-red-100 text-red-800; }
        
        /* Image placeholder */
        .image-placeholder {
            @apply w-full h-48 md:h-64 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:bg-gray-200 transition-colors duration-200;
        }
    </style>
</head>
<body class="p-4 md:p-6">

<!-- Header -->
<header class="glass-card rounded-2xl p-4 md:p-6 mb-6 md:mb-8 max-w-7xl mx-auto shadow-md">
    <div class="flex items-center space-x-3">
        <i class="fas fa-file-medical text-[#a31d1d] text-2xl md:text-3xl"></i>
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Student Excuse Applications</h1>
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
    <div class="glass-card rounded-2xl p-4 md:p-6 mb-6 md:mb-8 shadow-md">
        <!-- Search Bar with Debouncing -->
        <div class="mb-4">
            <div class="flex gap-2">
                <input type="text" id="searchInput" 
                       placeholder="Search by student name, event, or program..."
                       value="<?php echo htmlspecialchars($searchQuery ?? ''); ?>"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                <button id="searchBtn" class="btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <!-- Filter and Stats Row -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Status Filter -->
            <div class="flex flex-wrap gap-2">
                <button type="button" data-filter="all" 
                        class="filter-btn btn-secondary <?php echo ($currentFilter === 'all') ? 'bg-[#a31d1d] text-white' : ''; ?>">
                    <i class="fas fa-list mr-1"></i> All
                </button>
                <button type="button" data-filter="0" 
                        class="filter-btn btn-secondary <?php echo ($currentFilter === '0') ? 'bg-yellow-500 text-white' : ''; ?>">
                    <i class="fas fa-clock mr-1"></i> Pending
                </button>
                <button type="button" data-filter="1" 
                        class="filter-btn btn-secondary <?php echo ($currentFilter === '1') ? 'bg-green-600 text-white' : ''; ?>">
                    <i class="fas fa-check mr-1"></i> Approved
                </button>
                <button type="button" data-filter="2" 
                        class="filter-btn btn-secondary <?php echo ($currentFilter === '2') ? 'bg-red-600 text-white' : ''; ?>">
                    <i class="fas fa-times mr-1"></i> Rejected
                </button>
            </div>
            
            <!-- Stats -->
            <div class="flex flex-wrap gap-2 md:gap-4 text-sm">
                <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium">
                    <i class="fas fa-clock mr-1"></i>
                    <span id="pendingCount"><?php echo $pendingCount ?? 0; ?></span> Pending
                </div>
                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">
                    <i class="fas fa-check mr-1"></i>
                    <span id="approvedCount"><?php echo $approvedCount ?? 0; ?></span> Approved
                </div>
                <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-medium">
                    <i class="fas fa-times mr-1"></i>
                    <span id="rejectedCount"><?php echo $rejectedCount ?? 0; ?></span> Rejected
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="hidden text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#a31d1d]"></div>
        <p class="mt-2 text-gray-600">Loading applications...</p>
    </div>

    <!-- Applications Container with Virtual Scrolling -->
    <div id="applicationsContainer" class="virtual-scroll-container">
        <div id="virtualScrollContent" class="virtual-scroll-content">
            <!-- Applications will be rendered here -->
        </div>
    </div>

    <!-- Pagination Controls -->
    <div id="paginationContainer" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Page Size Selector -->
        <div class="flex items-center space-x-2">
            <label for="pageSize" class="text-sm text-gray-600">Show:</label>
            <select id="pageSize" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                <option value="10">10 per page</option>
                <option value="20">20 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
        
        <!-- Navigation Controls -->
        <div class="flex items-center space-x-2">
            <button id="prevPage" class="btn-secondary disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-chevron-left"></i> Previous
            </button>
            <div id="pageInfo" class="px-4 py-2 text-sm text-gray-600">
                Page <span id="currentPage">1</span> of <span id="totalPages">1</span>
            </div>
            <button id="nextPage" class="btn-secondary disabled:opacity-50 disabled:cursor-not-allowed">
                Next <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Results Info -->
        <div class="text-sm text-gray-600">
            Showing <span id="showingStart">1</span> to <span id="showingEnd">10</span> of <span id="totalResults">0</span> applications
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800"></h3>
            <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="flex justify-center">
            <img id="modalImage" src="" alt="Full size image" class="max-w-full h-auto max-h-96 rounded-lg">
        </div>
    </div>
</div>

<!-- Hidden data container for caching -->
<div id="applicationsData" style="display: none;">
    <?php if (!empty($applications)): ?>
        <?php foreach ($applications as $app): ?>
            <div class="application-data" 
                 data-id="<?php echo $app['id']; ?>"
                 data-status="<?php echo $app['application_status']; ?>"
                 data-event="<?php echo htmlspecialchars($app['event_name']); ?>"
                 data-student="<?php echo htmlspecialchars($app['name']); ?>"
                 data-program="<?php echo htmlspecialchars($app['program']); ?>"
                 data-date="<?php echo $app['event_date']; ?>"
                 data-submitted="<?php echo $app['date_submitted']; ?>"
                 data-description="<?php echo htmlspecialchars($app['application_description']); ?>"
                 data-remarks="<?php echo htmlspecialchars($app['admin_remarks'] ?? ''); ?>"
                 data-document1="<?php echo $app['document1'] ? base64_encode($app['document1']) : ''; ?>"
                 data-document2="<?php echo $app['document2'] ? base64_encode($app['document2']) : ''; ?>">
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
// Performance optimization variables
let applicationsCache = new Map();
let currentPage = 1;
let itemsPerPage = 10;
let currentFilter = '<?php echo $currentFilter ?? "all"; ?>';
let allApplications = [];
let filteredApplications = [];
let searchTimeout = null;
let observer = null;
let isVirtualScrolling = false;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApplication();
    setupEventListeners();
    loadApplicationsFromCache();
});

// Initialize application
function initializeApplication() {
    // Load applications from hidden data container
    const dataContainer = document.getElementById('applicationsData');
    const applicationElements = dataContainer.querySelectorAll('.application-data');
    
    allApplications = Array.from(applicationElements).map(el => ({
        id: el.dataset.id,
        status: el.dataset.status,
        event: el.dataset.event,
        student: el.dataset.student,
        program: el.dataset.program,
        date: el.dataset.date,
        submitted: el.dataset.submitted,
        description: el.dataset.description,
        remarks: el.dataset.remarks,
        document1: el.dataset.document1,
        document2: el.dataset.document2
    }));
    
    // Apply initial filter
    applyFilter(currentFilter);
}

// Setup event listeners with debouncing
function setupEventListeners() {
    // Debounced search
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(this.value);
        }, 300);
    });
    
    // Search button
    document.getElementById('searchBtn').addEventListener('click', function() {
        performSearch(searchInput.value);
    });
    
    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filterValue = this.dataset.filter;
            setActiveFilter(filterValue);
            applyFilter(filterValue);
        });
    });
    
    // Pagination
    document.getElementById('prevPage').addEventListener('click', previousPage);
    document.getElementById('nextPage').addEventListener('click', nextPage);
    
    // Page size selector
    document.getElementById('pageSize').addEventListener('change', function() {
        itemsPerPage = parseInt(this.value);
        currentPage = 1;
        applyFilter(currentFilter);
    });
}

// Perform search with optimization
function performSearch(query) {
    if (query.trim() === '') {
        applyFilter(currentFilter);
        return;
    }
    
    const searchTerm = query.toLowerCase();
    filteredApplications = allApplications.filter(app => 
        app.student.toLowerCase().includes(searchTerm) ||
        app.event.toLowerCase().includes(searchTerm) ||
        app.program.toLowerCase().includes(searchTerm)
    );
    
    currentPage = 1;
    renderApplications();
}

// Set active filter button
function setActiveFilter(filterValue) {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-[#a31d1d]', 'bg-yellow-500', 'bg-green-600', 'bg-red-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    const activeBtn = document.querySelector(`[data-filter="${filterValue}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-200', 'text-gray-700');
        if (filterValue === 'all') {
            activeBtn.classList.add('bg-[#a31d1d]', 'text-white');
        } else if (filterValue === '0') {
            activeBtn.classList.add('bg-yellow-500', 'text-white');
        } else if (filterValue === '1') {
            activeBtn.classList.add('bg-green-600', 'text-white');
        } else if (filterValue === '2') {
            activeBtn.classList.add('bg-red-600', 'text-white');
        }
    }
    
    currentFilter = filterValue;
}

// Apply filter with optimization
function applyFilter(filterValue) {
    if (filterValue === 'all') {
        filteredApplications = [...allApplications];
    } else {
        filteredApplications = allApplications.filter(app => app.status === filterValue);
    }
    
    currentPage = 1;
    renderApplications();
}

// Render applications with virtual scrolling for large datasets
function renderApplications() {
    const container = document.getElementById('virtualScrollContent');
    const totalPages = Math.ceil(filteredApplications.length / itemsPerPage);
    
    if (filteredApplications.length === 0) {
        container.innerHTML = `
            <div class="glass-card rounded-2xl p-8 text-center">
                <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Applications Found</h3>
                <p class="text-gray-500">No applications match your current filter.</p>
            </div>
        `;
        updatePagination(0, 0, 0);
        return;
    }
    
    // Use virtual scrolling for large datasets
    if (filteredApplications.length > 100) {
        isVirtualScrolling = true;
        setupVirtualScrolling();
    } else {
        isVirtualScrolling = false;
        renderNormalPagination();
    }
}

// Setup virtual scrolling
function setupVirtualScrolling() {
    const container = document.getElementById('virtualScrollContent');
    const itemHeight = 400; // Approximate height of each application card
    const visibleItems = Math.ceil(container.clientHeight / itemHeight);
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, filteredApplications.length);
    
    // Clear container
    container.innerHTML = '';
    
    // Create document fragment for better performance
    const fragment = document.createDocumentFragment();
    
    for (let i = startIndex; i < endIndex; i++) {
        const app = filteredApplications[i];
        const appElement = createApplicationElement(app);
        appElement.style.top = `${(i - startIndex) * itemHeight}px`;
        appElement.classList.add('virtual-scroll-item');
        fragment.appendChild(appElement);
    }
    
    container.appendChild(fragment);
    container.style.height = `${(endIndex - startIndex) * itemHeight}px`;
    
    updatePagination(startIndex + 1, endIndex, filteredApplications.length);
}

// Render normal pagination
function renderNormalPagination() {
    const container = document.getElementById('virtualScrollContent');
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, filteredApplications.length);
    
    // Clear container
    container.innerHTML = '';
    
    // Create document fragment for better performance
    const fragment = document.createDocumentFragment();
    
    for (let i = startIndex; i < endIndex; i++) {
        const app = filteredApplications[i];
        const appElement = createApplicationElement(app);
        fragment.appendChild(appElement);
    }
    
    container.appendChild(fragment);
    updatePagination(startIndex + 1, endIndex, filteredApplications.length);
}

// Create application element with optimized rendering
function createApplicationElement(app) {
    const div = document.createElement('div');
    div.className = 'application-card glass-card rounded-2xl p-6 shadow-md hover-card mb-6';
    div.dataset.id = app.id;
    div.dataset.status = app.status;
    
    const statusInfo = getStatusInfo(app.status);
    const date = new Date(app.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    const submittedDate = new Date(app.submitted).toLocaleString('en-US', { 
        month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' 
    });
    
    div.innerHTML = `
        <!-- Header with Status -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h3 class="text-lg md:text-xl font-bold text-[#a31d1d]">${app.event}</h3>
                <p class="text-gray-600 text-sm md:text-base">${date}</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusInfo.class}">
                    <i class="${statusInfo.icon} mr-1"></i>
                    ${statusInfo.text}
                </span>
            </div>
        </div>

        <!-- Student Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-4">
            <div>
                <h4 class="font-semibold text-gray-800 mb-2 text-sm md:text-base">Student Information</h4>
                <div class="space-y-1 text-xs md:text-sm">
                    <p><strong>Name:</strong> ${app.student}</p>
                    <p><strong>Program:</strong> ${app.program}</p>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-gray-800 mb-2 text-sm md:text-base">Application Details</h4>
                <div class="space-y-1 text-xs md:text-sm">
                    <p><strong>Submitted:</strong> ${submittedDate}</p>
                    <p><strong>Application ID:</strong> #${app.id}</p>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <h4 class="font-semibold text-gray-800 mb-2 text-sm md:text-base">Excuse Description</h4>
            <div class="bg-gray-50 rounded-lg p-4 text-xs md:text-sm">
                ${app.description}
            </div>
        </div>

        <!-- Supporting Images -->
        ${app.document1 || app.document2 ? `
            <div class="mb-4">
                <h4 class="font-semibold text-gray-800 mb-2 flex items-center text-sm md:text-base">
                    <i class="fas fa-image text-[#a31d1d] mr-2"></i>
                    Supporting Images
                </h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    ${app.document1 ? createImageSection(app.document1, 'Image 1', app.id, 1) : ''}
                    ${app.document2 ? createImageSection(app.document2, 'Image 2', app.id, 2) : ''}
                </div>
            </div>
        ` : ''}

        <!-- Admin Remarks -->
        ${app.remarks ? `
            <div class="mb-4">
                <h4 class="font-semibold text-gray-800 mb-2 text-sm md:text-base">
                    <i class="fas fa-comment mr-1"></i>Admin Remarks
                </h4>
                <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4 text-xs md:text-sm">
                    ${app.remarks}
                </div>
            </div>
        ` : ''}

        <!-- Action Buttons -->
        ${app.status == 0 ? createActionButtons(app.id) : `
            <div class="pt-4 border-t border-gray-200">
                <p class="text-xs md:text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    This application has been ${statusInfo.text.toLowerCase()}.
                </p>
            </div>
        `}
    `;
    
    return div;
}

// Create image section with lazy loading
function createImageSection(base64Data, title, appId, imageNum) {
    return `
        <div class="bg-gray-50 rounded-lg p-4 border">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                <h5 class="font-medium text-gray-800 text-sm md:text-base">${title}</h5>
                <button onclick="downloadImage('${base64Data}', '${title.toLowerCase().replace(' ', '')}.jpg')" 
                        class="btn-success text-xs md:text-sm flex items-center gap-1 self-start sm:self-auto">
                    <i class="fas fa-download"></i> Download
                </button>
            </div>
            <div class="flex justify-center">
                <div id="image${imageNum}-container-${appId}" 
                     class="image-placeholder"
                     onclick="loadAndViewImage('${base64Data}', '${title}', 'image${imageNum}-container-${appId}')">
                    <div class="text-center">
                        <i class="fas fa-eye text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600 font-medium">Click to view image</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Create action buttons
function createActionButtons(appId) {
    return `
        <div class="flex flex-col lg:flex-row gap-3 pt-4 border-t border-gray-200">
            <form method="POST" action="" class="flex-1" onsubmit="return confirmApprove()">
                <input type="hidden" name="application_id" value="${appId}">
                <input type="hidden" name="status" value="1">
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="remarks" placeholder="Optional remarks..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <button type="submit" class="btn-success text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </div>
            </form>
            
            <form method="POST" action="" class="flex-1" onsubmit="return confirmReject()">
                <input type="hidden" name="application_id" value="${appId}">
                <input type="hidden" name="status" value="2">
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="remarks" placeholder="Reason for rejection..." required
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <button type="submit" class="btn-danger text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    `;
}

// Get status information
function getStatusInfo(status) {
    switch (status) {
        case '0':
            return { class: 'status-pending', text: 'Pending', icon: 'fas fa-clock' };
        case '1':
            return { class: 'status-approved', text: 'Approved', icon: 'fas fa-check-circle' };
        case '2':
            return { class: 'status-rejected', text: 'Rejected', icon: 'fas fa-times-circle' };
        default:
            return { class: 'status-pending', text: 'Pending', icon: 'fas fa-clock' };
    }
}

// Update pagination controls
function updatePagination(start, end, total) {
    const totalPages = Math.ceil(total / itemsPerPage);
    
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalPages').textContent = totalPages;
    document.getElementById('showingStart').textContent = start;
    document.getElementById('showingEnd').textContent = end;
    document.getElementById('totalResults').textContent = total;
    
    // Update navigation buttons
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
    
    // Show/hide pagination container
    document.getElementById('paginationContainer').style.display = totalPages <= 1 ? 'none' : 'flex';
}

// Navigation functions
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        renderApplications();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredApplications.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderApplications();
    }
}

// Load applications from cache
function loadApplicationsFromCache() {
    const cached = sessionStorage.getItem('applicationsCache');
    if (cached) {
        try {
            const data = JSON.parse(cached);
            if (data.timestamp > Date.now() - 300000) { // 5 minutes cache
                allApplications = data.applications;
                applyFilter(currentFilter);
                return;
            }
        } catch (e) {
            console.warn('Failed to load cached applications');
        }
    }
    
    // If no cache or expired, render from server data
    renderApplications();
}

// Cache applications
function cacheApplications() {
    const cacheData = {
        applications: allApplications,
        timestamp: Date.now()
    };
    sessionStorage.setItem('applicationsCache', JSON.stringify(cacheData));
}

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

// Image handling functions with optimization
function loadAndViewImage(base64Data, title, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    // Create image element
    const img = document.createElement('img');
    img.src = 'data:image/jpeg;base64,' + base64Data;
    img.alt = title;
    img.className = 'max-w-full h-auto max-h-48 md:max-h-64 rounded-lg shadow-md cursor-pointer hover:scale-105 transition-transform duration-200';
    img.onclick = function() {
        openImageModal('data:image/jpeg;base64,' + base64Data, title);
    };
    
    // Replace container content with image
    container.innerHTML = '';
    container.appendChild(img);
}

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
    } catch (error) {
        console.error('Error downloading image:', error);
        alert('Error downloading image. Please try again.');
    }
}

// Cache applications when page is about to unload
window.addEventListener('beforeunload', function() {
    cacheApplications();
});
</script>
</body>
</html>