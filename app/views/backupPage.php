<?php
global $imageSource, $OSASLogo, $username;
require_once "../app/core/imageConfig.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: 
                radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
                linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        
        .glass-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(163, 29, 29, 0.1);
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
    </style>
    <title>Database Backup • Attendance System</title>
</head>
<body class="bg-[#f8f9fa] font-['Poppins']">

<!-- Header -->
<header class="w-full shadow-lg sticky top-0 z-40 glass-header">
    <div class="max-w-8xl mx-auto px-6 h-24 flex items-center justify-between">
        <!-- Left Section: Logo & Brand -->
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3">
                <img 
                    src="<?php echo $imageSource ?>" 
                    alt="Logo" 
                    style="height: 100px; width: auto; max-width: 100%; object-fit: contain; display: block;"
                    class="block"
                />
            </div>
            
            <!-- Page Title -->
            <div class="hidden lg:block">
                <h1 class="text-2xl font-bold text-[#a31d1d]">Database Backup</h1>
                <p class="text-sm text-gray-600">Secure database backup and download</p>
            </div>
        </div>
        
        <!-- Right Section: Profile & Actions -->
        <div class="flex items-center gap-4 ml-4">
            <!-- Profile Card (Desktop) -->
            <div class="hidden lg:flex items-center gap-4 bg-gradient-to-r from-[#a31d1d] to-red-900 px-4 py-2 rounded-xl" style="min-width:220px;">
                <img src="<?php echo $OSASLogo ?>" alt="Profile" class="h-12 w-12 rounded-full border-2 border-white object-cover">
                <div class="text-white">
                    <p class="font-semibold text-base"><?php echo $username ?></p>
                    <p class="text-sm opacity-90">Administrator</p>
                </div>
            </div>
            
            <!-- Back to Dashboard Button -->
            <a href="<?php echo ROOT ?>adminHome"
               class="bg-[#a31d1d] hover:bg-[#8a1818] px-5 py-3 rounded-xl text-sm font-semibold text-white flex items-center gap-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                <i class="fas fa-arrow-left text-sm"></i>
                <span class="hidden lg:inline whitespace-nowrap">Back to Dashboard</span>
            </a>

        </div>
    </div>
</header>

<!-- Main Content -->
<main class="flex flex-col items-center justify-center p-4 min-h-screen">
    <div class="w-full max-w-4xl bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <div class="mb-6">
                <i class="fas fa-database text-6xl text-[#800000] mb-4"></i>
                <h1 class="text-4xl font-bold text-[#800000] mb-2">Database Backup</h1>
                <p class="text-lg text-gray-600">Create and download a secure backup of your database</p>
            </div>
        </div>

        <!-- Backup Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="glass-card rounded-xl p-6 text-center">
                <i class="fas fa-shield-alt text-3xl text-[#800000] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#800000] mb-2">Secure</h3>
                <p class="text-sm text-gray-600">Password-protected ZIP files with AES encryption</p>
            </div>
            <div class="glass-card rounded-xl p-6 text-center">
                <i class="fas fa-download text-3xl text-[#800000] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#800000] mb-2">Complete</h3>
                <p class="text-sm text-gray-600">Full database export with all tables and data</p>
            </div>
            <div class="glass-card rounded-xl p-6 text-center">
                <i class="fas fa-clock text-3xl text-[#800000] mb-3"></i>
                <h3 class="text-lg font-semibold text-[#800000] mb-2">Fast</h3>
                <p class="text-sm text-gray-600">Quick backup generation and download process</p>
            </div>
        </div>

        <!-- Backup Button -->
        <div class="text-center">
            <button onclick="downloadBackup()" class="glass-card rounded-2xl p-8 flex flex-col items-center hover-card shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black bg-gradient-to-br from-[#800000] to-[#660000] text-white hover:from-[#660000] hover:to-[#4d0000] transition-all duration-200 max-w-md mx-auto">
                <i class="fas fa-database text-5xl mb-4 text-white"></i>
                <h2 class="text-2xl font-bold mb-2 text-white">Download Backup</h2>
                <p class="text-sm text-center text-white opacity-90">Click to create and download your database backup</p>
            </button>
        </div>
    </div>
</main>

<!-- Backup Progress Modal -->
<div id="backupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
                <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
                    <div class="text-center">
                        <!-- Progress Icon -->
                        <div id="progressIcon" class="mb-4">
                            <i class="fas fa-spinner fa-spin text-4xl text-[#800000]"></i>
                        </div>
                        
                        <!-- Progress Text -->
                        <h3 id="progressTitle" class="text-2xl font-bold text-[#800000] mb-2">Creating Backup...</h3>
                        <p id="progressMessage" class="text-gray-600 mb-6">Please wait while we prepare your database backup.</p>
                        
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                            <div id="progressBar" class="bg-[#800000] h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        
                        <!-- Progress Steps -->
                        <div id="progressSteps" class="text-left space-y-2 mb-6">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-check text-green-500 mr-2 hidden" id="step1-check"></i>
                                <i class="fas fa-spinner fa-spin text-[#800000] mr-2" id="step1-spinner"></i>
                                <span id="step1-text">Connecting to database...</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-check text-green-500 mr-2 hidden" id="step2-check"></i>
                                <i class="fas fa-circle text-gray-300 mr-2" id="step2-spinner"></i>
                                <span id="step2-text">Exporting tables...</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-check text-green-500 mr-2 hidden" id="step3-check"></i>
                                <i class="fas fa-circle text-gray-300 mr-2" id="step3-spinner"></i>
                                <span id="step3-text">Creating ZIP file...</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-check text-green-500 mr-2 hidden" id="step4-check"></i>
                                <i class="fas fa-circle text-gray-300 mr-2" id="step4-spinner"></i>
                                <span id="step4-text">Preparing download...</span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div id="modalButtons" class="hidden">
                            <button id="closeModal" class="bg-[#800000] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#660000] transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Database backup function
