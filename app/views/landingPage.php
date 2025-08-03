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
    <title>QR Code Attendance System • USeP Tagum-Mabini Campus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #1e293b;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 50%, #7f1d1d 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .process-step {
            position: relative;
        }
        
        .process-step::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -2rem;
            width: 1rem;
            height: 2px;
            background: #dc2626;
            transform: translateY(-50%);
        }
        
        .process-step:first-child::before {
            display: none;
        }
        
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 1rem;
        }
        
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        @media (max-width: 768px) {
            .mobile-hero {
                min-height: 60vh;
            }
            
            .mobile-text {
                font-size: 1.75rem;
                line-height: 1.2;
            }
            
            .mobile-subtitle {
                font-size: 1rem;
                line-height: 1.5;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center space-x-4">
                    <img src="<?php echo $imageSource4 ?>" alt="USeP Logo" class="h-12 w-auto">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">QR Code Attendance System</h1>
                        <p class="text-sm text-gray-600">USeP Tagum-Mabini Campus</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="<?php echo ROOT ?>login" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20 mobile-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 mobile-text">
                    Secure Attendance Management
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto mobile-subtitle">
                    Advanced QR code attendance system with facial recognition authentication and geofencing for enhanced security and accuracy.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#how-it-works" class="bg-white text-red-600 hover:bg-gray-100 px-8 py-4 rounded-lg font-semibold transition-colors">
                        Learn How It Works
                    </a>
                    <a href="<?php echo ROOT ?>login" class="border-2 border-white text-white hover:bg-white hover:text-red-600 px-8 py-4 rounded-lg font-semibold transition-colors">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    How Our System Works
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our secure attendance system uses a facilitator-based approach with multiple layers of authentication to ensure accuracy and prevent fraud.
                </p>
            </div>

            <!-- Process Steps -->
            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <div class="process-step text-center">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">1. Facilitator Authentication</h3>
                    <p class="text-gray-600">
                        Facilitators must pass facial recognition authentication to access the system, preventing unauthorized access and ensuring accountability.
                    </p>
                </div>

                <div class="process-step text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">2. Location Verification</h3>
                    <p class="text-gray-600">
                        Geofencing technology verifies that attendance is being recorded within designated campus areas, preventing remote attendance fraud.
                    </p>
                </div>

                <div class="process-step text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-qrcode text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">3. QR Code Scanning</h3>
                    <p class="text-gray-600">
                        Facilitators scan each student's unique QR code to record attendance, ensuring accurate tracking and preventing self-scanning fraud.
                    </p>
                </div>
            </div>

            <!-- Video Section -->
            <div class="max-w-4xl mx-auto">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">System Overview</h3>
                <div class="video-container">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/l7Kj-QySG9s?si=gr0H2bRiXAbbWtdc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Key Features
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Advanced security features designed for educational institutions
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 feature-card">
                    <div class="bg-red-100 w-12 h-12 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-user-circle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Facial Recognition</h3>
                    <p class="text-gray-600">
                        Biometric authentication ensures only authorized facilitators can access the system, preventing unauthorized attendance recording.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-8 feature-card">
                    <div class="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-map-marked-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Geofencing</h3>
                    <p class="text-gray-600">
                        Location-based verification ensures attendance is recorded within designated campus areas, preventing remote attendance fraud.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-8 feature-card">
                    <div class="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Facilitator Control</h3>
                    <p class="text-gray-600">
                        Only authenticated facilitators can scan student QR codes, ensuring accountability and preventing self-attendance fraud.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-8 feature-card">
                    <div class="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Real-time Analytics</h3>
                    <p class="text-gray-600">
                        Comprehensive attendance reports and analytics with detailed insights for better decision-making and monitoring.
                    </p>
                </div>



                <div class="bg-white rounded-xl p-8 feature-card">
                    <div class="bg-indigo-100 w-12 h-12 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Mobile Friendly</h3>
                    <p class="text-gray-600">
                        Responsive design works seamlessly on all devices, making it easy for facilitators to record attendance anywhere on campus.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Why Choose Our System?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Designed specifically for educational institutions with security and accuracy in mind
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fas fa-shield-alt text-green-600"></i>
                        Enhanced Security
                    </h3>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>Facial recognition prevents unauthorized access to the system</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>Geofencing ensures attendance is recorded within campus boundaries</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>Facilitator-based scanning prevents self-attendance fraud</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>Encrypted data transmission and secure storage</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fas fa-clock text-blue-600"></i>
                        Efficiency & Accuracy
                    </h3>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>Quick QR code scanning for instant attendance recording</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>Automated report generation saves administrative time</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>Real-time attendance tracking and monitoring</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <span>Comprehensive analytics for better decision-making</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-red-600 text-white">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Ready to Transform Your Attendance Management?
            </h2>
            <p class="text-xl mb-8 opacity-90">
                Join educational institutions that trust our secure QR Code Attendance System for accurate and reliable attendance tracking.
            </p>
            <!-- <div class="flex justify-center">
                <a href="<?php echo ROOT ?>login" 
                   class="bg-white text-red-600 hover:bg-gray-100 px-8 py-4 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In Now
                </a>
            </div> -->
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="<?php echo $imageSource4 ?>" alt="USeP Logo" class="h-8 w-auto">
                        <div>
                            <h3 class="text-lg font-semibold">QR Code Attendance System</h3>
                            <p class="text-gray-400 text-sm">USeP Tagum-Mabini Campus</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Advanced attendance management system with facial recognition authentication and geofencing for enhanced security and accuracy.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                                         <ul class="space-y-2 text-gray-400">
                         <li><a href="#how-it-works" class="hover:text-white transition-colors">How It Works</a></li>
                         <li><a href="<?php echo ROOT ?>login" class="hover:text-white transition-colors">Sign In</a></li>
                     </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-envelope"></i>
                            <span>ddtiongson00006@usep.edu.ph</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Tagum-Mabini Campus</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> QRCode Attendance System. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
