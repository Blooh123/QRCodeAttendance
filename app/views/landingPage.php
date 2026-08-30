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
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --brand-red: #dc2626;
            --brand-red-dark: #7f1d1d;
            --brand-red-soft: #fee2e2;
            --ink: #111827;
            --muted: #475569;
            --panel: rgba(255, 255, 255, 0.12);
            --line: rgba(148, 163, 184, 0.18);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
            color: var(--ink);
        }

        .hero-gradient {
            position: relative;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.18), transparent 35%),
                        linear-gradient(135deg, #dc2626 0%, #991b1b 42%, #7f1d1d 100%);
            overflow: hidden;
        }

        .hero-gradient::before,
        .hero-gradient::after {
            content: "";
            position: absolute;
            border-radius: 9999px;
            background: rgba(255,255,255,0.08);
            filter: blur(10px);
        }

        .hero-gradient::before {
            width: 24rem;
            height: 24rem;
            top: -7rem;
            right: -4rem;
        }

        .hero-gradient::after {
            width: 18rem;
            height: 18rem;
            bottom: -5rem;
            left: -4rem;
        }

        .glass-card {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.14);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .soft-card {
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(148, 163, 184, 0.15);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
        }

        .soft-card:hover,
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 40px rgba(15, 23, 42, 0.12);
        }

        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(148, 163, 184, 0.16);
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
            background: rgba(220, 38, 38, 0.7);
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
            border-radius: 1.5rem;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.18);
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
                font-size: 2.2rem;
                line-height: 1.15;
            }

            .mobile-subtitle {
                font-size: 1rem;
                line-height: 1.5;
            }

            .process-step::before {
                display: none;
            }
        }
    </style>
