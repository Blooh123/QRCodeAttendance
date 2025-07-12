<?php
global $imageSource, $imageSource2, $imageSource4;
require "../app/core/imageConfig.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource ?>">
    <title>Attendance System • Welcome</title>
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
        
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .hover-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
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
            .glass-card {
                padding: 1rem !important;
            }
            
            .mobile-hero {
                height: 60vh !important;
                min-height: 400px !important;
            }
            
            .mobile-text {
                font-size: 1.5rem !important;
                line-height: 1.3 !important;
            }
            
            .mobile-subtitle {
                font-size: 1rem !important;
                line-height: 1.4 !important;
            }
            
            .mobile-btn {
                padding: 0.75rem 1.5rem !important;
                font-size: 1rem !important;
            }
            
            .mobile-card {
                padding: 1.5rem !important;
            }
            
            .mobile-icon {
                width: 3rem !important;
                height: 3rem !important;
            }
            
            .mobile-icon i {
                font-size: 1.25rem !important;
            }
            
            .mobile-header {
                padding: 1rem !important;
            }
            
            .mobile-header h1 {
                font-size: 1.25rem !important;
            }
            
            .mobile-header p {
                font-size: 0.875rem !important;
            }
            
            .mobile-logo {
                height: 3rem !important;
            }
            
            .mobile-footer {
                padding: 1rem !important;
                text-align: center !important;
            }
            
            .mobile-footer .flex {
                flex-direction: column !important;
                gap: 1rem !important;
            }
            
            .mobile-footer a {
                font-size: 0.875rem !important;
            }
        }
    </style>
