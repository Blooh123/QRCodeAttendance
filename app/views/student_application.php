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
    <div id="loadingIndicator" class="text-center py-8">
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

<!-- Application Details Modal -->
<div id="applicationDetailsModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="flex justify-between items-center mb-4">
            <h3 id="detailsModalTitle" class="text-xl font-semibold text-gray-800">Application Details</h3>
            <button onclick="closeApplicationDetailsModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="applicationDetailsContent">
            <!-- Application details will be loaded here -->
        </div>
    </div>
</div>

<!-- Approve/Reject Modal -->
<div id="actionModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="flex justify-between items-center mb-4">
            <h3 id="actionModalTitle" class="text-lg font-semibold text-gray-800">Application Action</h3>
            <button onclick="closeActionModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="actionModalContent">
            <!-- Action form will be loaded here -->
        </div>
    </div>
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
    // Check if we have applications data from PHP
    const applicationsData = <?php echo json_encode($applications ?? []); ?>;
    
    if (applicationsData && applicationsData.length > 0) {
        allApplications = applicationsData.map(app => ({
            id: app.id,
            status: app.application_status.toString(),
            event: app.event_name,
            student: app.name,
            program: app.program,
            date: app.event_date,
            submitted: app.date_submitted,
            description: app.application_description,
            remarks: app.admin_remarks || '',
            document1: app.document1 ? true : false,
            document2: app.document2 ? true : false
        }));
    } else {
        allApplications = [];
    }
    
    // Apply initial filter
    applyFilter(currentFilter);
    
    // Hide loading indicator
    document.getElementById('loadingIndicator').style.display = 'none';
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
    div.className = 'application-card glass-card rounded-2xl p-4 shadow-md hover-card mb-4';
    div.dataset.id = app.id;
    div.dataset.status = app.status;
    
    const statusInfo = getStatusInfo(app.status);
    const date = new Date(app.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    
    div.innerHTML = `
        <!-- Simplified Card Layout -->
        <div class="flex items-center justify-between">
            <!-- Student Info -->
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-[#a31d1d] rounded-full flex items-center justify-center text-white font-bold text-lg">
                        ${app.student.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">${app.student}</h3>
                        <p class="text-sm text-gray-600">${app.program} • ${app.event}</p>
                        <p class="text-xs text-gray-500">Event: ${date}</p>
                    </div>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusInfo.class}">
                    <i class="${statusInfo.icon} mr-1"></i>
                    ${statusInfo.text}
                </span>
                
                <!-- View Details Button -->
                <button onclick="viewApplicationDetails(${app.id})" 
                        class="btn-primary text-sm flex items-center gap-2 px-3 py-2">
                    <i class="fas fa-eye"></i>
                    View Details
                </button>
                
                <!-- Action Buttons (only for pending applications) -->
                ${app.status == 0 ? `
                    <div class="flex gap-2">
                        <button onclick="approveApplication(${app.id})" 
                                class="btn-success text-sm flex items-center gap-1 px-3 py-2">
                            <i class="fas fa-check"></i>
                            Approve
                        </button>
                        <button onclick="rejectApplication(${app.id})" 
                                class="btn-danger text-sm flex items-center gap-1 px-3 py-2">
                            <i class="fas fa-times"></i>
                            Reject
                        </button>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
    
    return div;
}