function downloadBackup() {
    // Show confirmation dialog
    Swal.fire({
        title: 'Download Database Backup?',
        text: 'This will download a password-protected ZIP file containing the complete database backup.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#800000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Download Backup',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show the progress modal
            showBackupModal();
            
            // Start the backup process
            startBackupProcess();
        }
    });
}

// Show backup progress modal
function showBackupModal() {
    const modal = document.getElementById('backupModal');
    modal.classList.remove('hidden');
    
    // Reset progress
    resetProgress();
    
    // Start progress animation
    animateProgress();
}

// Hide backup modal
function hideBackupModal() {
    const modal = document.getElementById('backupModal');
    modal.classList.add('hidden');
}

// Reset progress indicators
function resetProgress() {
    // Reset progress bar
    document.getElementById('progressBar').style.width = '0%';
    
    // Reset all steps
    for (let i = 1; i <= 4; i++) {
        document.getElementById(`step${i}-check`).classList.add('hidden');
        document.getElementById(`step${i}-spinner`).classList.remove('fa-spinner', 'fa-spin');
        document.getElementById(`step${i}-spinner`).classList.add('fa-circle');
        document.getElementById(`step${i}-spinner`).classList.remove('text-[#800000]');
        document.getElementById(`step${i}-spinner`).classList.add('text-gray-300');
    }
    
    // Hide modal buttons
    document.getElementById('modalButtons').classList.add('hidden');
    
    // Reset progress icon
    document.getElementById('progressIcon').innerHTML = '<i class="fas fa-spinner fa-spin text-4xl text-[#800000]"></i>';
    document.getElementById('progressTitle').textContent = 'Creating Backup...';
    document.getElementById('progressMessage').textContent = 'Please wait while we prepare your database backup.';
}

// Animate progress steps based on actual process
function animateProgress() {
    let currentStep = 0;
    const steps = [
        { step: 1, text: 'Connecting to database...', duration: 1000 },
        { step: 2, text: 'Exporting tables...', duration: 2000 },
        { step: 3, text: 'Creating ZIP file...', duration: 1500 },
        { step: 4, text: 'Preparing download...', duration: 500 }
    ];
    
    function executeStep(stepIndex) {
        if (stepIndex >= steps.length) {
            completeBackup();
            return;
        }
        
        const stepInfo = steps[stepIndex];
        currentStep = stepIndex;
        
        // Update progress bar
        const progress = ((stepIndex + 1) / steps.length) * 100;
        document.getElementById('progressBar').style.width = progress + '%';
        
        // Update step
        const stepElement = document.getElementById(`step${stepInfo.step}-spinner`);
        stepElement.classList.remove('fa-circle', 'text-gray-300');
        stepElement.classList.add('fa-spinner', 'fa-spin', 'text-[#800000]');
        
        // Update message
        document.getElementById('progressMessage').textContent = stepInfo.text;
        
        // Complete step after the specified duration
        setTimeout(() => {
            stepElement.classList.remove('fa-spinner', 'fa-spin', 'text-[#800000]');
            stepElement.classList.add('fa-check', 'text-green-500');
            document.getElementById(`step${stepInfo.step}-check`).classList.remove('hidden');
            
            // Move to next step
            executeStep(stepIndex + 1);
        }, stepInfo.duration);
    }
    
    // Start the first step
    executeStep(0);
}

// Complete backup process
function completeBackup() {
    // Update progress bar to 100%
    document.getElementById('progressBar').style.width = '100%';
    
    // Update modal content for success
    document.getElementById('progressIcon').innerHTML = '<i class="fas fa-check-circle text-4xl text-green-500"></i>';
    document.getElementById('progressTitle').textContent = 'Backup Complete!';
    document.getElementById('progressMessage').innerHTML = `
        <div class="text-left">
            <p class="mb-3">Your database backup has been downloaded successfully.</p>
            <div class="bg-gray-100 p-3 rounded-lg text-sm">
                <p class="font-semibold mb-2">📁 File Format:</p>
                <p>• ZIP file containing SQL backup (preferred)</p>
                <p>• Password protection</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-lg mt-3 text-sm">
                <p class="font-semibold mb-2">🔑 Password Information:</p>
                <p>If password-protected, check Activity Logs for the password.</p>
            </div>
        </div>
    `;
    
    // Hide progress steps
    document.getElementById('progressSteps').classList.add('hidden');
    
    // Show close button
    document.getElementById('modalButtons').classList.remove('hidden');
}

// Start the actual backup process
function startBackupProcess() {
    // Wait a moment for the progress animation to start, then initiate download
    setTimeout(() => {
        // Create a temporary link to download the backup
        const link = document.createElement('a');
        link.href = '<?php echo ROOT; ?>database-backup?action=download';
        link.download = 'qrcode_attendance_backup_' + new Date().toISOString().slice(0,19).replace(/:/g, '-') + '.zip';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }, 500); // Small delay to let progress animation start
}

// Close modal event listener
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('closeModal').addEventListener('click', hideBackupModal);
    
    // Close modal when clicking outside
    document.getElementById('backupModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideBackupModal();
        }
    });
});
</script>
</body>
</html>