</head>
<body>

    <header class="bg-white/90 backdrop-blur-sm shadow-sm border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center py-4 sm:py-5 gap-4 sm:gap-0">
                <div class="flex items-center space-x-4 w-full sm:w-auto justify-center sm:justify-start">
                    <img src="<?php echo $imageSource4 ?>" alt="USeP Logo" class="h-10 sm:h-12 w-auto">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-slate-900 text-center sm:text-left">QR Code Attendance System</h1>
                        <p class="text-xs sm:text-sm text-slate-600 text-center sm:text-left">USeP Tagum-Mabini Campus</p>
                    </div>
                </div>

                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                    <a href="#how-it-works" class="hover:text-red-600 transition-colors">How It Works</a>
                    <a href="#features" class="hover:text-red-600 transition-colors">Features</a>
                    <a href="#overview" class="hover:text-red-600 transition-colors">Overview</a>
                    <a href="<?php echo ROOT ?>login" class="bg-red-600 text-white px-5 py-2.5 rounded-full hover:bg-red-700 transition-colors shadow-sm">Sign In</a>
                </nav>
            </div>
        </div>
    </header>

    <section class="hero-gradient text-white py-20 mobile-hero relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-[1.1fr_0.9fr] items-center gap-12">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <div class="inline-flex items-center gap-2 mb-5 px-4 py-2 rounded-full border border-white/20 bg-white/10 text-sm font-medium text-white/90">
                        <i class="fas fa-shield-alt"></i>
                        Secure campus attendance management
                    </div>
                    <h1 class="text-4xl md:text-6xl font-extrabold mb-6 mobile-text leading-tight" data-aos="fade-up" data-aos-delay="200">
                        Secure Attendance Management
                    </h1>
                    <p class="text-lg md:text-2xl mb-8 max-w-xl mobile-subtitle text-white/85" data-aos="fade-up" data-aos-delay="400">
                        Advanced QR code attendance system with facial recognition authentication and geofencing for enhanced security and accuracy.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4" data-aos="fade-up" data-aos-delay="600">
                        <a href="#how-it-works" class="bg-white text-red-700 hover:bg-slate-100 px-7 py-4 rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-red-950/20">
                            Learn How It Works
                        </a>
                        <a href="<?php echo ROOT ?>login" class="border border-white/60 text-white hover:bg-white hover:text-red-700 px-7 py-4 rounded-xl font-semibold transition-all duration-200 backdrop-blur-sm">
                            Get Started
                        </a>
                    </div>

                    <div class="mt-10 flex flex-wrap gap-4 text-sm text-white/80">
                        <div class="glass-card rounded-full px-4 py-2"><i class="fas fa-check-circle mr-2 text-green-300"></i>Facial verification</div>
                        <div class="glass-card rounded-full px-4 py-2"><i class="fas fa-check-circle mr-2 text-green-300"></i>Geofenced check-in</div>
                        <div class="glass-card rounded-full px-4 py-2"><i class="fas fa-check-circle mr-2 text-green-300"></i>Instant reports</div>
                    </div>
                </div>

                <div class="relative" data-aos="zoom-in" data-aos-delay="300">
                    <div class="glass-card rounded-[2rem] p-5 shadow-2xl shadow-red-950/25">
                        <div class="bg-white/10 border border-white/10 rounded-[1.5rem] p-5">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <p class="text-white/70 text-xs uppercase tracking-[0.2em]">Campus Security</p>
                                    <h3 class="text-2xl font-bold mt-2">Attendance Overview</h3>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                                    <i class="fas fa-qrcode text-2xl text-white"></i>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-white/8 rounded-2xl p-4 border border-white/10">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-white/70 text-sm">Facilitator Verified</span>
                                        <span class="text-green-300 font-semibold">98%</span>
                                    </div>
                                    <div class="h-2.5 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full w-[98%] rounded-full bg-gradient-to-r from-green-400 to-emerald-300"></div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white/8 rounded-2xl p-4 border border-white/10">
                                        <i class="fas fa-user-shield text-red-200 text-xl mb-3"></i>
                                        <p class="text-white/70 text-sm">Authentication</p>
                                        <p class="text-2xl font-bold mt-1">Secure</p>
                                    </div>
                                    <div class="bg-white/8 rounded-2xl p-4 border border-white/10">
                                        <i class="fas fa-map-marker-alt text-blue-200 text-xl mb-3"></i>
                                        <p class="text-white/70 text-sm">Location</p>
                                        <p class="text-2xl font-bold mt-1">On-site</p>
                                    </div>
                                </div>

                                <div class="bg-white/8 rounded-2xl p-4 border border-white/10">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-white/70 text-sm">Students Checked In</p>
                                            <p class="text-3xl font-bold mt-1">1,284</p>
                                        </div>
                                        <div class="w-12 h-12 rounded-xl bg-emerald-400/20 flex items-center justify-center">
                                            <i class="fas fa-check text-xl text-emerald-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <p class="text-red-600 font-semibold uppercase tracking-[0.2em] text-sm mb-3">How it works</p>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    How Our System Works
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    Our secure attendance system uses a facilitator-based approach with multiple layers of authentication to ensure accuracy and prevent fraud.
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <div class="process-step text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-red-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-user-shield text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">1. Facilitator Authentication</h3>
                    <p class="text-slate-600">
                        Facilitators must pass facial recognition authentication to access the system, preventing unauthorized access and ensuring accountability.
                    </p>
                </div>

                <div class="process-step text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-map-marker-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">2. Location Verification</h3>
                    <p class="text-slate-600">
                        Geofencing technology verifies that attendance is being recorded within designated campus areas, preventing remote attendance fraud.
                    </p>
                </div>

                <div class="process-step text-center" data-aos="fade-up" data-aos-delay="600">
                    <div class="bg-green-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-qrcode text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">3. QR Code Scanning</h3>
                    <p class="text-slate-600">
                        Facilitators scan each student's unique QR code to record attendance, ensuring accurate tracking and preventing self-scanning fraud.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="overview" class="py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="zoom-in" data-aos-duration="1000">
            <div class="text-center mb-10">
                <p class="text-red-600 font-semibold uppercase tracking-[0.2em] text-sm mb-3">System overview</p>
                <h3 class="text-3xl font-bold text-slate-900">System Overview</h3>
            </div>
            <div class="video-container">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/l7Kj-QySG9s?si=gr0H2bRiXAbbWtdc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <p class="text-red-600 font-semibold uppercase tracking-[0.2em] text-sm mb-3">Features</p>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Key Features
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    Advanced security features designed for educational institutions
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl p-8 feature-card soft-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-red-100 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-user-circle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Facial Recognition</h3>
                    <p class="text-slate-600">
                        Biometric authentication ensures only authorized facilitators can access the system, preventing unauthorized attendance recording.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 feature-card soft-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-blue-100 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-map-marked-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Geofencing</h3>
                    <p class="text-slate-600">
                        Location-based verification ensures attendance is recorded within designated campus areas, preventing remote attendance fraud.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 feature-card soft-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="bg-green-100 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Facilitator Control</h3>
                    <p class="text-slate-600">
                        Only authenticated facilitators can scan student QR codes, ensuring accountability and preventing self-attendance fraud.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 feature-card soft-card" data-aos="fade-up" data-aos-delay="800">
                    <div class="bg-purple-100 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Real-time Analytics</h3>
                    <p class="text-slate-600">
                        Comprehensive attendance reports and analytics with detailed insights for better decision-making and monitoring.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 feature-card soft-card" data-aos="fade-up" data-aos-delay="1000">
                    <div class="bg-indigo-100 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Mobile Friendly</h3>
                    <p class="text-slate-600">
                        Responsive design works seamlessly on all devices, making it easy for facilitators to record attendance anywhere on campus.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 feature-card soft-card" data-aos="fade-up" data-aos-delay="1200">
                    <div class="bg-amber-100 w-14 h-14 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-file-alt text-amber-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Automated Reporting</h3>
                    <p class="text-slate-600">
                        Generate attendance summaries and records quickly to support efficient administrative review and monitoring.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <p class="text-red-600 font-semibold uppercase tracking-[0.2em] text-sm mb-3">Benefits</p>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Why Choose Our System?
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    Designed specifically for educational institutions with security and accuracy in mind
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div class="soft-card rounded-3xl p-8" data-aos="fade-right" data-aos-delay="200">
                    <h3 class="text-2xl font-semibold text-slate-900 mb-6 flex items-center gap-3">
                        <i class="fas fa-shield-alt text-green-600"></i>
                        Enhanced Security
                    </h3>
                    <ul class="space-y-4 text-slate-600">
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

                <div class="soft-card rounded-3xl p-8" data-aos="fade-left" data-aos-delay="400">
                    <h3 class="text-2xl font-semibold text-slate-900 mb-6 flex items-center gap-3">
                        <i class="fas fa-clock text-blue-600"></i>
                        Efficiency & Accuracy
                    </h3>
                    <ul class="space-y-4 text-slate-600">
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

    <section class="py-20 bg-red-600 text-white">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8" data-aos="zoom-in" data-aos-duration="1000">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Developed by Students of USeP Tagum-Mabini Campus
            </h2>
            <p class="text-xl mb-8 opacity-90">
                This QR Code Attendance System was created by students of the University of Southeastern Philippines Tagum-Mabini Campus, showcasing innovation and dedication to secure and reliable attendance management.
            </p>
        </div>
    </section>

    <footer class="bg-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="<?php echo $imageSource4 ?>" alt="USeP Logo" class="h-8 w-auto">
                        <div>
                            <h3 class="text-lg font-semibold">QR Code Attendance System</h3>
                            <p class="text-slate-400 text-sm">USeP Tagum-Mabini Campus</p>
                        </div>
                    </div>
                    <p class="text-slate-400 mb-4">
                        Advanced attendance management system with facial recognition authentication and geofencing for enhanced security and accuracy.
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="#how-it-works" class="hover:text-white transition-colors">How It Works</a></li>
                        <li><a href="<?php echo ROOT ?>login" class="hover:text-white transition-colors">Sign In</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-slate-400">
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

            <div class="border-t border-slate-800 mt-8 pt-8 text-center text-slate-400">
                <p>&copy; <?php echo date('Y'); ?> QRCode Attendance System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