// Create image section with lazy loading
function createImageSection(hasDocument, title, appId, imageNum) {
    if (!hasDocument) return '';
    
    return `
        <div class="bg-gray-50 rounded-lg p-4 border">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                <h5 class="font-medium text-gray-800 text-sm md:text-base">${title}</h5>
                <button onclick="downloadImageFromServer(${appId}, ${imageNum}, '${title.toLowerCase().replace(' ', '')}.jpg')" 
                        class="btn-success text-xs md:text-sm flex items-center gap-1 self-start sm:self-auto">
                    <i class="fas fa-download"></i> Download
                </button>
            </div>
            <div class="flex justify-center">
                <div id="image${imageNum}-container-${appId}" 
                     class="image-placeholder"
                     onclick="loadImageFromServer(${appId}, ${imageNum}, '${title}', 'image${imageNum}-container-${appId}')">
                    <div class="text-center">
                        <i class="fas fa-eye text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600 font-medium">Click to view image</p>
                    </div>
                </div>
            </div>
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
    // The data is already loaded in initializeApplication()
}

// Cache applications
function cacheApplications() {
    const cacheData = {
        applications: allApplications,
        timestamp: Date.now()
    };
    sessionStorage.setItem('applicationsCache', JSON.stringify(cacheData));
}

// Application detail and action functions
function viewApplicationDetails(appId) {
    const app = allApplications.find(a => a.id == appId);
    if (!app) {
        alert('Application not found');
        return;
    }
    
    const statusInfo = getStatusInfo(app.status);
    const date = new Date(app.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    const submittedDate = new Date(app.submitted).toLocaleString('en-US', { 
        month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' 
    });
    
    const modal = document.getElementById('applicationDetailsModal');
    const content = document.getElementById('applicationDetailsContent');
    
    content.innerHTML = `
        <!-- Header with Status -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h3 class="text-xl font-bold text-[#a31d1d]">${app.event}</h3>
                <p class="text-gray-600">${date}</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusInfo.class}">
                    <i class="${statusInfo.icon} mr-1"></i>
                    ${statusInfo.text}
                </span>
            </div>
        </div>

        <!-- Student Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="font-semibold text-gray-800 mb-3">Student Information</h4>
                <div class="space-y-2">
                    <p><strong>Name:</strong> ${app.student}</p>
                    <p><strong>Program:</strong> ${app.program}</p>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-gray-800 mb-3">Application Details</h4>
                <div class="space-y-2">
                    <p><strong>Submitted:</strong> ${submittedDate}</p>
                    <p><strong>Application ID:</strong> #${app.id}</p>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <h4 class="font-semibold text-gray-800 mb-3">Excuse Description</h4>
            <div class="bg-gray-50 rounded-lg p-4">
                ${app.description}
            </div>
        </div>

        <!-- Supporting Images -->
        ${app.document1 || app.document2 ? `
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
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
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-3">
                    <i class="fas fa-comment mr-1"></i>Admin Remarks
                </h4>
                <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4">
                    ${app.remarks}
                </div>
            </div>
        ` : ''}

        <!-- Action Buttons -->
        ${app.status == 0 ? `
            <div class="flex flex-col lg:flex-row gap-3 pt-6 border-t border-gray-200">
                <button onclick="approveApplication(${app.id})" 
                        class="btn-success flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i> Approve Application
                </button>
                <button onclick="rejectApplication(${app.id})" 
                        class="btn-danger flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Reject Application
                </button>
            </div>
        ` : `
            <div class="pt-6 border-t border-gray-200">
                <p class="text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    This application has been ${statusInfo.text.toLowerCase()}.
                </p>
            </div>
        `}
    `;
    
    modal.style.display = 'block';
    
    // Close modal when clicking outside
    modal.onclick = function(event) {
        if (event.target === modal) {
            closeApplicationDetailsModal();
        }
    };
}

function closeApplicationDetailsModal() {
    const modal = document.getElementById('applicationDetailsModal');
    modal.style.display = 'none';
}

function approveApplication(appId) {
    const modal = document.getElementById('actionModal');
    const content = document.getElementById('actionModalContent');
    const title = document.getElementById('actionModalTitle');
    
    title.textContent = 'Approve Application';
    content.innerHTML = `
        <form id="approveForm" method="POST" action="">
            <input type="hidden" name="application_id" value="${appId}">
            <input type="hidden" name="status" value="1">
            
            <div class="mb-4">
                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Optional Remarks</label>
                <textarea name="remarks" id="remarks" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                          placeholder="Add any remarks about this approval..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="btn-success flex-1">
                    <i class="fas fa-check mr-2"></i>Confirm Approval
                </button>
                <button type="button" onclick="closeActionModal()" class="btn-secondary flex-1">
                    Cancel
                </button>
            </div>
        </form>
    `;
    
    modal.style.display = 'block';
    
    // Handle form submission
    document.getElementById('approveForm').addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to approve this excuse application?')) {
            e.preventDefault();
        }
    });
    
    // Close modal when clicking outside
    modal.onclick = function(event) {
        if (event.target === modal) {
            closeActionModal();
        }
    };
}

function rejectApplication(appId) {
    const modal = document.getElementById('actionModal');
    const content = document.getElementById('actionModalContent');
    const title = document.getElementById('actionModalTitle');
    
    title.textContent = 'Reject Application';
    content.innerHTML = `
        <form id="rejectForm" method="POST" action="">
            <input type="hidden" name="application_id" value="${appId}">
            <input type="hidden" name="status" value="2">
            
            <div class="mb-4">
                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection *</label>
                <textarea name="remarks" id="remarks" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                          placeholder="Please provide a reason for rejecting this application..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="btn-danger flex-1">
                    <i class="fas fa-times mr-2"></i>Confirm Rejection
                </button>
                <button type="button" onclick="closeActionModal()" class="btn-secondary flex-1">
                    Cancel
                </button>
            </div>
        </form>
    `;
    
    modal.style.display = 'block';
    
    // Handle form submission
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        const remarks = document.getElementById('remarks').value.trim();
        if (!remarks) {
            alert('Please provide a reason for rejection.');
            e.preventDefault();
            return;
        }
        if (!confirm('Are you sure you want to reject this excuse application?')) {
            e.preventDefault();
        }
    });
    
    // Close modal when clicking outside
    modal.onclick = function(event) {
        if (event.target === modal) {
            closeActionModal();
        }
    };
}

function closeActionModal() {
    const modal = document.getElementById('actionModal');
    modal.style.display = 'none';
}

// Form confirmation functions (kept for backward compatibility)
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
function loadImageFromServer(appId, imageNum, title, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    // Show loading state
    container.innerHTML = `
        <div class="flex items-center justify-center h-full">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#a31d1d]"></div>
        </div>
    `;
    
    // Fetch image from server
    fetch(`?action=viewDocument&id=${appId}&doc=${imageNum}`)
        .then(response => response.blob())
        .then(blob => {
            const url = URL.createObjectURL(blob);
            const img = document.createElement('img');
            img.src = url;
            img.alt = title;
            img.className = 'max-w-full h-auto max-h-48 md:max-h-64 rounded-lg shadow-md cursor-pointer hover:scale-105 transition-transform duration-200';
            img.onclick = function() {
                openImageModal(url, title);
            };
            
            // Replace container content with image
            container.innerHTML = '';
            container.appendChild(img);
        })
        .catch(error => {
            console.error('Error loading image:', error);
            container.innerHTML = `
                <div class="text-center text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Failed to load image</p>
                </div>
            `;
        });
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

function downloadImageFromServer(appId, imageNum, filename) {
    // Create download link
    const link = document.createElement('a');
    link.href = `?action=downloadDocument&id=${appId}&doc=${imageNum}`;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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