</head>
<body class="p-4 md:p-6">

    <!-- Header -->
    <header class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 mb-8 max-w-7xl mx-auto mobile-header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Mobile: Centered Logo Only -->
            <div class="flex justify-center md:hidden">
                <img src="<?php echo $imageSource4 ?>" alt="Logo" class="h-16 w-auto floating mobile-logo">
            </div>
            
            <!-- Desktop: Logo with Text -->
            <div class="hidden md:flex items-center space-x-3">
                <img src="<?php echo $imageSource4 ?>" alt="Logo" class="h-12 w-auto floating">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-[#a31d1d] tracking-tight">USeP QR Code Attendance System</h1>
                    <p class="text-gray-600 font-medium">University of Southeastern Philippines Tagum-Mabini Campus</p>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto space-y-10">

        <!-- Main Welcome Section -->
        <div class="glass-card rounded-2xl overflow-hidden p-0">
            <div class="relative">
                <img src="<?php echo $imageSource2 ?>" alt="Illustration" class="w-full h-64 md:h-96 object-cover mobile-hero">
                <div class="absolute inset-0 bg-gradient-to-r from-[#a31d1d]/80 to-red-900/80 flex items-center justify-center">
                    <div class="text-center text-white p-4 md:p-8">
                        <h2 class="text-2xl md:text-5xl font-bold mb-4 text-shadow-lg mobile-text">
                            Welcome to QR Code Attendance System
                        </h2>
                        <p class="text-base md:text-xl mb-6 max-w-2xl mx-auto mobile-subtitle">
                            Experience seamless attendance tracking through secure QR code scanning technology.
                        </p>
                        <a href="<?php echo ROOT ?>login" 
                           class="event-btn px-6 md:px-8 py-3 md:py-4 text-lg md:text-xl font-bold transition-all duration-200 flex items-center gap-2 mx-auto w-fit mobile-btn">
                            <i class="fas fa-rocket"></i> Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-[#a31d1d] mb-6 flex items-center gap-2">
                <i class="fas fa-star"></i>
                Key Features
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="glass-card rounded-2xl p-4 md:p-6 hover-card mobile-card">
                    <div class="text-center">
                        <div class="bg-blue-100 p-3 md:p-4 rounded-full w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 md:mb-4 flex items-center justify-center mobile-icon">
                            <i class="fas fa-qrcode text-blue-600 text-xl md:text-2xl"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-[#a31d1d] mb-2">QR Code Scanning</h3>
                        <p class="text-sm md:text-base text-gray-600">Quick and efficient attendance tracking using modern QR technology with instant verification.</p>
                    </div>
                </div>
                
                <div class="glass-card rounded-2xl p-4 md:p-6 hover-card mobile-card">
                    <div class="text-center">
                        <div class="bg-green-100 p-3 md:p-4 rounded-full w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 md:mb-4 flex items-center justify-center mobile-icon">
                            <i class="fas fa-chart-line text-green-600 text-xl md:text-2xl"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-[#a31d1d] mb-2">Real-time Analytics</h3>
                        <p class="text-sm md:text-base text-gray-600">Comprehensive attendance data and insights with detailed reporting and statistics.</p>
                    </div>
                </div>
                
                <div class="glass-card rounded-2xl p-4 md:p-6 hover-card mobile-card">
                    <div class="text-center">
                        <div class="bg-purple-100 p-3 md:p-4 rounded-full w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 md:mb-4 flex items-center justify-center mobile-icon">
                            <i class="fas fa-cogs text-purple-600 text-xl md:text-2xl"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-[#a31d1d] mb-2">Automated Tracking</h3>
                        <p class="text-sm md:text-base text-gray-600">Streamlined attendance management for school activities with automated processing.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefits Section -->
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-[#a31d1d] mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                Why Choose Our System?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div class="glass-card rounded-2xl p-4 md:p-6 mobile-card">
                    <h3 class="text-lg md:text-xl font-bold text-[#a31d1d] mb-3 md:mb-4 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-green-600"></i>
                        Secure & Reliable
                    </h3>
                    <ul class="space-y-2 text-sm md:text-base text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            Facial Recognition Authentication
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            Real-time attendance verification
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            Geofencing Attendance
                        </li>
                    </ul>
                </div>
                
                <div class="glass-card rounded-2xl p-4 md:p-6 mobile-card">
                    <h3 class="text-lg md:text-xl font-bold text-[#a31d1d] mb-3 md:mb-4 flex items-center gap-2">
                        <i class="fas fa-clock text-blue-600"></i>
                        Time Efficient
                    </h3>
                    <ul class="space-y-2 text-sm md:text-base text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            Instant attendance recording
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            Automated report generation
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            Quick data access and retrieval
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="glass-card rounded-2xl p-6 md:p-8 text-center mobile-card">
            <h2 class="text-2xl md:text-3xl font-bold text-[#a31d1d] mb-4">Ready to Get Started?</h2>
            <p class="text-gray-600 text-base md:text-lg mb-6 max-w-2xl mx-auto">
                Join thousands of students and faculty who trust our QR Code Attendance System for efficient attendance management.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
                <a href="<?php echo ROOT ?>login" 
                   class="event-btn px-6 md:px-8 py-3 md:py-4 text-lg md:text-xl font-bold transition-all duration-200 flex items-center gap-2 justify-center mobile-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In Now
                </a>
                <a href="<?php echo ROOT ?>register" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 justify-center mobile-btn">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-4 md:p-6 mt-12 max-w-7xl mx-auto mobile-footer">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="flex items-center space-x-3">
                <img src="<?php echo $imageSource4 ?>" alt="Logo" class="h-6 md:h-8 w-auto mobile-logo">
                <p class="text-xs md:text-sm text-gray-600">&copy; <?php echo date('Y'); ?> QRCode Attendance System. All rights reserved.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                <a href="#" class="text-gray-600 hover:text-[#a31d1d] transition-colors text-xs md:text-sm">
                    <i class="fas fa-shield-alt mr-1"></i>Privacy Policy
                </a>
                <a href="#" class="text-gray-600 hover:text-[#a31d1d] transition-colors text-xs md:text-sm">
                    <i class="fas fa-file-contract mr-1"></i>Terms of Service
                </a>
                <a href="#" class="text-gray-600 hover:text-[#a31d1d] transition-colors text-xs md:text-sm">
                    <i class="fas fa-envelope mr-1"></i>Contact
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
